<?php
class cortesRuta {

	function encontrarVentasPorCliente($ventas_cliente, $clienteID){
		foreach ($ventas_cliente as $venta){
			if($venta["cliente"] === $clienteID){
				return $venta;
			}
		}
	}


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

			$query = "SELECT c.ID_Cliente, c.Orden_Ruta, CONCAT(c.Nombre, ' ', c.Primer_Apellido, ' ', c.Segundo_Apellido ) AS Nombre_Cliente, Nombre, (SELECT SUM(v.Total) FROM ventas AS v WHERE v.FK_Cliente = c.ID_Cliente AND v.Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND v.Estatus = 'Completada') AS Total_Cliente, CONCAT('C. ',c.Calle, ', No. ', c.No_Exterior, (CASE WHEN NULLIF(c.No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', c.No_Interior, ', ') ELSE ', ' END), c.Colonia, ', ', c.Ciudad, ', ', c.Estado, ', ', c.Pais) AS Domicilio_Cliente, (SELECT COUNT(*) FROM clientes WHERE FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta') $busqueda) AS Num FROM clientes AS c WHERE c.FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta') $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

						$total_calc = "";
						
						if(isset($ventas)){
							$ventas = json_decode($ventas, true);
							$tempVentasCliente = $this->encontrarVentasPorCliente($ventas, $row[$i]['ID_Cliente']);

							if(isset($tempVentasCliente)){
								$looked = "";

								for ($j=0; $j < count($tempVentasCliente['ventas']); $j++) { 
									if($j === count($tempVentasCliente['ventas'])-1){
										$looked .= $tempVentasCliente['ventas'][$j];
									}else{
										$looked .= $tempVentasCliente['ventas'][$j].',';
									}
								}

								$query2 = "SELECT SUM(Total) as Total FROM ventas WHERE ID_Venta IN ($looked)";
								$row2 = $omodelo->_consultar($query2);
								
								$total_calc = $row2[0]['Total'];
							}
						}
						
