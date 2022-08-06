<?php
class negocios {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "NuevoNegocio") {
			$NombreNegocioNuevo = $omodelo->link->real_escape_string($NombreNegocioNuevo);
			$DescripcionNegocioNuevo = $omodelo->link->real_escape_string($DescripcionNegocioNuevo);
			$ClasificacionNegocio = $omodelo->link->real_escape_string($ClasificacionNegocio);
			$UsuarioNegocio = $omodelo->link->real_escape_string($UsuarioNegocio);
			$ContrasenaNegocio = $omodelo->link->real_escape_string($ContrasenaNegocio);
			$CostoEnvioNegocio = $omodelo->link->real_escape_string($CostoEnvioNegocio);
			//$DuracionNegocio = $omodelo->link->real_escape_string($DuracionNegocio);
			$TelefonoNuevoNegocio = $omodelo->link->real_escape_string($TelefonoNuevoNegocio);
			$WhatsappNuevoNegocio = $omodelo->link->real_escape_string($WhatsappNuevoNegocio);
			$RangoInicio = $omodelo->link->real_escape_string($RangoInicio);
			$RangoFin = $omodelo->link->real_escape_string($RangoFin);
			$rangoTiempo = "De ".$RangoInicio." a ".$RangoFin;
			$query = "
			INSERT INTO usuarios_negocio SET 
				FK_Clasificacion = '$ClasificacionNegocio',
				Nombre='$NombreNegocioNuevo',
				Costo_Envio='$CostoEnvioNegocio',
				Tiempo_Prepa_Prom = '$rangoTiempo',
				Descripcion='$DescripcionNegocioNuevo',
				Telefono='$TelefonoNuevoNegocio',
				Whatsapp='$WhatsappNuevoNegocio',
				Usuario='$UsuarioNegocio',
				Contrasena= MD5('$ContrasenaNegocio'),
				Estatus_Cuenta = 'Pendiente',
				Estatus = 'Desbloqueado',
				Calificacion = '5'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);
				$status = 1;
				if ($_FILES['ImagenNegocioNuevo']['size'] > 0 && $_FILES['ImagenNegocioNuevo']['error'] == 0) {
					$file = $_FILES["ImagenNegocioNuevo"];
					$nombreDoc = $file["name"];
					$tipo = $file["type"];
					$ruta_provisional = $file["tmp_name"];
					$size = $file["size"];
					$carpeta = "../server_SPIDI_APP/images/negocios/";

					if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != ''){
						echo "Error 2 Formato";
					}else if ($size > (1024*1024*10)){
						echo "Error 3 Peso";
					}else{
						$status = 0;
						$ruta = $carpeta;
					}
				}
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

				if($status == 0){
					$query2 = "UPDATE usuarios_negocio SET Imagen = '".$id.'_'.$nombreDoc."' WHERE  ID_Negocio = '$id'";
					$error3 = $omodelo->_insertar($query2);	

					if ($error3 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.''.$id.'_'.$nombreDoc);
					}
				}

				echo "Correcto~".$id;
			}
		}else if ($tipo == "NuevoHorario") {
			
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$DiaSemana = $omodelo->link->real_escape_string($DiaSemana);
			$HoraInicio = $omodelo->link->real_escape_string($HoraInicio);
			$HoraFinal = $omodelo->link->real_escape_string($HoraFinal);
			$HorarioEspecial = $omodelo->link->real_escape_string($HorarioEspecial);
			$FechaInicio = $omodelo->link->real_escape_string($FechaInicio);
			$FechaFinal = $omodelo->link->real_escape_string($FechaFinal);
			$query = "INSERT INTO horarios_negocios SET FK_Usuario_Negocio = '$idNegocio', Dia_Semana = '$DiaSemana', Hora_Apertura = '$HoraInicio', Hora_Cierre = '$HoraFinal', Especial = '$HorarioEspecial', Fecha_Inicio = '$FechaInicio', Fecha_Final = '$FechaFinal'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);
				echo "Correcto~".$id;
			}

		}else if ($tipo == "NuevaCategoria") {
			
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$Nombre = $omodelo->link->real_escape_string($NombreCategoria);
			$Descripcion = $omodelo->link->real_escape_string($DescripcionCategoria);
			$query = "INSERT INTO categorias SET FK_Usuario_Negocio = '$idNegocio', Nombre = '$Nombre', Descripcion = '$Descripcion'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);
				echo "Correcto~".$id;
			}

		}else if ($tipo == "NuevoProducto") {
			
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$NombreProducto = $omodelo->link->real_escape_string($NombreProducto);
			$PreparacionProducto = $omodelo->link->real_escape_string($PreparacionProducto);
			$DescripcionProducto = $omodelo->link->real_escape_string($DescripcionProducto);
			$PresentacionProducto = $omodelo->link->real_escape_string($PresentacionProducto);
			$PrecioProducto = $omodelo->link->real_escape_string($PrecioProducto);
			$DescuentoProducto = $omodelo->link->real_escape_string($DescuentoProducto);
			$TipoVentaProducto = $omodelo->link->real_escape_string($TipoVentaProducto);
			$UnidadGranelProducto = $omodelo->link->real_escape_string($UnidadGranelProducto);
			$MadurezProducto = $omodelo->link->real_escape_string($MadurezProducto);
			$AgotadoProducto = $omodelo->link->real_escape_string($AgotadoProducto);
			$DadoBajaProducto = $omodelo->link->real_escape_string($DadoBajaProducto);

			$query = "INSERT INTO productos SET 
			FK_Usuario_Negocio = '$idNegocio', 
			Nombre = '$NombreProducto', 
			Duracion = '$PreparacionProducto', 
			Descripcion = '$DescripcionProducto', 
			Presentacion = '$PresentacionProducto', 
			Precio = '$PrecioProducto', 
			Descuento = '$DescuentoProducto', 
			Tipo_Venta = '$TipoVentaProducto', 
			Unidad_Granel = '$UnidadGranelProducto', 
			Madurez = '$MadurezProducto', 
			Agotado = '$AgotadoProducto', 
			Dado_Baja = '$DadoBajaProducto'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);
				$status = 1;
				if ($_FILES['ImagenProducto']['size'] > 0 && $_FILES['ImagenProducto']['error'] == 0) {
					$file = $_FILES["ImagenProducto"];
					$nombreDoc = $file["name"];
					$tipo = $file["type"];
					$ruta_provisional = $file["tmp_name"];
					$size = $file["size"];
					$carpeta = "../server_SPIDI_APP/images/productos/";
											    
					if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != ''){
						echo "Error 2 Formato";
					}else if ($size > (1024*1024*10)){
						echo "Error 3 Peso";
					}else{
						$status = 0;
						$ruta = $carpeta;
					}
				}			
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

				if($status == 0){
					$query2 = "UPDATE productos SET Imagen = '".$id.'_'.$nombreDoc."' WHERE ID_Producto = '$id'";
					$error3 = $omodelo->_insertar($query2);	
					if ($error3 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.''.$id.'_'.$nombreDoc);
					}
				}
				echo "Correcto~".$id;
			}

		}else if($tipo == "NuevoExtraProducto"){

			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$Titulo = $omodelo->link->real_escape_string($Titulo);
			$TipoOpcion = $omodelo->link->real_escape_string($TipoOpcion);
			$CantidadOpcion = $omodelo->link->real_escape_string($CantidadOpcion);
			$TipoSeleccion = $omodelo->link->real_escape_string($TipoSeleccion);
			$EstatusOpcion = $omodelo->link->real_escape_string($EstatusOpcion);
			//arregloProductos
			$query = "INSERT INTO opciones_productos SET FK_Producto = '$idProducto', Titulo = '$Titulo', Es_Cantidad = '$TipoOpcion', Obligatorio = '$TipoSeleccion', Maximo = '$CantidadOpcion', Activa = '$EstatusOpcion'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);
				$separar = explode("~", $arregloProductos);
				for ($i=0; $i < sizeof($separar) - 1; $i++) {
					$datos = explode(",", $separar[$i]);
					$query2 = "INSERT INTO detalle_productos_opciones SET FK_Opciones = '$id', Nombre = '".$datos[0]."', Precio = '".$datos[1]."', Agotada = '".$datos[3]."', Activa = '".$datos[2]."'";
					$error2 = $omodelo->_insertar($query2);
					if ($error2 == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}
				}
				echo "Correcto~".$id;
			}
		}
	}

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$arreglo = null;
		if ($tipo == "tabla") {
			$query = "SELECT ID_Negocio, FK_Clasificacion, clasificaciones.Nombre AS NombreClasificacion, Usuario, Contrasena, usuarios_negocio.Nombre, usuarios_negocio.Imagen, Tiempo_Prepa_Prom, Costo_Envio, Porcentaje_Ganancia, usuarios_negocio.Descripcion, Calificacion, Correo, Telefono, Pagina_web, Instagram, Facebook, Twitter, Youtube, Whatsapp, Tiktok, Latitud, Longitud, Calle, No_Interior, No_Exterior, Codigo_Postal, Colonia, Ciudad, Estado, Pais, Detalles, Tipo_Edificio, Nombre_Contacto, Telefono_Contacto, Correo_Contacto, Fecha_Registro, No_Intentos, Tiempo_Inicio, Tiempo_Final, Ultimo_Intento, Estatus, Estatus_Cuenta, usuarios_negocio.Activo, Abierto FROM usuarios_negocio INNER JOIN clasificaciones ON FK_Clasificacion = ID_Clasificacion  ORDER BY Nombre DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$estatus = ""; $negocio=""; $direccion="";$Contacto = '';
						if($row[$i]['Estatus'] == 'Bloqueado'){
							$estatus= '<span class="badge rounded-pill bg-danger">Bloqueado</span>';
						}else if($row[$i]['Estatus'] == 'Desbloqueado'){
							$estatus= '<span class="badge rounded-pill bg-success">Desbloqueado</span>';
						}

						//$foto = "<span class='imagenNegocio' style='background-image: url(../server_SPIDI_APP/images/productos/sinimagen.png);'></span><br>";

						$archivo = '<a href="../server_SPIDI_APP/images/productos/sinimagen.png" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/productos/sinimagen.png'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';
						if($row[$i]['Imagen'] != ""){

							$archivo = '<a href="../server_SPIDI_APP/images/negocios/'.$row[$i]["Imagen"].'" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/negocios/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';

							//$foto = '<span class="imagenNegocio" style="background-image: url(../server_SPIDI_APP/images/negocios/'.$row[$i]['Imagen'].');"></span><br>';
						}

						$negocio = $archivo.'<b class="mt-2">'.$row[$i]['Nombre'].'</b><br> Clasificación: '.$row[$i]['NombreClasificacion'];

						if ($row[$i]['Nombre_Contacto'] != "") {
							$Contacto .= 'Nombre del contacto: <b>'.$row[$i]['Nombre_Contacto'].'</b><br>';
						}

						if ($row[$i]['Telefono_Contacto'] != "") {
							$Contacto .= 'Teléfono: <b>'.$row[$i]['Telefono_Contacto'].'</b><br>';
						}

						if ($row[$i]['Correo_Contacto'] != "") {
							$Contacto .= 'Correo electrónico: <b>'.$row[$i]['Correo_Contacto'].'</b><br>';
						}

						if ($row[$i]['Telefono'] != "") {
							$Contacto .= 'Teléfono del negocio: <b>'.$row[$i]['Telefono'].'</b><br>';
						}

						if ($row[$i]['Whatsapp'] != "") {
							$Contacto .= 'Whatsapp del negocio: <b>'.$row[$i]['Whatsapp'].'</b>';
						}

						if ($Contacto == "") {
							$Contacto = "El negocio no ha ingresado sus datos";
						}

						if ($row[$i]['Calle'] != "") {
							$direccion .= 'Calle: <b>'.$row[$i]['Calle'].'</b> <br>';
						}

						if ($row[$i]['No_Interior'] != "") {
							$direccion .= 'No. Exterior: <b>#'.$row[$i]['No_Interior'].'</b> <br>';
						}

						if ($row[$i]['No_Interior'] != "") {
							$direccion .= 'No. Interior: <b>#'.$row[$i]['No_Exterior'].'</b> <br>';
						}

						if ($row[$i]['Colonia'] != "") {
							$direccion .= 'Colonia: <b>'.$row[$i]['Colonia'].'</b><br>';
						}

						if ($row[$i]['Codigo_Postal'] != "") {
							$direccion .= 'Código Postal: <b>'.$row[$i]['Codigo_Postal'].'</b>';
						}

						if ($direccion == "") {
							$direccion = 'No hay datos ingresados por el negocio';
						}

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Negocio'],
							"Negocio" => $negocio,
							"Contacto" => $Contacto,
							"Direccion" => $direccion,
							"Estatus" => $estatus,
							"Acciones" => '<button type="button" class="btn btn-primary btn-sm DetallesNegocio botonVerDetalles'.$row[$i]['ID_Negocio'].'" attrid="'.$row[$i]['ID_Negocio'].'"><i class="fas fa-pencil-alt"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);	
		}else if ($tipo == "tablaNuevo") {
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$query = "SELECT ID_Negocio, FK_Clasificacion, clasificaciones.Nombre AS NombreClasificacion, Usuario, Contrasena, usuarios_negocio.Nombre, usuarios_negocio.Imagen, Tiempo_Prepa_Prom, Costo_Envio, Porcentaje_Ganancia, usuarios_negocio.Descripcion, Calificacion, Correo, Telefono, Pagina_web, Instagram, Facebook, Twitter, Youtube, Whatsapp, Tiktok, Latitud, Longitud, Calle, No_Interior, No_Exterior, Codigo_Postal, Colonia, Ciudad, Estado, Pais, Detalles, Tipo_Edificio, Nombre_Contacto, Telefono_Contacto, Correo_Contacto, Fecha_Registro, No_Intentos, Tiempo_Inicio, Tiempo_Final, Ultimo_Intento, Estatus, Estatus_Cuenta, usuarios_negocio.Activo, Abierto FROM usuarios_negocio INNER JOIN clasificaciones ON FK_Clasificacion = ID_Clasificacion WHERE ID_Negocio = '$idNegocio' ORDER BY Nombre DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$estatus = ""; $negocio=""; $direccion="";$Contacto = '';
						if($row[$i]['Estatus'] == 'Bloqueado'){
							$estatus= '<span class="badge rounded-pill bg-danger">Bloqueado</span>';
						}else if($row[$i]['Estatus'] == 'Desbloqueado'){
							$estatus= '<span class="badge rounded-pill bg-success">Desbloqueado</span>';
						}

						//$foto = "<span class='imagenNegocio' style='background-image: url(../server_SPIDI_APP/images/productos/sinimagen.png);'></span><br>";

						$archivo = '<a href="../server_SPIDI_APP/images/productos/sinimagen.png" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/productos/sinimagen.png'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';
						if($row[$i]['Imagen'] != ""){

							$archivo = '<a href="../server_SPIDI_APP/images/negocios/'.$row[$i]["Imagen"].'" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/negocios/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';

							//$foto = '<span class="imagenNegocio" style="background-image: url(../server_SPIDI_APP/images/negocios/'.$row[$i]['Imagen'].');"></span><br>';
						}

						$negocio = $archivo.'<b class="mt-2">'.$row[$i]['Nombre'].'</b><br> Clasificación: '.$row[$i]['NombreClasificacion'];

						if ($row[$i]['Nombre_Contacto'] != "") {
							$Contacto .= 'Nombre del contacto: <b>'.$row[$i]['Nombre_Contacto'].'</b><br>';
						}

						if ($row[$i]['Telefono_Contacto'] != "") {
							$Contacto .= 'Teléfono: <b>'.$row[$i]['Telefono_Contacto'].'</b><br>';
						}

						if ($row[$i]['Correo_Contacto'] != "") {
							$Contacto .= 'Correo electrónico: <b>'.$row[$i]['Correo_Contacto'].'</b><br>';
						}

						if ($row[$i]['Telefono'] != "") {
							$Contacto .= 'Teléfono del negocio: <b>'.$row[$i]['Telefono'].'</b><br>';
						}

						if ($row[$i]['Whatsapp'] != "") {
							$Contacto .= 'Whatsapp del negocio: <b>'.$row[$i]['Whatsapp'].'</b>';
						}

						if ($Contacto == "") {
							$Contacto = "El negocio no ha ingresado sus datos";
						}

						if ($row[$i]['Calle'] != "") {
							$direccion .= 'Calle: <b>'.$row[$i]['Calle'].'</b> <br>';
						}

						if ($row[$i]['No_Interior'] != "") {
							$direccion .= 'No. Exterior: <b>#'.$row[$i]['No_Interior'].'</b> <br>';
						}

						if ($row[$i]['No_Interior'] != "") {
							$direccion .= 'No. Interior: <b>#'.$row[$i]['No_Exterior'].'</b> <br>';
						}

						if ($row[$i]['Colonia'] != "") {
							$direccion .= 'Colonia: <b>'.$row[$i]['Colonia'].'</b><br>';
						}

						if ($row[$i]['Codigo_Postal'] != "") {
							$direccion .= 'Código Postal: <b>'.$row[$i]['Codigo_Postal'].'</b>';
						}

						if ($direccion == "") {
							$direccion = 'No hay datos ingresados por el negocio';
						}

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Negocio'],
							"Negocio" => $negocio,
							"Contacto" => $Contacto,
							"Direccion" => $direccion,
							"Estatus" => $estatus,
							"Acciones" => '<button type="button" class="btn btn-primary btn-sm DetallesNegocio botonVerDetalles'.$row[$i]['ID_Negocio'].'" attrid="'.$row[$i]['ID_Negocio'].'"><i class="fas fa-pencil-alt"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "ConsultarDatosNegocio"){
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$query = "SELECT ID_Negocio, FK_Clasificacion, clasificaciones.Nombre AS NombreClasificacion, Usuario, Contrasena, usuarios_negocio.Nombre AS NombreNegocio, usuarios_negocio.Imagen, Tiempo_Prepa_Prom, Costo_Envio, Porcentaje_Ganancia, usuarios_negocio.Descripcion, Calificacion, Correo, Telefono, Pagina_web, Instagram, Facebook, Twitter, Youtube, Whatsapp, Tiktok, Latitud, Longitud, Calle, No_Interior, No_Exterior, Codigo_Postal, Colonia, Ciudad, Estado, Pais, Detalles, Tipo_Edificio, Nombre_Contacto, Telefono_Contacto, Correo_Contacto, Fecha_Registro, No_Intentos, Tiempo_Inicio, Tiempo_Final, Ultimo_Intento, Estatus, Estatus_Cuenta, usuarios_negocio.Activo, Abierto, Prioridad FROM usuarios_negocio INNER JOIN clasificaciones ON FK_Clasificacion = ID_Clasificacion WHERE ID_Negocio = '$idNegocio'";
 			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);	
				}
			}
		}else if($tipo == "ConsultarDatosCategoria"){
			$idCategoria = $omodelo->link->real_escape_string($idCategoria);
			$query = "SELECT ID_Categoria, Nombre, Descripcion FROM categorias WHERE ID_Categoria = '$idCategoria'";
 			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);	
				}
			}
		}else if($tipo == "ConsultarDatosProducto"){
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$query = "SELECT * FROM productos WHERE ID_Producto = '$idProducto'";
 			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);	
				}
			}
		}else if($tipo == "ConsultarDatosOpcion"){
			$idOpcion = $omodelo->link->real_escape_string($idOpcion);
			$query = "SELECT ID_Opcion, FK_Producto, Titulo, Es_Cantidad, Obligatorio, Maximo, Activa FROM opciones_productos WHERE ID_Opcion = '$idOpcion'";
 			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$subarreglo = null;
					$queryP = "SELECT ID_Detalle_Productos, Nombre, Precio, Agotada, Activa FROM detalle_productos_opciones WHERE FK_Opciones = '".$row[0]['ID_Opcion']."'";
		 			$rowP = $omodelo->_consultar($queryP);
					$numerofilasP = $omodelo->numerofilas; 
					if ($rowP == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilasP > 0){
							for ($i=0; $i < $numerofilasP; $i++) { 
								$subarreglo[] = array(
									"ID_Detalle" => $rowP[$i]['ID_Detalle_Productos'],
									"Nombre" => $rowP[$i]['Nombre'],
									"Precio" => $rowP[$i]['Precio'],
									"Agotada" => $rowP[$i]['Agotada'],
									"Activa" => $rowP[$i]['Activa'],
								);
							}
						}
					}	

					$arreglo['data'][0] = array(
						"ID_Opcion" => $row[0]['ID_Opcion'],
						"FK_Producto" => $row[0]['FK_Producto'],
						"Titulo" => $row[0]['Titulo'],
						"Es_Cantidad" => $row[0]['Es_Cantidad'],
						"Obligatorio" => $row[0]['Obligatorio'],
						"Maximo" => $row[0]['Maximo'],
						"Activa" => $row[0]['Activa'],
						"ArregloProductos" => $subarreglo,
					);
				}
			}
			echo json_encode($arreglo);
		}else if($tipo == "ConsultarCategoriasProducto"){
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);

			$query = "SELECT ID_Categoria, Nombre, Descripcion FROM categorias WHERE FK_Usuario_Negocio = '$idNegocio'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				$tabla = "";
				if($numerofilas > 0){
					$tabla = "<table class='table table-hover tablaDatatable'>";
					for ($i=0; $i < $numerofilas; $i++) { 

						$query2 = "SELECT FK_Categoria FROM detalle_categorias WHERE FK_Categoria = '".$row[$i]["ID_Categoria"]."' AND FK_Producto = '$idProducto'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas; 
						if ($row2 == "si") {
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								$tabla .= "
									<tr>
										<td>
											<input class='form-check-input' type='checkbox' value='' id='SeleccionarCategoria' name='SeleccionarCategoria' attrid='".$row[$i]["ID_Categoria"]."' producto='".$idProducto."' negocio='".$idNegocio."' checked>
										<td>
										<td>
											".$row[$i]["Nombre"]."
										<td>
										<td>
											".$row[$i]["Descripcion"]."
										<td>
									</tr>
								";
							}else{
								$tabla .= "
									<tr>
										<td>
											<input class='form-check-input' type='checkbox' value='' id='SeleccionarCategoria' name='SeleccionarCategoria' attrid='".$row[$i]["ID_Categoria"]."' producto='".$idProducto."' negocio='".$idNegocio."'>
										<td>
										<td>
											".$row[$i]["Nombre"]."
										<td>
										<td>
											".$row[$i]["Descripcion"]."
										<td>
									</tr>
								";
							}
						}
					}
					$tabla .= "</table>";
				}
			}
			echo $tabla;

			//
		}else if($tipo == "InsertarCategoriaProducto"){
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$idCategoria = $omodelo->link->real_escape_string($idCategoria);
			$seleccionar = $omodelo->link->real_escape_string($seleccionar);
			if ($seleccionar == "si") {
				$query = "INSERT INTO detalle_categorias SET FK_Categoria = '$idCategoria', FK_Producto = '$idProducto'";
			}else{
				$query = "DELETE FROM detalle_categorias WHERE FK_Categoria = '$idCategoria' AND FK_Producto = '$idProducto'";
			}
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if ($tipo == "ConsultarHorariosNegocio") {
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$query = "SELECT ID_Horario, FK_Usuario_Negocio, Dia_Semana, Hora_Apertura, Hora_Cierre, DATE_FORMAT(Hora_Apertura, '%r') AS HoraApertura, DATE_FORMAT(Hora_Cierre, '%r') AS HoraCierre, Especial, Fecha_Inicio, Fecha_Final FROM horarios_negocios WHERE FK_Usuario_Negocio = '$idNegocio' ORDER BY ID_Horario DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			$arregloDias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$DiaSemana = $arregloDias[$row[$i]['Dia_Semana']];

						if ($row[$i]['Especial'] == "1") {
							$HorarioEspecial = "Si";
						}else{
							$HorarioEspecial = "No";
						}

						$Horario = $row[$i]['HoraApertura']." a ".$row[$i]['HoraCierre'];

						if ($row[$i]['Fecha_Inicio'] != "0000-00-00") {
							$Fechas = $row[$i]['Fecha_Inicio']." al ".$row[$i]['Fecha_Final'];
						}else{
							$Fechas = "No aplica";
						}

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Horario'],
							"DiaRegistrado" => $DiaSemana,
							"Horario" => $Horario,
							"HorarioEspecial" => $HorarioEspecial,
							"Rango" => $Fechas,
							"Accion" => '<button type="button" class="btn btn-primary btn-sm ModificarHorario btnModificarHorario'.$row[$i]['ID_Horario'].'" attrid="'.$row[$i]['ID_Horario'].'"><i class="fas fa-pencil-alt"></i></button> <button type="button" class="btn btn-danger btn-sm EliminarHorario" attrid="'.$row[$i]['ID_Horario'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarHorariosNegocioNuevo") {
			$idHorario = $omodelo->link->real_escape_string($idHorario);
			$query = "SELECT ID_Horario, FK_Usuario_Negocio, Dia_Semana, Hora_Apertura, Hora_Cierre, DATE_FORMAT(Hora_Apertura, '%r') AS HoraApertura, DATE_FORMAT(Hora_Cierre, '%r') AS HoraCierre, Especial, Fecha_Inicio, Fecha_Final FROM horarios_negocios WHERE ID_Horario = '$idHorario' ORDER BY ID_Horario DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			$arregloDias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$DiaSemana = $arregloDias[$row[$i]['Dia_Semana']];

						if ($row[$i]['Especial'] == "1") {
							$HorarioEspecial = "Si";
						}else{
							$HorarioEspecial = "No";
						}

						$Horario = $row[$i]['HoraApertura']." a ".$row[$i]['HoraCierre'];

						if ($row[$i]['Fecha_Inicio'] != "0000-00-00") {
							$Fechas = $row[$i]['Fecha_Inicio']." al ".$row[$i]['Fecha_Final'];
						}else{
							$Fechas = "No aplica";
						}

						

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Horario'],
							"DiaRegistrado" => $DiaSemana,
							"Horario" => $Horario,
							"HorarioEspecial" => $HorarioEspecial,
							"Rango" => $Fechas,
							"Accion" => '<button type="button" class="btn btn-primary btn-sm ModificarHorario btnModificarHorario'.$row[$i]['ID_Horario'].'" attrid="'.$row[$i]['ID_Horario'].'"><i class="fas fa-pencil-alt"></i></button> <button type="button" class="btn btn-danger btn-sm EliminarHorario" attrid="'.$row[$i]['ID_Horario'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if($tipo == "ConsultarDatosHorarios"){
			$idHorario = $omodelo->link->real_escape_string($idHorario);
			$query = "SELECT * FROM horarios_negocios WHERE ID_Horario = '$idHorario'";
 			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);	
				}
			}
		}else if ($tipo == "ConsultarCategoriasNegocio") {
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$query = "SELECT ID_Categoria, Nombre, Descripcion FROM categorias WHERE FK_Usuario_Negocio = '$idNegocio'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Categoria'],
							"Nombre"=> utf8_encode(utf8_decode($row[$i]['Nombre'])),
							"Descripcion"=> utf8_encode(utf8_decode($row[$i]['Descripcion'])),
							"Accion" => '<button type="button" class="btn btn-primary btn-sm ModificarCategoria btnModificarCategoria'.$row[$i]['ID_Categoria'].'" attrid="'.$row[$i]['ID_Categoria'].'"><i class="fas fa-pencil-alt"></i></button> <button type="button" class="btn btn-danger btn-sm EliminarCategoria" attrid="'.$row[$i]['ID_Categoria'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarCategoriasNegocioNuevo") {
			$idCategoria = $omodelo->link->real_escape_string($idCategoria);
			$query = "SELECT ID_Categoria, Nombre, Descripcion FROM categorias WHERE ID_Categoria = '$idCategoria'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Categoria'],
							"Nombre"=> utf8_encode(utf8_decode($row[$i]['Nombre'])),
							"Descripcion"=> utf8_encode(utf8_decode($row[$i]['Descripcion'])),
							"Accion" => '<button type="button" class="btn btn-primary btn-sm ModificarCategoria btnModificarCategoria'.$row[$i]['ID_Categoria'].'" attrid="'.$row[$i]['ID_Categoria'].'"><i class="fas fa-pencil-alt"></i></button> <button type="button" class="btn btn-danger btn-sm EliminarCategoria" attrid="'.$row[$i]['ID_Categoria'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarProductosNegocio") {
			$idNegocio = $omodelo->link->real_escape_string($idNegocio);
			$query = "SELECT ID_Producto, FK_Usuario_Negocio, Nombre, Descripcion, Duracion, Imagen, Presentacion, Unidad_Granel, Precio, Descuento, Tipo_Venta, Madurez, Agotado, Dado_Baja, Similar FROM productos WHERE FK_Usuario_Negocio = '$idNegocio'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$detalles = "";
						if ($row[$i]['Duracion'] != "0") {
							$detalles .= "<span style='font-size: 13px;'>Preparación: <b>".$row[$i]['Duracion']." min.</b></span><br>";
						}
						$presentacion = "";
						if ($row[$i]['Presentacion'] != "") {
							$presentacion = "<br><span style='font-size: 13px;'>Presentación: <b>".$row[$i]['Presentacion']."</b></span>";
						}

						if ($row[$i]['Tipo_Venta'] == "Pieza") {
							$detalles .= "<span style='font-size: 13px;'>Tipo de venta: <b>".$row[$i]['Tipo_Venta']."</b></span><br>";
						}else{
							$detalles .= "<span style='font-size: 13px;'>Tipo de venta: <b>".$row[$i]['Tipo_Venta']."</b></span><br>";
							if ($row[$i]['Unidad_Granel'] != "") {
								$detalles .= "<span style='font-size: 13px;'>Unidad: <b>".$row[$i]['Unidad_Granel']."</b></span><br>";
							}
						}

						if ($row[$i]['Descuento'] != "0") {
							$detalles .= "<span style='font-size: 13px;'>Descuento: <b>".$row[$i]['Descuento']."</b></span><br>";
						}

						if ($row[$i]['Madurez'] == "") {
							$detalles .= "";
						}else{
							$detalles .= "<span style='font-size: 13px;'>Madurez del producto: <b>".$row[$i]['Madurez']."</b></span><br>";
						}

						$Estatus = "";
						if ($row[$i]['Agotado'] == "1") {
							$Estatus .= "<span style='font-size: 13px;'><b style='color: red;'>El producto está agotado</b></span><br>";	
						}else{
							$Estatus .= "<span style='font-size: 13px;'><b style='color: black;'>Producto en existencia</b></span><br>";		
						}

						if ($row[$i]['Dado_Baja'] == "1") {
							$Estatus .= "<span style='font-size: 13px;'><b style='color: red;'>Producto dado de baja</b></span><br>";	
						}else{
							$Estatus .= "<span style='font-size: 13px;'><b style='color: black;'>Producto activo</b></span><br>";	
						}

						$archivo = '<a href="../server_SPIDI_APP/images/productos/sinimagen.png" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/productos/sinimagen.png'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';
						if($row[$i]['Imagen'] != ""){

							$archivo = '<a href="../server_SPIDI_APP/images/productos/'.$row[$i]["Imagen"].'" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/productos/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';

							//$foto = '<span class="imagenNegocio" style="background-image: url(../server_SPIDI_APP/images/negocios/'.$row[$i]['Imagen'].');"></span><br>';
						}

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Producto'],
							"Producto"=>$archivo."<br>".utf8_encode(utf8_decode($row[$i]['Nombre'])),
							"Precio"=> "<b class='dinero'>".$row[$i]['Precio']."</b>",
							"Detalles"=> "<span style='font-size: 13px;'>Descripción: <b>".utf8_encode(utf8_decode($row[$i]['Descripcion']))."</b></span>".$presentacion."<br>".$detalles,
							"Estatus"=> $Estatus,
							"Extras"=> '
								<button type="button" class="btn btn-outline-primary btn-sm NuevaOpcionProducto" attrid="'.$row[$i]['ID_Producto'].'" nombreProducto="'.utf8_encode(utf8_decode($row[$i]['Nombre'])).'">Nueva opción <i class="fas fa-plus"></i></button>
								<br>
								<br>
								<button type="button" class="btn btn-outline-primary btn-sm ConsultarExtrasProducto" attrid="'.$row[$i]['ID_Producto'].'" nombreProducto="'.utf8_encode(utf8_decode($row[$i]['Nombre'])).'">Ver opciones <i class="fas fa-list"></i></button>',
							"Categorias"=> '<button type="button" class="btn btn-link btn-sm ConsultarCategoriasProd" attrid="'.$row[$i]['ID_Producto'].'" nombreProducto="'.utf8_encode(utf8_decode($row[$i]['Nombre'])).'">Ver categorias</button>',
							"Accion" => '<button type="button" class="btn btn-primary btn-sm ModificarProducto btnModificarProducto'.$row[$i]['ID_Producto'].'" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-pencil-alt"></i></button> <button type="button" class="btn btn-danger btn-sm EliminarProducto" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarProductosNegocioNuevo") {
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$query = "SELECT ID_Producto, FK_Usuario_Negocio, Nombre, Descripcion, Duracion, Imagen, Presentacion, Unidad_Granel, Precio, Descuento, Tipo_Venta, Madurez, Agotado, Dado_Baja, Similar FROM productos WHERE ID_Producto = '$idProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						$detalles = "";
						if ($row[$i]['Duracion'] != "0") {
							$detalles .= "<span style='font-size: 13px;'>Preparación: <b>".$row[$i]['Duracion']." min.</b></span><br>";
						}
						$presentacion = "";
						if ($row[$i]['Presentacion'] != "") {
							$presentacion = "<br><span style='font-size: 13px;'>Presentación: <b>".$row[$i]['Presentacion']."</b></span>";
						}

						if ($row[$i]['Tipo_Venta'] == "Pieza") {
							$detalles .= "<span style='font-size: 13px;'>Tipo de venta: <b>".$row[$i]['Tipo_Venta']."</b></span><br>";
						}else{
							$detalles .= "<span style='font-size: 13px;'>Tipo de venta: <b>".$row[$i]['Tipo_Venta']."</b></span><br>";
							if ($row[$i]['Unidad_Granel'] != "") {
								$detalles .= "<span style='font-size: 13px;'>Unidad: <b>".$row[$i]['Unidad_Granel']."</b></span><br>";
							}
						}

						if ($row[$i]['Descuento'] != "0") {
							$detalles .= "<span style='font-size: 13px;'>Descuento: <b>".$row[$i]['Descuento']."</b></span><br>";
						}

						if ($row[$i]['Madurez'] == "") {
							$detalles .= "";
						}else{
							$detalles .= "<span style='font-size: 13px;'>Madurez del producto: <b>".$row[$i]['Madurez']."</b></span><br>";
						}

						$Estatus = "";
						if ($row[$i]['Agotado'] == "1") {
							$Estatus .= "<span style='font-size: 13px;'><b style='color: red;'>El producto está agotado</b></span><br>";	
						}else{
							$Estatus .= "<span style='font-size: 13px;'><b style='color: black;'>Producto en existencia</b></span><br>";		
						}

						if ($row[$i]['Dado_Baja'] == "1") {
							$Estatus .= "<span style='font-size: 13px;'><b style='color: red;'>Producto dado de baja</b></span><br>";	
						}else{
							$Estatus .= "<span style='font-size: 13px;'><b style='color: black;'>Producto activo</b></span><br>";	
						}

						$archivo = '<a href="../server_SPIDI_APP/images/productos/sinimagen.png" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/productos/sinimagen.png'."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';
						if($row[$i]['Imagen'] != ""){

							$archivo = '<a href="../server_SPIDI_APP/images/productos/'.$row[$i]["Imagen"].'" data-fancybox="images">
									<div style="background-image: url('."'".'../server_SPIDI_APP/images/productos/'.$row[$i]["Imagen"]."'".'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;border-radius: 100%;"></div>
								</a>';

							//$foto = '<span class="imagenNegocio" style="background-image: url(../server_SPIDI_APP/images/negocios/'.$row[$i]['Imagen'].');"></span><br>';
						}

						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Producto'],
							"Producto"=>$archivo."<br>".utf8_encode(utf8_decode($row[$i]['Nombre'])),
							"Precio"=> "<b class='dinero'>".$row[$i]['Precio']."</b>",
							"Detalles"=> "<span style='font-size: 13px;'>Descripción: <b>".utf8_encode(utf8_decode($row[$i]['Descripcion']))."</b></span>".$presentacion."<br>".$detalles,
							"Estatus"=> $Estatus,
							"Extras"=> '
								<button type="button" class="btn btn-outline-primary btn-sm NuevaOpcionProducto" attrid="'.$row[$i]['ID_Producto'].'" nombreProducto="'.utf8_encode(utf8_decode($row[$i]['Nombre'])).'">Nueva opción <i class="fas fa-plus"></i></button>
								<br>
								<br>
								<button type="button" class="btn btn-outline-primary btn-sm ConsultarExtrasProducto" attrid="'.$row[$i]['ID_Producto'].'" nombreProducto="'.utf8_encode(utf8_decode($row[$i]['Nombre'])).'">Ver opciones <i class="fas fa-list"></i></button>',
							"Categorias"=> '<button type="button" class="btn btn-link btn-sm ConsultarCategoriasProd" attrid="'.$row[$i]['ID_Producto'].'" nombreProducto="'.utf8_encode(utf8_decode($row[$i]['Nombre'])).'">Ver categorias</button>',
							"Accion" => '<button type="button" class="btn btn-primary btn-sm ModificarProducto btnModificarProducto'.$row[$i]['ID_Producto'].'" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-pencil-alt"></i></button> <button type="button" class="btn btn-danger btn-sm EliminarProducto" attrid="'.$row[$i]['ID_Producto'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarOpcionesProducto") { 
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$query = "SELECT ID_Opcion, FK_Producto, Titulo, Es_Cantidad, Obligatorio, Maximo, Activa FROM opciones_productos WHERE FK_Producto = '$idProducto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						if ($row[$i]['Es_Cantidad'] == "1") {
							$TipoOpcion = "Por cantidad";
						}else{
							$TipoOpcion = "Seleccionar";
						}

						$Estatus = "";
						if ($row[$i]['Obligatorio'] == "1") {
							$Estatus .= "<b>Obligatorio</b> <br>";
						}else{
							$Estatus .= "<b>Opcional</b> <br>";
						}

						if ($row[$i]['Activa'] == "1") {
							$Estatus .= "Estatus: <b>Activo</b>";
						}else{
							$Estatus .= "Estatus: <b>Desactivado</b>";
						}

						$DatosProductos = "
						<table class='table' style='width: 100%;'>
							<thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Agotado</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
						";
						$query2 = "SELECT ID_Detalle_Productos, FK_Opciones, Nombre, Precio, Agotada, Activa FROM detalle_productos_opciones WHERE FK_Opciones = '".$row[$i]['ID_Opcion']."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas; 
						if ($row2 == "si") {
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								for($x=0; $x<$numerofilas2; $x++){
									$Agotado = "No";
									if ($row2[$x]['Agotada'] == "1") {
										$Agotado = "Si";
									}

									$activo = "Inactivo";
									if ($row2[$x]['Activa'] == "1") {
										$activo = "Activo";
									}

									$DatosProductos .= "
			                            <tr>
			                            	<th>".$row2[$x]['Nombre']."</th>
			                                <th>".$row2[$x]['Precio']."</th>
			                                <th>".$Agotado."</th>
			                                <th>".$activo."</th>
			                            </tr>   
									";
								}
							}
						}
						$DatosProductos .= "</tbody></table>";


						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Opcion'],
							"Titulo"=> utf8_encode(utf8_decode($row[$i]['Titulo'])),
							"Tipo"=> $TipoOpcion,
							"Cantidad"=> "<b class='cantidad'>".$row[$i]['Maximo']."</b>",
							"Detalles"=> $Estatus,
							"Productos"=> $DatosProductos,
							"Accion" => '
							<button type="button" class="btn btn-primary btn-sm ModificarOpcion btnModificarOpcion'.$row[$i]['ID_Opcion'].'" attrid="'.$row[$i]['ID_Opcion'].'"><i class="fas fa-pencil-alt"></i></button>
							<button type="button" class="btn btn-danger btn-sm EliminarOpcion" attrid="'.$row[$i]['ID_Opcion'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarOpcionesProductoNuevo") { 
			$idOpcion = $omodelo->link->real_escape_string($idOpcion);
			$query = "SELECT ID_Opcion, FK_Producto, Titulo, Es_Cantidad, Obligatorio, Maximo, Activa FROM opciones_productos WHERE ID_Opcion = '$idOpcion'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){

						if ($row[$i]['Es_Cantidad'] == "1") {
							$TipoOpcion = "Por cantidad";
						}else{
							$TipoOpcion = "Seleccionar";
						}

						$Estatus = "";
						if ($row[$i]['Obligatorio'] == "1") {
							$Estatus .= "<b>Obligatorio</b> <br>";
						}else{
							$Estatus .= "<b>Opcional</b> <br>";
						}

						if ($row[$i]['Activa'] == "1") {
							$Estatus .= "Estatus: <b>Activo</b>";
						}else{
							$Estatus .= "Estatus: <b>Desactivado</b>";
						}

						$DatosProductos = "
						<table class='table' style='width: 100%;'>
							<thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Agotado</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
						";
						$query2 = "SELECT ID_Detalle_Productos, FK_Opciones, Nombre, Precio, Agotada, Activa FROM detalle_productos_opciones WHERE FK_Opciones = '".$row[$i]['ID_Opcion']."'";
						$row2 = $omodelo->_consultar($query2);
						$numerofilas2 = $omodelo->numerofilas; 
						if ($row2 == "si") {
							echo "Error: ".mysqli_error($omodelo->link);
						}else{
							if($numerofilas2 > 0){
								for($x=0; $x<$numerofilas2; $x++){
									$Agotado = "Si";
									if ($row2[$x]['Agotada'] == "1") {
										$Agotado = "No";
									}

									$activo = "Inactivo";
									if ($row2[$x]['Activa'] == "1") {
										$activo = "Activo";
									}

									$DatosProductos .= "
			                            <tr>
			                            	<th>".$row2[$x]['Nombre']."</th>
			                                <th>".$row2[$x]['Precio']."</th>
			                                <th>".$Agotado."</th>
			                                <th>".$activo."</th>
			                            </tr>   
									";
								}
							}
						}
						$DatosProductos .= "</tbody></table>";


						$arreglo['data'][$i] = array(
							"DT_RowId"=> $row[$i]['ID_Opcion'],
							"Titulo"=> utf8_encode(utf8_decode($row[$i]['Titulo'])),
							"Tipo"=> $TipoOpcion,
							"Cantidad"=> "<b class='cantidad'>".$row[$i]['Maximo']."</b>",
							"Detalles"=> $Estatus,
							"Productos"=> $DatosProductos,
							"Accion" => '
							<button type="button" class="btn btn-primary btn-sm ModificarOpcion btnModificarOpcion'.$row[$i]['ID_Opcion'].'" attrid="'.$row[$i]['ID_Opcion'].'"><i class="fas fa-pencil-alt"></i></button>
							<button type="button" class="btn btn-danger btn-sm EliminarOpcion" attrid="'.$row[$i]['ID_Opcion'].'"><i class="fas fa-trash"></i></button>'
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}else if ($tipo == "ConsultarFotosRepartidores") {
			$query = "SELECT ID_Repartidor, Foto FROM usuarios_repartidor WHERE Estatus_Cuenta = 'Aceptada'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$arreglo[$row[$i]['ID_Repartidor']] = array(
							"Foto" => $row[$i]['Foto']
						);
					}	
				}
			}

			echo json_encode($arreglo);
		}
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "EliminarHorario") {
			$idHorario = $omodelo->link->real_escape_string($idHorario);
			$query = "DELETE FROM horarios_negocios WHERE ID_Horario = '$idHorario'";
			$error = $omodelo->_insertar($query);
			
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if ($tipo == "EliminarCategoria") {
			$idCategoria = $omodelo->link->real_escape_string($idCategoria);
			$query = "DELETE FROM categorias WHERE ID_Categoria = '$idCategoria'";
			$error = $omodelo->_insertar($query);
			
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if ($tipo == "EliminarProducto") {
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$query = "DELETE FROM productos WHERE ID_Producto = '$idProducto'";
			$error = $omodelo->_insertar($query);
			
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if ($tipo == "EliminarOpcion") {
			$idOpcion = $omodelo->link->real_escape_string($idOpcion);
			$query = "DELETE FROM opciones_productos WHERE ID_Opcion = '$idOpcion'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}
		
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);
		
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "DatosNegocio") {
			$id = $omodelo->link->real_escape_string($id);
			$nombreNegocio = $omodelo->link->real_escape_string($NombreNegocio);
			//$tiempoPreparacion = $omodelo->link->real_escape_string($TiempoPreparacion);
			$costoEnvio = $omodelo->link->real_escape_string($CostoEnvio);
			$descripcionNegocio = $omodelo->link->real_escape_string($DescripcionNegocio);
			$correoelectronico = $omodelo->link->real_escape_string($Correoelectronico);
			$UsuarioNegocioModificar = $omodelo->link->real_escape_string($UsuarioNegocioModificar);
			$telefonoNegocio = $omodelo->link->real_escape_string($TelefonoNegocio);
			$whatsNegocio = $omodelo->link->real_escape_string($WhatsNegocio);
			$paginaWeb = $omodelo->link->real_escape_string($PaginaWeb);
			$instaNegocio = $omodelo->link->real_escape_string($InstaNegocio);
			$facebookNegocio = $omodelo->link->real_escape_string($FacebookNegocio);
			$twitterNegocio = $omodelo->link->real_escape_string($TwitterNegocio);
			$youtubeNegocio = $omodelo->link->real_escape_string($YoutubeNegocio);
			$tikTokNegocio = $omodelo->link->real_escape_string($TikTokNegocio);
			$nombreContactoNegocio = $omodelo->link->real_escape_string($NombreContactoNegocio);
			$telefonoContactoNegocio = $omodelo->link->real_escape_string($TelefonoContactoNegocio);
			$correoContactoNegocio = $omodelo->link->real_escape_string($CorreoContactoNegocio);
			$RangoInicio = $omodelo->link->real_escape_string($RangoInicio);
			$RangoFin = $omodelo->link->real_escape_string($RangoFin);
			$rangoTiempo = "De ".$RangoInicio." a ".$RangoFin;
			$PrioridadNegocio = $omodelo->link->real_escape_string($PrioridadNegocio);

			$queryContra = "";
			if (isset($NuevaContraNegocio) && $NuevaContraNegocio != "") {
				$queryContra = "Contrasena = MD5('$NuevaContraNegocio'),";
			}

			$query = "
			UPDATE usuarios_negocio SET 
				Usuario = '$UsuarioNegocioModificar',
				$queryContra
				FK_Clasificacion = '$ClasificacionNegocioModificar',
				Nombre='$nombreNegocio',
				Tiempo_Prepa_Prom='$rangoTiempo',
				Costo_Envio='$costoEnvio',
				Descripcion='$descripcionNegocio',
				Correo='$correoelectronico',
				Telefono='$telefonoNegocio',
				Whatsapp='$whatsNegocio',
				Pagina_web='$paginaWeb',
				Instagram='$instaNegocio',
				Facebook='$facebookNegocio',
				Twitter='$twitterNegocio',
				Youtube='$youtubeNegocio',
				Tiktok='$tikTokNegocio',
				Nombre_Contacto='$nombreContactoNegocio',
				Telefono_Contacto='$telefonoContactoNegocio',
				Correo_Contacto='$correoContactoNegocio',
				Estatus = '$estatus',
				Estatus_Cuenta = '$EstatusCuenta',
				Activo = '$activo',
				Abierto = '$abierto',
				Prioridad = '$PrioridadNegocio' 
			WHERE ID_Negocio = '$id'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{

				$status = 1;
				if ($_FILES['ImagenNegocio']['size'] > 0 && $_FILES['ImagenNegocio']['error'] == 0) {
					$file = $_FILES["ImagenNegocio"];
					$nombreDoc = $file["name"];
					$tipo = $file["type"];
					$ruta_provisional = $file["tmp_name"];
					$size = $file["size"];
					$carpeta = "../server_SPIDI_APP/images/negocios/";

					if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != 'application/pdf' && $tipo != ''){
						echo "Error 2 Formato";
					}else if ($size > (1024*1024*10)){
						echo "Error 3 Peso";
					}else{
						$status = 0;
						$ruta = $carpeta;
					}
				}
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

				if($status == 0){
					$query2 = "UPDATE usuarios_negocio SET Imagen = '".$id.'_'.$nombreDoc."' WHERE  ID_Negocio = '$id'";
					$error3 = $omodelo->_insertar($query2);	

					if ($error3 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.''.$id.'_'.$nombreDoc);
					}
				}

				echo "Correcto";
			}
		}else if($tipo == "Ubicacion"){
			$calleNegocio = $omodelo->link->real_escape_string($CalleNegocio);
			$numeroInteriorNegocio = $omodelo->link->real_escape_string($NumeroInteriorNegocio);
			$numeroExteriorNegocio = $omodelo->link->real_escape_string($NumeroExteriorNegocio);
			$codigoPostal = $omodelo->link->real_escape_string($CodigoPostal);
			$coloniaNegocio = $omodelo->link->real_escape_string($ColoniaNegocio);
			$detallesAdicionales = $omodelo->link->real_escape_string($DetallesAdicionales);
			$ciudadNegocio = $omodelo->link->real_escape_string($CiudadNegocio);
			$latitudNegocio = $omodelo->link->real_escape_string($LatitudNegocio);
			$longitudNegocio = $omodelo->link->real_escape_string($LongitudNegocio);
			$query = "
			UPDATE usuarios_negocio SET 
				Calle='$calleNegocio',
				No_Interior='$numeroInteriorNegocio',
				No_Exterior='$numeroExteriorNegocio',
				Codigo_Postal='$codigoPostal',
				Colonia='$coloniaNegocio',
				Detalles='$detallesAdicionales',
				Ciudad='$ciudadNegocio',
				Latitud='$latitudNegocio',
				Longitud='$longitudNegocio' 
			WHERE ID_Negocio = '$id'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if($tipo == "ModificarHorario"){

			$idHorario = $omodelo->link->real_escape_string($idHorario);
			$DiaSemana = $omodelo->link->real_escape_string($DiaSemana);
			$HoraInicio = $omodelo->link->real_escape_string($HoraInicio);
			$HoraFinal = $omodelo->link->real_escape_string($HoraFinal);
			$HorarioEspecial = $omodelo->link->real_escape_string($HorarioEspecial);
			$FechaInicio = $omodelo->link->real_escape_string($FechaInicio);
			$FechaFinal = $omodelo->link->real_escape_string($FechaFinal);
			$query = "UPDATE horarios_negocios SET Dia_Semana = '$DiaSemana', Hora_Apertura = '$HoraInicio', Hora_Cierre = '$HoraFinal', Especial = '$HorarioEspecial', Fecha_Inicio = '$FechaInicio', Fecha_Final = '$FechaFinal' WHERE ID_Horario = '$idHorario'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);
				echo "Correcto";
			}


		}else if($tipo == "ModificarCategoria"){

			$idCategoria = $omodelo->link->real_escape_string($idCategoria);
			$Nombre = $omodelo->link->real_escape_string($NombreCategoria);
			$Descripcion = $omodelo->link->real_escape_string($DescripcionCategoria);

			$query = "UPDATE categorias SET Nombre = '$Nombre', Descripcion = '$Descripcion' WHERE ID_Categoria = '$idCategoria'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}else if($tipo == "ModificarProducto"){

			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$NombreProducto = $omodelo->link->real_escape_string($NombreProducto);
			$PreparacionProducto = $omodelo->link->real_escape_string($PreparacionProducto);
			$DescripcionProducto = $omodelo->link->real_escape_string($DescripcionProducto);
			$PresentacionProducto = $omodelo->link->real_escape_string($PresentacionProducto);
			$PrecioProducto = $omodelo->link->real_escape_string($PrecioProducto);
			$DescuentoProducto = $omodelo->link->real_escape_string($DescuentoProducto);
			$TipoVentaProducto = $omodelo->link->real_escape_string($TipoVentaProducto);
			$UnidadGranelProducto = $omodelo->link->real_escape_string($UnidadGranelProducto);
			$MadurezProducto = $omodelo->link->real_escape_string($MadurezProducto);
			$AgotadoProducto = $omodelo->link->real_escape_string($AgotadoProducto);
			$DadoBajaProducto = $omodelo->link->real_escape_string($DadoBajaProducto);

			$query = "UPDATE productos SET 
			Nombre = '$NombreProducto', 
			Duracion = '$PreparacionProducto', 
			Descripcion = '$DescripcionProducto', 
			Presentacion = '$PresentacionProducto', 
			Precio = '$PrecioProducto', 
			Descuento = '$DescuentoProducto', 
			Tipo_Venta = '$TipoVentaProducto', 
			Unidad_Granel = '$UnidadGranelProducto', 
			Madurez = '$MadurezProducto', 
			Agotado = '$AgotadoProducto', 
			Dado_Baja = '$DadoBajaProducto' WHERE ID_Producto = '$idProducto'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				$id = mysqli_insert_id($omodelo->link);

				$status = 1;
				if ($_FILES['ImagenProducto']['size'] > 0 && $_FILES['ImagenProducto']['error'] == 0) {
					$file = $_FILES["ImagenProducto"];
					$nombreDoc = $file["name"];
					$tipo = $file["type"];
					$ruta_provisional = $file["tmp_name"];
					$size = $file["size"];
					$carpeta = "../server_SPIDI_APP/images/productos/";
											    
					if ($tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png' && $tipo != ''){
						echo "Error 2 Formato";
					}else if ($size > (1024*1024*10)){
						echo "Error 3 Peso";
					}else{
						$status = 0;
						$ruta = $carpeta;
					}
				}			
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

				if($status == 0){

					$idProducto = $omodelo->link->real_escape_string($idProducto);
					$query1 = "SELECT Imagen FROM productos WHERE ID_Producto = '$idProducto'";
					$row1 = $omodelo->_consultar($query1);
					$numerofilas = $omodelo->numerofilas; 
					if ($row1 == "si") {
						echo "Error: ".mysqli_error($omodelo->link);
					}else{
						if($numerofilas > 0){
							if (file_exists("../../server_SPIDI_APP/images/productos/".$row1[0]["Imagen"] && $row1[0]["Imagen"] != "")) {
								unlink("../../server_SPIDI_APP/images/productos/".$row1[0]["Imagen"]);
							}
						}
					}
					$query2 = "UPDATE productos SET Imagen = '".$idProducto.'_'.$nombreDoc."' WHERE ID_Producto = '$idProducto'";
					$error3 = $omodelo->_insertar($query2);	
					if ($error3 == "si") {
						echo "Error 4: ".mysqli_error($omodelo->link); 
					}else{
						move_uploaded_file($ruta_provisional,  $ruta.''.$idProducto.'_'.$nombreDoc);
					}
				}
				echo "Correcto";
			}
		}else if($tipo == "ModificarExtraProducto"){

			$idOpcion = $omodelo->link->real_escape_string($idOpcion);
			$Titulo = $omodelo->link->real_escape_string($Titulo);
			$TipoOpcion = $omodelo->link->real_escape_string($TipoOpcion);
			$CantidadOpcion = $omodelo->link->real_escape_string($CantidadOpcion);
			$TipoSeleccion = $omodelo->link->real_escape_string($TipoSeleccion);
			$EstatusOpcion = $omodelo->link->real_escape_string($EstatusOpcion);
			//arregloProductos
			$query = "UPDATE opciones_productos SET Titulo = '$Titulo', Es_Cantidad = '$TipoOpcion', Obligatorio = '$TipoSeleccion', Maximo = '$CantidadOpcion', Activa = '$EstatusOpcion' WHERE ID_Opcion = '$idOpcion'";
			$error = $omodelo->_insertar($query);
			if ($error == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				
				$queryeliminar = "DELETE FROM detalle_productos_opciones WHERE FK_Opciones = '$idOpcion'";
				$erroreliminar = $omodelo->_insertar($queryeliminar);
				if ($erroreliminar == "si") {
					echo "Error 2: ".mysqli_error($omodelo->link);
				}

				$separar = explode("~", $arregloProductos);
				for ($i=0; $i < sizeof($separar) - 1; $i++) {
					$datos = explode(",", $separar[$i]);
					$query2 = "INSERT INTO detalle_productos_opciones SET FK_Opciones = '$idOpcion', Nombre = '".$datos[0]."', Precio = '".$datos[1]."', Agotada = '".$datos[3]."', Activa = '".$datos[2]."'";
					$error2 = $omodelo->_insertar($query2);
					if ($error2 == "si") {
						echo "Error 2: ".mysqli_error($omodelo->link);
					}
				}
				echo "Correcto~".$idOpcion;
			}
		}
	}
}
?>