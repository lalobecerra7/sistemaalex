<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';
include '../../modelo/m_modelo.php';
session_start();

$omodelo      = new m_modelo();
$id           = intval($_GET['id'] ?? 0);
$tipo         = $_GET['tipo'] ?? 'concentrado';
$producto     = intval($_GET['producto'] ?? 0);
$presentacion = intval($_GET['presentacion'] ?? 0);

if (!$id) die('ID inválido');

// ── Datos del traslado ────────────────────────────────────────────────────────
$qT = "SELECT t.*,
    DATE_FORMAT(t.Fecha_Registro,'%d-%m-%Y %h:%i:%s %p') AS FechaReg,
    DATE_FORMAT(t.Fecha_Traslado,'%d-%m-%Y') AS FechaTras,
    IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = t.FK_Sucursal_Origen),'') AS Origen,
    IFNULL((SELECT Nombre FROM sucursales WHERE ID_Sucursal = t.FK_Sucursal_Destino),'') AS Destino
    FROM traslados t WHERE ID_Traslado = $id LIMIT 1";
$resT     = $omodelo->_consultar($qT);
$traslado = $resT[0] ?? null;
if (!$traslado) die('Traslado no encontrado');

$folio = str_pad($id, 8, '0', STR_PAD_LEFT);

// ── Query base de productos ───────────────────────────────────────────────────
$whereProducto = ($tipo == 'accion' && $producto)
    ? " AND dt.FK_Producto = $producto AND dt.FK_Presentacion = $presentacion"
    : '';

