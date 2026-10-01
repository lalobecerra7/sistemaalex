<?php
class promociones {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

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
				$busqueda .= "CONCAT(Nombre, Descripcion, Tipo_Promocion, (SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto_Promocion), (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Promocion), (SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto_Combo), (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Combo), Cantidad_Promocion, (SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto_Regalar), (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Regalar), Cantidad_Regalar, Precio_Especial, Estatus, Fecha_Registro) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}


		//SI ERES ADMINISTRADOR MUESTRA TODAS LAS PROMOCIONES DE TODAS LAS SUCURSALES
		$query = "SELECT ID_Promocion, Nombre, Descripcion, Tipo_Promocion AS Tipo, FK_Producto_Promocion, (SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto_Promocion) AS NombreProductoPromocion, FK_Presentacion_Promocion, (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Promocion) AS NombrePresentacionPromocion, FK_Producto_Combo, (SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto_Combo) AS NombreProductoCombo, FK_Presentacion_Combo, (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Combo) AS NombrePresentacionCombo, Cantidad_Promocion, FK_Producto_Regalar, (SELECT Descripcion FROM productos WHERE ID_Producto = FK_Producto_Regalar) AS NombreProductoRegalar, FK_Presentacion_Regalar, (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Regalar) AS NombrePresentacionRegalar, Cantidad_Regalar, (SELECT Nombre FROM presentaciones WHERE ID_Presentacion = FK_Presentacion_Cantidad_Regalar) AS NombrePresentacionCantidadRegalar, Precio_Especial, FK_Sucursal, Estatus, FK_Usuario, Fecha_Registro AS Fecha, (SELECT COUNT(*) FROM promociones $busqueda) AS Num FROM promociones $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$bFinalizar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_promociones'][3] == '1') {
						$bFinalizar = '<button type="button" class="btn btn-sm btn-success bTerminarPromocion" attrID="'.$row[$i]['ID_Promocion'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-check"></i></button>';
					}

					$bEliminar = '';
					if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_promociones'][4] == '1') {
						$bEliminar = '<button type="button" class="mt-2 btn btn-sm btn-danger bEliminarPromocion" attrID="'.$row[$i]['ID_Promocion'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>';
					}

					$textoPromocion = '';
					$Promocion = '';
					
					if ($row[$i]["Estatus"] == "Vigente") {
						$estatus='<span class="badge rounded-pill bg-primary">Vigente</span>';
					}else{
						$estatus='<span class="badge rounded-pill bg-success">Finalizada</span>';
						$bFinalizar = '';
					}

					$sucursales = '';
					$query = "SELECT Nombre FROM detalles_promociones_sucursales INNER JOIN sucursales ON FK_Sucursal = ID_Sucursal WHERE FK_Promocion = '".$row[$i]['ID_Promocion']."'";
					$row2 = $omodelo->_consultar($query);
					$numerofilas2 = $omodelo->numerofilas;

