<?php
class hacerPedido {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		if ($tipo == "consultarProductos") {
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
					$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), Codigo, Descripcion, Precio) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}	  

			$query = "SELECT ID_Producto, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Codigo, Descripcion, Precio, Foto, IFNULL((SELECT SUM(Cantidad) FROM inventario WHERE FK_Producto = ID_Producto), 0) AS Existencia, precio + (precio * (COALESCE((SELECT SUM(Porcentaje) FROM impuestos WHERE ID_Impuesto IN (SELECT FK_Impuesto FROM productos_impuestos WHERE FK_Producto = ID_Producto)), 0))/100) AS precio_con_impuestos, (SELECT GROUP_CONCAT(CONCAT(nombre, CONCAT(Porcentaje, '%','($',(((productos.Precio) * (Porcentaje/100)) * 1),')')) SEPARATOR '<br> ') FROM impuestos LEFT JOIN productos_impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = ID_Producto) AS nombreImpuestos, (SELECT COUNT(*) FROM productos $busqueda) AS Num FROM productos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = '<a href="vistas/assets/images/producto-generico.png" data-fancybox="images">
				        	<div style="background-image: url(' . "'" . 'vistas/assets/images/producto-generico.png' . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
				        	</div>
				        </a>';

				        if ($row[$i]['Foto'] != '' && file_exists('vistas/assets/images/productos/' . $row[$i]['Foto'])) {
				        	$foto = '<a href="vistas/assets/images/productos/' . $row[$i]['Foto'] . '" data-fancybox="images">
				          		<div style="background-image: url(' . "'" . 'vistas/assets/images/productos/' . $row[$i]['Foto'] . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
				          		</div>
				          	</a>';
				        }

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Producto'],
							'Fecha' => $row[$i]['Fecha_Registro'],
							'Codigo' => $foto.$row[$i]['Codigo'],
							'Descripcion' => $row[$i]['Descripcion'],
							'Impuestos' => 'Precio: <span class="dinero">'.$row[$i]['Precio'].'</span><br>'.$row[$i]['nombreImpuestos'],
							'Precio' => '<span class="dinero">'.$row[$i]['precio_con_impuestos'].'</span>'
						);
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "consultarClientes"){
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
					$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), RFC, Nombre, ID_Cliente, Credito, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Contacto, Email_Contacto, Telefono_Contacto, Telefono, Segundo_Telefono, Email) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT ID_Cliente, Nombre, RFC, Credito, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Contacto, Email_Contacto, Telefono_Contacto, Telefono, Segundo_Telefono, Email, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, (SELECT COUNT(*) FROM clientes $busqueda) AS Num FROM clientes  $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$domicilio = '<b>'.$row[$i]['Calle'].' #'.$row[$i]['No_Exterior'].'</b> ';
				        $contacto = 'Tel: <b>'.$row[$i]['Telefono'].'</b><br>';

				        if ($row[$i]['No_Interior'] != "") {
				        	$domicilio .= 'No. Int. <b>'.$row[$i]['No_Interior'].'</b> ';
				        }

				        if ($row[$i]['Colonia'] != "") {
				            $domicilio .= 'Col. <b>'.$row[$i]['Colonia'].'</b> ';
				        }

				        $domicilio .= 'C.P. <b>'.$row[$i]['CP'].'</b>, <b>'.$row[$i]['Ciudad'].'</b> <b>'.$row[$i]['Estado'].'</b>, <b>'.$row[$i]['Pais'].'</b>';
				          

				        if ($row[$i]['Segundo_Telefono'] != "") {
				            $contacto .= 'Tel: <b>'.$row[$i]['Segundo_Telefono'].'</b><br>';
				        }

				        if ($row[$i]['Email'] != "") {
				            $contacto .= 'Email: <b>'.$row[$i]['Email'].'</b><br>';
				        }

				        if ($row[$i]['Contacto'] != '') {
				            $contacto .= 'Contacto: <b>'.$row[$i]['Contacto'].'</b>';
				        }

				        if ($row[$i]['Email_Contacto'] != '') {
				            $contacto .= 'Email contacto: <b>'.$row[$i]['Email_Contacto'].'</b>';
				        }

				        if ($row[$i]['Telefono_Contacto'] != '') {
				            $contacto .= 'Tel contacto: <b>'.$row[$i]['Telefono_Contacto'].'</b>';
				        }

				        $cuenta = '';
				       

				        $nombre = '<span>'.$row[$i]['Nombre'].'</span>';
				        if ($row[$i]['RFC'] != '') {
				            $nombre .= '<br>RFC: <b>'.$row[$i]['RFC'].'</b>';
				        }

				        if ($row[$i]['Credito'] != '') {
				            $nombre .= '<br>Crédito: <b  class="dinero">'.$row[$i]['Credito'].'</b><br>Adeudo: <b  class="dinero">'.$row[$i]['Credito'].'</b><br>Restante: <b class="dinero">'.($row[$i]['Credito'] - $row[$i]['Credito']).'</b>';
				        }

				        $arreglo['data'][$i] = array(
				            'ID' => $row[$i]['ID_Cliente'],
				            'Fecha' => $row[$i]['Fecha_Registro'],
				            'Nombre' => $nombre,
				            'Domicilio' => $domicilio,
				            'Contacto' => $contacto,
				            'Cuenta' => $cuenta
				        );
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "consultarProductoCodigo"){
			$codigo =  $omodelo->link->real_escape_string($codigo);
			$arreglo = null;	

			$query = "SELECT ID_Producto, Codigo, Descripcion, Precio, Precio + (Precio * (COALESCE((SELECT SUM(Porcentaje) FROM impuestos WHERE ID_Impuesto IN (SELECT FK_Impuesto FROM productos_impuestos WHERE FK_Producto = ID_Producto)), 0))/100) AS PrecioFinal, (SELECT GROUP_CONCAT(CONCAT(nombre, CONCAT(Porcentaje, '%','($',(((productos.Precio) * (Porcentaje/100)) * 1),')')) SEPARATOR '<br> ') FROM impuestos LEFT JOIN productos_impuestos ON FK_Impuesto = ID_Impuesto WHERE FK_Producto = ID_Producto) AS nombreImpuestos FROM productos WHERE Codigo = '$codigo'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo = $row[0];
				}
			}

			echo json_encode($arreglo);
		}
	}	

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s'); 

		$idCliente = $omodelo->link->real_escape_string($idCliente);
		$tipoPedido = $omodelo->link->real_escape_string($tipoPedido);
		$descuento = $omodelo->link->real_escape_string($descuento);
		$total = $omodelo->link->real_escape_string($total);
		$importe = $omodelo->link->real_escape_string($importe);
		$tipoPC = $omodelo->link->real_escape_string($tipoPC);
		$concepto = $omodelo->link->real_escape_string($concepto);
		$tipoPago = $omodelo->link->real_escape_string($tipoPago);
		$detalles = $omodelo->link->real_escape_string($detalles);
		$fechaEntrega = $omodelo->link->real_escape_string($fechaEntrega);
		$fechaCaducacion = $omodelo->link->real_escape_string($fechaCaducacion);
		$selectSucursalPedido = $omodelo->link->real_escape_string($selectSucursalPedido);
		$fechaRegistro = $omodelo->link->real_escape_string($fechaRegistro);


		$estatus = 'Pendiente';

		if ($tipoPC == 'Pedido') {
			if($tipoPedido == 'Contado') {
				$estatus = 'Completado';
			}else if($importe >= $total){
				$estatus = 'Completado';
			}
		}

		$query = "INSERT INTO pedidos_cotizaciones SET FK_Cliente = '$idCliente', Total = '$total', Tipo = '$tipoPC', Estado = '$estatus', Descuento = '$descuento', Fecha_Caducacion = '$fechaCaducacion', Fecha_Entrega = '$fechaEntrega', Fecha_Registro = '$fechaRegistro', FK_Sucursal = '$selectSucursalPedido'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$id = mysqli_insert_id($omodelo->link);

			$productos = json_decode($productos, true);

			foreach ($productos as $producto) {
				$producto['id'] = $omodelo->link->real_escape_string($producto['id']);
				$producto['descripcion'] = $omodelo->link->real_escape_string($producto['descripcion']);
				$producto['precio'] = $omodelo->link->real_escape_string($producto['precio']);
				$producto['cantidad'] = $omodelo->link->real_escape_string($producto['cantidad']);
				$producto['subtotal'] = $omodelo->link->real_escape_string($producto['subtotal']);
				
				$query3 = "INSERT INTO pedidos_cotizaciones_detalles SET FK_Pedidos_Cotizaciones = '$id', FK_Producto = '$producto[id]', Subtotal = '$producto[precio]', Cantidad = '$producto[cantidad]', Total = '$producto[subtotal]', Descuento = 0";
				$error3 = $omodelo->_insertar($query3);

				if ($error3 == "si") {
					echo "Error detalles: ".mysqli_error($omodelo->link);
				}
			}

			if($tipoPC == 'Pedido') {

				if ($tipoPago == 'Efectivo') {
					$cajaPagarPedidos = $omodelo->link->real_escape_string($cajaPagarPedidos);
				}else{
					$cajaPagarPedidos = '';
				}
				
				if($importe > 0){
					$query1 = "INSERT INTO pedidos_cotizaciones_pagos SET FK_Pedidos_Cotizaciones = '$id', Concepto = '$concepto', Monto = '$importe', Tipo_Pago = '$tipoPago', Detalles = '$detalles', Fecha_Registro = '$fecha', FK_Detalle_Caja = '$cajaPagarPedidos'";
					$error1 = $omodelo->_insertar($query1);
					$status = 1;

					if ($error1 == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}else{
						$idPago = mysqli_insert_id($omodelo->link);

						if ($_FILES['comprobantePagoCobrarP']['size'] > 0 && $_FILES['comprobantePagoCobrarP']['error'] == 0) {
							$file = $_FILES["comprobantePagoCobrarP"];
							$nombreDoc = $file["name"];
							$tipo = $file["type"];
							$ruta_provisional = $file["tmp_name"];
							$size = $file["size"];
							$carpeta = "vistas/assets/files/pagos/";

							if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != ''){
								echo "Error 2 Formato";
							}else if ($size > (1024*1024*10)){
								echo "Error 3 Peso";
							}else{
								$status = 0;
								$ruta = $carpeta;
							}
						}
									
						if($status == 0){
							$query2 = "UPDATE compras_pagos SET Archivo = '".$idPago.'_'.$nombreDoc."' WHERE ID_Pago = '$idPago'";
							$error2 = $omodelo->_insertar($query2);	

							if ($error2 == "si") {
								echo "Error 4: ".mysqli_error($omodelo->link); 
							}else{
								move_uploaded_file($ruta_provisional,  $ruta.$idPago.'_'.$nombreDoc);
							}
						}
					}
				}	
			}

			echo 'Correcto~'.$id;

			$omodelo->movimiento($query, $_SESSION['user_punto_venta']['ID_Usuario']);
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$arreglo = array();

		$id = $omodelo->link->real_escape_string($id);

      	$query = "SELECT ID_Pedidos_Cotizaciones, FK_Cliente, (SELECT CONCAT(Nombre, ' ', Primer_Apellido) FROM Clientes WHERE ID_Cliente = 1) AS Nombre_Completo, Tipo, Total, Descuento, Fecha_Registro, DATE_FORMAT(Fecha_Caducacion, '%Y-%m-%d') AS Fecha_Caducacion, FK_Sucursal FROM pedidos_cotizaciones WHERE ID_Pedidos_Cotizaciones = '$id'";
      	$row = $omodelo->_consultar($query);
      	$numerofilas = $omodelo->numerofilas;

      	if ($row == 'si') {
        	echo 'Error : ' . mysqli_error($omodelo->link);
     	 } else {
        	if ($numerofilas > 0) {
          		for ($i = 0; $i < $numerofilas; $i++) {
            		$arreglo['general'][$i] = array(
		              'ID_Pedidos_Cotizaciones' => $row[$i]['ID_Pedidos_Cotizaciones'],
		              'FK_Cliente' => $row[$i]['Nombre_Completo'],
		              'Tipo' => $row[$i]['Tipo'],
		              'Descuento' => $row[$i]['Descuento'],
		              'Total' => $row[$i]['Total'],
		              'Fecha_Registro' => $row[$i]['Fecha_Registro'],
		              'Fecha_Caducacion' => $row[$i]['Fecha_Caducacion'],
		              'FK_Sucursal' => $row[$i]['FK_Sucursal']
		            );
          		}
        	}
        }

        $query = "SELECT ID_Producto, Codigo, Descripcion, Cantidad, Subtotal, Total, IFNULL((Subtotal / (1 + (SELECT SUM(Porcentaje) FROM detalles_impuestos_pedidos_cotizaciones WHERE FK_Detalle_Pedido_Cotizacion = ID_Pedidos_Cotizaciones_Detalles) / 100)), Subtotal) AS PrecioAntes, IFNULL((SELECT GROUP_CONCAT(CONCAT(nombre, CONCAT(Porcentaje, '%','($',(((PrecioAntes) * (Porcentaje/100)) * 1),')')) SEPARATOR '<br> ') FROM detalles_impuestos_pedidos_cotizaciones WHERE FK_Detalle_Pedido_Cotizacion = ID_Pedidos_Cotizaciones_Detalles), '') AS Impuestos FROM pedidos_cotizaciones_detalles JOIN productos ON FK_Producto = ID_Producto WHERE FK_Pedidos_Cotizaciones ='$id'";
      	$row = $omodelo->_consultar($query);
      	$numerofilas = $omodelo->numerofilas;

      	if ($row == 'si') {
        	echo 'Error : ' . mysqli_error($omodelo->link);
     	 } else {
        	if ($numerofilas > 0) {
          		for ($i = 0; $i < $numerofilas; $i++) {
            		$arreglo['productos'][$i] = array(
		              'ID_Producto' => $row[$i]['ID_Producto'],
		              'Codigo' => $row[$i]['Codigo'],
		              'Impuestos' => $row[$i]['Impuestos'],
		              'PrecioAntes' => $row[$i]['PrecioAntes'],
		              'Descripcion' => $row[$i]['Descripcion'],
		              'Cantidad' => $row[$i]['Cantidad'],
		              'Subtotal' => $row[$i]['Subtotal'],
		              'Total' => $row[$i]['Total']
		            );
          		}
        	}
        }
        echo json_encode($arreglo);	
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$fecha = date('Y-m-d H:i:s'); 

		$idP = $omodelo->link->real_escape_string($idP);
		$descuento = $omodelo->link->real_escape_string($descuento);
		$total = $omodelo->link->real_escape_string($total);
		$fechaCaducacion = $omodelo->link->real_escape_string($fechaCaducacion);
		$descuento = $omodelo->link->real_escape_string($descuento);

		$query = "UPDATE pedidos_cotizaciones SET Total = '$total', Descuento = '$descuento', Fecha_Caducacion = '$fechaCaducacion' WHERE ID_Pedidos_Cotizaciones = '$idP'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$id = mysqli_insert_id($omodelo->link);

			$query2 = "DELETE FROM pedidos_cotizaciones_detalles WHERE FK_Pedidos_Cotizaciones = '$idP'";
			$error2 = $omodelo->_insertar($query2);

			$productos = json_decode($productos, true);

			foreach ($productos as $producto) {
				$producto['id'] = $omodelo->link->real_escape_string($producto['id']);
				$producto['descripcion'] = $omodelo->link->real_escape_string($producto['descripcion']);
				$producto['precio'] = $omodelo->link->real_escape_string($producto['precio']);
				$producto['cantidad'] = $omodelo->link->real_escape_string($producto['cantidad']);
				$producto['subtotal'] = $omodelo->link->real_escape_string($producto['subtotal']);
				
				$query3 = "INSERT INTO pedidos_cotizaciones_detalles SET FK_Pedidos_Cotizaciones = '$idP', FK_Producto = '$producto[id]', Subtotal = '$producto[precio]', Cantidad = '$producto[cantidad]', Total = '$producto[subtotal]', Descuento = 0";
				$error3 = $omodelo->_insertar($query3);

				if ($error3 == "si") {
					echo "Error detalles: ".mysqli_error($omodelo->link);
				}
			}
		echo 'Correcto~'.$idP;

		$omodelo->movimiento($query, $_SESSION['user_punto_venta']['ID_Usuario']);
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
	}
}
?>
