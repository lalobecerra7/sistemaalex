<?php
class pedidos {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$arreglo = null;
		if ($tipo == "ConsultarPendientes") {
			$query = "SELECT ID_Pedido, usuarios_cliente.ID_Cliente AS idCliente, usuarios_negocio.ID_Negocio AS idNegocio, CONCAT(usuarios_cliente.Nombre,' ', usuarios_cliente.Primer_Apellido,' ',usuarios_cliente.Segundo_Apellido) AS NombreCliente, usuarios_cliente.Telefono AS TelefonoCliente, usuarios_cliente.Imagen AS FotoCliente, ubicaciones.Calle AS CalleCliente, ubicaciones.Numero_Exterior AS NoExtCliente, ubicaciones.Numero_Interior AS NoIntCliente, ubicaciones.Colonia AS ColoniaCliente, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Telefono AS TelefonoNegocio, usuarios_negocio.Imagen AS FotoNegocio, usuarios_negocio.Calle AS CalleNegocio, usuarios_negocio.No_Exterior AS NoExtNegocio, usuarios_negocio.No_Interior AS NoIntNegocio, usuarios_negocio.Colonia AS ColoniaNegocio, pedidos.Total AS TotalPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedidoFolio, Metodo_Pago AS MetodoPago, pedidos.Costo_Envio AS CostoEnvio, pedidos.Estatus AS EstatusPedido,DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedido FROM pedidos INNER JOIN usuarios_cliente ON FK_Usuario_Cliente = ID_Cliente INNER JOIN usuarios_negocio ON FK_Usuario_Negocio = ID_Negocio INNER JOIN ubicaciones ON ubicaciones.FK_Usuario_Cliente = usuarios_cliente.ID_Cliente WHERE pedidos.Estatus = 'Pendiente'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$separaFecha = str_replace("-", "", $row[$i]["FechaPedido"]);
						$folio = $separaFecha.''.$row[$i]["ID_Pedido"];

						$fotoCliente = '';
						if ($row[$i]["FotoCliente"] == "") {
							$fotoCliente = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoCliente = '../server_SPIDI_APP/images/clientes/'.$row[$i]["FotoCliente"];
						}

						$fotoNegocio = '';
						if ($row[$i]["FotoNegocio"] == "") {
							$fotoNegocio = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoNegocio = '../server_SPIDI_APP/images/negocios/'.$row[$i]["FotoNegocio"];
						}

						$datosCliente = '

						<a href="'.$fotoCliente.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoCliente."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreCliente'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoCliente'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleCliente'].'</span>';

						if ($row[$i]['NoExtCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtCliente'].'</span>';
						}
						if ($row[$i]['NoIntCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntCliente'].'</span>';
						}

						$datosNegocio = '
						<a href="'.$fotoNegocio.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoNegocio."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreNegocio'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoNegocio'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleNegocio'].'</span>';

						if ($row[$i]['NoExtNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtNegocio'].'</span>';
						}
						if ($row[$i]['NoIntNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntNegocio'].'</span>';
						}

						$Totales = '
							Método de pago: <br><span style="font-weight: bold;">'.$row[$i]['MetodoPago'].'</span><br>
							Subtotal: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['TotalPedido'].'</span><br>
							Costo de envío: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['CostoEnvio'].'</span><br>
							Total: <br><span class="dinero" style="font-weight: bold;">'.($row[$i]['TotalPedido'] + $row[$i]['CostoEnvio']).'</span><br>
						';

						$dias = array('Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo');
						$meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

						$separa = explode(' ', $row[0]['FechaPedidoCompleta']);
						$fechaR = $separa[0];
						$separadaFecha = explode('-', $fechaR);
						$fechaLetras = $dias[$separadaFecha[0]].' '.$separadaFecha[1].' de '.$meses[$separadaFecha[2]].' del '.$separadaFecha[3];
						$apartado = "";
						/*$botonApartar = "";
						$queryApartar = "SELECT ID_Apartar, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS NombreRepartidor FROM apartar INNER JOIN usuarios_repartidor ON FK_Usuario_Repartidor = ID_Repartidor WHERE FK_Pedido = '".$row[$i]["ID_Pedido"]."'";
						$rowApartar = $omodelo->_consultar($queryApartar);
						$numerofilasApartar = $omodelo->numerofilas;

						if ($rowApartar == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							if ($numerofilasApartar > 0) {
								$apartado = '<br><br>Apartado: <b>'.$rowApartar[0]["NombreRepartidor"].'</b>';
							}else{
								$botonApartar = '
								<button type="button" class="btn btn-info btn-block ApartarPedido" attrid="'.$row[$i]["ID_Pedido"].'"  folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  style="font-weight: bold; font-size: 12px;">Apartar
								</button>';
							}
						}*/


						$arreglo['data'][$i] = array(
							"DT_RowId" => "PEDIDO-".$row[$i]['ID_Pedido'],
							"Cliente" => $datosCliente,
							"Negocio" => $datosNegocio,
							"Totales" => $Totales,
							"Detalles" => '
								Folio: <b>'.$folio.'</b><br>
								<button type="button" data-bs-toggle="modal" data-bs-target="#ModalVerDetalles" class="btn btn-primary btn-block DetallesPedido btnDetallesPendiente'.$row[$i]["ID_Pedido"].'" estatusPedido="'.$row[$i]["EstatusPedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  attrid="'.$row[$i]["ID_Pedido"].'" fechaPedido="'.$fechaLetras.'" horapedido="'.$row[$i]["HoraPedido"].'" totalpedido="'.$row[$i]["TotalPedido"].'" costoenvio="'.$row[$i]['CostoEnvio'].'" metodopago="'.$row[$i]["MetodoPago"].'" style="font-weight: bold; font-size: 12px;">Detalles
								</button>'.$apartado,
							"Acciones" => '
								<button type="button" class="btn btn-success btn-block AceptarPedido botonAceptarPedido'.$row[$i]["ID_Pedido"].'" attrid="'.$row[$i]["ID_Pedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  style="font-weight: bold; font-size: 12px;">Aceptar
								</button><br><br>
								<button type="button" class="btn btn-danger btn-block RechazarPedido botonRechazarPedido'.$row[$i]["ID_Pedido"].'" attrid="'.$row[$i]["ID_Pedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'" style="font-weight: bold; font-size: 11px;">Rechazar
								</button>',
						);
					}
				}
			}
			echo json_encode($arreglo);	
		}if ($tipo == "ConsultarPendientesNuevo") {
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$query = "SELECT ID_Pedido, usuarios_cliente.ID_Cliente AS idCliente, usuarios_negocio.ID_Negocio AS idNegocio, CONCAT(usuarios_cliente.Nombre,' ', usuarios_cliente.Primer_Apellido,' ',usuarios_cliente.Segundo_Apellido) AS NombreCliente, usuarios_cliente.Telefono AS TelefonoCliente, usuarios_cliente.Imagen AS FotoCliente, ubicaciones.Calle AS CalleCliente, ubicaciones.Numero_Exterior AS NoExtCliente, ubicaciones.Numero_Interior AS NoIntCliente, ubicaciones.Colonia AS ColoniaCliente, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Telefono AS TelefonoNegocio, usuarios_negocio.Imagen AS FotoNegocio, usuarios_negocio.Calle AS CalleNegocio, usuarios_negocio.No_Exterior AS NoExtNegocio, usuarios_negocio.No_Interior AS NoIntNegocio, usuarios_negocio.Colonia AS ColoniaNegocio, pedidos.Total AS TotalPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedidoFolio, Metodo_Pago AS MetodoPago, pedidos.Costo_Envio AS CostoEnvio, pedidos.Estatus AS EstatusPedido,DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedido FROM pedidos INNER JOIN usuarios_cliente ON FK_Usuario_Cliente = ID_Cliente INNER JOIN usuarios_negocio ON FK_Usuario_Negocio = ID_Negocio INNER JOIN ubicaciones ON ubicaciones.FK_Usuario_Cliente = usuarios_cliente.ID_Cliente WHERE pedidos.Estatus = 'Pendiente' AND ID_Pedido = '$idPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$separaFecha = str_replace("-", "", $row[$i]["FechaPedido"]);
						$folio = $separaFecha.''.$row[$i]["ID_Pedido"];

						$fotoCliente = '';
						if ($row[$i]["FotoCliente"] == "") {
							$fotoCliente = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoCliente = '../server_SPIDI_APP/images/clientes/'.$row[$i]["FotoCliente"];
						}

						$fotoNegocio = '';
						if ($row[$i]["FotoNegocio"] == "") {
							$fotoNegocio = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoNegocio = '../server_SPIDI_APP/images/negocios/'.$row[$i]["FotoNegocio"];
						}

						$datosCliente = '

						<a href="'.$fotoCliente.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoCliente."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreCliente'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoCliente'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleCliente'].'</span>';

						if ($row[$i]['NoExtCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtCliente'].'</span>';
						}
						if ($row[$i]['NoIntCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntCliente'].'</span>';
						}

						$datosNegocio = '
						<a href="'.$fotoNegocio.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoNegocio."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreNegocio'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoNegocio'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleNegocio'].'</span>';

						if ($row[$i]['NoExtNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtNegocio'].'</span>';
						}
						if ($row[$i]['NoIntNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntNegocio'].'</span>';
						}

						$Totales = '
							Método de pago: <br><span style="font-weight: bold;">'.$row[$i]['MetodoPago'].'</span><br>
							Subtotal: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['TotalPedido'].'</span><br>
							Costo de envío: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['CostoEnvio'].'</span><br>
							Total: <br><span class="dinero" style="font-weight: bold;">'.($row[$i]['TotalPedido'] + $row[$i]['CostoEnvio']).'</span><br>
						';

						$dias = array('Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo');
						$meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

						$separa = explode(' ', $row[0]['FechaPedidoCompleta']);
						$fechaR = $separa[0];
						$separadaFecha = explode('-', $fechaR);
						$fechaLetras = $dias[$separadaFecha[0]].' '.$separadaFecha[1].' de '.$meses[$separadaFecha[2]].' del '.$separadaFecha[3];
						$apartado = "";
						$botonApartar = "";
						$queryApartar = "SELECT ID_Apartar, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS NombreRepartidor FROM apartar INNER JOIN usuarios_repartidor ON FK_Usuario_Repartidor = ID_Repartidor WHERE FK_Pedido = '".$row[$i]["ID_Pedido"]."'";
						$rowApartar = $omodelo->_consultar($queryApartar);
						$numerofilasApartar = $omodelo->numerofilas;

						if ($rowApartar == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							if ($numerofilasApartar > 0) {
								$apartado = '<br><br>Apartado: <b>'.$rowApartar[0]["NombreRepartidor"].'</b>';
							}else{
								$botonApartar = '
								<button type="button" class="btn btn-info btn-block ApartarPedido" attrid="'.$row[$i]["ID_Pedido"].'"  folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  style="font-weight: bold; font-size: 12px;">Apartar
								</button>';
							}
						}


						$arreglo['data'][$i] = array(
							"DT_RowId" => $row[$i]['ID_Pedido'],
							"Cliente" => $datosCliente,
							"Negocio" => $datosNegocio,
							"Totales" => $Totales,
							"Detalles" => '
								Folio: <b>'.$folio.'</b><br>
								<button type="button" data-bs-toggle="modal" data-bs-target="#ModalVerDetalles" class="btn btn-primary btn-block DetallesPedido" estatusPedido="'.$row[$i]["EstatusPedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  attrid="'.$row[$i]["ID_Pedido"].'" fechaPedido="'.$fechaLetras.'" horapedido="'.$row[$i]["HoraPedido"].'" totalpedido="'.$row[$i]["TotalPedido"].'" costoenvio="'.$row[$i]['CostoEnvio'].'" metodopago="'.$row[$i]["MetodoPago"].'" style="font-weight: bold; font-size: 12px;">Detalles
								</button>'.$apartado,
							"Acciones" => '
								<button type="button" class="btn btn-success btn-block AceptarPedido botonAceptarPedido'.$row[$i]["ID_Pedido"].'" attrid="'.$row[$i]["ID_Pedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  style="font-weight: bold; font-size: 12px;">Aceptar
								</button><br><br>
								<button type="button" class="btn btn-danger btn-block RechazarPedido botonRechazarPedido'.$row[$i]["ID_Pedido"].'" attrid="'.$row[$i]["ID_Pedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'" style="font-weight: bold; font-size: 11px;">Rechazar
								</button><br><br>'.$botonApartar.'

							',
						);
					}
				}
			}
			echo json_encode($arreglo);	
		}else if ($tipo == "ConsultarEnCurso") {
			$pedidos = '';
			$query = "SELECT ID_Pedido, usuarios_cliente.ID_Cliente AS idCliente, usuarios_negocio.ID_Negocio AS idNegocio, CONCAT(usuarios_cliente.Nombre,' ', usuarios_cliente.Primer_Apellido,' ',usuarios_cliente.Segundo_Apellido) AS NombreCliente, usuarios_cliente.Telefono AS TelefonoCliente, usuarios_cliente.Imagen AS FotoCliente, ubicaciones.Calle AS CalleCliente, ubicaciones.Numero_Exterior AS NoExtCliente, ubicaciones.Numero_Interior AS NoIntCliente, ubicaciones.Colonia AS ColoniaCliente, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Telefono AS TelefonoNegocio, usuarios_negocio.Imagen AS FotoNegocio, usuarios_negocio.Calle AS CalleNegocio, usuarios_negocio.No_Exterior AS NoExtNegocio, usuarios_negocio.No_Interior AS NoIntNegocio, usuarios_negocio.Colonia AS ColoniaNegocio, usuarios_repartidor.ID_Repartidor, CONCAT(usuarios_repartidor.Nombre,' ',usuarios_repartidor.Primer_Apellido,' ',usuarios_repartidor.Segundo_Apellido) AS NombreRepartidor, usuarios_repartidor.Telefono AS TelefonoRepartidor, usuarios_repartidor.Foto AS FotoRepartidor, pedidos.Total AS TotalPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedido, Metodo_Pago AS MetodoPago, pedidos.Costo_Envio AS CostoEnvio, pedidos.Estatus AS EstatusPedido, Estatus_Pedido_Negocio, DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido, TIME_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedidoMostrar, TIME_FORMAT(DATE_ADD(pedidos.Fecha_Registro, INTERVAL detalle_pedido_negocio.Tiempo_Preparacion MINUTE), '%r') AS HoraEntregarMostrar, detalle_pedido_negocio.Tiempo_Preparacion, detalle_pedido_negocio.Tiempo_Preparacion AS Tiempo FROM pedidos INNER JOIN usuarios_cliente ON FK_Usuario_Cliente = ID_Cliente INNER JOIN usuarios_negocio ON FK_Usuario_Negocio = ID_Negocio INNER JOIN ubicaciones ON ubicaciones.FK_Usuario_Cliente = usuarios_cliente.ID_Cliente INNER JOIN detalle_pedido_negocio ON FK_Pedido = ID_Pedido LEFT JOIN asignar_pedido ON asignar_pedido.FK_Pedido = ID_Pedido LEFT JOIN usuarios_repartidor ON asignar_pedido.FK_Usuario_Repartidor = ID_Repartidor WHERE (pedidos.Estatus = 'Esperando' OR pedidos.Estatus = 'Asignado' OR pedidos.Estatus = 'TengoPedido' OR pedidos.Estatus = 'LlegoNegocio' OR pedidos.Estatus = 'LlegoDestino') AND detalle_pedido_negocio.Estatus = 'Esperando' GROUP BY ID_Pedido";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {

				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$separaFecha = str_replace("-", "", $row[$i]["FechaPedido"]);
						$folio = $separaFecha.''.$row[$i]["ID_Pedido"];

						$fotoCliente = '';
						if ($row[$i]["FotoCliente"] == "") {
							$fotoCliente = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoCliente = '../server_SPIDI_APP/images/clientes/'.$row[$i]["FotoCliente"];
						}

						$fotoNegocio = '';
						if ($row[$i]["FotoNegocio"] == "") {
							$fotoNegocio = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoNegocio = '../server_SPIDI_APP/images/negocios/'.$row[$i]["FotoNegocio"];
						}

						$datosCliente = '

						<a href="'.$fotoCliente.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoCliente."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreCliente'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoCliente'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleCliente'].'</span>';

						if ($row[$i]['NoExtCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtCliente'].'</span>';
						}
						if ($row[$i]['NoIntCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntCliente'].'</span>';
						}

						$datosNegocio = '
						<a href="'.$fotoNegocio.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoNegocio."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreNegocio'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoNegocio'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleNegocio'].'</span>';

						if ($row[$i]['NoExtNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtNegocio'].'</span>';
						}
						if ($row[$i]['NoIntNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntNegocio'].'</span>';
						}

						$botonAsignado = '';

						$queryApartar = "SELECT ID_Asignar_Pedido, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS NombreRepartidor, usuarios_repartidor.Telefono AS TelefonoRepartidor, usuarios_repartidor.Foto AS FotoRepartidor FROM asignar_pedido INNER JOIN usuarios_repartidor ON FK_Usuario_Repartidor = ID_Repartidor WHERE FK_Pedido = '".$row[$i]['ID_Pedido']."'";
						$rowApartar = $omodelo->_consultar($queryApartar);
						$numerofilasApartar = $omodelo->numerofilas;

						if ($rowApartar == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							if ($numerofilasApartar > 0) {

								$fotoRepartidor = '';
								if ($rowApartar[0]["FotoRepartidor"] == "") {
									$fotoRepartidor = '../server_SPIDI_APP/images/logos/logo rojo.png';
								}else{
									$fotoRepartidor = '../server_SPIDI_APP/images/repartidores/'.$rowApartar[0]["FotoRepartidor"];
								}

								$datosRepartidor = '
									<a href="'.$fotoRepartidor.'" data-fancybox="images">
										<div style="background-image: url('."'".$fotoRepartidor."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
										</div>
									</a>
									<br>
									<span style="font-size: 14px;">'.$rowApartar[0]['NombreRepartidor'].'</span>
									<br>
									<span style="font-size: 14px; font-weight: bold;">'.$rowApartar[0]['TelefonoRepartidor'].'</span>';	
							}else{
								$datosRepartidor = '<span style="font-size: 14px;">Todavia no ha sido asignado a ningun repartidor</span> <br>';
								$botonAsignado = '<br><br><button type="button" class="btn btn-primary btn-block AsignarPedidoRepartidor btnAsignarPedido'.$row[$i]['ID_Pedido'].'" attrid="'.$row[$i]["ID_Pedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  style="font-weight: bold; font-size: 12px;">Asignar Pedido</button>';
							}
						}

						$Totales = '
							Método de pago: <br><span style="font-weight: bold;">'.$row[$i]['MetodoPago'].'</span><br>
							Subtotal: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['TotalPedido'].'</span><br>
							Costo de envío: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['CostoEnvio'].'</span><br>
							Total: <br><span class="dinero" style="font-weight: bold;">'.($row[$i]['TotalPedido'] + $row[$i]['CostoEnvio']).'</span><br>
						';

						$estatusProducto = "";
						if ($row[$i]["EstatusPedido"] == "Esperando") {
							$estatusProducto = "Buscando al repartidor";
						}else if ($row[$i]["EstatusPedido"] == "Asignado") {
							$estatusProducto = "Esperando al repartidor";
						}else if ($row[$i]["EstatusPedido"] == "TengoPedido") {
							$estatusProducto = "El repartidor tiene el pedido";
						}else if ($row[$i]["EstatusPedido"] == "LlegoNegocio") {
							$estatusProducto = "El repartidor llego al negocio";
						}else if ($row[$i]["EstatusPedido"] == "LlegoDestino") {
							$estatusProducto = "El repartidor llego al destino";
						}

						$dias = array('Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo');
						$meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

						$separa = explode(' ', $row[0]['FechaPedidoCompleta']);
						$fechaR = $separa[0];
						$separadaFecha = explode('-', $fechaR);
						$fechaLetras = $dias[$separadaFecha[0]].' '.$separadaFecha[1].' de '.$meses[$separadaFecha[2]].' del '.$separadaFecha[3];

						$EstatusPedido = "";
						if ($row[$i]['Estatus_Pedido_Negocio'] == "TiempoPedido") {
							$EstatusPedido = "<b>El repartidor debe pasar por el pedido a las <b style='color:red;'>".$row[$i]['HoraEntregarMostrar']."<b></b>";
						}else if ($row[$i]['Estatus_Pedido_Negocio'] == "OrdenarPedido") {
							$EstatusPedido = "<b>El repartidor debe de ir al negocio a realizar la orden</b>";
						}

						$arreglo['data'][$i] = array(
							"DT_RowId" => "PEDIDOCURSO-".$row[$i]['ID_Pedido'],
							"Cliente" => $datosCliente,
							"Negocio" => $datosNegocio,
							"Repartidor" => $datosRepartidor.'<br>Estatus <br><b>'.$estatusProducto.'</b>',
							"Totales" => $Totales,
							"Detalles" => 'Folio: <b>'.$folio.'</b><br>'.$EstatusPedido.' <br> <button class="btn btn-link btn-sm DetallesPedidoEnCurso btndetallesEnCurso'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'" attrid="'.$row[$i]['ID_Pedido'].'" fechaPedido="'.$fechaLetras.'" horapedido="'.$row[$i]["HoraPedido"].'" totalpedido="'.$row[$i]["TotalPedido"].'" costoenvio="'.$row[$i]['CostoEnvio'].'" metodopago="'.$row[$i]["MetodoPago"].'">Ver detalles del pedido</button>'.$botonAsignado,
						);
					}
				}
			}
			echo json_encode($arreglo);	
		}else if ($tipo == "ConsultarEnCursoNuevo") {
			$pedidos = '';
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$query = "SELECT ID_Pedido, usuarios_cliente.ID_Cliente AS idCliente, usuarios_negocio.ID_Negocio AS idNegocio, CONCAT(usuarios_cliente.Nombre,' ', usuarios_cliente.Primer_Apellido,' ',usuarios_cliente.Segundo_Apellido) AS NombreCliente, usuarios_cliente.Telefono AS TelefonoCliente, usuarios_cliente.Imagen AS FotoCliente, ubicaciones.Calle AS CalleCliente, ubicaciones.Numero_Exterior AS NoExtCliente, ubicaciones.Numero_Interior AS NoIntCliente, ubicaciones.Colonia AS ColoniaCliente, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Telefono AS TelefonoNegocio, usuarios_negocio.Imagen AS FotoNegocio, usuarios_negocio.Calle AS CalleNegocio, usuarios_negocio.No_Exterior AS NoExtNegocio, usuarios_negocio.No_Interior AS NoIntNegocio, usuarios_negocio.Colonia AS ColoniaNegocio, usuarios_repartidor.ID_Repartidor, CONCAT(usuarios_repartidor.Nombre,' ',usuarios_repartidor.Primer_Apellido,' ',usuarios_repartidor.Segundo_Apellido) AS NombreRepartidor, usuarios_repartidor.Telefono AS TelefonoRepartidor, usuarios_repartidor.Foto AS FotoRepartidor, pedidos.Total AS TotalPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedido, Metodo_Pago AS MetodoPago, pedidos.Costo_Envio AS CostoEnvio, pedidos.Estatus AS EstatusPedido, Estatus_Pedido_Negocio, DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido, TIME_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedidoMostrar, TIME_FORMAT(DATE_ADD(pedidos.Fecha_Registro, INTERVAL detalle_pedido_negocio.Tiempo_Preparacion MINUTE), '%r') AS HoraEntregarMostrar, detalle_pedido_negocio.Tiempo_Preparacion, detalle_pedido_negocio.Tiempo_Preparacion AS Tiempo FROM pedidos INNER JOIN usuarios_cliente ON FK_Usuario_Cliente = ID_Cliente INNER JOIN usuarios_negocio ON FK_Usuario_Negocio = ID_Negocio INNER JOIN ubicaciones ON ubicaciones.FK_Usuario_Cliente = usuarios_cliente.ID_Cliente INNER JOIN detalle_pedido_negocio ON FK_Pedido = ID_Pedido LEFT JOIN asignar_pedido ON asignar_pedido.FK_Pedido = ID_Pedido LEFT JOIN usuarios_repartidor ON asignar_pedido.FK_Usuario_Repartidor = ID_Repartidor WHERE ID_Pedido = '$idPedido' AND (pedidos.Estatus = 'Esperando' OR pedidos.Estatus = 'Asignado' OR pedidos.Estatus = 'TengoPedido' OR pedidos.Estatus = 'LlegoNegocio' OR pedidos.Estatus = 'LlegoDestino') AND detalle_pedido_negocio.Estatus = 'Esperando' GROUP BY ID_Pedido";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			if ($row == "si") {

				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$separaFecha = str_replace("-", "", $row[$i]["FechaPedido"]);
						$folio = $separaFecha.''.$row[$i]["ID_Pedido"];

						$fotoCliente = '';
						if ($row[$i]["FotoCliente"] == "") {
							$fotoCliente = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoCliente = '../server_SPIDI_APP/images/clientes/'.$row[$i]["FotoCliente"];
						}

						$fotoNegocio = '';
						if ($row[$i]["FotoNegocio"] == "") {
							$fotoNegocio = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoNegocio = '../server_SPIDI_APP/images/negocios/'.$row[$i]["FotoNegocio"];
						}

						$datosCliente = '

						<a href="'.$fotoCliente.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoCliente."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreCliente'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoCliente'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleCliente'].'</span>';

						if ($row[$i]['NoExtCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtCliente'].'</span>';
						}
						if ($row[$i]['NoIntCliente'] != "") {
							$datosCliente .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntCliente'].'</span>';
						}

						$datosNegocio = '
						<a href="'.$fotoNegocio.'" data-fancybox="images">
							<div style="background-image: url('."'".$fotoNegocio."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
							</div>
						</a>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['NombreNegocio'].'</span>
						<br>
						<span style="font-size: 14px; font-weight: bold;">'.$row[$i]['TelefonoNegocio'].'</span>
						<br>
						<span style="font-size: 14px;">'.$row[$i]['CalleNegocio'].'</span>';

						if ($row[$i]['NoExtNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Exterior: '.$row[$i]['NoExtNegocio'].'</span>';
						}
						if ($row[$i]['NoIntNegocio'] != "") {
							$datosNegocio .= '<br> <span style="font-size: 14px;">No. Interior: '.$row[$i]['NoIntNegocio'].'</span>';
						}

						$botonAsignado = '';

						$queryApartar = "SELECT ID_Asignar_Pedido, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS NombreRepartidor, usuarios_repartidor.Telefono AS TelefonoRepartidor, usuarios_repartidor.Foto AS FotoRepartidor FROM asignar_pedido INNER JOIN usuarios_repartidor ON FK_Usuario_Repartidor = ID_Repartidor WHERE FK_Pedido = '".$row[$i]['ID_Pedido']."'";
						$rowApartar = $omodelo->_consultar($queryApartar);
						$numerofilasApartar = $omodelo->numerofilas;

						if ($rowApartar == "si") {
							echo "Error 1: ".mysqli_error($omodelo->link);
						}else{
							if ($numerofilasApartar > 0) {

								$fotoRepartidor = '';
								if ($rowApartar[0]["FotoRepartidor"] == "") {
									$fotoRepartidor = '../server_SPIDI_APP/images/logos/logo rojo.png';
								}else{
									$fotoRepartidor = '../server_SPIDI_APP/images/repartidores/'.$rowApartar[0]["FotoRepartidor"];
								}

								$datosRepartidor = '
									<a href="'.$fotoRepartidor.'" data-fancybox="images">
										<div style="background-image: url('."'".$fotoRepartidor."'".'); display: inline-block;width: 40px;height: 40px;border-radius: 100%;background-size: cover;background-position: center;">
										</div>
									</a>
									<br>
									<span style="font-size: 14px;">'.$rowApartar[0]['NombreRepartidor'].'</span>
									<br>
									<span style="font-size: 14px; font-weight: bold;">'.$rowApartar[0]['TelefonoRepartidor'].'</span>';	
							}else{
								$datosRepartidor = '<span style="font-size: 14px;">Todavia no ha sido asignado a ningun repartidor</span> <br>';
								$botonAsignado = '<br><br><button type="button" class="btn btn-primary btn-block AsignarPedidoRepartidor btnAsignarPedido'.$row[$i]['ID_Pedido'].'" attrid="'.$row[$i]["ID_Pedido"].'" folio="'.$folio.'" idNegocio="'.$row[$i]["idNegocio"].'" idCliente="'.$row[$i]["idCliente"].'"  style="font-weight: bold; font-size: 12px;">Asignar Pedido</button>';
							}
						}

						$Totales = '
							Método de pago: <br><span style="font-weight: bold;">'.$row[$i]['MetodoPago'].'</span><br>
							Subtotal: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['TotalPedido'].'</span><br>
							Costo de envío: <br><span class="dinero" style="font-weight: bold;">'.$row[$i]['CostoEnvio'].'</span><br>
							Total: <br><span class="dinero" style="font-weight: bold;">'.($row[$i]['TotalPedido'] + $row[$i]['CostoEnvio']).'</span><br>
						';

						$estatusProducto = "";
						if ($row[$i]["EstatusPedido"] == "Esperando") {
							$estatusProducto = "Buscando al repartidor";
						}else if ($row[$i]["EstatusPedido"] == "Asignado") {
							$estatusProducto = "Esperando al repartidor";
						}else if ($row[$i]["EstatusPedido"] == "TengoPedido") {
							$estatusProducto = "El repartidor tiene el pedido";
						}else if ($row[$i]["EstatusPedido"] == "LlegoNegocio") {
							$estatusProducto = "El repartidor llego al negocio";
						}else if ($row[$i]["EstatusPedido"] == "LlegoDestino") {
							$estatusProducto = "El repartidor llego al destino";
						}

						$dias = array('Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo');
						$meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

						$separa = explode(' ', $row[0]['FechaPedidoCompleta']);
						$fechaR = $separa[0];
						$separadaFecha = explode('-', $fechaR);
						$fechaLetras = $dias[$separadaFecha[0]].' '.$separadaFecha[1].' de '.$meses[$separadaFecha[2]].' del '.$separadaFecha[3];

						$EstatusPedido = "";
						if ($row[$i]['Estatus_Pedido_Negocio'] == "TiempoPedido") {
							$EstatusPedido = "<b>El repartidor debe pasar por el pedido a las <b style='color:red;'>".$row[$i]['HoraEntregarMostrar']."<b></b>";
						}else{
							$EstatusPedido = "<b>El repartidor debe de ir al negocio a realizar la orden</b>";
						}

						$arreglo['data'][$i] = array(
							"DT_RowId" => "PEDIDOCURSO-".$row[$i]['ID_Pedido'],
							"Cliente" => $datosCliente,
							"Negocio" => $datosNegocio,
							"Repartidor" => $datosRepartidor.'<br>Estatus <br><b>'.$estatusProducto.'</b>',
							"Totales" => $Totales,
							"Detalles" => 'Folio: <b>'.$folio.'</b><br>'.$EstatusPedido.' <br> <button class="btn btn-link btn-sm DetallesPedidoEnCurso btndetallesEnCurso'.$row[$i]['ID_Pedido'].'" folio="'.$folio.'" attrid="'.$row[$i]['ID_Pedido'].'" fechaPedido="'.$fechaLetras.'" horapedido="'.$row[$i]["HoraPedido"].'" totalpedido="'.$row[$i]["TotalPedido"].'" costoenvio="'.$row[$i]['CostoEnvio'].'" metodopago="'.$row[$i]["MetodoPago"].'">Ver detalles del pedido</button>'.$botonAsignado,
						);
					}
				}
			}
			echo json_encode($arreglo);	
		}else if ($tipo == "ConsultarOrden") {
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$arreglo = null;
			$tabla = "";
			$query = "SELECT ID_Detalle_Pedido, FK_Producto, detalle_pedido.Nombre, detalle_pedido.Precio, detalle_pedido.Cantidad, detalle_pedido.Unidad, detalle_pedido.Total, detalle_pedido.Comentario, productos.Imagen AS ImagenProducto, detalle_pedido.Presentacion, detalle_pedido.Descuento, detalle_pedido.Subtotal, pedidos.Total AS TotalPedido, pedidos.Fecha_Registro AS FechaPedido, pedidos.Metodo_Pago AS MetodoPago, DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido FROM detalle_pedido LEFT JOIN productos ON FK_Producto = ID_Producto INNER JOIN pedidos ON detalle_pedido.FK_Pedido = pedidos.ID_Pedido WHERE FK_Pedido = '$idPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo json_encode("Error 1: ".mysqli_error($omodelo->link));
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$query2 = "SELECT ID_Extra, Nombre, Cantidad, Precio, Total FROM detalle_pedido_extras WHERE FK_Detalle_Pedido = '".$row[$i]["ID_Detalle_Pedido"]."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas; 
						if ($row2 == "si") {
							echo json_encode("Error 2: ".mysqli_error($omodelo->link));
						}else{
							$subtabla = '';
							if($numerofilas2 > 0){
								$subtabla = '<table class="table-striped" style="width: 100%; margin-bottom: 20px !important;">';
								for ($x=0; $x < $numerofilas2; $x++) { 
									$subtabla .= '
										<tr class="text-center" style="padding: 10px;">
											<td style="width: 25%; font-weight: bold;">
												<span>'.$row2[$x]["Cantidad"].'x</span>
											</td>
											<td style="width: 50%">
												<span>'.$row2[$x]["Nombre"].'</span>
											</td>
											<td style="width: 25%">
												<span class="dinero" style="font-weight: bold;">'.$row2[$x]["Total"].'</span>
											</td>
										</tr>
									';
								}
								$subtabla .= '</table>';
							}
						}

						$fotoProducto = '';
						if ($row[$i]["ImagenProducto"] == "") {
							$fotoProducto = '../server_SPIDI_APP/images/logos/logo rojo.png';
						}else{
							$fotoProducto = '../server_SPIDI_APP/images/productos/'.$row[$i]["ImagenProducto"];
						}

						$tabla .= '
						<table class="table table-hover text-center">
							<tr style="">
								<td style="width: 20%;"><img src="'.$fotoProducto.'" style="width: 39px; border-radius: 5px;"> 
								</td>
								<td style="width: 30%;font-size: 15px;font-weight: bold;" class="text-center">
									'.$row[$i]["Nombre"].'<br>';
									if ($row[$i]["Presentacion"] != "") {
										$tabla .= '
										<span class="text-muted">Presentación: '.$row[$i]["Presentacion"].'</span>';
									}
						$tabla .= '
								</td>
								<td style="width: 25%;">
									<span class="cantidad">'.$row[$i]["Cantidad"].'</span> '.$row[$i]["Unidad"].'
								</td>
								<td style="width: 25%;">
									<span class="dinero">'.$row[$i]["Precio"].'</span>
								</td>
							</tr>';
							if ($row[$i]["Comentario"] != "") {
								$tabla .= '
								<tr style="">
									<td colspan="4">Comentarios:  <b>'.$row[$i]["Comentario"].'</b>
									</td>
								</tr>';
							}
							if ($subtabla != "") {
								$tabla .= '
								<tr>
									<td colspan="4" class="text-start"  style="width: 100%; padding: 10px;">
										<span style="font-weight: bold;font-size: 20px;">Extras</span>
									</td>
								</tr>
								'.$subtabla;
							}
						$tabla .= '
							<table style="border-bottom: 1px solid #CCC;width: 100%; margin-bottom: 20px;">
								<tr>
									<td colspan="4" class="text-end"  style="width: 100%;">
										Subtotal: <span class="dinero" style="font-weight: bold;font-size: 15px;">'.$row[$i]["Subtotal"].'</span>
									</td>
								</tr>
								<tr>
									<td colspan="4" class="text-end"  style="width: 100%;">
										Descuento: <span class="dinero" style="font-weight: bold;font-size: 15px;">'.$row[$i]["Descuento"].'</span>
									</td>
								</tr>
								<tr>
									<td colspan="4" class="text-end"  style="width: 100%;">
										Total: <span class="dinero" style="font-weight: bold;font-size: 15px;">'.$row[$i]["Total"].'</span>
									</td>
								</tr>
							</table>';
						$tabla .= '	
							</table>
						';


						
					}
				}
				echo $tabla;
			}
		}else if ($tipo == "ConsultarInformacionPedido") {
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$arreglo = null;
			$query = "SELECT ID_Pedido, CONCAT(usuarios_cliente.Nombre,' ', usuarios_cliente.Primer_Apellido,' ',usuarios_cliente.Segundo_Apellido) AS NombreCliente, usuarios_cliente.Telefono AS TelefonoCliente, usuarios_cliente.Imagen AS FotoCliente, ubicaciones.Calle AS CalleCliente, ubicaciones.Numero_Exterior AS NoExtCliente, ubicaciones.Numero_Interior AS NoIntCliente, ubicaciones.Colonia AS ColoniaCliente, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Telefono AS TelefonoNegocio, usuarios_negocio.Imagen AS FotoNegocio, usuarios_negocio.Calle AS CalleNegocio, usuarios_negocio.No_Exterior AS NoExtNegocio, usuarios_negocio.No_Interior AS NoIntNegocio, usuarios_negocio.Colonia AS ColoniaNegocio, pedidos.Total AS TotalPedido, pedidos.Fecha_Registro AS FechaPedido, Metodo_Pago AS MetodoPago, pedidos.Costo_Envio AS CostoEnvio, pedidos.Estatus AS EstatusPedido,DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedido FROM pedidos INNER JOIN usuarios_cliente ON FK_Usuario_Cliente = ID_Cliente INNER JOIN usuarios_negocio ON FK_Usuario_Negocio = ID_Negocio INNER JOIN ubicaciones ON ubicaciones.FK_Usuario_Cliente = usuarios_cliente.ID_Cliente WHERE pedidos.Estatus = 'Pendiente' AND ID_Pedido = '$idPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo json_encode("Error 1: ".mysqli_error($omodelo->link));
			}else{
				if ($numerofilas > 0) {
					$pedidos = '';
					$separaFecha = str_replace("-", "", $row[0]["FechaPedido"]);
					$folio = $separaFecha.''.$row[0]["ID_Pedido"];
					$fotoCliente = '';
					if ($row[0]["FotoCliente"] == "") {
						$fotoCliente = '../server_SPIDI_APP/images/logos/logo rojo.png';
					}else{
						$fotoCliente = '../server_SPIDI_APP/images/clientes/'.$row[0]["FotoCliente"];
					}

					$fotoNegocio = '';
					if ($row[0]["FotoNegocio"] == "") {
						$fotoNegocio = '../server_SPIDI_APP/images/logos/logo rojo.png';
					}else{
						$fotoNegocio = '../server_SPIDI_APP/images/negocios/'.$row[0]["FotoNegocio"];
					}
					
					if ($row[0]["NoIntCliente"] != "" && $row[0]["NoIntCliente"] != "0") {
						$NumeroInteriorCliente = ' Int. #'.$row[0]["NoIntCliente"];
					}else{
						$NumeroInteriorCliente = '';
					}

					if ($row[0]["ColoniaCliente"] != "") {
						$ColoniaCliente = ', Col.: '.$row[0]["ColoniaCliente"].'.';
					}else{
						$ColoniaCliente = '';
					}

					$dias = array('Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo');
					$meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

					$separa = explode(' ', $row[0]['FechaPedidoCompleta']);
					$fechaR = $separa[0];
					$separadaFecha = explode('-', $fechaR);
					$fechaLetras = $dias[$separadaFecha[0]].' '.$separadaFecha[1].' de '.$meses[$separadaFecha[2]].' del '.$separadaFecha[3];

					$direccionCliente = $row[0]["CalleCliente"].' #'.$row[0]["NoExtCliente"].$NumeroInteriorCliente.$ColoniaCliente;

					if ($row[0]["NoIntNegocio"] != "" && $row[0]["NoIntNegocio"] != "0") {
						$NumeroInteriorNegocio = ' Int. #'.$row[0]["NoIntNegocio"];
					}else{
						$NumeroInteriorNegocio = '';
					}

					if ($row[0]["ColoniaNegocio"] != "") {
						$ColoniaNegocio = ', Col.: '.$row[0]["ColoniaNegocio"].'.';
					}else{
						$ColoniaNegocio = '';
					}
					$direccionNegocio = $row[0]["CalleNegocio"].' #'.$row[0]["NoExtNegocio"].$NumeroInteriorNegocio.$ColoniaNegocio;
					$pedidos .= '
					<div class="row" style="color: #000; margin: auto; padding: 2%;margin-bottom: 20px !important;">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3 text-center">
									<img src="'.$fotoCliente.'" alt="" style="width: 100%; border-radius: 15px;"> 
								</div>
								<div class="col-md-9 text-start" style="margin: auto; font-size: 14px;">
									Cliente: <span style="font-weight: bold;">'.$row[0]["NombreCliente"].'</span>
									<br>
						      			Telefono: <span style="font-weight: bold;">'.$row[0]["TelefonoCliente"].'</span>
									<br>
										Dirección: <span style="font-weight: bold;">'.$direccionCliente.'</span>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3 text-center">
									<img src="'.$fotoNegocio.'" alt="" style="width: 100%; border-radius: 15px;"> 
								</div>
								<div class="col-md-9 text-start" style="margin: auto; font-size: 14px;">
									Negocio: <span style="font-weight: bold;">'.$row[0]["NombreNegocio"].'</span>
									<br>
									Telefono: <span style="font-weight: bold;">'.$row[0]["TelefonoNegocio"].'</span>
									<br>
									Dirección: <span style="font-weight: bold;">'.$direccionNegocio.'</span>
								</div>
							</div>
						</div>
					</div>
				    '; 
				}
				echo $pedidos;	
			}
		}else if ($tipo == "ConsultarInformacionPedidoEnCurso") {
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$arreglo = null;
			$query = "SELECT ID_Pedido, CONCAT(usuarios_cliente.Nombre,' ', usuarios_cliente.Primer_Apellido,' ',usuarios_cliente.Segundo_Apellido) AS NombreCliente, usuarios_cliente.Telefono AS TelefonoCliente, usuarios_cliente.Imagen AS FotoCliente, ubicaciones.Calle AS CalleCliente, ubicaciones.Numero_Exterior AS NoExtCliente, ubicaciones.Numero_Interior AS NoIntCliente, ubicaciones.Colonia AS ColoniaCliente, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Telefono AS TelefonoNegocio, usuarios_negocio.Imagen AS FotoNegocio, usuarios_negocio.Calle AS CalleNegocio, usuarios_negocio.No_Exterior AS NoExtNegocio, usuarios_negocio.No_Interior AS NoIntNegocio, usuarios_negocio.Colonia AS ColoniaNegocio, pedidos.Total AS TotalPedido, pedidos.Fecha_Registro AS FechaPedido, Metodo_Pago AS MetodoPago, pedidos.Costo_Envio AS CostoEnvio, pedidos.Estatus AS EstatusPedido,DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedido FROM pedidos INNER JOIN usuarios_cliente ON FK_Usuario_Cliente = ID_Cliente INNER JOIN usuarios_negocio ON FK_Usuario_Negocio = ID_Negocio INNER JOIN ubicaciones ON ubicaciones.FK_Usuario_Cliente = usuarios_cliente.ID_Cliente WHERE (pedidos.Estatus = 'Esperando' OR pedidos.Estatus = 'Asignado' OR pedidos.Estatus = 'TengoPedido' OR pedidos.Estatus = 'LlegoNegocio' OR pedidos.Estatus = 'LlegoDestino') AND ID_Pedido = '$idPedido'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo json_encode("Error 1: ".mysqli_error($omodelo->link));
			}else{
				if ($numerofilas > 0) {
					$pedidos = '';
					$separaFecha = str_replace("-", "", $row[0]["FechaPedido"]);
					$folio = $separaFecha.''.$row[0]["ID_Pedido"];
					$fotoCliente = '';
					if ($row[0]["FotoCliente"] == "") {
						$fotoCliente = '../server_SPIDI_APP/images/logos/logo rojo.png';
					}else{
						$fotoCliente = '../server_SPIDI_APP/images/clientes/'.$row[0]["FotoCliente"];
					}

					$fotoNegocio = '';
					if ($row[0]["FotoNegocio"] == "") {
						$fotoNegocio = '../server_SPIDI_APP/images/logos/logo rojo.png';
					}else{
						$fotoNegocio = '../server_SPIDI_APP/images/negocios/'.$row[0]["FotoNegocio"];
					}
					
					if ($row[0]["NoIntCliente"] != "" && $row[0]["NoIntCliente"] != "0") {
						$NumeroInteriorCliente = ' Int. #'.$row[0]["NoIntCliente"];
					}else{
						$NumeroInteriorCliente = '';
					}

					if ($row[0]["ColoniaCliente"] != "") {
						$ColoniaCliente = ', Col.: '.$row[0]["ColoniaCliente"].'.';
					}else{
						$ColoniaCliente = '';
					}

					$dias = array('Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo');
					$meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

					$direccionCliente = $row[0]["CalleCliente"].' #'.$row[0]["NoExtCliente"].$NumeroInteriorCliente.$ColoniaCliente;

					if ($row[0]["NoIntNegocio"] != "" && $row[0]["NoIntNegocio"] != "0") {
						$NumeroInteriorNegocio = ' Int. #'.$row[0]["NoIntNegocio"];
					}else{
						$NumeroInteriorNegocio = '';
					}

					if ($row[0]["ColoniaNegocio"] != "") {
						$ColoniaNegocio = ', Col.: '.$row[0]["ColoniaNegocio"].'.';
					}else{
						$ColoniaNegocio = '';
					}
					$direccionNegocio = $row[0]["CalleNegocio"].' #'.$row[0]["NoExtNegocio"].$NumeroInteriorNegocio.$ColoniaNegocio;
					$pedidos .= '
					<div class="row" style="color: #000; margin: auto; padding: 2%;margin-bottom: 20px !important;">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3 text-center">
									<img src="'.$fotoCliente.'" alt="" style="width: 100%; border-radius: 15px;"> 
								</div>
								<div class="col-md-9 text-start" style="margin: auto; font-size: 14px;">
									Cliente: <span style="font-weight: bold;">'.$row[0]["NombreCliente"].'</span>
									<br>
						      			Telefono: <span style="font-weight: bold;">'.$row[0]["TelefonoCliente"].'</span>
									<br>
										Dirección: <span style="font-weight: bold;">'.$direccionCliente.'</span>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3 text-center">
									<img src="'.$fotoNegocio.'" alt="" style="width: 100%; border-radius: 15px;"> 
								</div>
								<div class="col-md-9 text-start" style="margin: auto; font-size: 14px;">
									Negocio: <span style="font-weight: bold;">'.$row[0]["NombreNegocio"].'</span>
									<br>
									Telefono: <span style="font-weight: bold;">'.$row[0]["TelefonoNegocio"].'</span>
									<br>
									Dirección: <span style="font-weight: bold;">'.$direccionNegocio.'</span>
								</div>
							</div>
						</div>
					</div>
				    '; 
				}
				echo $pedidos;	
			}
		}else if ($tipo == "ConsultarInformacionPedidoNegocio") {
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$arreglo = null;
			$query = "SELECT ID_Pedido, usuarios_cliente.ID_Cliente AS idCliente, usuarios_negocio.ID_Negocio AS idNegocio, CONCAT(usuarios_cliente.Nombre,' ', usuarios_cliente.Primer_Apellido,' ',usuarios_cliente.Segundo_Apellido) AS NombreCliente, usuarios_cliente.Telefono AS TelefonoCliente, usuarios_cliente.Imagen AS FotoCliente, ubicaciones.Calle AS CalleCliente, ubicaciones.Numero_Exterior AS NoExtCliente, ubicaciones.Numero_Interior AS NoIntCliente, ubicaciones.Colonia AS ColoniaCliente, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Telefono AS TelefonoNegocio, usuarios_negocio.Imagen AS FotoNegocio, usuarios_negocio.Calle AS CalleNegocio, usuarios_negocio.No_Exterior AS NoExtNegocio, usuarios_negocio.No_Interior AS NoIntNegocio, usuarios_negocio.Colonia AS ColoniaNegocio, pedidos.Total AS TotalPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%m-%d') AS FechaPedido, Metodo_Pago AS MetodoPago, pedidos.Costo_Envio AS CostoEnvio, pedidos.Estatus AS EstatusPedido, DATE_FORMAT(pedidos.Fecha_Registro, '%a-%d-%m-%Y %r') AS FechaPedidoCompleta, DATE_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedido, TIME_FORMAT(pedidos.Fecha_Registro, '%r') AS HoraPedidoMostrar, TIME_FORMAT(DATE_ADD(pedidos.Fecha_Registro, INTERVAL detalle_pedido_negocio.Tiempo_Preparacion MINUTE), '%r') AS HoraEntregarMostrar, detalle_pedido_negocio.Tiempo_Preparacion FROM pedidos INNER JOIN usuarios_cliente ON FK_Usuario_Cliente = ID_Cliente INNER JOIN usuarios_negocio ON FK_Usuario_Negocio = ID_Negocio INNER JOIN ubicaciones ON ubicaciones.FK_Usuario_Cliente = usuarios_cliente.ID_Cliente INNER JOIN detalle_pedido_negocio ON FK_Pedido = ID_Pedido WHERE (pedidos.Estatus = 'Aceptado') AND(detalle_pedido_negocio.Estatus = 'Aceptado') AND ID_Pedido = '$idPedido' GROUP BY ID_Pedido";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo json_encode("Error 1: ".mysqli_error($omodelo->link));
			}else{
				if ($numerofilas > 0) {
					$pedidos = '';
					$separaFecha = str_replace("-", "", $row[0]["FechaPedido"]);
					$folio = $separaFecha.''.$row[0]["ID_Pedido"];
					$fotoCliente = '';
					if ($row[0]["FotoCliente"] == "") {
						$fotoCliente = '../server_SPIDI_APP/images/logos/logo rojo.png';
					}else{
						$fotoCliente = '../server_SPIDI_APP/images/clientes/'.$row[0]["FotoCliente"];
					}

					$fotoNegocio = '';
					if ($row[0]["FotoNegocio"] == "") {
						$fotoNegocio = '../server_SPIDI_APP/images/logos/logo rojo.png';
					}else{
						$fotoNegocio = '../server_SPIDI_APP/images/negocios/'.$row[0]["FotoNegocio"];
					}
					
					if ($row[0]["NoIntCliente"] != "" && $row[0]["NoIntCliente"] != "0") {
						$NumeroInteriorCliente = ' Int. #'.$row[0]["NoIntCliente"];
					}else{
						$NumeroInteriorCliente = '';
					}

					if ($row[0]["ColoniaCliente"] != "") {
						$ColoniaCliente = ', Col.: '.$row[0]["ColoniaCliente"].'.';
					}else{
						$ColoniaCliente = '';
					}

					$dias = array('Mon' => 'Lunes', 'Tue' => 'Martes', 'Wed' => 'Miércoles', 'Thu' => 'Jueves', 'Fri' => 'Viernes', 'Sat' => 'Sábado', 'Sun' => 'Domingo');
					$meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

					$separa = explode(' ', $row[0]['FechaPedidoCompleta']);
					$fechaR = $separa[0];
					$separadaFecha = explode('-', $fechaR);
					$fechaLetras = $dias[$separadaFecha[0]].' '.$separadaFecha[1].' de '.$meses[$separadaFecha[2]].' del '.$separadaFecha[3];

					$direccionCliente = $row[0]["CalleCliente"].' #'.$row[0]["NoExtCliente"].$NumeroInteriorCliente.$ColoniaCliente;

					if ($row[0]["NoIntNegocio"] != "" && $row[0]["NoIntNegocio"] != "0") {
						$NumeroInteriorNegocio = ' Int. #'.$row[0]["NoIntNegocio"];
					}else{
						$NumeroInteriorNegocio = '';
					}

					if ($row[0]["ColoniaNegocio"] != "") {
						$ColoniaNegocio = ', Col.: '.$row[0]["ColoniaNegocio"].'.';
					}else{
						$ColoniaNegocio = '';
					}
					$direccionNegocio = $row[0]["CalleNegocio"].' #'.$row[0]["NoExtNegocio"].$NumeroInteriorNegocio.$ColoniaNegocio;
					$pedidos .= '
					<div class="row" style="color: #000; margin: auto; padding: 2%;margin-bottom: 20px !important;">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3 text-center">
									<img src="'.$fotoNegocio.'" alt="" style="width: 100%; border-radius: 15px;"> 
								</div>
								<div class="col-md-9 text-start" style="margin: auto; font-size: 14px;">
									Negocio: <span style="font-weight: bold;">'.$row[0]["NombreNegocio"].'</span>
									<br>
									Telefono: <span style="font-weight: bold;">'.$row[0]["TelefonoNegocio"].'</span>
									<br>
									Dirección: <span style="font-weight: bold;">'.$direccionNegocio.'</span>
								</div>
							</div>
						</div>
					</div>
				    '; 
				}
				echo $pedidos;	
			}
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "AceptarPedido") {
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$idCliente = $omodelo->link->real_escape_string($idCliente);
			$fecha = date('Y-m-d');
			$Hora = date('H:i:s');
			$FechaCompleta = date('Y:m:d H:i:s');
			$query = "UPDATE pedidos SET Estatus = 'Aceptado' WHERE FK_Usuario_Negocio = '$idNegocio' AND ID_Pedido = '$idPedido'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo json_encode("Error 1: ".mysqli_error($omodelo->link));
			}else{

				$query2 = "INSERT INTO detalle_pedido_negocio SET FK_Pedido = '$idPedido', FK_Usuario_Negocio = '$idNegocio', Estatus = 'Aceptado', Fecha = '$FechaCompleta', Tiempo_Preparacion = '0'";
				$error2 = $omodelo->_insertar($query2);
				if ($error2 == "si") {
					echo json_encode("Error 2: ".mysqli_error($omodelo->link));
				}else{
					/*$query3 = "INSERT INTO detalle_estatus_pedido SET FK_Pedido = '$idPedido', Estatus = 'Tu pedido ha sido aceptado y está siendo preparado', Fecha_Registro = '$FechaCompleta'";
					$error3 = $omodelo->_insertar($query3);
					if ($error3 == "si") {
						echo json_encode("Error 3: ".mysqli_error($omodelo->link));
					}else{*/
						$query4 = "INSERT INTO notificaciones_cliente SET FK_Usuario_Cliente = '$idCliente', Tipo = 'pedidos', FK_Tipo = '$idPedido', Titulo = 'Pedido aceptado', Mensaje = 'Tu pedido ha sido aceptado y está siendo preparado', Fecha_Registro = '$FechaCompleta', Activo = 1";
						$error4 = $omodelo->_insertar($query4);
						if ($error4 == "si") {
							echo json_encode("Error 4: ".mysqli_error($omodelo->link));
						}else{
							$tokenNotificacion = '';
							$queryNotiC = "SELECT Notificacion FROM devices_clientes WHERE FK_Usuario_Cliente = '$idCliente'";
							$rowNotiC = $omodelo->_consultar($queryNotiC);
							$numerofilaNotiC = $omodelo->numerofilas; 
							if ($rowNotiC == "si") {
								echo json_encode("Error notificacion cliente: ".mysqli_error($omodelo->link));
							}else{
								if ($numerofilaNotiC > 0) { 
									$tokenNotificacion = $rowNotiC[0]["Notificacion"];
								}
							}
							$omodelo->_notificacion($tokenNotificacion, 'Pedido aceptado', 'El pedido fue aceptado.');
						}
						echo "Correcto";

					//}
				}
			}
		}else if ($tipo == "RechazarPedido") {
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$idCliente = $omodelo->link->real_escape_string($idCliente);
			$problema = $omodelo->link->real_escape_string($problema);
			$MensajeCancelado = '';
			$fecha = date('Y-m-d');
			$Hora = date('H:i:s');
			$FechaCompleta = date('Y:m:d H:i:s');

			$MensajeCancelado = $problema;
			

			$query = "UPDATE pedidos SET Estatus = 'CanceladoNegocio', Motivo = '$MensajeCancelado' WHERE FK_Usuario_Negocio = '$idNegocio' AND ID_Pedido = '$idPedido'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo json_encode("Error 1: ".mysqli_error($omodelo->link));
			}else{
				
				$query2 = "INSERT INTO detalle_pedido_negocio SET FK_Pedido = '$idPedido', FK_Usuario_Negocio = '$idNegocio', Estatus = 'CanceladoNegocio', Fecha = '$fecha'";
				$error2 = $omodelo->_insertar($query2);
				if ($error2 == "si") {
					echo json_encode("Error 2: ".mysqli_error($omodelo->link));
				}else{
					$query4 = "INSERT INTO notificaciones_cliente SET FK_Usuario_Cliente = '$idCliente', Tipo = 'pedidos', FK_Tipo = '$idPedido', Titulo = 'Pedido cancelado', Mensaje = '$MensajeCancelado',  Fecha_Registro = '$FechaCompleta', Activo = 1";
					$error4 = $omodelo->_insertar($query4);
					if ($error4 == "si") {
						echo json_encode("Error 4: ".mysqli_error($omodelo->link));
					}else{
						/*$tokenNotificacion = '';
						$queryNotiC = "SELECT Notificacion FROM devices_clientes WHERE FK_Usuario_Cliente = '$idCliente'";
						$rowNotiC = $omodelo->_consultar($queryNotiC);
						$numerofilaNotiC = $omodelo->numerofilas; 
						if ($rowNotiC == "si") {
							echo json_encode("Error notificacion cliente: ".mysqli_error($omodelo->link));
						}else{
							if ($numerofilaNotiC > 0) { 
								$tokenNotificacion = $rowNotiC[0]["Notificacion"];
							}
						}
						$omodelo->_notificacion($tokenNotificacion, 'Pedido rechazado', 'El pedido fue rechazado. Motivos: '.$MensajeCancelado);*/

						echo "Correcto";
					}
				}
			}
		}else if($tipo == "AsignarPedido"){
			$idRepartidor = $omodelo->link->real_escape_string($idRepartidor);
			$idPedido = $omodelo->link->real_escape_string($idPedido);
			$fecha = date('Y-m-d H:i:s'); 

			$query = "SELECT ID_Asignar_Pedido FROM asignar_pedido WHERE FK_Pedido = '$idPedido' AND FK_Usuario_Repartidor = '$idRepartidor'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if ($numerofilas > 0) {
					echo "Pedido Asignado";
				}else{
					$query1 = "INSERT INTO asignar_pedido SET FK_Pedido = '$idPedido', FK_Usuario_Repartidor = '$idRepartidor', Fecha_Registro = '$fecha'";
					$resultado = $omodelo->_insertar($query1);
					if ($resultado == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						$queryPedido = "UPDATE pedidos SET Estatus = 'Asignado' WHERE ID_Pedido = '$idPedido'";
						$errorPedido = $omodelo->_insertar($queryPedido);
						if ($errorPedido == "si") {
							echo json_encode("Error 1: ".mysqli_error($omodelo->link));
						}else{
							echo "Correcto";
						}
					}
				}
			}
		}
	}
}
?>