					if($row2 == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas2 > 0){
							for($x=0; $x<$numerofilas2; $x++){
								$sucursales .= $row2[$x]["Nombre"].'<br>';
							}
						}
					}

					$ProductosPromociones = "";
					$queryPromocion = "SELECT Nombre, Descripcion FROM detalles_promociones INNER JOIN productos ON FK_Producto = ID_Producto INNER JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Promocion = '".$row[$i]['ID_Promocion']."'";
					$rowPromocion = $omodelo->_consultar($queryPromocion);
					$numerofilasPromocion = $omodelo->numerofilas;

					if($rowPromocion == 'si'){
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasPromocion > 0){
							for($z=0; $z<$numerofilasPromocion; $z++){	
								$ProductosPromociones .= 'Producto: '.$rowPromocion[$z]['Descripcion'].' '.$rowPromocion[$z]['Nombre'].'<br>';
							}
						}
					}

					if ($row[$i]['Tipo'] == "CantidadRegalo") {
						$textoPromocion = 'Por X piezas recibe X piezas de regalo';

						$Promocion =  $ProductosPromociones.'Cantidad: '.$row[$i]['Cantidad_Promocion'].'<br>Cantidad Regalada: <b>'.$row[$i]['Cantidad_Regalar'].'</b><br>Presentación: <b>'.$row[$i]["NombrePresentacionCantidadRegalar"].'</b>';

					}else if($row[$i]['Tipo'] == "ProductoRegalo") {
						$textoPromocion = 'Por x piezas recibe X producto de regalo';

						$Promocion = $ProductosPromociones.'Cantidad: '.$row[$i]['Cantidad_Promocion'].'<br>Producto Regalado: <b>'.$row[$i]['NombreProductoRegalar'].'</b>';

					}else if($row[$i]['Tipo'] == "ComboProductoRegalo") {
						$textoPromocion = 'Producto 1 + Producto 2 recibe X producto de regalo';

						$Promocion = $ProductosPromociones.'Producto Regalado: <b>'.$row[$i]['NombreProductoRegalar'].' '.$row[$i]['NombrePresentacionRegalar'].'</b>';


					}else if($row[$i]['Tipo'] == "ComboPrecioEspecial") {
						$textoPromocion = 'Producto 1 + Producto 2 recibe precio especial por los 2 productos';

						$Promocion = $ProductosPromociones.'Precio Especial: <b>$'.number_format($row[$i]['Precio_Especial'], 2).'</b>';
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Promocion'],
						'Fecha' => $row[$i]['Fecha'],
						'Nombre' => $row[$i]['Nombre'].'<br>'.$row[$i]['Descripcion'],
						'Tipo' => $textoPromocion,
						'Promocion' => $Promocion,
						'Sucursales' => $sucursales,
						'Estatus' => $estatus,
						'Acciones' => $bFinalizar.' '.$bEliminar
					);
					
				}

				$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
			}
		}

		echo json_encode($arreglo);
	}

	public function _insertar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
        $FechaPromocion = $omodelo->link->real_escape_string($FechaPromocion);
		$TipoPromocion = $omodelo->link->real_escape_string($TipoPromocion);
		$NombrePromocion = $omodelo->link->real_escape_string($NombrePromocion);
		$DescripcionPromocion = $omodelo->link->real_escape_string($DescripcionPromocion);
		
		$qSucursales = "";
		$cadenaSucursales = "";
		$sucursales = json_decode($sucursales, true);

		if ($TipoPromocion == "CantidadRegalo") {

        	$ProductosPromocion = $omodelo->link->real_escape_string($ProductosPromocion);
            $PresentacionesProductoPromocion = $omodelo->link->real_escape_string($PresentacionesProductoPromocion);
            $CantidadCondicionPromocion = $omodelo->link->real_escape_string($CantidadCondicionPromocion);
		    $CantidadRegalarPromocion = $omodelo->link->real_escape_string($CantidadRegalarPromocion);
		    $PresentacionProductoCantidadRegalar = $omodelo->link->real_escape_string($PresentacionProductoCantidadRegalar);

			/*
				FK_Producto_Promocion = '$ProductosPromocion', 
		    	FK_Presentacion_Promocion = '$PresentacionesProductoPromocion', 
			*/
		    $query = "INSERT INTO promociones SET 
		    Fecha_Registro = '$FechaPromocion', 
		    Nombre = '$NombrePromocion', 
		    Descripcion = '$DescripcionPromocion', 
		    Tipo_Promocion = '$TipoPromocion', 
		    Cantidad_Promocion = '$CantidadCondicionPromocion', 
		    Cantidad_Regalar = '$CantidadRegalarPromocion', 
		    FK_Presentacion_Cantidad_Regalar = '$PresentacionProductoCantidadRegalar', 
		    Estatus = 'Vigente', 
		    FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$idPromocion = mysqli_insert_id($omodelo->link);

				$query1 = "INSERT INTO detalles_promociones SET FK_Promocion = '$idPromocion', FK_Producto = '$ProductosPromocion', FK_Presentacion = '$PresentacionesProductoPromocion'";
				$row1 = $omodelo->_insertar($query1);

				if ($row1 == "si") {
					echo "Error Producto Promocion: ".mysqli_error($omodelo->link);
				}
	
				foreach ($sucursales as $sucursal) {
					$queryS = "INSERT INTO detalles_promociones_sucursales SET FK_Promocion = '$idPromocion', FK_Sucursal = '".$sucursal["ID"]."'";
					$rowS = $omodelo->_insertar($queryS);
	
					if ($rowS == "si") {
						echo "Error Sucursales: ".mysqli_error($omodelo->link);
					}
				}
	
				echo "Correcto";
			}

        }else if($TipoPromocion == "ProductoRegalo") {

            $ProductosPromocion = $omodelo->link->real_escape_string($ProductosPromocion);
            $PresentacionesProductoPromocion = $omodelo->link->real_escape_string($PresentacionesProductoPromocion);
            $CantidadCondicionPromocion = $omodelo->link->real_escape_string($CantidadCondicionPromocion);
            $ProductosPromocionRegalo = $omodelo->link->real_escape_string($ProductosPromocionRegalo);
			$PresentacionesProductoPromocionRegalo = $omodelo->link->real_escape_string($PresentacionesProductoPromocionRegalo);

			/*
			FK_Producto_Promocion = '$ProductosPromocion', 
			FK_Presentacion_Promocion = '$PresentacionesProductoPromocion', 
			*/
			$query = "INSERT INTO promociones SET 
			Fecha_Registro = '$FechaPromocion',  
			Nombre = '$NombrePromocion', 
			Descripcion = '$DescripcionPromocion', 
			Tipo_Promocion = '$TipoPromocion', 
			Cantidad_Promocion = '$CantidadCondicionPromocion', 
			FK_Producto_Regalar = '$ProductosPromocionRegalo', 
			FK_Presentacion_Regalar = '$PresentacionesProductoPromocionRegalo',  
			Estatus = 'Vigente', 
			FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$idPromocion = mysqli_insert_id($omodelo->link);

				$query1 = "INSERT INTO detalles_promociones SET FK_Promocion = '$idPromocion', FK_Producto = '$ProductosPromocion', FK_Presentacion = '$PresentacionesProductoPromocion'";
				$row1 = $omodelo->_insertar($query1);

				if ($row1 == "si") {
					echo "Error Producto Promocion: ".mysqli_error($omodelo->link);
				}
	
				foreach ($sucursales as $sucursal) {
					$queryS = "INSERT INTO detalles_promociones_sucursales SET FK_Promocion = '$idPromocion', FK_Sucursal = '".$sucursal["ID"]."'";
					$rowS = $omodelo->_insertar($queryS);
	
					if ($rowS == "si") {
						echo "Error Sucursales: ".mysqli_error($omodelo->link);
					}
				}
	
				echo "Correcto";
			}

		}else if($TipoPromocion == "ComboProductoRegalo") {

        	/*$ProductosPromocion = $omodelo->link->real_escape_string($ProductosPromocion);
        	$PresentacionesProductoPromocion = $omodelo->link->real_escape_string($PresentacionesProductoPromocion);
        	$ProductosPromocion2 = $omodelo->link->real_escape_string($ProductosPromocion2);
			$PresentacionesProductoPromocion2 = $omodelo->link->real_escape_string($PresentacionesProductoPromocion2);
			$ProductosPromocionRegalo = $omodelo->link->real_escape_string($ProductosPromocionRegalo);
			$PresentacionesProductoPromocionRegalo = $omodelo->link->real_escape_string($PresentacionesProductoPromocionRegalo);

			$query = "INSERT INTO promociones SET 
			Fecha_Registro = '$FechaPromocion',  
			Nombre = '$NombrePromocion', 
			Descripcion = '$DescripcionPromocion', 
			Tipo_Promocion = '$TipoPromocion', 
			FK_Producto_Promocion = '$ProductosPromocion', 
			FK_Presentacion_Promocion = '$PresentacionesProductoPromocion', 
			FK_Producto_Combo = '$ProductosPromocion2',
			FK_Presentacion_Combo = '$PresentacionesProductoPromocion2',
			FK_Producto_Regalar = '$ProductosPromocionRegalo', 
			FK_Presentacion_Regalar = '$PresentacionesProductoPromocionRegalo',  
			Estatus = 'Vigente', 
			FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";*/

			$ProductosPromocion = $omodelo->link->real_escape_string($ProductosPromocion);
        	$PresentacionesProductoPromocion = $omodelo->link->real_escape_string($PresentacionesProductoPromocion);
        	$ProductosPromocion2 = $omodelo->link->real_escape_string($ProductosPromocion2);
			$PresentacionesProductoPromocion2 = $omodelo->link->real_escape_string($PresentacionesProductoPromocion2);
			$ProductosPromocionRegalo = $omodelo->link->real_escape_string($ProductosPromocionRegalo);
			$PresentacionesProductoPromocionRegalo = $omodelo->link->real_escape_string($PresentacionesProductoPromocionRegalo);

			$query = "INSERT INTO promociones SET 
			Fecha_Registro = '$FechaPromocion',  
			Nombre = '$NombrePromocion', 
			Descripcion = '$DescripcionPromocion', 
			Tipo_Promocion = '$TipoPromocion', 
			FK_Producto_Regalar = '$ProductosPromocionRegalo', 
			FK_Presentacion_Regalar = '$PresentacionesProductoPromocionRegalo',  
			Estatus = 'Vigente', 
			FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$idPromocion = mysqli_insert_id($omodelo->link);

				$query1 = "INSERT INTO detalles_promociones SET FK_Promocion = '$idPromocion', FK_Producto = '$ProductosPromocion', FK_Presentacion = '$PresentacionesProductoPromocion'";
				$row1 = $omodelo->_insertar($query1);

				if ($row1 == "si") {
					echo "Error Producto Promocion: ".mysqli_error($omodelo->link);
				}

				$query2 = "INSERT INTO detalles_promociones SET FK_Promocion = '$idPromocion', FK_Producto = '$ProductosPromocion2', FK_Presentacion = '$PresentacionesProductoPromocion2'";
				$row2 = $omodelo->_insertar($query2);

				if ($row2 == "si") {
					echo "Error Producto Combo: ".mysqli_error($omodelo->link);
				}

	
				foreach ($sucursales as $sucursal) {
					$queryS = "INSERT INTO detalles_promociones_sucursales SET FK_Promocion = '$idPromocion', FK_Sucursal = '".$sucursal["ID"]."'";
					$rowS = $omodelo->_insertar($queryS);
	
					if ($rowS == "si") {
						echo "Error Sucursales: ".mysqli_error($omodelo->link);
					}
				}
	
				echo "Correcto";
			}

        }else if($TipoPromocion == "ComboPrecioEspecial") {

        	$ProductosPromocion = $omodelo->link->real_escape_string($ProductosPromocion);
        	$PresentacionesProductoPromocion = $omodelo->link->real_escape_string($PresentacionesProductoPromocion);
        	$ProductosPromocion2 = $omodelo->link->real_escape_string($ProductosPromocion2);
			$PresentacionesProductoPromocion2 = $omodelo->link->real_escape_string($PresentacionesProductoPromocion2);
        	$PrecioPromocionCombo = $omodelo->link->real_escape_string($PrecioPromocionCombo);

        	$query = "INSERT INTO promociones SET 
			Fecha_Registro = '$FechaPromocion',  
			Nombre = '$NombrePromocion', 
			Descripcion = '$DescripcionPromocion', 
			Tipo_Promocion = '$TipoPromocion', 
			FK_Producto_Promocion = '$ProductosPromocion', 
			FK_Presentacion_Promocion = '$PresentacionesProductoPromocion', 
			FK_Producto_Combo = '$ProductosPromocion2',
			FK_Presentacion_Combo = '$PresentacionesProductoPromocion2',
			Precio_Especial = '$PrecioPromocionCombo', 
			Estatus = 'Vigente', 
			FK_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";

			$row = $omodelo->_insertar($query);

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$idPromocion = mysqli_insert_id($omodelo->link);

				$query1 = "INSERT INTO detalles_promociones SET FK_Promocion = '$idPromocion', FK_Producto = '$ProductosPromocion', FK_Presentacion = '$PresentacionesProductoPromocion'";
				$row1 = $omodelo->_insertar($query1);

				if ($row1 == "si") {
					echo "Error Producto Promocion: ".mysqli_error($omodelo->link);
				}

				$query2 = "INSERT INTO detalles_promociones SET FK_Promocion = '$idPromocion', FK_Producto = '$ProductosPromocion2', FK_Presentacion = '$PresentacionesProductoPromocion2'";
				$row2 = $omodelo->_insertar($query2);

				if ($row2 == "si") {
					echo "Error Producto Combo: ".mysqli_error($omodelo->link);
				}

				foreach ($sucursales as $sucursal) {
					$queryS = "INSERT INTO detalles_promociones_sucursales SET FK_Promocion = '$idPromocion', FK_Sucursal = '".$sucursal["ID"]."'";
					$rowS = $omodelo->_insertar($queryS);

					if ($rowS == "si") {
						echo "Error Sucursales: ".mysqli_error($omodelo->link);
					}
				}

				echo "Correcto";
			}

        }
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id = $omodelo->link->real_escape_string($id);
		$nombre = $omodelo->link->real_escape_string($nombre);
		$primerApellido = $omodelo->link->real_escape_string($primerApellido);
		$segundoApellido = $omodelo->link->real_escape_string($segundoApellido);
		$vehiculo = $omodelo->link->real_escape_string($vehiculosChofer);

		$query = "UPDATE choferes SET Nombre = '$nombre', Primer_Apellido = '$primerApellido', Segundo_Apellido = '$segundoApellido', FK_Vehiculo = '$vehiculo' WHERE ID_Chofer = '$id'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _eliminar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$id =  $omodelo->link->real_escape_string($id);

		$query = "DELETE FROM promociones WHERE ID_Promocion = '$id'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			echo "Correcto";			
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo =  $omodelo->link->real_escape_string($tipo);

		if($tipo == 'ConsultarSucursales'){
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
					$busqueda .= "CONCAT(ID_Sucursal, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Email, FK_Encargado, usuarios.Nombre,  usuarios.Primer_Apellido, usuarios.Segundo_Apellido, zonas.Nombre) REGEXP '".$separa[$i]."'";
					if($i < (count($separa)-1)){
						$busqueda .= ' AND ';
					}
				}
			}
			
			$query = "SELECT ID_Sucursal, '' AS Seleccionar, sucursales.Nombre, Calle, No_Exterior, No_Interior, Colonia, CP, Ciudad, Estado, Pais, Telefono, Segundo_Telefono, Email, FK_Encargado, usuarios.Nombre AS NombreEncargado, usuarios.Primer_Apellido AS PrimerApellido, usuarios.Segundo_Apellido AS SegundoApellido, FK_Zona, zonas.Nombre AS NombreZona, Latitud, Longitud, (SELECT COUNT(*) FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda) AS Num, ((SELECT COUNT(*) FROM ventas INNER JOIN cajas ON FK_Caja = ID_Caja WHERE cajas.FK_Sucursal = ID_Sucursal) + (SELECT COUNT(*) FROM cajas WHERE FK_Sucursal = ID_Sucursal)) AS numSucu FROM sucursales LEFT JOIN usuarios ON ID_Usuario = FK_Encargado LEFT JOIN zonas ON FK_Zona = ID_Zona $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$direccion = "";$telefono="";$email="";$gerente="";

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

						$arreglo['data'][$i] = array(
							'ID' => $row[$i]['ID_Sucursal'],
							'Seleccionar' => '<input type="checkbox" class="CheckInputSucursalPromociones" nombre="'.$row[$i]['Nombre'].'" attrid="'.$row[$i]['ID_Sucursal'].'">',
							'Sucursal' => $row[$i]['Nombre'],
							'Direccion' => $direccion
						);
						
					}

					$arreglo['totales'] = array('NumRows' => $row[0]['Num']);	
				}
			}
			
			echo json_encode($arreglo);
		}else if($tipo == "ConsultarProductosPromocion"){
			$qSucursales = "";
			$cadenaSucursales = "";
			$sucursales = json_decode($Sucursales, true);
			foreach ($sucursales as $sucursal) {
				$cadenaSucursales .= $sucursal["ID"].",";
			}
			$string = rtrim($cadenaSucursales, ",");
			if ($string != "") {
				$qSucursales = "WHERE inventario.FK_Sucursal IN(".$string.")";
			}

			$opciones = "<option value=''>Seleccione un producto</option>";
			$query = "SELECT ID_Producto, Descripcion FROM `inventario` INNER JOIN productos ON FK_Producto = ID_Producto $qSucursales GROUP BY ID_Producto;";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$opciones .= '<option value="'.$row[$i]["ID_Producto"].'">'.$row[$i]["Descripcion"].'</option>';
					}
				}
			}

			if ($qSucursales == "") {
				$opciones = "";
			}
			echo $opciones;
		}else if($tipo == "ConsultarPresentaciones"){
			$idProducto =  $omodelo->link->real_escape_string($IDProducto);

			//$opciones = "<option value='0'>Sin presentación</option>";
			$opciones = "";
			$query = "SELECT ID_Presentacion, Nombre FROM `presentaciones` WHERE FK_Producto = '$idProducto';";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$opciones .= '<option value="'.$row[$i]["ID_Presentacion"].'">'.$row[$i]["Nombre"].'</option>';
					}
				}
			}
			echo $opciones;
		}else if($tipo == "FinalizarPromocion"){
			$id =  $omodelo->link->real_escape_string($id);

			$query = "UPDATE promociones SET Estatus = 'Finalizada' WHERE ID_Promocion = '$id'";
			$error = $omodelo->_insertar($query);

			if ($error == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				echo "Correcto";			
			}

		}
	}
}
?>