						$arreglo['data'][$i] = array(
							'Orden_Ruta' =>'<span class="orden" attrID="'.$row[$i]['ID_Cliente'].'">'.$row[$i]['Orden_Ruta'].'</span>',
							'Nombre' => $row[$i]['Nombre_Cliente'],
							'Domicilio' => $row[$i]['Domicilio_Cliente'],
							'Total' => '<span class="dinero">'.$total_calc != "" ? $total_calc : $row[$i]['Total_Cliente'].'</span>',
							'Acciones' => $bDetalles
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);


		}else if ($tipo == 'all_ventas_clientes') {
			$FechaInicioCorte = $omodelo->link->real_escape_string($FechaInicioCorte);
			$FechaFinCorte = $omodelo->link->real_escape_string($FechaFinCorte);
			$Ruta =  $omodelo->link->real_escape_string($Ruta);

			$query = "SELECT  c.ID_Cliente, (SELECT GROUP_CONCAT(vn.ID_Venta SEPARATOR ',') FROM ventas AS vn WHERE vn.FK_Cliente = C.ID_Cliente AND vn.Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte') AS Cliente_Ventas  FROM clientes AS c WHERE FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta')";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			$ventas_cliente = array();

			for ($i=0; $i < $numerofilas; $i++) { 

				$temp_helper = array();
				$temp_helper['cliente'] = $row[$i]['ID_Cliente'];
				$temp_helper['ventas'] = array();  

				$numberArray = array_map('intval', explode(',' , $row[$i]['Cliente_Ventas']));

				for ($j=0; $j < count($numberArray); $j++) { 
					array_push($temp_helper['ventas'] , $numberArray[$j]);
				}

				array_push($ventas_cliente , $temp_helper);

			}

			echo json_encode($ventas_cliente);
			
		}else if ($tipo == 'clientesDetalles') {
			
			$idCliente =  $omodelo->link->real_escape_string($idCliente);
			$FechaInicioCorte = $omodelo->link->real_escape_string($FechaInicioCorte);
			$FechaFinCorte = $omodelo->link->real_escape_string($FechaFinCorte);
			$ventas = json_decode($ventas);

			if(!$ventas){
				//TODO: Cortar ejecucicion
			}

			$ventasIDs = implode(', ', $ventas);

			$query = "SELECT ID_Venta, Fecha_Registro, Total FROM ventas WHERE FK_Cliente = $idCliente AND Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND ID_Venta IN ($ventasIDs)";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;


			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						//TODO: Hacer la concat en un string y hacer una sola consulta con un OR en php
						//Recibir Objeto AJAX con json_decode
						$query2 = "SELECT Descripcion, Cantidad, (Total / Cantidad) as Precio, Total, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion) ELSE (SELECT Codigo FROM productos WHERE ID_Producto = FK_Producto) END) AS Codigo FROM detalles_ventas WHERE FK_Venta ='".$row[$i]['ID_Venta']."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas;
						

						$card = "<div class='row border border-secondary rounded px-2 py-3 mb-3'>
									<div class='col'>
										<div class='ventas-header row justify-content-between align-items-center mb-2'>
											<div class='col-8 px-2'>
												<div class='row'>
													<p class='py-0 my-0 col-4 pl-2'><b>Id:</b> ".$row[$i]['ID_Venta']."</p>
													<p class=py-0 my-0 col-8'><b>Fecha de Venta:</b> ".date('d-m-Y', strtotime($row[$i]['Fecha_Registro']))."</span>
												</div>
											</div>
											<div class='col text-end'>
												<button class='btn btn-danger borrarDeCorteDeRuta' ID_Venta='".$row[$i]['ID_Venta']."' ID_Cliente='".$idCliente."' type='button'><i class='fa-solid fa-trash'></i> Eliminar</button>
											</div>
										</div>
										<div class='ventas-detalle row px-3'>
											<table class='table'>
											<thead>
												<tr>
													<th scope='col'>Prod</th>
													<th scope='col'>Cod.</th>
													<th scope='col'>Cant</th>
													<th scope='col'>Precio</th>
													<th scope='col'>Subt</th>
												</tr>
											</thead>
											<tbody>
											";

						if($numerofilas2 > 0){
							for ($j=0; $j < $numerofilas2; $j++) { 
								$card .= "
									<tr>
										<th>".$row2[$j]['Descripcion']."</th>
										<th>".$row2[$j]['Codigo']."</th>
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

			$id =  $omodelo->link->real_escape_string($id);
			$ventas = json_decode($ventas);
			
			if(!$ventas){
				//TODO: Cortar ejecucicion
			}

			$busqueda = "";
			if(isset($buscado)){
				$buscado = $omodelo->link->real_escape_string($buscado);
				if(trim($buscado) != ''){
					$busqueda = "AND v.ID_Venta = $buscado";
				}
			}

			$ventasIDs = implode(', ', $ventas);

			$query = "SELECT ID_Venta, Fecha_Registro, Total FROM ventas AS v WHERE v.FK_Cliente = $id AND v.ID_Venta NOT IN ($ventasIDs) $busqueda";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;


			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$query2 = "SELECT Descripcion, Cantidad, (Total / Cantidad) as Precio, Total, (CASE WHEN FK_Presentacion <> 0 THEN (SELECT Codigo FROM presentaciones WHERE ID_Presentacion = FK_Presentacion) ELSE (SELECT Codigo FROM productos WHERE ID_Producto = FK_Producto) END) AS Codigo FROM detalles_ventas WHERE FK_Venta ='".$row[$i]['ID_Venta']."'";
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
												<button class='btn btn-primary btn-sm agregarDeCorteDeRuta' ID_Venta='".$row[$i]['ID_Venta']."' ID_Cliente='".$id."' type='button'><i class='fa-solid fa-plus'></i> Agregar</button>
											</div>
										</div>
										<div class='ventas-detalle row table-responsive'>
											<table class='table w-100'>
											<thead>
												<tr>
													<th scope='col'>Prod.</th>
													<th scope='col'>Cod.</th>
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
										<th>".$row2[$j]['Codigo']."</th>
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
			
		}else if ($tipo == 'obtenerCorteGuardado'){
			$idCorte = $omodelo->link->real_escape_string($idCorte);

			$query = "SELECT Ruta, Fecha_Inicio, Fecha_Fin, Verificado, FK_Chofer, FK_Vehiculo, Estado, Total, Recaudado FROM cortes_ruta WHERE ID_Corte = $idCorte";
			$row = $omodelo->_consultar($query);
			

			$query2 = "SELECT FK_Cliente, Ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = $idCorte";
			$row2 = $omodelo->_consultar($query2);
			$numerofilas2 = $omodelo->numerofilas;

			if($row === "si" || $row2 === "si"){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{

				$detalles_corte = array();

				for ($i=0; $i < $numerofilas2; $i++) { 
					$ventas_temp = array_map('intval', explode(",", $row2[$i]['Ventas']));
					
					$objEntry = [
						"cliente" => $row2[$i]['FK_Cliente'],
						"ventas" => $ventas_temp
					];

					$detalles_corte[] = $objEntry;
				}

				$cortes_route_data = array(
					'ID_Corte' => $idCorte,
					'Ruta' => $row[0]['Ruta'],
					'Fecha_Inicio' => $row[0]['Fecha_Inicio'],
					'Fecha_Fin' => $row[0]['Fecha_Fin'],
					'Verificado' => $row[0]['Verificado'],
					'FK_Chofer' => $row[0]['FK_Chofer'],
					'FK_Vehiculo' => $row[0]['FK_Vehiculo'],
					'Estado' => $row[0]['Estado'],
					'Total' => $row[0]['Total'],
					'Recaudado' => '<span class="fs-5 recaudado dinero" ID_Ruta="'.$idCorte.'">'.$row[0]['Recaudado'].'</span>',
					'Recaudado_numero' => $row[0]['Recaudado'],
					'Detalles' => json_encode($detalles_corte)
				);
	
				echo json_encode($cortes_route_data);
			}
		}else if($tipo == "obtener_balance_datos"){
			$ID_Ruta = $omodelo->link->real_escape_string($ID_Ruta);

			$query = "SELECT ID_Gasto_Corte_Ruta, Descripcion, Coste FROM gastos_cortes_ruta WHERE FK_Corte_Ruta = $ID_Ruta";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row === "si"){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$costes = "";
				$total_costes = 0;
				if($numerofilas > 0){
					for ($j=0; $j < $numerofilas; $j++) { 
						$costes .= "<tr><td class='fs-6'>".$row[$j]['Descripcion']."</td><td class='fs-6'>".$row[$j]['Coste']."</td><td><button class='btn btn-sm btn-danger eliminateCoste' ID_Corte='".$ID_Ruta."' ID_Coste='".$row[$j]['ID_Gasto_Corte_Ruta']."'><i class='fa-solid fa-trash'></button></td></tr>";
						$total_costes+=$row[$j]['Coste'];
					}
				}else{
					$costes = "<tr><td class='fs-6 text-center' colspan='3'>No existen gastos</td></tr>";
				}

				$balances_ruta = array(
					'Costes' => $costes,
					'Total_Costes' => $total_costes
				);

				echo json_encode($balances_ruta);
			}
		}else if($tipo == "obtener_total_recalc"){
			$ventas = json_decode($ventas, true);

			$idventas = array_merge(...array_column($ventas, 'ventas'));
			$idventasstr = implode(',' , $idventas);
			$idventasnumber = str_replace('"' , '', $idventasstr );

			$query = "SELECT SUM(v.Total) as Total FROM ventas AS v WHERE v.ID_Venta IN ($idventasnumber)";
			$row = $omodelo->_consultar($query);

			if($row == "si"){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo $row[0]['Total'];
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

			$query = "SELECT ID_Corte, Ruta, Fecha_Inicio, DATE_FORMAT(Fecha_Inicio, '%d-%m-%Y') AS FechaI, Fecha_Fin,  DATE_FORMAT(Fecha_Fin, '%d-%m-%Y') AS FechaF, Verificado, FK_Chofer, FK_Vehiculo, Estado, Imagen, Fecha_Registro AS Fecha, Total, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, (SELECT COUNT(*) FROM cortes_ruta $busqueda) AS Num FROM cortes_ruta $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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
							'Total' => '<span class="dinero">'.$row[$i]['Total'].'</span>',
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

		if ($tipo == "insertar_gastos"){
			$ID_Ruta = $omodelo->link->real_escape_string($ID_Ruta);
			$Descripcion = $omodelo->link->real_escape_string($Descripcion);
			$Coste = $omodelo->link->real_escape_string($Coste);
			$FloatingCost = floatval($Coste);

			$query = "INSERT INTO gastos_cortes_ruta SET FK_Corte_Ruta = '$ID_Ruta', Descripcion = '$Descripcion', Coste = $FloatingCost";
			$row = $omodelo->_insertar($query);

			if($row == "si"){
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
			$detalleVentas = json_decode($detalleVentas, true);

			$idventas = array_merge(...array_column($detalleVentas, 'ventas'));

			$idventasstr = implode(',' , $idventas);

			$idventasnumber = str_replace('"' , '', $idventasstr );

			$query = "INSERT INTO cortes_ruta SET Ruta = '$rutasCorte', Fecha_Inicio = '$FechaInicioCorte', Fecha_Fin = '$FechaFinCorte', Verificado = false, Imagen = '', Fecha_Registro = '$fecha', Fk_Chofer = '$selectChofer', FK_Vehiculo = '$selectVehiculo', Estado = 'Pendiente', Total = (SELECT SUM(v.Total) as Total FROM ventas AS v WHERE v.ID_Venta IN ($idventasnumber)) ";
			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$last_corte_ruta = mysqli_insert_id($omodelo->link);
				
				foreach ($detalleVentas as $detalle){
					$inserted = "";
					for ($i=0; $i < count($detalle['ventas']); $i++) { 
						if($i === count($detalle['ventas'])-1){
							$inserted .= $detalle['ventas'][$i];
						}else{
							$inserted .= $detalle['ventas'][$i].',';
						}
					}

					$sum_inserted = str_replace('"', '', $inserted );
					
					$query2 = "INSERT INTO detalles_corte_ruta SET FK_Corte_Ruta = '$last_corte_ruta', FK_Cliente = '$detalle[cliente]', Ventas = '$inserted', Total = (SELECT SUM(Total) FROM ventas WHERE ID_Venta IN ($sum_inserted))";
					$row2 = $omodelo->_insertar($query2);

					if($row2 == "si"){
						echo "Error: ".mysqli_error($omodelo->link);
						return false;
					}
				}

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
		}else if($tipo == 'actualizar_dinero_obtenido'){
			$ID_Ruta = $omodelo->link->real_escape_string($ID_Ruta);
			$Monto = $omodelo->link->real_escape_string($Monto);

			$query = "UPDATE cortes_ruta SET Recaudado = $Monto WHERE ID_Corte = '$ID_Ruta'";
			$row = $omodelo->_insertar($query);

			if($row == "si"){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if($tipo == 'subirArchivo'){
			$ID_Corte_Ruta = $omodelo->link->real_escape_string($ID_Corte_Ruta);

			$status = 0;
			$nombreArchivo = '';
			$ruta = '';
			$rutaProvisional = '';
			$carpeta = 'vistas/assets/archivos/cortesRuta/';

			if($_FILES['imageInput']['size'] > 0 && $_FILES['imageInput']['error'] == 0){
				$file = $_FILES['imageInput'];
				$nombreArchivo = $file['name'];
				$tipoImg = $file['type'];
				$rutaProvisional = $file['tmp_name'];
				$sizeImg = $file['size'];
			
				if($tipoImg != 'image/jpeg' && $tipoImg != 'image/jpg' && $tipoImg != 'image/png' && $tipoImg != 'image/svg' && $tipo != 'application/pdf' && $tipoImg != ''){
					echo 'Error 1 formato ' . $tipoImg;
					$status = 1;
				}else if($sizeImg > (1024 * 1024 * 10)){
					echo 'Error 2 peso';
					$status = 1;
				}else {
					$ruta = $carpeta . $ID_Corte_Ruta . '_' . $nombreArchivo;
				}

				if($status == 0 && $nombreArchivo != ''){
					$query = "UPDATE cortes_ruta SET Imagen = '".$ID_Corte_Ruta . "_" . $nombreArchivo."' WHERE ID_Corte = $ID_Corte_Ruta";
					$error = $omodelo->_insertar($query);

					if($error == "si"){
						echo "Error 3: " . mysqli_error($omodelo->link);
					}else{
						move_uploaded_file($rutaProvisional, $ruta);
					}
				}
			}

			if($status == 0){
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if($tipo == 'terminarCorte'){
			$ID_Corte_Ruta = $omodelo->link->real_escape_string($ID_Corte_Ruta);

			$query = "UPDATE cortes_ruta SET Estado = 'Finalizado' WHERE ID_Corte = $ID_Corte_Ruta";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error 3: " . mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else {
			$selectChofer = $omodelo->link->real_escape_string($selectChofer);
			$selectVehiculo = $omodelo->link->real_escape_string($selectVehiculo);
			$id = $omodelo->link->real_escape_string($id);
			$ventas_obj = json_decode($detalleVentas, true);

			$idventas = array_merge(...array_column($ventas_obj, 'ventas'));
			$idventasstr = implode(',' , $idventas);
			$idventasnumber = str_replace('"' , '', $idventasstr );

			$query = "UPDATE cortes_ruta SET FK_Vehiculo = '$selectChofer', FK_Chofer = '$selectVehiculo', Total = (SELECT SUM(v.Total) as Total FROM ventas AS v WHERE v.ID_Venta IN ($idventasnumber)) WHERE ID_Corte = $id";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "ErrorModificar: ".mysqli_error($omodelo->link);
			}else{
				if(isset($ventas_obj)){
					foreach($ventas_obj as $venta){
						$cliente = (int) $venta['cliente'];
						$ventas_upt = implode(',', $venta['ventas']);

						$query2 = "UPDATE detalles_corte_ruta SET Ventas = '$ventas_upt' WHERE FK_Cliente = $cliente AND FK_Corte_Ruta = $id";
						$error2 = $omodelo->_insertar($query2);

						if($error2 == "si"){
							echo "ErrorModificar: ".mysqli_error($omodelo->link);
						}else{
							$omodelo->movimiento($query2, $_SESSION['user_admin']['ID_Usuario']);
						}
					}
					echo "Correcto";
				}else{
					echo "Correcto";
					$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
				}
			}

		}
	}


	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		if($tipo == 'eliminar_gasto'){
			$ID_Gasto = $omodelo->link->real_escape_string($ID_Gasto);

			$query = "DELETE FROM gastos_cortes_ruta WHERE ID_Gasto_Corte_Ruta = '$ID_Gasto'";
			$error = $omodelo->_insertar($query);

			if($error == "si"){
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);	
			}

		}else{
			$id =  $omodelo->link->real_escape_string($id);

			$query2 = "DELETE FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$id'";
			$error2 = $omodelo->_insertar($query2);

			$query = "DELETE FROM cortes_ruta WHERE ID_Corte = '$id'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);			
			}
		}
	}
}
?>
