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
							'Total' => $total_calc != "" ? $total_calc : $row[$i]['Total_Cliente'],
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
												<button class='btn btn-danger borrarDeCorteDeRuta' ID_Venta='".$row[$i]['ID_Venta']."' ID_Cliente='".$idCliente."' type='button'><i class='fa-solid fa-trash'></i> Eliminar</button>
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
												<button class='btn btn-primary btn-sm agregarDeCorteDeRuta' ID_Venta='".$row[$i]['ID_Venta']."' ID_Cliente='".$id."' type='button'><i class='fa-solid fa-plus'></i> Agregar</button>
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
			
		}else if ($tipo == 'obtenerCorteGuardado'){
			$idCorte = $omodelo->link->real_escape_string($idCorte);

			$query = "SELECT Ruta, Fecha_Inicio, Fecha_Fin, Verificado, FK_Chofer, FK_Vehiculo, Estado, Total FROM cortes_ruta WHERE ID_Corte = $idCorte";
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
					'Detalles' => json_encode($detalles_corte)
				);
	
				echo json_encode($cortes_route_data);
			}
		}else if ($tipo == 'verificarCorte') {
			$idCorte =  $omodelo->link->real_escape_string($idCorte);

			$query = "SELECT productos.Codigo AS Codigo, productos.Descripcion AS Producto, (SELECT SUM(Cantidad) FROM detalles_ventas WHERE FK_Producto = productos.ID_Producto) AS Cantidadtol, (SELECT SUM(Cantidad_Verificada) FROM detalles_ventas WHERE FK_Producto = productos.ID_Producto) AS Verificacion FROM  detalles_ventas JOIN productos ON detalles_ventas.FK_Producto = productos.ID_Producto WHERE detalles_ventas.FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$idCorte');";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$verificacion = 0;
			$cantidad = 0;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){

					for($j=0; $j<$numerofilas; $j++){
						$verificacion = $verificacion + $row[$j]['Verificacion'];
						$cantidad = $cantidad + $row[$j]['Cantidadtol'];
					}

					if ($cantidad == $verificacion) {
						$query1 = "UPDATE cortes_ruta SET Verificado = '1' WHERE ID_Corte = '$idCorte'";
						$row1 = $omodelo->_insertar($query1);

						echo '1' ;

					}else{
						for($i=0; $i<$numerofilas; $i++){

						$card = "<tr>
								  <td>".$row[$i]['Codigo']."</td>
							      <th>".$row[$i]['Producto']."</th>
							      <td>".$row[$i]['Cantidadtol']."</td>
							      <td>".$row[$i]['Verificacion']."</td>
							    </tr>";

						echo $card ;

						}

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
							'Total' => $row[$i]['Total'],
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

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if ($tipo == 'ordenRuta') {

			$id = $omodelo->link->real_escape_string($id);
			$valor = $omodelo->link->real_escape_string($valor);
			$valorAntes = $omodelo->link->real_escape_string($valorAntes);

			$query = "UPDATE clientes SET Orden_Ruta = $valor WHERE ID_Cliente = '$id'";
			$row = $omodelo->_insertar($query);

			if ($valorAntes < $valor) {
				$query2 = "UPDATE clientes SET Orden_Ruta = Orden_Ruta + 1 WHERE Orden_Ruta >= '$valor' AND FK_Ruta = (SELECT FK_Ruta FROM clientes WHERE ID_Cliente = '$id') AND ID_Cliente != '$id'";
				$row2 = $omodelo->_insertar($query2);
			}else if ($valorAntes > $valor) {
				$query3 = "UPDATE clientes SET Orden_Ruta = Orden_Ruta - 1 WHERE Orden_Ruta <= '$valor' AND FK_Ruta = (SELECT FK_Ruta FROM clientes WHERE ID_Cliente = '$id') AND ID_Cliente != '$id'";
				$row3 = $omodelo->_insertar($query3);
			}


			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		}else if($tipo == 'verificar'){

			$IDCodigo = $omodelo->link->real_escape_string($IDCodigo);
			$IDCorte = $omodelo->link->real_escape_string($IDCorte);

			$query = "UPDATE detalles_ventas SET Cantidad_Verificada = Cantidad_Verificada + 1 WHERE FK_Producto = (SELECT ID_Producto FROM productos WHERE Codigo = '$IDCodigo' AND ID_Producto = (SELECT FK_Producto FROM detalles_ventas WHERE FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$IDCorte')) LIMIT 1) AND Cantidad != Cantidad_Verificada;";
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
?>
