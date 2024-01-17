<?php
class cortesRuta {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$tipo =  $omodelo->link->real_escape_string($tipo);

		if ($tipo == 'clientesRuta') {
			$Ruta =  $omodelo->link->real_escape_string($Ruta);
			$FechaInicioCorte = $omodelo->link->real_escape_string($FechaInicioCorte);
			$FechaFinCorte = $omodelo->link->real_escape_string($FechaFinCorte);

			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			//TODO Cmabiar consulta y llenar objeto

			$query = "SELECT c.ID_Cliente, c.Orden_Ruta, CONCAT(c.Nombre, ' ', c.Primer_Apellido, ' ', c.Segundo_Apellido ) AS Nombre_Cliente, Nombre, (SELECT SUM(v.Total) FROM ventas AS v WHERE v.FK_Cliente = c.ID_Cliente AND v.Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND v.Estatus = 'Completada' AND NOT EXISTS (SELECT tev.FK_Venta FROM temporal_excluir_venta AS tev WHERE tev.FK_Venta = v.ID_Venta)) AS Total_Cliente, CONCAT('C. ',c.Calle, ', No. ', c.No_Exterior, (CASE WHEN NULLIF(c.No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', c.No_Interior, ', ') ELSE ', ' END), c.Colonia, ', ', c.Ciudad, ', ', c.Estado, ', ', c.Pais) AS Domicilio_Cliente, (SELECT COUNT(*) FROM clientes WHERE FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta') $busqueda) AS Num FROM clientes AS c WHERE c.FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta') $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$bDetalles = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1') {
							$bDetalles = '<button type="button" class="btn btn-sm btn-outline-info bDetallesCorteClientes" attrID="'.$row[$i]['ID_Cliente'].'" title="Detalles"> <i class="fa-solid fa-list"></i></button>';
						}
						
						
						$arreglo['data'][$i] = array(
							'Orden_Ruta' =>'<span class="orden" attrID="'.$row[$i]['ID_Cliente'].'">'.$row[$i]['Orden_Ruta'].'</span>',
							'Nombre' => $row[$i]['Nombre_Cliente'],
							'Domicilio' => $row[$i]['Domicilio_Cliente'],
							'Total' => $row[$i]['Total_Cliente'],
							'Acciones' => $bDetalles
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);


		}else if ($tipo == 'clientesDetalles') {
			
			$idCliente =  $omodelo->link->real_escape_string($idCliente);
			$FechaInicioCorte = $omodelo->link->real_escape_string($FechaInicioCorte);
			$FechaFinCorte = $omodelo->link->real_escape_string($FechaFinCorte);

			$query = "SELECT ID_Venta, Fecha_Registro, Total FROM ventas WHERE FK_Cliente = $idCliente AND Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND NOT EXISTS (SELECT FK_Venta FROM temporal_excluir_venta WHERE FK_Venta = ID_Venta)";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;


			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						//TODO: Hacer la concat en un string y hacer una sola consulta con un OR en php
						//Recibir Objeto AJAX con json_decode
						$query2 = "SELECT Descripcion, Cantidad, (Total / Cantidad) as Precio, Total FROM detalles_ventas WHERE FK_Venta ='".$row[$i]['ID_Venta']."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;
						

						$card = "<div class='row border border-secondary rounded px-2 py-3 mb-3'>
									<div class='col'>
										<div class='ventas-header row justify-content-between align-items-center mb-2'>
											<div class='col-8 px-2'>
												<div class='row'>
													<p class='py-0 my-0 col-4 pl-2'><b>Id:</b> ".$row[$i]['ID_Venta']."</p>
													<p class=py-0 my-0 col-8'><b>Fecha de Venta:</b> ".date('d-m-Y', strtotime($row[$i]['Fecha_Registro']))."</p>
												</div>
											</div>
											<div class='col text-end'>
												<button class='btn btn-danger borrarDeCorteDeRuta'ID_Venta='".$row[$i]['ID_Venta']."' type='button'><i class='fa-solid fa-trash'></i> Eliminar</button>
											</div>
										</div>
										<div class='ventas-detalle row px-3'>
											<table class='table'>
											<thead>
												<tr>
													<th scope='col'>Producto</th>
													<th scope='col'>Cantidad</th>
													<th scope='col'>Precio U</th>
													<th scope='col'>Subtotal</th>
												</tr>
											</thead>
											<tbody>
											";

						if($numerofilas2 > 0){
							for ($j=0; $j < $numerofilas2; $j++) { 
								$card .= "
									<tr>
										<th>".$row2[$j]['Descripcion']."</th>
										<th>".$row2[$j]['Cantidad']."</th>
										<th>".$row2[$j]['Precio']."</th>
										<th>".$row2[$j]['Total']."</th>
									</tr>
								";
							}
						}

						$card .= "</tbody>
								</table>
										</div>
										<div class='ventas-footer text-end'>
											<p class='p-0 m-0 fs-3'><b>Total:</b> ".$row[$i]['Total']."</p>
										</div>
									</div>
								</div>";

						echo $card;
					}
				}
			}
		}else if ($tipo == 'obtenerExcluidos'){

			$id					 =  $omodelo->link->real_escape_string($id);
			$FechaInicioCorte	 =  $omodelo->link->real_escape_string($FechaInicioCorte);
			$FechaFinCorte		 =  $omodelo->link->real_escape_string($FechaFinCorte);

			$query = "SELECT ID_Venta, Fecha_Registro, Total FROM ventas AS v WHERE v.FK_Cliente = $id AND EXISTS ( SELECT tev.FK_Venta FROM temporal_excluir_venta AS tev WHERE tev.FK_Venta = v.ID_Venta ) OR  v.Fecha_Registro NOT BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$query2 = "SELECT Descripcion, Cantidad, (Total / Cantidad) as Precio, Total FROM detalles_ventas WHERE FK_Venta ='".$row[$i]['ID_Venta']."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;


						$card = "<div class='row border border-secondary rounded px-2 py-3 mb-3'>
									<div class='col'>
										<div class='ventas-header row justify-content-between align-items-center mb-2'>
											<div class='col-8 px-2'>
												<div class='row'>
													<p class='py-0 my-0 col-4 pl-2'><b>Id:</b> ".$row[$i]['ID_Venta']."</p>
													<p class='py-0 '><b>Fecha de Venta:</b> ".date('d-m-Y', strtotime($row[$i]['Fecha_Registro']))."</p>
												</div>
											</div>
											<div class='col text-end align-items-center'>
												<button class='btn btn-primary btn-sm agregarDeCorteDeRuta'ID_Venta='".$row[$i]['ID_Venta']."' type='button'><i class='fa-solid fa-plus'></i> Agregar</button>
											</div>
										</div>
										<div class='ventas-detalle row table-responsive'>
											<table class='table w-100'>
											<thead>
												<tr>
													<th scope='col'>Prod.</th>
													<th scope='col'>Cant.</th>
													<th scope='col'>Precio</th>
													<th scope='col'>Subt.</th>
												</tr>
											</thead>
											<tbody>
											";

						if($numerofilas2 > 0){
							for ($j=0; $j < $numerofilas2; $j++) { 
								$card .= "
									<tr>
										<th>".$row2[$j]['Descripcion']."</th>
										<th>".$row2[$j]['Cantidad']."</th>
										<th>".$row2[$j]['Precio']."</th>
										<th>".$row2[$j]['Total']."</th>
									</tr>
								";
							}
						}

						$card .= "</tbody>
								</table>
										</div>
										<div class='ventas-footer text-end'>
											<p class='p-0 m-0 fs-6'><b>Total:</b> ".$row[$i]['Total']."</p>
										</div>
									</div>
								</div>";

						echo $card;



					}
				}
			}			
			
		}else{
			$buscar =  $omodelo->link->real_escape_string($buscar);
			$limit =  $omodelo->link->real_escape_string($limit);
			$pagina =  $omodelo->link->real_escape_string($pagina);
			$ordenColumna =  $omodelo->link->real_escape_string($ordenColumna);
			$orden =  $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			$busqueda = '';
			if(trim($buscar) != ''){
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i=0; $i < count($separa); $i++) { 
					$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), Ruta, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y'), DATE_FORMAT(Fecha_Fin, '%d-%m-%Y')) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Corte, Ruta, Fecha_Inicio, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y') AS FechaI, Fecha_Fin,  DATE_FORMAT(Fecha_Fin, '%d-%m-%Y') AS FechaF, Verificado, FK_Chofer, FK_Vehiculo, Estado, Imagen, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, (SELECT COUNT(*) FROM cortes_ruta $busqueda) AS Num FROM cortes_ruta $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$bModificar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1') {
							$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-pencil"></i></button>';
						}

						$bEliminar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
							$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-trash"></i></button>';
						}

						$bVerificar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1') {
							$bVerificar = '<br><br><button type="button" class="btn btn-sm btn-outline-secondary bVerificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Verificar">Verificar <i class="fas fa-check"></i></button>';
						}

						$bImagen = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
							$bImagen = '<button type="button" class="btn btn-sm btn-secondary bImagenCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Imagen"><i class="fas fa-image"></i></button>';
						}

						$verificado = '<span class="badge rounded-pill bg-danger">No</span>'.$bVerificar;
						if($row[$i]['Verificado'] == '1'){
							$verificado = '<span class="badge rounded-pill bg-success">Si</span>';
						}
						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Corte'],
							'Fecha' => $row[$i]['Fecha_Registro'],
							'Ruta' => $row[$i]['Ruta'],
							'Fecha_Inicio' => $row[$i]['FechaI'],
							'Fecha_Fin' => $row[$i]['FechaF'],
							'Total' => '',
							'Verificado' => $verificado,
							'Detalles' => $row[$i]['FK_Chofer'].$row[$i]['FK_Vehiculo'],
							'Acciones' => $bModificar.' '.$bEliminar
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);

		}

		
	}

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$tipo = $omodelo->link->real_escape_string($tipo);

		if ($tipo == 'eliminarTemporal') {

			$id = $omodelo->link->real_escape_string($id);


			$query = "INSERT INTO temporal_excluir_venta SET FK_Venta = '$id'";
			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else{

			$rutasCorte = $omodelo->link->real_escape_string($rutasCorte);
			$FechaInicioCorte = $omodelo->link->real_escape_string($FechaInicioCorte);
			$FechaFinCorte = $omodelo->link->real_escape_string($FechaFinCorte);
			$selectChofer = $omodelo->link->real_escape_string($selectChofer);
			$selectVehiculo = $omodelo->link->real_escape_string($selectVehiculo);

			$query = "INSERT INTO cortes_ruta SET Ruta = '$rutasCorte', Fecha_Inicio = '$FechaInicioCorte', Fecha_Fin = '$FechaFinCorte', Verificado = false, Imagen = '', Fecha_Registro = '$fecha', Fk_Chofer = '$selectChofer', FK_Vehiculo = '$selectVehiculo', Estado = 'Pendiente' ";
			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}
		
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if ($tipo == 'ordenRuta') {

			$id = $omodelo->link->real_escape_string($id);
			$valor = $omodelo->link->real_escape_string($valor);

			$query = "UPDATE clientes SET Orden_Ruta = $valor WHERE ID_Cliente = '$id'";
			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}

	}


	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id =  $omodelo->link->real_escape_string($id);

		$query = "DELETE FROM temporal_excluir_detalle_venta WHERE FK_Detalle_Venta = '$id'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);			
		}
	}
}
?>
