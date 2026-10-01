<?php
class reporteInventarioGeneral {

	public function _consultar() {
        $omodelo = new m_modelo();
        extract($_POST);
    
        // $buscar =  $omodelo->link->real_escape_string($buscar);
        // $limit =  $omodelo->link->real_escape_string($limit);
        // $pagina =  $omodelo->link->real_escape_string($pagina);
        // $ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
        // $orden =  $omodelo->link->real_escape_string($orden);
        // $qWhere = "";
        // $qAnd = "";
        $qSucursales = "";
        $qProveedores = "";

        // Traer los proveedores seleccionados en el filtro proveedores.
        $cadenaProveedores = "";
		$proveedores = json_decode($Proveedores, true);
		foreach ($proveedores as $proveedor) {
            if ($proveedor["ID"] == "todos") {
                $cadenaProveedores = "";
            } else {
                $cadenaProveedores .= $proveedor["ID"].",";
            }
		}
		$stringProveedores = rtrim($cadenaProveedores, ",");
		if ($stringProveedores != "") {
			$qProveedores = "WHERE detalles_proveedores_productos.FK_Proveedor IN(".$stringProveedores.")";
		}

        // Traer las sucursales seleccionadas del filtro sucursales.
        $cadenaSucursales = "";
		$sucursales = json_decode($Sucursales, true);
		foreach ($sucursales as $sucursal) {
            if ($sucursal["ID"] == "todas") {
                $cadenaSucursales = "";
            } else {
                $cadenaSucursales .= $sucursal["ID"].",";
            }
		}
		$string = rtrim($cadenaSucursales, ",");
		if ($string != "") {
			$qSucursales = "WHERE sucursales.ID_Sucursal IN(".$string.")";
		}
		
		// if ($qSucursales != "" || $qProveedores != "") {
		// 	$qWhere = "WHERE";
		// }

		// if ($qSucursales != "" && $qProveedores != "") {
		// 	$qAnd = " AND ";
		// }

        // Consultar sucursales.
        $querySucursales = "SELECT ID_Sucursal, Nombre FROM sucursales $qSucursales";
        $resultSucursales = $omodelo->_consultar($querySucursales);
        $numerofilasSucursales = $omodelo->numerofilas;
        
        $sucursalesColumnas = [];
        for($i = 0; $i < $numerofilasSucursales; $i++){
            // echo $resultSucursales[$i]['Nombre'];
            $sucursalesColumnas[] = "SUM(CASE WHEN FK_Sucursal = {$resultSucursales[$i]['ID_Sucursal']} THEN Cantidad ELSE 0 END) AS `{$resultSucursales[$i]['Nombre']}`";
        }
        // echo $numerofilasSucursales;
    
        // foreach ($resultSucursales as $sucursal) {
        //     $sucursalesColumnas[] = "SUM(CASE WHEN FK_Sucursal = {$sucursal['ID_Sucursal']} THEN Cantidad ELSE 0 END) AS `{$sucursal['Nombre']}`";
        // }
        
        $columnasPivot = implode(", ", $sucursalesColumnas);

        $query = "SELECT 
            IFNULL(presentaciones.Codigo, productos.Codigo) AS Codigo,
            IFNULL(presentaciones.Costo, productos.Costo) AS Costo,
            IFNULL(presentaciones.Codigo, '') AS Cod_Presentacion,
            IFNULL(productos.Codigo, '') AS Cod_Producto,
            Descripcion,
            IFNULL(presentaciones.Nombre, '') AS Nombre_Presentacion,
            $columnasPivot,
            SUM(cantidad) AS Total_Existencias,
            IFNULL((SELECT Empresa FROM proveedores WHERE FK_Proveedor = ID_Proveedor), 'Sin proveedor') AS Proveedor
        FROM inventario
        INNER JOIN productos ON FK_Producto = ID_Producto
        INNER JOIN presentaciones ON FK_Presentacion = ID_Presentacion
        LEFT JOIN detalles_proveedores_productos ON detalles_proveedores_productos.FK_Producto = inventario.FK_Producto $qProveedores
        GROUP BY Codigo, Cod_Presentacion, Cod_Producto, Descripcion, Nombre_Presentacion, Proveedor";
        // echo $query;
        $row = $omodelo->_consultar($query);
        $numerofilas = $omodelo->numerofilas;
        $arreglo = [];
    
        if ($row == 'si') {
            echo "Error: ".mysqli_error($omodelo->link);
        } else {
            if ($numerofilas > 0) {
                $trTabla = '';
                for ($i=0; $i<$numerofilas; $i++) {
                    $sumaTotalesSucursales = 0;
                    $trSucursales = '';
                    $trTabla .= '<tr>
                    <td>'.$row[$i]["Codigo"].'</td>
                    <td>'.$row[$i]["Descripcion"]. ' (' .$row[$i]["Nombre_Presentacion"]. ')</td>
                    <td>$'.$row[$i]["Costo"].'</td>
                    <td>'.$row[$i]["Proveedor"].'</td>';
                    
                    for ($j = 0; $j < $numerofilasSucursales; $j++) {
                        $trSucursales .= '<td>'.$row[$i][$resultSucursales[$j]['Nombre']].'</td>';
                        $sumaTotalesSucursales += $row[$i][$resultSucursales[$j]['Nombre']];
                    }
                    
                    $costoTotal = $row[$i]["Costo"] * $sumaTotalesSucursales;
                    $trTabla .= '<td>' .$sumaTotalesSucursales. '</td>' . $trSucursales. '<td>$'.$costoTotal.'</td></tr>';
                    // $producto = [
                    //     'ID' => $row[$i]["Codigo"],
                    //     'Codigo' => $row[$i]["Codigo"],
                    //     'Descripcion' => $row[$i]["Descripcion"] . $row[$i]["Nombre_Presentacion"],
                    //     'Costo' => $row[$i]["Costo"],
                    //     'Proveedor' => $row[$i]["Proveedor"],
                    //     'Total_Existencias' => $row[$i]["Total_Existencias"],
                    // ];
                    
                    // 
            
                    
                }
                $arreglo['filas'] = ['Filas' => $trTabla];
                // echo $trTabla;
            }
            $arreglo['sucursales'] = [];
            $thSucursales = '<tr><th>Codigo</th>
		                        <th>Descripción</th>
								<th>Costo Neto</th>
		                        <th>Proveedor</th>
		                        <th>Existencias totales</th>';
            foreach ($resultSucursales as $sucursal) {
                if (!is_null($sucursal) && isset($sucursal["Nombre"])) {
                    $thSucursales .= '<th>'.$sucursal["Nombre"].'</th>';
                }
            }
            $thSucursales .= '<th>Costo total</th></tr>';
            $arreglo['Sucursales'] = ['Sucursales' => $thSucursales];
            // $arreglo['totales'] = ['NumRows' => $numerofilas];
            echo json_encode($arreglo);
        }
    }
    

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST); 
		$fecha = date('Y-m-d H:i:s'); 
		$IDVenta = $omodelo->link->real_escape_string($IDVenta);
		$IDSucursal = $omodelo->link->real_escape_string($IDSucursal);
		$Regresar = $omodelo->link->real_escape_string($Regresar);
		$Motivo = $omodelo->link->real_escape_string($Motivo);
		$queryRegresar = ', Regreso_Inventario = "0"';
		if ($Regresar == "Si") {
			$queryRegresar = ', Regreso_Inventario = "1"';
			$query = "SELECT ID_Detalle_Venta, FK_Producto, FK_Presentacion, Cantidad FROM detalles_ventas WHERE FK_Venta = '$IDVenta'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$query1 = "UPDATE inventario SET Cantidad = (Cantidad + '".$row[$i]["Cantidad"]."') WHERE FK_Producto = '".$row[$i]["FK_Producto"]."' AND FK_Presentacion = '".$row[$i]["FK_Presentacion"]."' AND FK_Sucursal = '".$IDSucursal."'";
						$error1 = $omodelo->_insertar($query1);
						if ($error1 == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}else{
							$query2 = "UPDATE detalles_ventas SET Regreso_Inventario = '1' WHERE ID_Detalle_Venta = '".$row[$i]["ID_Detalle_Venta"]."'";
							$error2 = $omodelo->_insertar($query2);
							if ($error2 == "si") {
								echo "Error 3: ".mysqli_error($omodelo->link);
							}
						}
					}
				}
			}
		}
		$query = "UPDATE ventas SET Notas = '$Motivo', Estatus = 'Cancelada',  Fecha_Cancelacion = '$fecha' $queryRegresar WHERE ID_Venta = '$IDVenta'";
		$error = $omodelo->_insertar($query);
		if ($error == "si") {
			echo "Error 5: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}	
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$IDVenta = $omodelo->link->real_escape_string($IDVenta);

		$query = "DELETE FROM ventas WHERE ID_Venta = '$IDVenta'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}	
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		if($tipo == 'ConsultarSucursalesRInventarioGral'){
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();
            $sucursalesSeleccionadas = json_decode($SucursalesCheckeadas, true);

            // Filtro de la barra de busqueda de la tabla sucursales en el modal que es invocado por el boton de filtrar sucursales.
			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(ID_Sucursal, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Email, FK_Encargado, usuarios.Nombre,  usuarios.Primer_Apellido, usuarios.Segundo_Apellido, zonas.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Sucursal, '' AS Seleccionar, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Segundo_Telefono, Email, FK_Encargado, usuarios.Nombre AS NombreEncargado, usuarios.Primer_Apellido AS PrimerApellido, usuarios.Segundo_Apellido AS SegundoApellido, FK_Zona, zonas.Nombre AS NombreZona, Latitud, Longitud, (SELECT COUNT(*) FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda) AS Num FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
            // echo $query;
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
                    
                    $checkboxSucursalesTodas = '<input type="checkbox" class="CheckInputSucursal" nombre="Todas" attrid="todas">';

					for($i=0; $i<$numerofilas; $i++){
						$direccion = "";
                        $telefono = "";
                        $email = "";
                        $gerente = "";
                        $checkboxSucursal = '<input type="checkbox" class="CheckInputSucursal" nombre="'.$row[$i]['Nombre'].'" attrid="'.$row[$i]['ID_Sucursal'].'">';

						if ($row[$i]['NombreZona'] != "") {
							$direccion .= "Zona: <b>".$row[$i]['NombreZona'].'</b><br>';
						}

						if ($row[$i]['Calle'] != "") {
							$direccion .= "Calle: <b>".$row[$i]['Calle'].'</b><br>';
						}

						if ($row[$i]['No_Exterior'] != "") {
							$direccion .= "No. Exterior: <b>".$row[$i]['No_Exterior'].'</b><br>';
						}

						if ($row[$i]['No_Interior'] != "") {
							$direccion .= "No. Interior: <b>".$row[$i]['No_Interior'].'</b><br>';
						}

						if ($row[$i]['Colonia'] != "") {
							$direccion .= "Colonia: <b>".$row[$i]['Colonia'].'</b><br>';
						}

						if ($row[$i]['CP'] != "") {
							$direccion .= "Codigo postal: <b>".$row[$i]['CP'].'</b><br>';
						}

						if ($row[$i]['Ciudad'] != "") {
							$direccion .= "Ciudad: <b>".$row[$i]['Ciudad'].'</b><br>';
						}

						if ($row[$i]['Estado'] != "") {
							$direccion .= "Estado: <b>".$row[$i]['Estado'].'</b><br>';
						}

						if ($row[$i]['Pais'] != "") {
							$direccion .= "País: <b>".$row[$i]['Pais'].'</b><br>';
						}

						if ($row[$i]['Telefono'] != "") {
							$telefono .= "Primer teléfono: <b>".$row[$i]['Telefono'].'</b><br>';
						}

						if ($row[$i]['Segundo_Telefono'] != "") {
							$telefono .= "Segundo teléfono: <b>".$row[$i]['Segundo_Telefono'].'</b><br>';
						}

						if ($row[$i]['Latitud'] != '' && $row[$i]['Longitud'] != '') {
							$telefono .= 'Ubicación: <b>'.$row[$i]['Latitud'].', '.$row[$i]['Longitud'].'</b>';
						}
						
						if ($telefono == "") {
							$telefono = "No hay telefono registrado";
						}
						
						if ($row[$i]['Email'] != "") {
							$email = $row[$i]['Email'];
						}else{	
							$email = "No hay un correo registrado";
						}

						if ($row[$i]['FK_Encargado'] != "") {
							$gerente = $row[$i]['NombreEncargado'].' '.$row[$i]['PrimerApellido'].' '.$row[$i]['SegundoApellido'];
						}else{	
							$gerente = "No hay datos registrados";
						}

                        foreach ($sucursalesSeleccionadas as $sucursalSeleccionada) {
                            if ($row[$i]['ID_Sucursal'] == $sucursalSeleccionada["ID"]) {
                                $checkboxSucursal = '<input type="checkbox" class="CheckInputSucursal" nombre="'.$row[$i]['Nombre'].'" attrid="'.$row[$i]['ID_Sucursal'].'" checked>';
                            }
                        }

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Sucursal'],
							'Seleccionar' => $checkboxSucursal,
							'Sucursal' => $row[$i]['Nombre'],
							'Direccion' => $direccion
						);
						
					}

                    foreach ($sucursalesSeleccionadas as $sucursalSeleccionadaOptTodas) {
                        if ($sucursalSeleccionadaOptTodas["ID"] == 'todas') {
                            $checkboxSucursalesTodas = '<input type="checkbox" class="CheckInputSucursal" nombre="Todas" attrid="todas" checked>';
                        } else {
                            $checkboxSucursalesTodas = '<input type="checkbox" class="CheckInputSucursal" nombre="Todas" attrid="todas">';
                        }
                    }

                    $sucursalesTodas = array(
                        'ID' => 'Todas',
                        'Seleccionar' => $checkboxSucursalesTodas,
                        'Sucursal' => '<b>TODAS</b>',
                        'Direccion' => '<b>Todas las sucursales</b>',
                    );

                    array_unshift($arreglo['data'], $sucursalesTodas);
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			
			echo json_encode($arreglo);
		}if($tipo == 'ConsultarProveedoresRInventarioGral'){
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();
            $proveedoresSeleccionados = json_decode($ProveedoresCheckeados, true);

            // Filtro de la barra de busqueda de la tabla proveedores en el modal que es invocado por el boton de filtrar proveedores.
			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(proveedores.ID_Proveedor, proveedores.Nombre, proveedores.Empresa, proveedores.Razon_Social) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT proveedores.ID_Proveedor AS ID_Proveedor, '' AS Seleccionar, proveedores.Nombre AS Nombre_Proveedor, proveedores.Empresa AS Empresa_Proveedor, proveedores.Razon_Social AS Razon_Social_Proveedor, (SELECT COUNT(*) FROM proveedores $busqueda) AS Num FROM proveedores $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
            // echo $query;
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){

                    $checkboxProveedoresTodos = '<input type="checkbox" class="CheckInputProveedor" nombre="Todos" attrid="todos">';

					for($i=0; $i<$numerofilas; $i++){
						$nombreProveedor = "";
                        $empresa = "";
                        $razonSocial = "";
                        $checkboxProveedor = '<input type="checkbox" class="CheckInputProveedor" nombre="'.$row[$i]['Empresa_Proveedor'].'" attrid="'.$row[$i]['ID_Proveedor'].'">';

						if ($row[$i]['Nombre_Proveedor'] != "") {
							$nombreProveedor .= "Nombre: <b>" .$row[$i]['Nombre_Proveedor']. "</b><br>";
						}

						if ($row[$i]['Empresa_Proveedor'] != "") {
							$empresa .= "Empresa: <b>" .$row[$i]['Empresa_Proveedor']. "</b><br>";
						}

						if ($row[$i]['Razon_Social_Proveedor'] != "") {
							$razonSocial .= "Razon social: <b>" .$row[$i]['Razon_Social_Proveedor']. "</b>";
						}

                        foreach ($proveedoresSeleccionados as $proveedorSeleccionado) {
                            if ($row[$i]['ID_Proveedor'] == $proveedorSeleccionado["ID"]) {
                                $checkboxProveedor = '<input type="checkbox" class="CheckInputProveedor" nombre="'.$row[$i]['Empresa_Proveedor'].'" attrid="'.$row[$i]['ID_Proveedor'].'" checked>';
                            }
                        }

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Proveedor'],
							'Seleccionar' => $checkboxProveedor,
							'Proveedor' => $nombreProveedor . $empresa . $razonSocial,
						);
						
					}

                    foreach ($proveedoresSeleccionados as $proveedorSeleccionadoOptTodos) {
                        if ($proveedorSeleccionadoOptTodos["ID"] == 'todos') {
                            $checkboxProveedoresTodos = '<input type="checkbox" class="CheckInputProveedor" nombre="Todos" attrid="todos" checked>';
                        } else {
                            $checkboxProveedoresTodos = '<input type="checkbox" class="CheckInputProveedor" nombre="Todos" attrid="todos">';
                        }
                    }

                    $proveedoresTodos = array(
                        'ID' => 'Todos',
                        'Seleccionar' => $checkboxProveedoresTodos,
                        'Proveedor' => '<b>Todos los proveedores</b>',
                    );

                    array_unshift($arreglo['data'], $proveedoresTodos);
					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			
			echo json_encode($arreglo);
		}
	}
}
?>
