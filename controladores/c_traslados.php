<?php
class traslados
{

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER — tipo de usuario como string
    // permisos() regresa 'Administrador' (string) para admin, o un ARREGLO de
    // permisos para usuarios normales. Nunca se debe concatenar directo.
    // ─────────────────────────────────────────────────────────────────────────
    private function tipoUsuario($omodelo)
    {
        $permisos = $omodelo->permisos();
        return is_array($permisos) ? 'Usuario' : (string) $permisos;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CONSULTAR — tabla principal
    // ─────────────────────────────────────────────────────────────────────────
    public function _consultar()
    {
        $omodelo = new m_modelo();
        extract($_POST);

        $buscar =  $omodelo->link->real_escape_string($buscar);
        $limit =  $omodelo->link->real_escape_string($limit);
        $pagina =  $omodelo->link->real_escape_string($pagina);
        $ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
        $orden =  $omodelo->link->real_escape_string($orden);
        $arreglo = array();

        $busqueda = '';
        if (trim($buscar) != '') {
            $separa = explode(' ', trim($buscar));
            $busqueda = 'WHERE ';
            for ($i = 0; $i < count($separa); $i++) {
                $busqueda .= "CONCAT(
                    Estatus, 
                    Detalles, 
                    ID_Traslado, 
                    DATE_FORMAT(Fecha_Traslado, '%d-%m-%Y'), 
                    DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), 
                    (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Origen), 
                    (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Destino)
                ) REGEXP '" . $separa[$i] . "'";
                if ($i < (count($separa) - 1)) {
                    $busqueda .= ' AND ';
                }
            }
        }

        // Filtro por sucursal según permisos
        $where = '';
        if ($omodelo->permisos() != 'Administrador' && $_SESSION['user_admin']['FK_Sucursal'] != '0') {
            $sucursal = $omodelo->link->real_escape_string($_SESSION['user_admin']['FK_Sucursal']);
            $where = 'WHERE ';
            if ($busqueda != '') {
                $where = 'AND ';
            }
            $where .= "(FK_Sucursal_Origen = '$sucursal' OR FK_Sucursal_Destino = '$sucursal')";
        }

        $query = "SELECT 
            ID_Traslado, 
            ID_Traslado AS FolioTraslado, 
            Estatus, 
            Detalles, 
            FK_Sucursal_Origen,
            FK_Sucursal_Destino,
            (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Origen) AS Origen, 
            (SELECT Nombre FROM sucursales WHERE ID_Sucursal = FK_Sucursal_Destino) AS Destino, 
            Fecha_Traslado AS FechaTraslado, 
            DATE_FORMAT(Fecha_Traslado, '%d-%m-%Y') AS Fecha_Traslado, 
            Fecha_Registro AS Fecha, 
            DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, 
            FK_Usuario, 
            (SELECT COUNT(*) FROM traslados $busqueda $where) AS Num 
        FROM traslados $busqueda $where 
        ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
        $row = $omodelo->_consultar($query);
        $numerofilas = $omodelo->numerofilas;

