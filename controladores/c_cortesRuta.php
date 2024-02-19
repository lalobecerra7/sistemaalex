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
			$IDCorteRuta = $omodelo->link->real_escape_string($IDCorteRuta);
			$arreglo = array();

			if ($IDCorteRuta == 'n/a') {
				$consulta = '';
			}else{
				$consulta = 'IFNULL((SELECT SUM(Cantidad) FROM productos_verificados_corte_ruta WHERE FK_Corte_Ruta = '.$IDCorteRuta.' AND FK_Cliente = c.ID_Cliente), 0) AS Verificacion, (SELECT SUM(Cantidad) FROM detalles_ventas JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '.$IDCorteRuta.') AND ventas.FK_Cliente = c.ID_Cliente) AS CantidadDetalle,';
			}
		
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

			$query = "SELECT c.ID_Cliente, c.Orden_Ruta, CONCAT(c.Nombre, ' ', c.Primer_Apellido, ' ', c.Segundo_Apellido ) AS Nombre_Cliente, Nombre, IFNULL((SELECT SUM(v.Total) FROM ventas AS v WHERE v.FK_Cliente = c.ID_Cliente AND v.Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND v.Estatus = 'Completada'), 0) AS Total_Cliente, CONCAT('C. ',c.Calle, ', No. ', c.No_Exterior, (CASE WHEN NULLIF(c.No_Interior, '') IS NOT NULL THEN CONCAT(', Int. ', c.No_Interior, ', ') ELSE ', ' END), c.Colonia, ', ', c.Ciudad, ', ', c.Estado, ', ', c.Pais) AS Domicilio_Cliente, $consulta (SELECT COUNT(*) FROM clientes AS c WHERE c.FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta') AND IFNULL((SELECT SUM(v.Total) FROM ventas AS v WHERE v.FK_Cliente = c.ID_Cliente AND v.Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND v.Estatus = 'Completada'), 0) > 0 $busqueda) AS Num FROM clientes AS c WHERE c.FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta') AND IFNULL((SELECT SUM(v.Total) FROM ventas AS v WHERE v.FK_Cliente = c.ID_Cliente AND v.Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND v.Estatus = 'Completada'), 0) > 0 $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
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

						$bVerificar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][3] == '1' AND $IDCorteRuta != 'n/a') {
							$bVerificar = '<button type="button" class="btn btn-sm btn-outline-info bVerificarCorteRutaCliente" attrID="'.$row[$i]['ID_Cliente'].'" title="Verificar cliente"> <i class="fa-solid fa-check"></i></button>';
						}

						$total_calc = "";

						if(isset($ventas) && $ventas != null){
							$random = json_decode($ventas, true);
							$tempVentasCliente = $this->encontrarVentasPorCliente($random, $row[$i]['ID_Cliente']);

							if(isset($tempVentasCliente) && count($tempVentasCliente['ventas']) > 0){
								$looked = "";

								for ($j=0; $j < count($tempVentasCliente['ventas']); $j++) { 
									if($j === count($tempVentasCliente['ventas'])-1){
										$looked .= $tempVentasCliente['ventas'][$j];
									}else{
										$looked .= $tempVentasCliente['ventas'][$j].',';
									}
								}

								$query2 = "SELECT IFNULL(SUM(Total), 0) as Total FROM ventas WHERE ID_Venta IN ($looked)";
								$row2 = $omodelo->_consultar($query2);
								
								$total_calc = $row2[0]['Total'];
							}else{
								$total_calc = '0';
							}
						}

						if ($IDCorteRuta == 'n/a') {
							$estado = '';
						}else{
							if ($row[$i]['CantidadDetalle'] == $row[$i]['Verificacion']) {
								$estado = "<span class='badge rounded-pill bg-success'>Listo</span>";
							}else{
								$estado = "<span class='badge rounded-pill bg-danger'>Pendiente</span>";
							}
						}

						$arreglo['data'][$i] = array(
							'Orden_Ruta' =>'<span class="orden" attrID="'.$row[$i]['ID_Cliente'].'">'.$row[$i]['Orden_Ruta'].'</span>',
							'Nombre' => $row[$i]['Nombre_Cliente'],
							'Domicilio' => $row[$i]['Domicilio_Cliente'],
							'Total' => '<span class="dinero">'.( $total_calc != "" ? $total_calc : $row[$i]['Total_Cliente']).'</span>',
							'Estado' => $estado,
							'Acciones' => $bDetalles.' '.$bVerificar
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
			$selectSucursal =  $omodelo->link->real_escape_string($selectSucursal);

			$query = "SELECT c.ID_Cliente, (SELECT GROUP_CONCAT(vn.ID_Venta SEPARATOR ',') FROM ventas AS vn WHERE vn.FK_Cliente = c.ID_Cliente AND vn.Fecha_Registro BETWEEN '$FechaInicioCorte' AND '$FechaFinCorte' AND vn.Total > 0 AND vn.FK_Sucursal = '$selectSucursal') AS Cliente_Ventas  FROM clientes AS c WHERE FK_Ruta = (SELECT r.ID_Ruta FROM rutas AS r WHERE r.Nombre = '$Ruta')";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			$ventas_cliente = array();

			for ($i=0; $i < $numerofilas; $i++) { 

				if($row[$i]['Cliente_Ventas'] != ''){
					$temp_helper = array();
					$temp_helper['cliente'] = $row[$i]['ID_Cliente'];
					$temp_helper['ventas'] = array();  
	
					$numberArray = array_map('intval', explode(',' , $row[$i]['Cliente_Ventas']));
	
					for ($j=0; $j < count($numberArray); $j++) { 
						array_push($temp_helper['ventas'] , $numberArray[$j]);
					}
	
					array_push($ventas_cliente , $temp_helper);
				}
			}

			echo json_encode($ventas_cliente);
			
		}else if ($tipo == 'clientesDetalles') {
			
			$idCliente =  $omodelo->link->real_escape_string($idCliente);
			$FechaInicioCorte = $omodelo->link->real_escape_string($FechaInicioCorte);
			$FechaFinCorte = $omodelo->link->real_escape_string($FechaFinCorte);
			$selectSucursal = $omodelo->link->real_escape_string($selectSucursal);
			$ventas = json_decode($ventas);

			$filterHelper = '';
			if(isset($ventas) &&  count($ventas) > 0){
				$ventasIDs = implode(', ', $ventas);
				$filterHelper = 'AND ID_Venta IN ('.$ventasIDs.')';
			}else{
				$filterHelper = 'AND FK_Sucursal = '.$selectSucursal.' AND Fecha_Registro BETWEEN '.$FechaInicioCorte.' AND '.$FechaFinCorte;
			}
			

			$query = "SELECT ID_Venta, Fecha_Registro, Total FROM ventas WHERE FK_Cliente = $idCliente $filterHelper";
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
										<th><span class='dinero'>".$row2[$j]['Precio']."</span></th>
										<th><span class='dinero'>".$row2[$j]['Total']."</span></th>
									</tr>
								";
							}
						}

						$card .= "</tbody>
								</table>
										</div>
										<div class='ventas-footer text-end'>
											<p class='p-0 m-0 fs-3'><b>Total:</b> <span class='dinero'>".$row[$i]['Total']."</span></p>
										</div>
									</div>
								</div>";

						echo $card;
					}
				}
			}
		}else if ($tipo == 'obtenerExcluidos'){

			$id =  $omodelo->link->real_escape_string($id);
			$selectSucursal =  $omodelo->link->real_escape_string($selectSucursal);
			$ventas = json_decode($ventas);
			

			$busqueda = "";
			if(isset($buscado)){
				$buscado = $omodelo->link->real_escape_string($buscado);
				if(trim($buscado) != ''){
					$busqueda = "AND v.ID_Venta = $buscado";
				}
			}

			$filtrado = '';

			if(isset($ventas) && count($ventas) > 0 ){
				$ventasIDs = implode(', ', $ventas);

				$filtrado = "AND v.FK_Sucursal = ".$selectSucursal." AND v.ID_Venta NOT IN (".$ventasIDs.")";
			}


			$query = "SELECT ID_Venta, Fecha_Registro, Total FROM ventas AS v WHERE v.FK_Cliente = $id $filtrado $busqueda";
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

			$query = "SELECT Ruta, Fecha_Inicio, Fecha_Fin, Verificado, FK_Chofer, FK_Vehiculo, Estado, FK_Sucursal, Total, Recaudado, Imagen FROM cortes_ruta WHERE ID_Corte = $idCorte";
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
					'FK_Sucursal' => $row[0]['FK_Sucursal'],
					'Total' => $row[0]['Total'],
					'Imagen' => $row[0]['Imagen'],
					'Recaudado' => '<span class="fs-5 dinero">'.($row[0]['Recaudado'] && $row[0]['Recaudado'] != 0 ? $row[0]['Recaudado'] : $row[0]['Total']).'</span>',
					'Recaudado_numero' => $row[0]['Recaudado'],
					'Detalles' => json_encode($detalles_corte)
				);
	
				echo json_encode($cortes_route_data);
			}
		}else if ($tipo == 'Concentrado') {
			$idCorte =  $omodelo->link->real_escape_string($idCorte);

			$query = "SELECT FK_Producto, FK_Presentacion, IF (FK_Presentacion <> 0, (SELECT Codigo FROM presentaciones WHERE FK_Presentacion = ID_Presentacion), (SELECT Codigo FROM productos WHERE FK_Producto = ID_Producto)) AS Codigo, Descripcion AS Producto,SUM(Cantidad) as Cantidadtol, IFNULL((SELECT Cantidad FROM productos_verificados_corte_ruta WHERE FK_Corte_Ruta = '$idCorte' AND FK_Producto = detalles_ventas.FK_Producto AND FK_Presentacion = detalles_ventas.FK_Presentacion), 0) AS Verificacion, IFNULL((SELECT ID_Categoria FROM categorias WHERE Nombre LIKE '%HELADO%' AND ID_Categoria = (SELECT FK_Categoria FROM productos WHERE FK_Producto = ID_Producto)), '') AS Categoria FROM detalles_ventas WHERE FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$idCorte') GROUP BY Codigo ORDER BY Categoria DESC, (SELECT Importe FROM productos WHERE ID_Producto = detalles_ventas.FK_Producto )";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$verificacion = 0;
			$cantidad = 0;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){

				
						for($i=0; $i<$numerofilas; $i++){
							if ($row[$i]['Verificacion'] == $row[$i]['Cantidadtol']) {
								$estado = "<span class='badge rounded-pill bg-success'>Listo</span>";
							}else{
								$estado = "<span class='badge rounded-pill bg-danger'>Pendiente</span>";
							}

						$card = "<tr producto='".$row[$i]['FK_Producto']."' presentacion='".$row[$i]['FK_Presentacion']."'>
									<td class='codigoProducto'>".$row[$i]['Codigo']."</td>
									<th>".$row[$i]['Producto']."</th>
									<td>".$row[$i]['Cantidadtol']."</td>
									<td>".$row[$i]['Verificacion']."</td>
									<td>".$estado."</td>
							    </tr>";

						echo $card ;

						}

					
				}
			}	
		}else if ($tipo == 'obtener_balance_datos'){
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
		}else if ($tipo == 'obtener_total_recalc'){
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
		}else if ($tipo == 'verificarCorte') {
			$idCorte =  $omodelo->link->real_escape_string($idCorte);
			$idCliente =  $omodelo->link->real_escape_string($idCliente);

			$query = "SELECT FK_Producto, FK_Presentacion, IF (FK_Presentacion <> 0, (SELECT Codigo FROM presentaciones WHERE FK_Presentacion = ID_Presentacion), (SELECT Codigo FROM productos WHERE FK_Producto = ID_Producto)) AS Codigo, Descripcion AS Producto, SUM(Cantidad) as Cantidadtol, IFNULL((SELECT Cantidad FROM productos_verificados_corte_ruta WHERE FK_Corte_Ruta = '$idCorte' AND FK_Producto = detalles_ventas.FK_Producto AND FK_Presentacion = detalles_ventas.FK_Presentacion AND FK_Cliente = '$idCliente'), 0) AS Verificacion, IFNULL((SELECT ID_Categoria FROM categorias WHERE Nombre LIKE '%HELADO%' AND ID_Categoria = (SELECT FK_Categoria FROM productos WHERE FK_Producto = ID_Producto)), '') AS Categoria FROM detalles_ventas JOIN ventas ON FK_Venta = ID_Venta WHERE FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$idCorte') AND ventas.FK_Cliente = '$idCliente' GROUP BY Codigo ORDER BY Categoria DESC ,(SELECT Importe FROM productos WHERE ID_Producto = detalles_ventas.FK_Producto )";
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

				
						for($i=0; $i<$numerofilas; $i++){
							if ($row[$i]['Verificacion'] == $row[$i]['Cantidadtol']) {
								$estado = "<span class='badge rounded-pill bg-success'>Listo</span>";
							}else{
								$estado = "<span class='badge rounded-pill bg-danger'>Pendiente</span>";
							}

						$card = "<tr producto='".$row[$i]['FK_Producto']."' presentacion='".$row[$i]['FK_Presentacion']."'>
									<td class='codigoProducto'>".$row[$i]['Codigo']."</td>
									<th>".$row[$i]['Producto']."</th>
									<td>".$row[$i]['Cantidadtol']."</td>
									<td>".$row[$i]['Verificacion']."</td>
									<td>".$estado."</td>
							    </tr>";

						echo $card ;

						}

						$query1 = "SELECT FK_Producto, FK_Presentacion, IF (FK_Presentacion <> 0, (SELECT Codigo FROM presentaciones WHERE FK_Presentacion = ID_Presentacion), (SELECT Codigo FROM productos WHERE FK_Producto = ID_Producto)) AS Codigo, Descripcion AS Producto, (SELECT SUM(Cantidad) FROM detalles_ventas WHERE FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$idCorte')) as Cantidadtol, IFNULL((SELECT Cantidad FROM productos_verificados_corte_ruta WHERE FK_Corte_Ruta = '$idCorte' AND FK_Producto = detalles_ventas.FK_Producto AND FK_Presentacion = detalles_ventas.FK_Presentacion), 0) AS Verificacion FROM detalles_ventas WHERE FK_Venta IN (SELECT ventas FROM detalles_corte_ruta WHERE FK_Corte_Ruta = '$idCorte') GROUP BY Codigo ORDER BY (SELECT Importe FROM productos WHERE ID_Producto = detalles_ventas.FK_Producto )";
						$row1 = $omodelo->_consultar($query1);
						$numerofilas1 = $omodelo->numerofilas;

						$verificacion2 = 0;
						$cantidad2 = 0;

						if($row1 == 'si'){
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas1 > 0){

								for($h=0; $h<$numerofilas; $h++){
									$verificacion2 = $verificacion2 + $row1[$h]['Verificacion'];
									$cantidad2 = $cantidad2 + $row1[$h]['Cantidadtol'];
								}

								if ($cantidad == $verificacion) {
									$query2 = "UPDATE cortes_ruta SET Verificado = '1' WHERE ID_Corte = '$idCorte'";
									$row2 = $omodelo->_insertar($query2);

										

								}

								
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
							if($row[$i]['Estado'] == 'Pendiente'){
								$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-pencil"></i></button>';
							}else if($row[$i]['Estado'] == 'Finalizado'){
								$bModificar = '<button type="button" class="btn btn-sm btn-info bModificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-eye"></i></button>';
							}
						}

						$bEliminar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
							$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-trash"></i></button>';
						}

						$bVerificar = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][5] == '1') {
							$bVerificar = '<br><br><button type="button" class="btn btn-sm btn-outline-secondary bVerificarCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Concentrado">Concentrado <i class="fas fa-check"></i></button>';
						}

						$bImagen = '';
						if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][4] == '1') {
							$bImagen = '<button type="button" class="btn btn-sm btn-secondary bImagenCorteRuta" attrID="'.$row[$i]['ID_Corte'].'" title="Imagen"><i class="fas fa-image"></i></button>';
						}

						$verificado = '<span class="badge rounded-pill bg-danger">No</span>'.$bVerificar;
						if($row[$i]['Verificado'] == '1'){
							$verificado = '<span class="badge rounded-pill bg-success">Si</span>'.$bVerificar;
						}

						$estado = '';
						if($row[$i]['Estado'] == 'Pendiente'){
							$estado = '<span class="badge rounded-pill bg-warning">Pendiente</span>';
						}else if($row[$i]['Estado'] == 'Finalizado'){
							$estado = '<span class="badge rounded-pill bg-success">Finalizado</span>';
						}

						$generatePDF = '<button type="button" class="btn btn-sm btn-success bGenerarPDFCorteRuta" attrID="'.$row[$i]['ID_Corte'].'"><i class="fas fa-file"></i></button>';

						$verImagen = '';
						if($row[$i]['Imagen'] && $row[$i]['Imagen'] != ''){
							$verImagen = '<a href="vistas/assets/archivos/cortesRuta/'.$row[$i]['Imagen'].'" data-fancybox><div style="background-image: url('."'".'vistas/assets/archivos/cortesRuta/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
							</div></a>';
						}
						
						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Corte'],
							'Fecha' => $row[$i]['Fecha_Registro'],
							'Ruta' => $row[$i]['Ruta'],
							'Fecha_Inicio' => $row[$i]['FechaI'],
							'Fecha_Fin' => $row[$i]['FechaF'],
							'Total' => '<span class="dinero">'.$row[$i]['Total'].'</span>',
							'Concentrado' => $verificado,
							'Detalles' => $estado.'</br>'.$row[$i]['FK_Chofer'].'</br>'.$row[$i]['FK_Vehiculo'].'</br>'.$verImagen,
							'Acciones' => $bModificar.' '.$bEliminar.' '.$generatePDF
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
			$selectSucursal = $omodelo->link->real_escape_string($selectSucursal);
			$detalleVentas = json_decode($detalleVentas, true);

			$idventas = array_merge(...array_column($detalleVentas, 'ventas'));

			$idventasstr = implode(',' , $idventas);

			$idventasnumber = str_replace('"' , '', $idventasstr );

			$query = "INSERT INTO cortes_ruta SET Ruta = '$rutasCorte', Fecha_Inicio = '$FechaInicioCorte', Fecha_Fin = '$FechaFinCorte', Verificado = false, Imagen = '', Fecha_Registro = '$fecha', Fk_Chofer = '$selectChofer', FK_Vehiculo = '$selectVehiculo', FK_Sucursal = '$selectSucursal', Estado = 'Pendiente', Total = (SELECT SUM(v.Total) as Total FROM ventas AS v WHERE v.ID_Venta IN ($idventasnumber)) ";
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

			$IDCorte = $omodelo->link->real_escape_string($IDCorte);
			$Producto = $omodelo->link->real_escape_string($Producto);
			$Presentacion = $omodelo->link->real_escape_string($Presentacion);
			$idCliente = $omodelo->link->real_escape_string($idCliente);


			$query = "CALL InsertOrUpdateVerificacion('$IDCorte', '$Producto', '$Presentacion', '$idCliente');";

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
			$ImagenAnterior = $omodelo->link->real_escape_string($ImagenAnterior);

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
			
				if($tipoImg != 'image/jpeg' && $tipoImg != 'image/jpg' && $tipoImg != 'image/png' && $tipoImg != 'image/svg' && $tipoImg != 'application/pdf' && $tipoImg != ''){
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
						$status = 1;
					}else{
						move_uploaded_file($rutaProvisional, $ruta);
						if($ImagenAnterior && $ImagenAnterior != ''){
							if(file_exists($carpeta.$ImagenAnterior)){
								if(unlink($carpeta.$ImagenAnterior)){

								}else{
									echo "Error 4 Borrar";
									$status = 1;
								}
							}
						}
					}
				}
			}

			if($status == 0){
				$uploadResponse = array(
					'status' => "Correcto",
					'newImage' => $ID_Corte_Ruta . '_' . $nombreArchivo
				);

				echo json_encode($uploadResponse);
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
		}else if($tipo == 'reabrirCorteRuta'){
			$ID_Corte_Ruta = $omodelo->link->real_escape_string($ID_Corte_Ruta);

			$query = "UPDATE cortes_ruta SET Estado = 'Pendiente' WHERE ID_Corte = $ID_Corte_Ruta";
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

			$query = "UPDATE cortes_ruta SET FK_Vehiculo = '$selectVehiculo', FK_Chofer = '$selectChofer', Total = (SELECT SUM(v.Total) as Total FROM ventas AS v WHERE v.ID_Venta IN ($idventasnumber)) WHERE ID_Corte = $id";
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