$qBase = "SELECT
    dt.ID_Detalle_Traslado, dt.FK_Producto, dt.FK_Presentacion,
    dt.Cantidad AS Cantidad_Solicitada, dt.Estatus,
    IFNULL(IF(dt.FK_Presentacion = 0, p.Codigo, pr.Codigo), p.Codigo) AS Codigo,
    p.Descripcion,
    IFNULL(IF(dt.FK_Presentacion = 0, CONCAT(p.Nombre_Unidad,' ',p.Abreviatura_Unidad), CONCAT(pr.Nombre,' ',pr.Abreviatura)),'Sin presentación') AS Presentacion,
    IFNULL((SELECT SUM(Cantidad) FROM verificar_traslado WHERE FK_Traslado = $id AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Salida'), 0) AS Cantidad_Salida,
    IFNULL((SELECT SUM(Cantidad) FROM verificar_traslado WHERE FK_Traslado = $id AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada'), 0) AS Cantidad_Entrada,
    IFNULL((SELECT Accion FROM verificar_traslado WHERE FK_Traslado = $id AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada' LIMIT 1), '') AS Accion,
    IFNULL((SELECT Observaciones FROM verificar_traslado WHERE FK_Traslado = $id AND FK_Producto = dt.FK_Producto AND FK_Presentacion = dt.FK_Presentacion AND Tipo = 'Entrada' LIMIT 1), '') AS Observaciones,
    IFNULL((SELECT GROUP_CONCAT(
        CONCAT('<p class=\"m-0\" attrLote=\"', vdl.FK_Lote, '\">Lote - ', vdl.FK_Lote, ' ', IFNULL((SELECT Nombre FROM lotes WHERE ID_Lote = vdl.FK_Lote), ''), ' (<span class=\"cantidad\">', vdl.Cantidad, '</span>)</p>')
        SEPARATOR ''
    ) FROM verificar_detalles_lotes vdl
    INNER JOIN verificar_traslado vt ON vdl.FK_Verificar = vt.ID_Verificar
    WHERE vt.FK_Traslado = '$id' AND vt.FK_Producto = dt.FK_Producto AND vt.FK_Presentacion = dt.FK_Presentacion AND vt.Tipo = 'Salida'), '') AS Lotes_Salida,

    IFNULL((SELECT GROUP_CONCAT(
        CONCAT('<p class=\"m-0\" attrLote=\"', vdl.FK_Lote, '\">Lote - ', vdl.FK_Lote, ' ', IFNULL((SELECT Nombre FROM lotes WHERE ID_Lote = vdl.FK_Lote), ''), ' (<span class=\"cantidad\">', vdl.Cantidad, '</span>)</p>')
        SEPARATOR ''
    ) FROM verificar_detalles_lotes vdl
    INNER JOIN verificar_traslado vt ON vdl.FK_Verificar = vt.ID_Verificar
    WHERE vt.FK_Traslado = '$id' AND vt.FK_Producto = dt.FK_Producto AND vt.FK_Presentacion = dt.FK_Presentacion AND vt.Tipo = 'Entrada'), '') AS Lotes_Entrada
FROM detalles_traslados dt
INNER JOIN productos p ON dt.FK_Producto = p.ID_Producto
LEFT JOIN presentaciones pr ON dt.FK_Presentacion = pr.ID_Presentacion
WHERE dt.FK_Traslado = $id $whereProducto";

$resD     = $omodelo->_consultar($qBase);
$productos = ($resD != 'si' && $omodelo->numerofilas > 0) ? $resD : [];


$logoHtml = '<img src="/../../vistas/assets/img/favicon/favicon.jpg" style="height:55px; margin-bottom:6px;">';

// ── CSS común ─────────────────────────────────────────────────────────────────
$css = '
    body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
    .header { text-align: center; margin-bottom: 16px; }
    .header h1 { font-size: 18px; font-weight: bold; margin: 4px 0; }
    .header h2 { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 4px 0; letter-spacing: 1px; }
    .datos { margin-bottom: 12px; line-height: 1.8; }
    .folio { color: #0055aa; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 11px; }
    th, td { border: 1px solid #999; padding: 4px 6px; text-align: center; vertical-align: middle; }
    th { background-color: #f0f0f0; font-weight: bold; }
    .left { text-align: left; }
    .badge-completado { background: #28a745; color: #fff; padding: 1px 6px; border-radius: 8px; font-size: 10px; }
    .badge-pendiente  { background: #ffc107; color: #000; padding: 1px 6px; border-radius: 8px; font-size: 10px; }
    .badge-merma      { background: #dc3545; color: #fff; padding: 1px 6px; border-radius: 8px; font-size: 10px; }
    .badge-devolucion { background: #fd7e14; color: #fff; padding: 1px 6px; border-radius: 8px; font-size: 10px; }
    .badge-reposicion { background: #17a2b8; color: #fff; padding: 1px 6px; border-radius: 8px; font-size: 10px; }
    .text-danger { color: #dc3545; font-weight: bold; }
    .firmas { margin-top: 28px; line-height: 2.6; }
    .linea       { border-bottom: 1px solid #333; display: inline-block; width: 260px; }
    .linea-corta { border-bottom: 1px solid #333; display: inline-block; width: 140px; }
    .obs-box { border: 1px solid #999; min-height: 65px; padding: 6px; margin-top: 6px; }
    hr { border: none; border-top: 1px solid #ccc; margin: 12px 0; }
    .m-0 { margin: 0; }
';

// ── Cabecera común ────────────────────────────────────────────────────────────
$titulos = [
    'concentrado' => 'Concentrado de traslado',
    'accion'      => 'Reporte de acción',
];

$cabecera = '
<div class="header">
    ' . $logoHtml . '
    <h1>MISCELÁNEA RÍOS</h1>
    <h2>' . ($titulos[$tipo] ?? '') . '</h2>
</div>
<div class="datos">
    <p class="m-0"><strong>Fecha registro:</strong> ' . $traslado['FechaReg'] . '</p>
    <p class="m-0"><strong>Fecha traslado:</strong> ' . $traslado['FechaTras'] . '</p>
    <p class="m-0"><strong>Origen:</strong> ' . htmlspecialchars($traslado['Origen']) . '</p>
    <p class="m-0"><strong>Destino:</strong> ' . htmlspecialchars($traslado['Destino']) . '</p>
</div>
<p style="margin-bottom:10px;">
    <strong>Orden de traslado: </strong>
    <span class="folio">' . $folio . '</span>
</p>';

// ── Helpers ───────────────────────────────────────────────────────────────────
function badgeEstatus($entrada, $solicitada)
{
    return ($entrada >= $solicitada && $solicitada > 0)
        ? '<span class="badge-completado">Completado</span>'
        : '<span class="badge-pendiente">Pendiente</span>';
}

function badgeAccion($accion)
{
    $map = [
        'Merma'      => '<span class="badge-merma">Merma</span>',
        'Devolucion' => '<span class="badge-devolucion">Devolución</span>',
        'Reposicion' => '<span class="badge-reposicion">Reposición</span>',
    ];
    return $map[$accion] ?? '-';
}

function accionLabel($accion)
{
    $map = [
        'Merma'      => 'Registrar como merma',
        'Devolucion' => 'Devolver al CEDIS',
        'Reposicion' => 'Solicitar reposición',
    ];
    return $map[$accion] ?? $accion;
}

// ── Contenido según tipo ──────────────────────────────────────────────────────
$contenido = '';

if ($tipo == 'concentrado') {

    $filas = '';
    foreach ($productos as $p) {
        if (empty($p['Codigo']) || trim($p['Codigo']) == '') {
            continue;
        }
        
        $salida     = floatval($p['Cantidad_Salida']);
        $entrada    = floatval($p['Cantidad_Entrada']);
        $solicitada = floatval($p['Cantidad_Solicitada']);
        $diferencia = $entrada - $solicitada;
        $difTexto   = ($diferencia >= 0 ? '+' . $diferencia : $diferencia);
        $difClass   = $diferencia < 0 ? 'text-danger' : '';

        $filas .= '
        <tr>
            <td>' . htmlspecialchars($p['Codigo']) . '</td>
            <td class="left">' . htmlspecialchars($p['Descripcion']) . '</td>
            <td>' . htmlspecialchars($p['Presentacion']) . '</td>
            <td>' . $solicitada . '</td>
            <td>' . $salida . '</td>
            <td class="left">' . $p['Lotes_Salida'] . '</td>
            <td>' . $entrada . '</td>
            <td class="left">' . $p['Lotes_Entrada'] . '</td>
            <td class="' . $difClass . '">' . $difTexto . '</td>
            <td>' . badgeEstatus($entrada, $solicitada) . '</td>
            <td>' . badgeAccion($p['Accion']) . '</td>
            <td class="left">' . htmlspecialchars($p['Observaciones'] ?: '-') . '</td>
        </tr>';
    }

    $contenido = '
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Presentación</th>
                <th>Cant. pedida</th>
                <th>Salida</th>
                <th>Lotes salida</th>
                <th>Entrada</th>
                <th>Lotes entrada</th>
                <th>Diferencia</th>
                <th>Estatus</th>
                <th>Acción</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>' . $filas . '</tbody>
    </table>
    <div class="firmas">
        <p class="m-0"><b>Nombre del solicitante:</b> ____________________________________________________</p>
        <p class="m-0"><b>Cargo:</b> ____________________________________________________</p>
        <p class="m-0" style="margin-top:20px;"><b>Firma:</b> ____________________________________________________</p>
    </div>';
} elseif ($tipo == 'accion') {

    // Solo los que tienen acción si no viene filtrado por producto
    $lista = $producto
        ? $productos
        : array_filter($productos, fn($p) => $p['Accion'] != '');

    $filas = '';
    foreach ($lista as $p) {
        if (empty($p['Codigo']) || trim($p['Codigo']) == '') {
            continue;
        }
        
        $solicitada   = floatval($p['Cantidad_Solicitada']);
        $entrada  = floatval($p['Cantidad_Entrada']);
        $diferencia = $entrada - $solicitada;
        $difTexto   = ($diferencia >= 0 ? '+' . $diferencia : $diferencia);
        $difClass   = $diferencia < 0 ? 'text-danger' : '';

        $filas .= '
        <tr>
            <td>' . htmlspecialchars($p['Codigo']) . '</td>
            <td class="left">' . htmlspecialchars($p['Descripcion']) . '</td>
            <td>' . htmlspecialchars($p['Presentacion']) . '</td>
            <td>' . $solicitada . '</td>
            <td>' . $entrada . '</td>
            <td class="' . $difClass .'">' . $difTexto . '</td>
            <td>' . htmlspecialchars(accionLabel($p['Accion'])) . '</td>
            <td class="left">' . htmlspecialchars($p['Observaciones'] ?: '-') . '</td>
        </tr>';
    }

    $contenido = '
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Presentación</th>
                <th>Cant. solicitada</th>
                <th>Cant. recibida</th>
                <th>Faltante</th>
                <th>Acción</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>' . $filas . '</tbody>
    </table>
    <div class="firmas">
        <p class="m-0"><b>Nombre del solicitante:</b> ________________________________________________</p>
        <p class="m-0"><b>Cargo:</b> ________________________________________________</p>
        <p class="m-0" style="margin-top:20px;"><b>Firma:</b> _________________________________________________</p>
    </div>';
}

// ── Generar PDF ───────────────────────────────────────────────────────────────
$mpdf = new \Mpdf\Mpdf([
    'margin_top'    => 15,
    'margin_bottom' => 15,
    'margin_left'   => 15,
    'margin_right'  => 15,
]);

$mpdf->SetTitle('Traslado ' . $folio);
$mpdf->WriteHTML('<style>' . $css . '</style>' . $cabecera . $contenido);
$mpdf->Output('traslado_' . $folio . '_' . $tipo . '.pdf', 'I');