        if ($row == 'si') {
            echo "Error: " . mysqli_error($omodelo->link);
        } else {
            if ($numerofilas > 0) {
                for ($i = 0; $i < $numerofilas; $i++) {

                    $botonModificar = '';
                    if ($row[$i]['Estatus'] == 'Solicitud' && ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_traslados'][3] == '1')) {
                        $botonModificar = '<button type="button" class="btn btn-sm btn-warning bEditarTraslado" attrID="' . $row[$i]['ID_Traslado'] . '" title="Modificar traslado"><i class="fas fa-pencil-alt"></i></button>';
                    }

                    $botonEliminar = '';
                    if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_traslados'][4] == '1') {
                        $botonEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarTraslado" attrID="' . $row[$i]['ID_Traslado'] . '" title="Eliminar traslado"><i class="fas fa-trash"></i></button>';
                    }

                    $botonCompletar = '';
                    if ($row[$i]['Estatus'] == 'Pendiente' && ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_traslados'][5] == '1')) {
                        $botonCompletar = '<button type="button" class="btn btn-sm btn-success bCompletarTraslado" attrID="' . $row[$i]['ID_Traslado'] . '" title="Completar traslado"><i class="fas fa-check"></i></button>';
                    }

                    $botonCancelar = '';
                    if ($row[$i]['Estatus'] == 'Completado' && ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_traslados'][6] == '1')) {
                        $botonCancelar = '<button type="button" class="btn btn-sm btn-warning bCancelarTraslado" attrID="' . $row[$i]['ID_Traslado'] . '" title="Cancelar traslado"><i class="fas fa-ban"></i></button>';
                    }

                    $bVerificarSalida = '';
                    if ($omodelo->permisos() == 'Administrador' || $_SESSION['user_admin']['FK_Sucursal'] == $row[$i]['FK_Sucursal_Origen']) {
                        $bVerificarSalida = '<button type="button" class="btn btn-sm btn-outline-primary bConcentradoTraslado d-block mb-2" attrID="' . $row[$i]['ID_Traslado'] . '" title="Verificar salida">Salida <i class="fas fa-indent"></i></button>';
                    }
                    $bVerificarEntrada = '';
                    if ($omodelo->permisos() == 'Administrador' || $_SESSION['user_admin']['FK_Sucursal'] == $row[$i]['FK_Sucursal_Destino']) {
                        $bVerificarEntrada = '<button type="button" class="btn btn-sm btn-outline-primary bEntradaTraslado d-block mb-2" attrID="' . $row[$i]['ID_Traslado'] . '" title="Verificar entrada">Recepción <i class="fas fa-outdent"></i></button>';
                    }

                    $estatus = '<span class="badge rounded-pill bg-info">Solicitud</span>';
                    if ($row[$i]['Estatus'] == 'En transito') {
                        $estatus = '<span class="badge rounded-pill bg-primary">En transito</span>';
                    } else if ($row[$i]['Estatus'] == 'Pendiente') {
                        $estatus = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
                    } else if ($row[$i]['Estatus'] == 'Completado') {
                        $estatus = '<span class="badge rounded-pill bg-success">Completado</span>';
                    } else if ($row[$i]['Estatus'] == 'Cancelado') {
                        $estatus = '<span class="badge rounded-pill bg-danger">Cancelado</span>';
                    }

                    $arreglo['data'][$i] = array(
                        'ID' => $row[$i]['ID_Traslado'],
                        'Fecha' => $row[$i]['Fecha_Registro'] . "<br>Folio: <b>" . $row[$i]['FolioTraslado'] . "</b>",
                        'FechaTraslado' => $row[$i]['Fecha_Traslado'],
                        'Origen' => $row[$i]['Origen'],
                        'Destino' => $row[$i]['Destino'],
                        'Concentrado' => '<button type="button" class="btn btn-sm btn-primary bVerConcentradoTraslado d-block mb-2" attrID="' . $row[$i]['ID_Traslado'] . '" title="Ver concentrado">Concentrado <i class="fas fa-list"></i></button> ' . $bVerificarSalida . ' ' . $bVerificarEntrada,
                        'Estatus' => $estatus,
                        'Detalles' => $row[$i]['Detalles'] . '<br><button type="button" class="btn btn-link btn-sm bDetalleTraslado" attrID="' . $row[$i]['ID_Traslado'] . '" title="Detalles traslado">Ver detalles <i class="fas fa-eye"></i></button>',
                        'Acciones' => $botonModificar . ' ' . $botonCompletar . ' ' . $botonCancelar . ' ' . $botonEliminar . ' <button type="button" class="btn btn-sm btn-info bImprimirTraslado" attrID="' . $row[$i]['ID_Traslado'] . '" title="Imprimir traslado"><i class="fas fa-print"></i></button>'
                    );
                }

                $arreglo['totales'] = array('NumRows' => $row[0]['Num']);
            }
        }

        echo json_encode($arreglo);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DETALLES — sub-consultas
    // ─────────────────────────────────────────────────────────────────────────
    public function _detalles()
    {
        $omodelo = new m_modelo();
        extract($_POST);
        $tipo = $omodelo->link->real_escape_string($tipo);

        if ($tipo == 'productosTraslado') {
            $id = $omodelo->link->real_escape_string($id);

            $buscar =  $omodelo->link->real_escape_string($buscar);
            $limit =  $omodelo->link->real_escape_string($limit);
            $pagina =  $omodelo->link->real_escape_string($pagina);
            $ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
            $orden =  $omodelo->link->real_escape_string($orden);
            $arreglo = array();

            $busqueda = '';
            if (trim($buscar) != '') {
                $separa = explode(' ', trim($buscar));
                $busqueda = 'AND ';
                for ($i = 0; $i < count($separa); $i++) {
                    $busqueda .= "CONCAT(
                        productos.Codigo, 
                        Descripcion, 
                        Nombre_Unidad, 
                        Abreviatura_Unidad, 
                        FK_Presentacion, 
                        Cantidad, 
                        Nombre, 
                        Abreviatura 
                    ) REGEXP '" . $separa[$i] . "'";
                    if ($i < (count($separa) - 1)) {
                        $busqueda .= ' AND ';
                    }
                }
            }

            $query = "SELECT 
                ID_Detalle_Traslado, 
                productos.Codigo AS Codigo, 
                Descripcion, 
                Nombre_Unidad, 
                Abreviatura_Unidad, 
                FK_Presentacion, 
                Cantidad, 
                Nombre, 
                Abreviatura,
                (SELECT COUNT(*) FROM detalles_traslados INNER JOIN productos ON FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Traslado = '$id' $busqueda) AS Num
            FROM detalles_traslados INNER JOIN productos ON FK_Producto = ID_Producto 
            LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Traslado = '$id' $busqueda 
            ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
            $row = $omodelo->_consultar($query);
            $numerofilas = $omodelo->numerofilas;

            if ($row == 'si') {
                echo "Error 1: " . mysqli_error($omodelo->link);
            } else {
                if ($numerofilas > 0) {
                    for ($i = 0; $i < $numerofilas; $i++) {
                        $presentacion = 'Sin presentación';
                        if ($row[$i]['FK_Presentacion'] == 0) {
                            if (trim($row[$i]['Nombre_Unidad']) != '') {
                                $presentacion = $row[$i]['Nombre_Unidad'];
                            }

                            if (trim($row[$i]['Abreviatura_Unidad']) != '') {
                                $presentacion .= '(' . $row[$i]['Abreviatura_Unidad'] . ')';
                            }
                        } else {
                            if (trim($row[$i]['Nombre']) != '') {
                                $presentacion = $row[$i]['Nombre'];
                            }

                            if (trim($row[$i]['Abreviatura']) != '') {
                                $presentacion .= '(' . $row[$i]['Abreviatura'] . ')';
                            }
                        }

                        $arreglo['data'][$i] = array(
                            'ID' => $row[$i]['ID_Detalle_Traslado'],
                            'Codigo' => $row[$i]['Codigo'],
                            'Descripcion' => $row[$i]['Descripcion'],
                            'Presentacion' => $presentacion,
                            'Cantidad' => $row[$i]['Cantidad']
                        );
                    }

                    $arreglo['totales'] = array('NumRows' => $row[0]['Num']);
                }
            }

            echo json_encode($arreglo);
        }

        // ── Productos de una sucursal (para el buscador) ──────────────────
        else if ($tipo == 'ProductosSucursal') {
            $sucursal    = $omodelo->link->real_escape_string($sucursal);
            $buscar      = $omodelo->link->real_escape_string($buscar);
            $limit       = $omodelo->link->real_escape_string($limit);
            $pagina      = $omodelo->link->real_escape_string($pagina);
            $ordenColumna = $omodelo->link->real_escape_string($ordenColumna);
            $orden       = $omodelo->link->real_escape_string($orden);
            $arreglo     = array();

            $busqueda = '';
            if (trim($buscar) != '') {
                $separa = explode(' ', trim($buscar));
                $busqueda = ' AND ';
                for ($i = 0; $i < count($separa); $i++) {
                    $busqueda .= "CONCAT(
                        IFNULL(p.Codigo,''), 
                        p.Descripcion, 
                        IFNULL(pr.Nombre,'')
                    ) REGEXP '" . $separa[$i] . "'";
                    if ($i < count($separa) - 1) $busqueda .= ' AND ';
                }
            }

            $query = "SELECT
                inv.ID_Inventario,
                IFNULL(IF(inv.FK_Presentacion = 0, p.Codigo, pr.Codigo), p.Codigo) AS Codigo,
                p.ID_Producto AS FK_Producto,
                inv.FK_Presentacion,
                p.Descripcion,
                IFNULL(IF(inv.FK_Presentacion = 0, CONCAT(p.Nombre_Unidad,' ',p.Abreviatura_Unidad), CONCAT(pr.Nombre,' ',pr.Abreviatura)),'Sin presentación') AS Presentacion,
                inv.Cantidad AS Existencia,
                (SELECT COUNT(*) FROM inventario inv2 INNER JOIN productos p2 ON inv2.FK_Producto = p2.ID_Producto
                 LEFT JOIN presentaciones pr2 ON inv2.FK_Presentacion = pr2.ID_Presentacion
                 WHERE inv2.FK_Sucursal = '$sucursal' $busqueda) AS Num
                FROM inventario inv
                INNER JOIN productos p ON inv.FK_Producto = p.ID_Producto
                LEFT JOIN presentaciones pr ON inv.FK_Presentacion = pr.ID_Presentacion
                WHERE inv.FK_Sucursal = '$sucursal' $busqueda
                ORDER BY $ordenColumna $orden
                LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);

            $row = $omodelo->_consultar($query);
            $nf  = $omodelo->numerofilas;

            if ($row == 'si') {
                echo "Error: " . mysqli_error($omodelo->link);
                return;
            }
            if ($nf > 0) {
                for ($i = 0; $i < $nf; $i++) {
                    $arreglo['data'][$i] = array(
                        'ID'           => $row[$i]['ID_Inventario'],
                        'Codigo'       => '<span data-productoid="' . $row[$i]['FK_Producto'] . '" data-presentacionid="' . $row[$i]['FK_Presentacion'] . '" data-codigo="' . $row[$i]['Codigo'] . '" data-descripcion="' . htmlspecialchars($row[$i]['Descripcion']) . '" data-presentacion="' . htmlspecialchars($row[$i]['Presentacion']) . '" data-existencia="' . $row[$i]['Existencia'] . '">' . $row[$i]['Codigo'] . '</span>',
                        'Descripcion'  => $row[$i]['Descripcion'],
                        'Presentacion' => $row[$i]['Presentacion'],
                        'Existencia'   => '<span class="cantidad">' . $row[$i]['Existencia'] . '</span>',
                    );
                }
                $arreglo['totales'] = array('NumRows' => $row[0]['Num']);
            }
            echo json_encode($arreglo);
        }

        // ── Buscar producto por código ─────────────────────────────────────
        else if ($tipo == 'BuscarProductoCodigo') {
            $codigo   = $omodelo->link->real_escape_string($codigo);
            $sucursal = $omodelo->link->real_escape_string($sucursal);

            $query = "SELECT
                IFNULL(IF(inv.FK_Presentacion = 0, p.Codigo, pr.Codigo), p.Codigo) AS Codigo,
                p.ID_Producto AS FK_Producto,
                inv.FK_Presentacion,
                p.Descripcion,
                IFNULL(IF(inv.FK_Presentacion = 0, CONCAT(p.Nombre_Unidad,' ',p.Abreviatura_Unidad), CONCAT(pr.Nombre,' ',pr.Abreviatura)),'Sin presentación') AS Presentacion,
                inv.Cantidad AS Existencia
                FROM inventario inv
                INNER JOIN productos p ON inv.FK_Producto = p.ID_Producto
                LEFT JOIN presentaciones pr ON inv.FK_Presentacion = pr.ID_Presentacion
                WHERE inv.FK_Sucursal = '$sucursal'
                AND (p.Codigo = '$codigo' OR pr.Codigo = '$codigo')
                LIMIT 1";

            $row = $omodelo->_consultar($query);
            $nf  = $omodelo->numerofilas;

            if ($row == 'si' || $nf == 0) {
                echo json_encode(['error' => 'Producto no encontrado en esta sucursal']);
            } else {
                echo json_encode($row[0]);
            }
        }

        // ── Datos de un traslado para editar ──────────────────────────────
        else if ($tipo == 'DatosTraslado') {
            $id = $omodelo->link->real_escape_string($id);

            $query = "SELECT 
                t.*, 
                DATE_FORMAT(t.Fecha_Traslado,'%Y-%m-%d') AS Fecha_Traslado 
            FROM traslados t 
            WHERE ID_Traslado = '$id' LIMIT 1";
            $row = $omodelo->_consultar($query);
            $nf  = $omodelo->numerofilas;
            if ($row == 'si' || $nf == 0) {
                echo json_encode(['error' => 'No encontrado']);
                return;
            }

            $traslado = $row[0];

            // Productos
            $qDet = "SELECT
                dt.ID_Detalle_Traslado, dt.FK_Producto, dt.FK_Presentacion,
                dt.Cantidad AS Cantidad_Solicitada, dt.Estatus,
                IFNULL(IF(dt.FK_Presentacion = 0, p.Codigo, pr.Codigo), p.Codigo) AS Codigo,
                p.Descripcion,
                IFNULL(IF(dt.FK_Presentacion = 0, CONCAT(p.Nombre_Unidad,' ',p.Abreviatura_Unidad), CONCAT(pr.Nombre,' ',pr.Abreviatura)),'Sin presentación') AS Presentacion,
                IFNULL((SELECT Cantidad FROM inventario WHERE FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND FK_Sucursal = t.FK_Sucursal_Origen), 0) AS Existencia
            FROM detalles_traslados dt
            INNER JOIN traslados t ON dt.FK_Traslado = t.ID_Traslado
            INNER JOIN productos p ON dt.FK_Producto = p.ID_Producto
            LEFT JOIN presentaciones pr ON dt.FK_Presentacion = pr.ID_Presentacion
            WHERE dt.FK_Traslado = '$id'";

            $rowDet = $omodelo->_consultar($qDet);
            $nfDet  = $omodelo->numerofilas;
            $productos = [];
            if ($rowDet != 'si' && $nfDet > 0) {
                for ($i = 0; $i < $nfDet; $i++) $productos[] = $rowDet[$i];
            }
            $traslado['productos'] = $productos;
            echo json_encode($traslado);
        }

        // ── Concentrado (origen surte: productos + lotes disponibles) ─────
        else if ($tipo == 'Concentrado') {
            $id = $omodelo->link->real_escape_string($id);

            // CORRECCIÓN: permisos() regresa un arreglo para usuarios normales.
            // Concatenarlo directo generaba "Array to string conversion", el warning
            // se imprimía antes del JSON y el JS se quedaba cargando.
            $tipoUsuario     = $omodelo->link->real_escape_string($this->tipoUsuario($omodelo));
            $sucursalUsuario = $omodelo->link->real_escape_string($_SESSION['user_admin']['FK_Sucursal']);

            $qT = "SELECT t.ID_Traslado, t.Estatus,
                IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = t.FK_Sucursal_Origen),'') AS Origen,
                IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = t.FK_Sucursal_Destino),'') AS Destino,
                t.FK_Sucursal_Origen,
                t.FK_Sucursal_Destino
            FROM traslados t WHERE ID_Traslado = '$id' LIMIT 1";
            $rowT = $omodelo->_consultar($qT);
            if ($rowT == 'si' || $omodelo->numerofilas == 0) {
                echo json_encode(['error' => 'No encontrado']);
                return;
            }
            $traslado = $rowT[0];

            $qDet = "SELECT
                dt.ID_Detalle_Traslado, dt.FK_Producto, dt.FK_Presentacion,
                dt.Cantidad AS Cantidad_Solicitada,
                IFNULL(IF(dt.FK_Presentacion = 0, p.Codigo, pr.Codigo), p.Codigo) AS Codigo,
                p.Descripcion,
                IFNULL(IF(dt.FK_Presentacion = 0, CONCAT(p.Nombre_Unidad,' ',p.Abreviatura_Unidad), CONCAT(pr.Nombre,' ',pr.Abreviatura)),'Sin presentación') AS Presentacion,
                IFNULL((SELECT SUM(Cantidad) FROM inventario WHERE FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND FK_Sucursal = '" . $traslado['FK_Sucursal_Origen'] . "'), 0) AS Existencia,
                IFNULL((SELECT SUM(Cantidad) FROM inventario WHERE FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND FK_Sucursal = '" . $traslado['FK_Sucursal_Destino'] . "'), 0) AS Existencia_Destino,
                IFNULL((SELECT SUM(cantidad) FROM verificar_traslado WHERE FK_Traslado = '$id' AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Salida'), 0) AS Cantidad_Verificada,
                IFNULL((SELECT SUM(cantidad) FROM verificar_traslado WHERE FK_Traslado = '$id' AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada'), 0) AS Cantidad_Entrada,
                
                IFNULL((SELECT GROUP_CONCAT(
                    CONCAT('<p class=\"m-0\" attrLote=\"', FK_Lote, '\">Lote - ', FK_Lote, ' ', IFNULL((SELECT Nombre FROM lotes WHERE ID_Lote = FK_Lote), ''), ' (<span class=\"cantidad\">', verificar_detalles_lotes.Cantidad, '</span>)</p>')
                    SEPARATOR ''
                ) FROM verificar_detalles_lotes INNER JOIN verificar_traslado ON FK_Verificar = ID_Verificar WHERE FK_Traslado = '$id' AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada'), '') AS Lotes_Entrada,

                IFNULL((SELECT GROUP_CONCAT(
                    CONCAT('<p class=\"m-0\" attrLote=\"', FK_Lote, '\">Lote - ', FK_Lote, ' ', IFNULL((SELECT Nombre FROM lotes WHERE ID_Lote = FK_Lote), ''), ' (<span class=\"cantidad\">', verificar_detalles_lotes.Cantidad, '</span>)</p>')
                    SEPARATOR ''
                ) FROM verificar_detalles_lotes INNER JOIN verificar_traslado ON FK_Verificar = ID_Verificar WHERE FK_Traslado = '$id' AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Salida'), '') AS Lotes_Salida,

                IFNULL((SELECT Observaciones FROM verificar_traslado WHERE FK_Traslado = '$id' AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada' LIMIT 1), '') AS Observaciones,
                IFNULL((SELECT Accion FROM verificar_traslado WHERE FK_Traslado = '$id' AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada' LIMIT 1), '') AS Accion,
                IFNULL((SELECT Estatus_Accion FROM verificar_traslado WHERE FK_Traslado = '$id' AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada' LIMIT 1), 'Pendiente') AS Estatus_Accion,
                '$sucursalUsuario' AS Sucursal_Usuario,
                '$tipoUsuario' AS Tipo_Usuario
            FROM detalles_traslados dt
            INNER JOIN productos p ON dt.FK_Producto = p.ID_Producto
            LEFT JOIN presentaciones pr ON dt.FK_Presentacion = pr.ID_Presentacion
            WHERE dt.FK_Traslado = '$id'";
            $rowDet = $omodelo->_consultar($qDet);
            $nfDet  = $omodelo->numerofilas;
            $productos = [];

            if ($rowDet == 'si') {
                echo json_encode(['error' => 'Error al consultar productos: ' . mysqli_error($omodelo->link)]);
                return;
            }

            if ($nfDet > 0) {
                for ($i = 0; $i < $nfDet; $i++) {
                    $p = $rowDet[$i];
                    // Lotes disponibles en sucursal origen
                    $qLotes = "SELECT ID_Lote, IFNULL(Nombre, ID_Lote) AS Nombre, Cantidad
                        FROM lotes
                        WHERE FK_Producto = '" . $p['FK_Producto'] . "'
                        AND FK_Presentacion = '" . $p['FK_Presentacion'] . "'
                        AND FK_Sucursal = '" . $traslado['FK_Sucursal_Origen'] . "'
                        AND Cantidad > 0";
                    $rowLotes = $omodelo->_consultar($qLotes);
                    $nfLotes  = $omodelo->numerofilas;
                    $lotes    = [];
                    if ($rowLotes != 'si' && $nfLotes > 0) {
                        for ($j = 0; $j < $nfLotes; $j++) $lotes[] = $rowLotes[$j];
                    }
                    $p['lotes_origen'] = $lotes;

                    // Lotes disponibles en sucursal destino
                    $qLotes = "SELECT ID_Lote, IFNULL(Nombre, ID_Lote) AS Nombre, Cantidad
                        FROM lotes
                        WHERE FK_Producto = '" . $p['FK_Producto'] . "'
                        AND FK_Presentacion = '" . $p['FK_Presentacion'] . "'
                        AND FK_Sucursal = '" . $traslado['FK_Sucursal_Destino'] . "'
                        AND Cantidad > 0";
                    $rowLotes = $omodelo->_consultar($qLotes);
                    $nfLotes  = $omodelo->numerofilas;
                    $lotes    = [];
                    if ($rowLotes != 'si' && $nfLotes > 0) {
                        for ($j = 0; $j < $nfLotes; $j++) $lotes[] = $rowLotes[$j];
                    }
                    $p['lotes_destino'] = $lotes;

                    $productos[] = $p;
                }
            }
            $traslado['productos'] = $productos;
            echo json_encode($traslado);
        }

        // -- Verificación de productos 
        else if ($tipo == 'GuardarVerificacion') {
            $id       = $omodelo->link->real_escape_string($id);
            $tipoMov  = $omodelo->link->real_escape_string($tipo_mov); // Salida | Entrada
            $productos = json_decode($productos, true);
            $usuario  = $_SESSION['user_admin']['ID_Usuario'];

            if (!is_array($productos)) {
                echo "Error: productos inválidos";
                return;
            }

            // Solo limpiamos los lotes anteriores del traslado actual para evitar lotes huérfanos o duplicados.
            $omodelo->_insertar("DELETE verificar_detalles_lotes 
            FROM verificar_detalles_lotes 
            INNER JOIN verificar_traslado ON verificar_detalles_lotes.FK_Verificar = verificar_traslado.ID_Verificar 
            WHERE verificar_traslado.FK_Traslado='$id' AND verificar_traslado.Tipo='$tipoMov'");

            foreach ($productos as $p) {
                $prod  = $omodelo->link->real_escape_string($p['producto']);
                $pres  = $omodelo->link->real_escape_string($p['presentacion']);
                $cant  = $omodelo->link->real_escape_string($p['cantidad']);
                $lotes = $p['lotes'] ?? [];

                // Buscamos si ya existe este producto en este traslado
                $buscar = mysqli_query($omodelo->link, "SELECT ID_Verificar FROM verificar_traslado 
                WHERE FK_Traslado = '$id' 
                AND FK_Producto = '$prod' 
                AND FK_Presentacion = '$pres' 
                AND Tipo = '$tipoMov' LIMIT 1");

                if ($buscar && mysqli_num_rows($buscar) > 0) {
                    // SI EXISTE: actualizamos la cantidad
                    $filaExistente = mysqli_fetch_assoc($buscar);
                    $idVerificar   = $filaExistente['ID_Verificar'];

                    $qV = "UPDATE verificar_traslado SET Cantidad = '$cant' WHERE ID_Verificar = '$idVerificar'";
                    $omodelo->_insertar($qV);
                } else {
                    // NO EXISTE: INSERT normal
                    $qV = "INSERT INTO verificar_traslado (FK_Traslado, FK_Producto, FK_Presentacion, Cantidad, Tipo)
                   VALUES ('$id','$prod','$pres','$cant','$tipoMov')";

                    if ($omodelo->_insertar($qV) == 'si') {
                        echo "Error 1: " . mysqli_error($omodelo->link);
                        return;
                    }
                    $idVerificar = mysqli_insert_id($omodelo->link);
                }

                // Volvemos a insertar los lotes actualizados
                foreach ($lotes as $l) {
                    $lote     = $omodelo->link->real_escape_string($l['lote']);
                    $cantLote = $omodelo->link->real_escape_string($l['cantidad']);
                    $omodelo->_insertar("INSERT INTO verificar_detalles_lotes (FK_Verificar, FK_Lote, Cantidad)
                    VALUES ('$idVerificar','$lote','$cantLote')");
                }
            }

            // Actualizar estatus del traslado
            if ($tipoMov == 'Salida') {
                $omodelo->_insertar("UPDATE traslados SET Estatus='En transito', Detalles = NOW() WHERE ID_Traslado='$id' AND Estatus = 'Solicitud'");
            }

            if ($tipoMov == 'Entrada') {
                $omodelo->_insertar("UPDATE traslados SET Estatus='Pendiente', Detalles = NOW() WHERE ID_Traslado='$id' AND Estatus = 'En transito'");
            }

            $omodelo->movimiento("Verificacion $tipoMov traslado $id", $usuario);
            echo "Correcto";
        }

        // ── Pedido sugerido ───────────────────────────────────────────────
        else if ($tipo == 'PedidoSugerido') {
            $sucursal    = $omodelo->link->real_escape_string($sucursal);
            $fechaInicio = $omodelo->link->real_escape_string($fechaInicio);
            $fechaFin    = $omodelo->link->real_escape_string($fechaFin);

            $query = "SELECT
                IFNULL(IF(dv.FK_Presentacion = 0, p.Codigo, pr.Codigo), p.Codigo) AS Codigo,
                p.ID_Producto AS FK_Producto,
                dv.FK_Presentacion,
                p.Descripcion,
                IFNULL(IF(dv.FK_Presentacion = 0, CONCAT(p.Nombre_Unidad,' ',p.Abreviatura_Unidad), CONCAT(pr.Nombre,' ',pr.Abreviatura)),'Sin presentación') AS Presentacion,
                SUM(dv.Cantidad) AS Cantidad,
                IFNULL((SELECT Cantidad FROM inventario WHERE FK_Producto = dv.FK_Producto AND FK_Presentacion = dv.FK_Presentacion AND FK_Sucursal = '$sucursal'), 0) AS Existencia
            FROM detalles_ventas dv
            INNER JOIN ventas v ON dv.FK_Venta = v.ID_Venta
            INNER JOIN productos p ON dv.FK_Producto = p.ID_Producto
            LEFT JOIN presentaciones pr ON dv.FK_Presentacion = pr.ID_Presentacion
            WHERE v.FK_Sucursal = '$sucursal'
            AND v.Estatus = 'Completada'
            AND (v.Fecha_Registro >= '$fechaInicio'
            AND v.Fecha_Registro <= '$fechaFin')
            GROUP BY dv.FK_Producto, dv.FK_Presentacion
            ORDER BY Cantidad DESC";

            $row = $omodelo->_consultar($query);
            $nf  = $omodelo->numerofilas;
            $resultado = [];
            if ($row != 'si' && $nf > 0) {
                for ($i = 0; $i < $nf; $i++) $resultado[] = $row[$i];
            }
            echo json_encode($resultado);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // INSERTAR
    // ─────────────────────────────────────────────────────────────────────────
    public function _insertar()
    {
        $omodelo = new m_modelo();
        extract($_POST);
        $tipo = $omodelo->link->real_escape_string($tipo);

        if ($tipo == 'GuardarTraslado') {
            $origen    = $omodelo->link->real_escape_string($origen);
            $destino   = $omodelo->link->real_escape_string($destino);
            $fecha     = $omodelo->link->real_escape_string($fecha);
            $estatus   = $omodelo->link->real_escape_string($estatus);
            $productos = json_decode($productos, true);
            $usuario   = $_SESSION['user_admin']['ID_Usuario'];

            // El select de estatus está "disabled" en la vista y no se envía; por defecto es Solicitud.
            if ($estatus == '') {
                $estatus = 'Solicitud';
            }

            $query = "INSERT INTO traslados (FK_Sucursal_Origen, FK_Sucursal_Destino, Fecha_Traslado, Estatus, FK_Usuario, Fecha_Registro)
                VALUES ('$origen','$destino','$fecha','$estatus','$usuario', NOW())";
            $error = $omodelo->_insertar($query);

            if ($error == 'si') {
                echo "Error 1: " . mysqli_error($omodelo->link);
                return;
            }
            $idTraslado = mysqli_insert_id($omodelo->link);

            foreach ($productos as $p) {
                $prod  = $omodelo->link->real_escape_string($p['producto']);
                $pres  = $omodelo->link->real_escape_string($p['presentacion']);
                $cant  = $omodelo->link->real_escape_string($p['cantidad']);

                $q2 = "INSERT INTO detalles_traslados (FK_Traslado, FK_Producto, FK_Presentacion, Cantidad, Estatus)
                    VALUES ('$idTraslado','$prod','$pres','$cant','Pendiente')";
                $e2 = $omodelo->_insertar($q2);
                if ($e2 == 'si') {
                    echo "Error 2: " . mysqli_error($omodelo->link);
                    return;
                }

                // Descontar inventario origen si pasa a En tránsito directo
                if ($estatus == 'En transito') {
                    $q3 = "UPDATE inventario SET Cantidad = Cantidad - '$cant' WHERE FK_Producto = '$prod' AND FK_Presentacion = '$pres' AND FK_Sucursal = '$origen'";
                    $omodelo->_insertar($q3);
                }
            }

            $omodelo->movimiento($query, $usuario);
            echo "Correcto";
        } else if ($tipo == 'GuardarAccion') {
            $traslado    = $omodelo->link->real_escape_string($traslado);
            $producto    = $omodelo->link->real_escape_string($producto);
            $presentacion = $omodelo->link->real_escape_string($presentacion);
            $accionTipo  = $omodelo->link->real_escape_string($accion_tipo);
            $observacion = $omodelo->link->real_escape_string($observacion);
            $usuario     = $_SESSION['user_admin']['ID_Usuario'];

            // Buscamos si ya existe este producto en este traslado
            $buscar = mysqli_query($omodelo->link, "SELECT ID_Verificar FROM verificar_traslado 
                WHERE FK_Traslado = '$traslado' 
                AND FK_Producto = '$producto' 
                AND FK_Presentacion = '$presentacion' 
                AND Tipo = 'Entrada' LIMIT 1");

            if ($buscar && mysqli_num_rows($buscar) > 0) {
                $qV = "UPDATE verificar_traslado 
                    SET Accion = '$accionTipo', Observaciones = '$observacion', Estatus_Accion = 'Enviada'
                    WHERE FK_Traslado = '$traslado' 
                    AND FK_Producto = '$producto' 
                    AND FK_Presentacion = '$presentacion' 
                    AND Tipo = 'Entrada'";
            } else {
                $qV = "INSERT INTO verificar_traslado (FK_Traslado, FK_Producto, FK_Presentacion, Tipo, Accion, Observaciones, Estatus_Accion)
                   VALUES ('$traslado','$producto','$presentacion','Entrada', '$accionTipo', '$observacion', 'Enviada')";
            }

            $error = $omodelo->_insertar($qV);
            if ($error == 'si') {
                echo "Error: " . mysqli_error($omodelo->link);
                return;
            }

            $omodelo->movimiento($qV, $usuario);
            echo "Correcto";
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MODIFICAR
    // ─────────────────────────────────────────────────────────────────────────
    public function _modificar()
    {
        $omodelo = new m_modelo();
        extract($_POST);
        $tipo    = $omodelo->link->real_escape_string($tipo);
        $usuario = $_SESSION['user_admin']['ID_Usuario'];

        // ── Editar traslado ───────────────────────────────────────────────
        if ($tipo == 'GuardarTraslado') {
            $id       = $omodelo->link->real_escape_string($id);
            $origen   = $omodelo->link->real_escape_string($origen);
            $destino  = $omodelo->link->real_escape_string($destino);
            $fecha    = $omodelo->link->real_escape_string($fecha);
            $estatus  = $omodelo->link->real_escape_string($estatus);
            $productos = json_decode($productos, true);

            if ($estatus == '') {
                $estatus = 'Solicitud';
            }

            $query = "UPDATE traslados SET FK_Sucursal_Origen='$origen', FK_Sucursal_Destino='$destino', Fecha_Traslado='$fecha', Estatus='$estatus' WHERE ID_Traslado='$id'";
            $error = $omodelo->_insertar($query);
            if ($error == 'si') {
                echo "Error 1: " . mysqli_error($omodelo->link);
                return;
            }

            // Eliminar detalles anteriores y reinsertar
            $omodelo->_insertar("DELETE FROM detalles_traslados WHERE FK_Traslado='$id'");

            foreach ($productos as $p) {
                $prod = $omodelo->link->real_escape_string($p['producto']);
                $pres = $omodelo->link->real_escape_string($p['presentacion']);
                $cant = $omodelo->link->real_escape_string($p['cantidad']);
                $q2   = "INSERT INTO detalles_traslados (FK_Traslado, FK_Producto, FK_Presentacion, Cantidad, Estatus)
                    VALUES ('$id','$prod','$pres','$cant','Pendiente')";
                $omodelo->_insertar($q2);
            }

            $omodelo->movimiento($query, $usuario);
            echo "Correcto";
        }

        // ── Guardar concentrado (origen surte) ────────────────────────────
        else if ($tipo == 'GuardarConcentrado') {
            $id      = $omodelo->link->real_escape_string($id);
            $detalles = json_decode($detalles, true);

            // Obtener traslado
            $qT = "SELECT FK_Sucursal_Origen FROM traslados WHERE ID_Traslado = '$id' LIMIT 1";
            $rowT = $omodelo->_consultar($qT);
            if ($rowT == 'si') {
                echo "Error 1";
                return;
            }
            $origen = $rowT[0]['FK_Sucursal_Origen'];

            foreach ($detalles as $d) {
                $detId     = $omodelo->link->real_escape_string($d['detalle']);
                $lote      = $omodelo->link->real_escape_string($d['lote']);
                $enviado   = $omodelo->link->real_escape_string($d['enviado']);
                $verificado = $omodelo->link->real_escape_string($d['verificado']);
                $estDetalle = ($verificado >= $enviado && $enviado > 0) ? 'Completado' : 'Pendiente';

                $q = "UPDATE detalles_traslados SET FK_Lote='$lote', Cantidad_Enviada='$enviado', Cantidad_Verificada='$verificado', Estatus='$estDetalle'
                    WHERE ID_Detalle_Traslado='$detId'";
                $omodelo->_insertar($q);

                // Descontar del inventario origen y del lote
                if ($enviado > 0) {
                    $qProd = "SELECT FK_Producto, FK_Presentacion FROM detalles_traslados WHERE ID_Detalle_Traslado='$detId' LIMIT 1";
                    $rowProd = $omodelo->_consultar($qProd);
                    if ($rowProd != 'si' && $omodelo->numerofilas > 0) {
                        $prod = $rowProd[0]['FK_Producto'];
                        $pres = $rowProd[0]['FK_Presentacion'];
                        $omodelo->_insertar("UPDATE inventario SET Cantidad = Cantidad - '$enviado' WHERE FK_Producto='$prod' AND FK_Presentacion='$pres' AND FK_Sucursal='$origen'");
                        if ($lote) {
                            $omodelo->_insertar("UPDATE lotes SET Cantidad = Cantidad - '$enviado' WHERE ID_Lote='$lote'");
                        }
                    }
                }
            }

            // Verificar si todos los detalles están completados → marcar traslado como En tránsito
            $qCheck = "SELECT COUNT(*) AS Total, SUM(IF(Estatus='Completado',1,0)) AS Completos FROM detalles_traslados WHERE FK_Traslado='$id'";
            $rowCheck = $omodelo->_consultar($qCheck);
            if ($rowCheck != 'si' && $rowCheck[0]['Total'] == $rowCheck[0]['Completos']) {
                $omodelo->_insertar("UPDATE traslados SET Estatus='En transito', Detalles=NOW() WHERE ID_Traslado='$id'");
            } else {
                $omodelo->_insertar("UPDATE traslados SET Detalles=NOW() WHERE ID_Traslado='$id'");
            }

            $omodelo->movimiento("Concentrado guardado traslado $id", $usuario);
            echo "Correcto";
        }

        // ── Aceptar / Rechazar reposición (solo cambia Estatus_Accion) ───────────
        else if ($tipo == 'EstatusAccion') {
            $traslado    = $omodelo->link->real_escape_string($traslado);
            $producto    = $omodelo->link->real_escape_string($producto);
            $presentacion = $omodelo->link->real_escape_string($presentacion);
            $estatus     = $omodelo->link->real_escape_string($estatus); // Aceptada | Rechazada

            $query = "UPDATE verificar_traslado 
            SET Estatus_Accion = '$estatus'
            WHERE FK_Traslado = '$traslado'
            AND FK_Producto = '$producto'
            AND FK_Presentacion = '$presentacion'
            AND Tipo = 'Entrada'";

            $error = $omodelo->_insertar($query);
            if ($error == 'si') {
                echo "Error: " . mysqli_error($omodelo->link);
                return;
            }
            $omodelo->movimiento($query, $usuario);
            echo "Correcto";
        }

        // ── Merma: registra la diferencia como merma en inventario destino ────────
        else if ($tipo == 'MermaTraslado') {
            $traslado    = $omodelo->link->real_escape_string($traslado);
            $producto    = $omodelo->link->real_escape_string($producto);
            $presentacion = $omodelo->link->real_escape_string($presentacion);
            $faltante    = $omodelo->link->real_escape_string($faltante);
            $motivo      = $omodelo->link->real_escape_string($motivo);

            // Obtener sucursal destino
            $rowT = $omodelo->_consultar("SELECT FK_Sucursal_Destino FROM traslados WHERE ID_Traslado='$traslado' LIMIT 1");
            if ($rowT == 'si' || $omodelo->numerofilas == 0) {
                echo "Error 1";
                return;
            }
            $destino = $rowT[0]['FK_Sucursal_Destino'];

            // Obtener ID_Inventario del destino
            $rowI = $omodelo->_consultar("SELECT ID_Inventario, Costo FROM inventario 
            INNER JOIN productos ON FK_Producto = ID_Producto
            WHERE FK_Producto = '$producto' AND FK_Presentacion = '$presentacion' AND FK_Sucursal = '$destino' LIMIT 1");
            if ($rowI == 'si' || $omodelo->numerofilas == 0) {
                echo "Error 2";
                return;
            }

            $idInventario = $rowI[0]['ID_Inventario'];
            $costo        = $rowI[0]['Costo'] ?? 0;

            $qMerma = "INSERT INTO merma (FK_Inventario, Costo, Cantidad, Fecha_Merma, Fecha_Registro, Motivo, FK_Usuario, Traslado)
            VALUES ('$idInventario','$costo','$faltante', CURDATE(), NOW(), '$motivo', '$usuario', 1)";
            $error = $omodelo->_insertar($qMerma);
            if ($error == 'si') {
                echo "Error 3: " . mysqli_error($omodelo->link);
                return;
            }

            // Marcar acción como procesada
            $omodelo->_insertar("UPDATE verificar_traslado SET Estatus_Accion = 'Procesada'
            WHERE FK_Traslado = '$traslado' AND FK_Producto = '$producto' AND FK_Presentacion = '$presentacion' AND Tipo = 'Entrada'");

            $omodelo->movimiento($qMerma, $usuario);
            echo "Correcto";
        }

        // ── Devolver a inventario origen (con lotes si los hay) ───────────────────
        else if ($tipo == 'DevolverInveTraslado') {
            $traslado    = $omodelo->link->real_escape_string($traslado);
            $producto    = $omodelo->link->real_escape_string($producto);
            $presentacion = $omodelo->link->real_escape_string($presentacion);
            $faltante    = floatval($faltante);

            // Obtener sucursal origen
            $rowT = $omodelo->_consultar("SELECT FK_Sucursal_Origen FROM traslados WHERE ID_Traslado='$traslado' LIMIT 1");
            if ($rowT == 'si' || $omodelo->numerofilas == 0) {
                echo "Error 1";
                return;
            }
            $origen = $rowT[0]['FK_Sucursal_Origen'];

            // Sumar al inventario general de origen
            $omodelo->_insertar("UPDATE inventario SET Cantidad = Cantidad + '$faltante'
            WHERE FK_Producto = '$producto' AND FK_Presentacion = '$presentacion' AND FK_Sucursal = '$origen'");

            // Obtener lotes de salida
            $rowSalida = $omodelo->_consultar("SELECT vdl.FK_Lote, vdl.Cantidad AS Cant_Salida
            FROM verificar_detalles_lotes vdl
            INNER JOIN verificar_traslado vt ON vdl.FK_Verificar = vt.ID_Verificar
            WHERE vt.FK_Traslado = '$traslado'
            AND vt.FK_Producto = '$producto'
            AND vt.FK_Presentacion = '$presentacion'
            AND vt.Tipo = 'Salida'");
            $nfSalida = $omodelo->numerofilas;

            // Obtener lotes de entrada
            $rowEntrada = $omodelo->_consultar("SELECT vdl.FK_Lote, vdl.Cantidad AS Cant_Entrada
            FROM verificar_detalles_lotes vdl
            INNER JOIN verificar_traslado vt ON vdl.FK_Verificar = vt.ID_Verificar
            WHERE vt.FK_Traslado = '$traslado'
            AND vt.FK_Producto = '$producto'
            AND vt.FK_Presentacion = '$presentacion'
            AND vt.Tipo = 'Entrada'");
            $nfEntrada = $omodelo->numerofilas;

            // Solo devolver por lote si hay lotes registrados en entrada
            if ($rowSalida != 'si' && $nfSalida > 0 && $rowEntrada != 'si' && $nfEntrada > 0) {

                // Indexar entrada por lote para comparar fácil
                $lotesEntrada = [];
                for ($i = 0; $i < $nfEntrada; $i++) {
                    $lotesEntrada[$rowEntrada[$i]['FK_Lote']] = floatval($rowEntrada[$i]['Cant_Entrada']);
                }

                // Por cada lote de salida calcular cuánto falta regresar
                for ($i = 0; $i < $nfSalida; $i++) {
                    $idLote      = $rowSalida[$i]['FK_Lote'];
                    $cantSalida  = floatval($rowSalida[$i]['Cant_Salida']);
                    $cantEntrada = $lotesEntrada[$idLote] ?? 0;
                    $diferencia  = $cantSalida - $cantEntrada;

                    if ($diferencia > 0) {
                        $difEsc = $omodelo->link->real_escape_string($diferencia);
                        $loteEsc = $omodelo->link->real_escape_string($idLote);
                        $omodelo->_insertar("UPDATE lotes SET Cantidad = Cantidad + '$difEsc'
                        WHERE ID_Lote = '$loteEsc'");
                    }
                }
            }

            // Marcar acción como procesada
            $omodelo->_insertar("UPDATE verificar_traslado SET Estatus_Accion = 'Procesada'
            WHERE FK_Traslado = '$traslado' AND FK_Producto = '$producto' AND FK_Presentacion = '$presentacion' AND Tipo = 'Entrada'");

            $omodelo->movimiento("Devolucion inventario traslado $traslado producto $producto", $usuario);
            echo "Correcto";
        }

        // ── Completar traslado ────────────────────────────────────────────
        else if ($tipo == 'completarTraslado') {
            $id = $omodelo->link->real_escape_string($id);

            $query = "UPDATE traslados SET Estatus = 'Completado' WHERE ID_Traslado = '$id'";
            $error = $omodelo->_insertar($query);

            if ($error == 'si') {
                echo "Error 1: " . mysqli_error($omodelo->link);
            } else {
                echo "Correcto";
                $omodelo->movimiento($query, $usuario);
            }
        }

        // ── Cancelar traslado (NUEVO: el JS lo llamaba pero no existía) ───
        else if ($tipo == 'CancelarTraslado') {
            $id     = $omodelo->link->real_escape_string($id);
            $motivo = $omodelo->link->real_escape_string($motivo ?? '');

            $permisos = $omodelo->permisos();
            if ($permisos != 'Administrador' && @$permisos['v_traslados'][6] != '1') {
                echo "No tienes permiso para cancelar traslados";
                return;
            }

            // Solo se cancela un traslado Completado (igual que la condición del botón)
            $query = "UPDATE traslados SET Estatus = 'Cancelado', Detalles = NOW() WHERE ID_Traslado = '$id' AND Estatus = 'Completado'";
            $error = $omodelo->_insertar($query);

            if ($error == 'si') {
                echo "Error 1: " . mysqli_error($omodelo->link);
                return;
            }

            if (mysqli_affected_rows($omodelo->link) == 0) {
                echo "El traslado no se encuentra en estatus Completado";
                return;
            }

            $omodelo->movimiento("Cancelacion traslado $id. Motivo: $motivo", $usuario);
            echo "Correcto";
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ELIMINAR
    // ─────────────────────────────────────────────────────────────────────────
    public function _eliminar()
    {
        $omodelo = new m_modelo();
        extract($_POST);
        $tipo    = $omodelo->link->real_escape_string($tipo);
        $usuario = $_SESSION['user_admin']['ID_Usuario'];

        if ($tipo == 'EliminarTraslado') {
            $id = $omodelo->link->real_escape_string($id);

            $query = "DELETE FROM traslados WHERE ID_Traslado='$id'";
            $error = $omodelo->_insertar($query);
            if ($error == 'si') {
                echo "Error: " . mysqli_error($omodelo->link);
                return;
            }
            $omodelo->movimiento($query, $usuario);
            echo "Correcto";
        }
    }
}
