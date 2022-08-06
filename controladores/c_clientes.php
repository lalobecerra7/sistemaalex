<?php
class clientes {

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$IDCliente = $omodelo->link->real_escape_string($idcliente);
		$IDComision = $omodelo->link->real_escape_string($venta);
		$PrecioNuevo = $omodelo->link->real_escape_string($precio);
		$Comision = 'LIKE "%attrID='."'".$IDComision."'".'%"';
		$query = "SELECT * FROM notificaciones WHERE FK_Cliente = '$IDCliente' AND Nombre = 'Compra Sugerida' AND Descripcion $Comision";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas; 

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){

				$queryp = "SELECT Precio FROM comisiones WHERE ID_Comision = '$IDComision'";
				$rowp = $omodelo->_consultar($queryp);
				$PrecioActual = $rowp[0]["Precio"];

				$query1 = "UPDATE comisiones SET Precio = '$PrecioNuevo' WHERE ID_Comision = '$IDComision'";
				$error = $omodelo->_insertar($query1);
				if ($error == "si") {
					echo "Error 1: ".mysqli_error($omodelo->link);
				}else{

					$Descripcion = $row[0]["Descripcion"];

					$DescripcionNueva = str_replace("<span class='dinero'>".$PrecioActual."</span>", "<span class='dinero'>".$PrecioNuevo."</span>", $Descripcion);

					$query2 = 'UPDATE notificaciones SET Descripcion = "'.$DescripcionNueva.'" WHERE ID_Notificacion = "'.$row[0]["ID_Notificacion"].'"';
					$error2 = $omodelo->_insertar($query2);
					if ($error2 == "si") {
						echo "Error 2: ".$query2;
					}else{
						echo "Correcto"; 
					}
				}
			}
		}
	}

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$arreglo = null;

		$query = "SELECT ID_Cliente, FK_Usuario, Foto, Fecha_Alta AS toDate, DATE_FORMAT(Fecha_Alta, '%d-%m-%Y %r') AS Fecha_Alta, Nombre, Primer_Apellido, Segundo_Apellido, Telefono, Correo, Estatus, Tiempo_Inicio, Tiempo_Final, Ultimo_Intento, Activo, Temporal, Tipo_Login, Vendedor, Aparecer, Puesto, Calificacion, (SELECT CONCAT(clientes.Nombre, ' ', clientes.Primer_Apellido, ' ', clientes.Segundo_Apellido) FROM clientes WHERE ID_Cliente = usuarios.FK_Referido) AS Referido  FROM clientes INNER JOIN usuarios ON FK_Usuario = ID_Usuario AND Tipo != 'Administrador'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas; 

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$estatus = "";$descrip = "";$botones = '';
					if($row[$i]['Estatus'] == 'Bloqueado'){
						$estatus= '<span class="badge rounded-pill bg-danger">Bloqueado</span>';
					}else if($row[$i]['Estatus'] == 'Desbloqueado'){
						$estatus= '<span class="badge rounded-pill bg-success">Desbloqueado</span>';
					}

					$foto = "<span class='imagenesCliente' style='background-image: url(vistas/assets/media/users/Default/user3.png);'></span><br>";
					if($row[$i]['Foto'] != ""){
						$foto = '<span class="imagenesCliente" style="background-image: url('."'".'../inicio/vistas/assets/media/users/'.$row[$i]['FK_Usuario'].'_'.$row[$i]['Foto']."'".');"></span><br>';
					}

					if ($row[$i]['Tipo_Login'] == '1') {
						$TipoLogin = '<b>Usuario y Contraseña</b>';
					}else if ($row[$i]['Tipo_Login'] == '2') {
						$TipoLogin = '<b>Facebook</b>';
					}else if ($row[$i]['Tipo_Login'] == '3') {
						$TipoLogin = '<b>Twitter</b>';
					}else if ($row[$i]['Tipo_Login'] == '4') {
						$TipoLogin = '<b>Google</b>';
					}

					if ($row[$i]['Vendedor'] == '1') {
						if($row[$i]['Puesto'] == ''){
							$row[$i]['Puesto'] = 'Vendedor';
						}

						$vendedor= '<span class="badge rounded-pill bg-primary">Vendedor</span><br>Calificación: <b>'.round($row[$i]['Calificacion']).'</b><br><b>Puesto: </b>'.$row[$i]['Puesto'];
					}else if ($row[$i]['Vendedor'] == '0') {
						$vendedor= 'N/A';
					}

					$b1 = '';$b2 = '';
					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][2] == '1'){
						$b1 = '<button type="button" class="btn btn-warning btn-sm bModificarCliente" attrID="'.$row[$i]["ID_Cliente"].'" usuario="'.$row[$i]['FK_Usuario'].'"><i class="fas fa-pencil-alt"></i></button>';
					}
					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][3] == '1'){
						$b2 = '<button type="button" class="btn btn-danger btn-sm bEliminarCliente" attrID="'.$row[$i]["ID_Cliente"].'" usuario="'.$row[$i]['FK_Usuario'].'"> <i class="fas fa-trash"></i></button>';
					}

					$arreglo['data'][$i] = array("DT_RowId"=> 'CLI'.$row[$i]['ID_Cliente'], 'ID' => $row[$i]['ID_Cliente'], 'Date' => strtotime($row[$i]['toDate']), 'Fecha' => $row[$i]['Fecha_Alta'], 'Usuario' => $foto.'<b>Nombre: </b>'.$row[$i]['Nombre'].' '.$row[$i]['Primer_Apellido'].' '.$row[$i]['Segundo_Apellido'].'<br><b>Correo: </b>'.$row[$i]['Correo'].'<br><b>Teléfono: </b>'.$row[$i]['Telefono']."<br><b>Referido de: </b>".$row[$i]['Referido'], 'Estatus' => $estatus, 'Sesion' => '<b>Ultimo Intento: </b>'.$row[$i]['Ultimo_Intento'].'<br><b>Tiempo Inicio: </b>'.$row[$i]['Tiempo_Inicio'].'<br><b>Tiempo Final: </b>'.$row[$i]['Tiempo_Final'], 'Login' => $TipoLogin, 'Vendedor' => $vendedor, 'Accion' => $b1.' '.$b2);
				}	
			}
		}

		echo json_encode($arreglo);	
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$id = $omodelo->link->real_escape_string($id);
		$usuario = $omodelo->link->real_escape_string($usuario);

		$query = "DELETE FROM clientes WHERE ID_Cliente = '$id' AND FK_Usuario = '$usuario' AND Tipo != 'Administrador'";
		$error = $omodelo->_insertar($query);
		
		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";

			$query1 = "DELETE FROM usuarios WHERE ID_Usuario = '$usuario'";
			$error1 = $omodelo->_insertar($query1);

			if ($error1 == "si") {
				echo "Error 2: ".mysqli_error($omodelo->link);
			}

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);
		$arreglo = null;

		if($tipo == '1'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "SELECT ID_Cliente, FK_Usuario, Foto, Fecha_Alta AS toDate, DATE_FORMAT(Fecha_Alta, '%d-%m-%Y %r') AS Fecha_Alta, Nombre, Primer_Apellido, Segundo_Apellido, Telefono, Correo, Estatus, Tiempo_Inicio, Tiempo_Final, Ultimo_Intento, Activo, Temporal, Tipo_Login, Vendedor, Aparecer, Puesto, Calificacion, (SELECT CONCAT(clientes.Nombre, ' ', clientes.Primer_Apellido, ' ', clientes.Segundo_Apellido) FROM clientes WHERE ID_Cliente = usuarios.FK_Referido) AS Referido FROM clientes INNER JOIN usuarios ON FK_Usuario = ID_Usuario AND Tipo != 'Administrador' WHERE ID_Cliente = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$estatus = "";$descrip = "";$botones = '';
					if($row[0]['Estatus'] == 'Bloqueado'){
						$estatus= '<span class="badge rounded-pill bg-danger">Bloqueado</span>';
					}else if($row[0]['Estatus'] == 'Desbloqueado'){
						$estatus= '<span class="badge rounded-pill bg-success">Desbloqueado</span>';
					}

					$foto = "<span class='imagenesCliente' style='background-image: url(vistas/assets/media/users/Default/user3.png);'></span><br>";
					if($row[0]['Foto'] != ""){
						$foto = '<span class="imagenesCliente" style="background-image: url('."'".'../inicio/vistas/assets/media/users/'.$row[0]['FK_Usuario'].'_'.$row[0]['Foto']."'".');"></span><br>';
					}

					if ($row[0]['Tipo_Login'] == '1') {
						$TipoLogin = '<b>Usuario y Contraseña</b>';
					}else if ($row[0]['Tipo_Login'] == '2') {
						$TipoLogin = '<b>Facebook</b>';
					}else if ($row[0]['Tipo_Login'] == '3') {
						$TipoLogin = '<b>Twitter</b>';
					}else if ($row[0]['Tipo_Login'] == '4') {
						$TipoLogin = '<b>Google</b>';
					}

					if ($row[0]['Vendedor'] == '1') {
						if($row[0]['Puesto'] == ''){
							$row[0]['Puesto'] = 'Vendedor';
						}

						$vendedor= '<span class="badge rounded-pill bg-primary">Vendedor</span><br>Calificación: <b>'.round($row[0]['Calificacion']).'</b><br><b>Puesto: </b>'.$row[0]['Puesto'];
					}else if ($row[0]['Vendedor'] == '0') {
						$vendedor= 'N/A';
					}

					$b1 = '';$b2 = '';$b3 = '';
					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][2] == '1'){
						$b1 = '<button type="button" class="btn btn-warning btn-sm bModificarCliente" attrID="'.$row[0]["ID_Cliente"].'" usuario="'.$row[0]['FK_Usuario'].'"><i class="fas fa-pencil-alt"></i></button>';
					}
					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][3] == '1'){
						$b2 = '<button type="button" class="btn btn-danger btn-sm bEliminarCliente" attrID="'.$row[0]["ID_Cliente"].'" usuario="'.$row[0]['FK_Usuario'].'"> <i class="fas fa-trash"></i></button>';
					}

					$arreglo = array("DT_RowId"=> 'CLI'.$row[0]['ID_Cliente'], 'ID' => $row[0]['ID_Cliente'], 'Date' => strtotime($row[0]['toDate']), 'Fecha' => $row[0]['Fecha_Alta'], 'Usuario' => $foto.'<b>Nombre: </b>'.$row[0]['Nombre'].' '.$row[0]['Primer_Apellido'].' '.$row[0]['Segundo_Apellido'].'<br><b>Correo: </b>'.$row[0]['Correo'].'<br><b>Teléfono: </b>'.$row[0]['Telefono']."<br><b>Referido de: </b>".$row[0]['Referido'], 'Estatus' => $estatus, 'Sesion' => '<b>Ultimo Intento: </b>'.$row[0]['Ultimo_Intento'].'<br><b>Tiempo Inicio: </b>'.$row[0]['Tiempo_Inicio'].'<br><b>Tiempo Final: </b>'.$row[0]['Tiempo_Final'], 'Login' => $TipoLogin, 'Vendedor' => $vendedor, 'Accion' => $b1.' '.$b2);	
				}
			}

			echo json_encode($arreglo);		
		}else if($tipo == '2'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "SELECT ID_Cliente, Nombre, Primer_Apellido, Segundo_Apellido, Telefono, Nombre_DNI, DNI, Fecha_Nacimiento, Sexo, Nacionalidad, Fecha_Alta, Pais, Estado, Ciudad, Colonia, Direccion, CP, SexoBe, Parentesco, Beneficiario, FK_Usuario, NIP, Tipo, Idioma, Moneda, Conocer, Correo, Estatus, Activo, Temporal, Regalado, usuarios.FK_Referido AS IDReferido, (SELECT CONCAT(clientes.Nombre, ' ', clientes.Primer_Apellido, ' ', clientes.Segundo_Apellido) FROM clientes WHERE ID_Cliente = IDReferido) AS Referido FROM clientes INNER JOIN usuarios ON FK_Usuario = ID_Usuario INNER JOIN saldos ON FK_Cliente = ID_Cliente AND Tipo != 'Administrador' WHERE ID_Cliente = '$id'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					$arreglo = array('ID_Cliente' => $row[0]['ID_Cliente'], 'Nombre' => $row[0]['Nombre'], 'Primer_Apellido' => $row[0]['Primer_Apellido'], 'Segundo_Apellido' => $row[0]['Segundo_Apellido'], 'Telefono' => $row[0]['Telefono'], 'Nombre_DNI' => $row[0]['Nombre_DNI'], 'DNI' => $row[0]['DNI'], 'Fecha_Nacimiento' => $row[0]['Fecha_Nacimiento'], 'Sexo' => $row[0]['Sexo'], 'Nacionalidad' => $row[0]['Nacionalidad'], 'Fecha_Alta ' => $row[0]['Fecha_Alta'], 'Pais' => $row[0]['Pais'], 'Estado' => $row[0]['Estado'], 'Ciudad' => $row[0]['Ciudad'], 'Colonia' => $row[0]['Colonia'], 'Direccion' => $row[0]['Direccion'], 'CP' => $row[0]['CP'], 'SexoBe' => $row[0]['SexoBe'], 'Parentesco' => $row[0]['Parentesco'], 'Beneficiario' => $row[0]['Beneficiario'], 'FK_Usuario' => $row[0]['FK_Usuario'], 'NIP' => $row[0]['NIP'], 'Tipo' => $row[0]['Tipo'], 'Idioma' => $row[0]['Idioma'], 'Moneda' => $row[0]['Moneda'], 'Conocer' => $row[0]['Conocer'], 'Correo' => $row[0]['Correo'], 'Estatus' => $row[0]['Estatus'], 'Activo' => $row[0]['Activo'], 'Temporal' => $row[0]['Temporal'], 'Regalado' => $row[0]['Regalado'], 'Referido' => $row[0]['IDReferido'], "NombreReferido" => $row[0]['Referido']);	
				}
			}

			echo json_encode($arreglo);		
		}else if($tipo == '3'){
			
			$query = "SELECT ID_Cliente, Nombre, Primer_Apellido, Segundo_Apellido, usuarios.Correo AS Correo, usuarios.Foto AS Foto, FK_Usuario FROM clientes INNER JOIN usuarios ON FK_Usuario = ID_Usuario WHERE usuarios.Vendedor = '1' AND clientes.Tipo = 'Cliente'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						//echo "entro".$i;
						$foto = "<span class='imagenesCliente' style='background-image: url(vistas/assets/media/users/Default/user3.png);'></span><br>";
						if($row[$i]['Foto'] != ""){
							$foto = '<span class="imagenesCliente" style="background-image: url('."'".'../inicio/vistas/assets/media/users/'.$row[$i]['FK_Usuario'].'_'.$row[$i]['Foto']."'".');"></span><br>';
						}


						$arreglo["data"][$i] = array("DT_RowId"=> $row[$i]['ID_Cliente'], 'ID_Cliente' => $row[$i]['ID_Cliente'], 'Nombre' => $row[$i]['Nombre'].' '.$row[$i]['Primer_Apellido'].' '.$row[$i]['Segundo_Apellido'], 'Correo' => $row[$i]['Correo'], 'Foto' => $foto);	
					}
				}
				echo json_encode($arreglo);		
			}
		}else if($tipo == '4'){
			$query = "SELECT ID_Cliente, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS Cliente, Telefono, Fecha_Alta AS toDate,DATE_FORMAT(Fecha_Alta, '%d-%m-%Y %r') AS Fecha_Alta, ID_Usuario, Correo, FK_Usuario, Foto FROM clientes INNER JOIN usuarios ON FK_Usuario = ID_Usuario INNER JOIN comisiones ON comisiones.FK_Cliente = ID_Cliente AND clientes.Tipo != 'Administrador' GROUP BY ID_Cliente";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = "<span class='imagenesCliente' style='background-image: url(vistas/assets/media/users/Default/user3.png);'></span>";
						if($row[$i]['Foto'] != ""){
							$foto = '<span class="imagenesCliente" style="background-image: url('."'".'../inicio/vistas/assets/media/users/'.$row[$i]['FK_Usuario'].'_'.$row[$i]['Foto']."'".');"></span><br>';
						}

						$arreglo['data'][$i] = array("DT_RowId"=> $row[$i]['ID_Cliente'], 'Date' => strtotime($row[$i]['toDate']), 'Fecha' => $row[$i]['Fecha_Alta'], 'Foto' => $foto, 'Nombre' => $row[$i]['Cliente'], 'Correo' => $row[$i]['Correo'], 'Telefono' => $row[$i]['Telefono']);
					}	

					echo json_encode($arreglo);	
				}
			}
		}else if($tipo == '5'){
			$tabla = '';
			$query = "SELECT comisiones.*, comisiones.Precio AS PrecioEnviada, predios.Nombre AS NombrePredio, ventas.Cantidad AS CantidadVenta, ventas.Precio AS PrecioVenta, (SELECT CONCAT(clientes.Nombre,' ',clientes.Primer_Apellido,' ',clientes.Segundo_Apellido) FROM clientes WHERE ID_Cliente = comisiones.FK_Vendedor) AS Vendedor, (SELECT CONCAT(clientes.Nombre,' ',clientes.Primer_Apellido,' ',clientes.Segundo_Apellido) FROM clientes WHERE ID_Cliente = ventas.FK_Vendedor) AS VendedorVenta, Tipo_Venta FROM comisiones INNER JOIN ventas ON FK_Venta = ID_Venta INNER JOIN predios ON FK_Predio = ID_Predio WHERE FK_Cliente = '$idcliente' AND FK_Compra = 0 AND comisiones.Tipo = 'Venta' AND comisiones.Estatus = '0' AND ventas.Tipo_Venta = 'Administrador'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						if ($row[$i]["Tipo_Venta"] == "Administrador") {
							$Ventas = 'Vendedor: Administrador <br> Predio: '.$row[$i]["NombrePredio"].'<br> Plantas: <span class="cantidad">'.$row[$i]["CantidadVenta"].'</span><br> Precio de venta: <span class="dinero">'.$row[$i]["PrecioVenta"].'</span>';
						}else{
							$Ventas = 'Vendedor: '.$row[$i]["VendedorVenta"].' <br> Predio: '.$row[$i]["NombrePredio"].'<br> Plantas: <span class="cantidad">'.$row[$i]["CantidadVenta"].'</span><br> Precio de venta: <span class="dinero">'.$row[$i]["PrecioVenta"].'</span>';
						}
						$tabla.= '
						<tr attrid="'.$row[$i]["ID_Comision"].'">
							<td>'.$row[$i]["Fecha_Registro"].'</td>
							<td>'.$row[$i]["Vendedor"].'</td>
							<td class="text-center">'.$Ventas.'</td>
							<td>'.$row[$i]["Cantidad"].'</td>
							<td>'.$row[$i]["PrecioEnviada"].'</td>
						</tr>';
					}	
					echo $tabla;	
				}
			}
		}else if($tipo == 'ClientesVentas'){
			$query = "SELECT ID_Cliente, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS Cliente, Telefono, Fecha_Alta AS toDate,DATE_FORMAT(Fecha_Alta, '%d-%m-%Y %r') AS Fecha_Alta, ID_Usuario, Correo, FK_Usuario, Foto FROM clientes INNER JOIN usuarios ON FK_Usuario = ID_Usuario AND clientes.Tipo != 'Administrador'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = "<span class='imagenesCliente' style='background-image: url(vistas/assets/media/users/Default/user3.png);'></span>";
						if($row[$i]['Foto'] != ""){
							$foto = '<span class="imagenesCliente" style="background-image: url('."'".'../inicio/vistas/assets/media/users/'.$row[$i]['FK_Usuario'].'_'.$row[$i]['Foto']."'".');"></span><br>';
						}

						$arreglo['data'][$i] = array("DT_RowId"=> $row[$i]['ID_Cliente'], 'Date' => strtotime($row[$i]['toDate']), 'Fecha' => $row[$i]['Fecha_Alta'], 'Foto' => $foto, 'Nombre' => $row[$i]['Cliente'], 'Correo' => $row[$i]['Correo'], 'Telefono' => $row[$i]['Telefono']);
					}	

					echo json_encode($arreglo);	
				}
			}
		}else if($tipo == 'consultarSaldo'){
			$query = "SELECT * FROM saldos WHERE FK_Cliente = '$idcliente'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo $row[0]["Total_Dinero"]."~".$row[0]["Regalado"];
				}
			}
		}else if($tipo == 'ConsultarVentas'){
			$query = "SELECT ID_Venta, Fecha_Registro, predios.Nombre AS NombrePredio, CONCAT(clientes.Nombre,' ',clientes.Primer_Apellido,' ',clientes.Segundo_Apellido) AS Vendedor, FK_Vendedor, ventas.Cantidad AS Cantidad, ventas.Precio AS Precio, Tipo_Venta, (SELECT SUM(Cantidad) FROM compras WHERE FK_Venta=ID_Venta) AS CantidadCompras FROM ventas INNER JOIN clientes ON ventas.FK_Vendedor = clientes.ID_Cliente INNER JOIN predios ON FK_Predio = ID_Predio WHERE ventas.Estatus = '0'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						if ($row[$i]['Tipo_Venta'] == "Administrador") {
							$Vendedor = 'Inversiones JIMA';
						}else{
							$Vendedor = $row[$i]['Vendedor'];
						}
						$arreglo['data'][$i] = array("DT_RowId"=> $row[$i]['ID_Venta'], 'Date' => strtotime($row[$i]['Fecha_Registro']), 'Fecha' => $row[$i]['Fecha_Registro'], 'Vendedor' => $Vendedor, 'Predio' => $row[$i]['NombrePredio'], 'Cantidad' => ($row[$i]['Cantidad'] - $row[$i]['CantidadCompras']), 'Precio' => $row[$i]['Precio']);
					}
				}
				echo json_encode($arreglo);	
			}
		}else if($tipo == 'GenerarCompra'){
			$fecha = date('Y-m-d H:i:s'); 
			$cliente = $omodelo->link->real_escape_string($idcliente);
			$venta = $omodelo->link->real_escape_string($idventa);
			$plantas = (int) $omodelo->link->real_escape_string($Plantas); 
			
			if($plantas > 0){
				$query = "SELECT ID_Venta, Precio, ventas.Cantidad AS Cantidad, Tipo, Tipo_Venta, FK_Predio, Fecha_Plantar, (SELECT SUM(Cantidad) FROM compras WHERE FK_Venta=ID_Venta) AS CantidadCompras, (SELECT Total_Dinero FROM saldos WHERE FK_Cliente = '".$cliente."') AS Total_Dinero, (SELECT Regalado FROM saldos WHERE FK_Cliente = '".$cliente."') AS Regalado, (SELECT COUNT(*) FROM compras INNER JOIN ventas ON FK_Venta=ID_Venta WHERE Tipo_Venta='Administrador' AND FK_Comprador='".$cliente."') AS NumCompras FROM ventas INNER JOIN predios ON FK_Predio=ID_Predio WHERE ID_Venta = '$venta'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas; 
					
				if($row == "si"){
					echo "Error 1: ".mysqli_error($omodelo->link);
				}else{
					if($numerofilas > 0){
						$disponibles = ((int) $row[0]["Cantidad"]) - ((int) $row[0]["CantidadCompras"]);

						if ($plantas <= $disponibles) {
							$precio = (double) $row[0]['Precio'];

							if($row[0]['Tipo_Venta'] == 'Administrador'){
								if($plantas > 2000 && $plantas <= 7000){
									$precio -= 2;
								}else if($plantas > 7000 && $plantas <= 14000){
									$precio -= 3;
								}else if($plantas > 14000 && $plantas <= 35000){
									$precio -= 5;
								}else if($plantas > 35000 && $plantas <= 70000){
									$precio -= 6;
								}else if($plantas > 70000 && $plantas <= 140000){
									$precio -= 8;
								}else if($plantas > 140000){
									$precio -= 10;
								}
							}

							$kilos=0;$total=0;$Regalado=0;
							$nuevafecha = strtotime('+3 year', strtotime($row[0]['Fecha_Plantar']));
							if(strtotime($fecha) >= $nuevafecha){
								$tabulador = $omodelo->_tabuladores($row[0]['FK_Predio'], $row[0]['Fecha_Plantar'], 0);
								$kilos = (double) $tabulador['Kilos'];
							}
							$total = $plantas * $precio;
							
							if($total < 10000 && $row[0]['NumCompras'] == '0' && $row[0]['Tipo_Venta'] == 'Administrador'){
								echo "Error 2 Menor a 10000";	
							}else{
								if ($row[0]['Tipo_Venta'] == 'Administrador') {
									$MiSaldo = (double) $row[0]["Total_Dinero"] + (double) $row[0]["Regalado"];

									if (((double) $row[0]["Regalado"] - $total) > 0) {
										$Regalado =  $total;
									}else{
										$Regalado = (double) $row[0]["Regalado"];
									}

								}else{
									$MiSaldo = (double) $row[0]["Total_Dinero"];
								}
								
								if ($MiSaldo >= $total) {

									$query1 = "INSERT INTO compras SET Fecha_Registro = '$fecha', FK_Venta = '$venta', Cantidad = '$plantas', Tipo = '".$row[0]['Tipo']."', Precio = '$precio', Kilos = '$kilos', Total = '$total', Regalado = '$Regalado', FK_Comprador = '".$cliente."'";
									$error = $omodelo->_insertar($query1);

									if ($error == "si") {
										echo "Error 3: ".mysqli_error($omodelo->link); 
									}else{
										$id = mysqli_insert_id($omodelo->link);
										echo "Correcto";
										$omodelo->movimiento($query1, $cliente);
									}
								}else{
									echo "Error 4 Saldo insuficiente";
								}
							}
						}else{
							echo "Error 5 Plantas insuficientes"; 
						}	
					}else{
						echo "Error 6 No existen registros";
					}
				}
			}else{
				echo "Error 7 Plantas 0";
			}
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);
		
		$id = $omodelo->link->real_escape_string($id);
		$usuario = $omodelo->link->real_escape_string($usuario);
		$correo = $omodelo->link->real_escape_string($email);
		$nombre = $omodelo->link->real_escape_string($nombre);
		$apellidoP = $omodelo->link->real_escape_string($apellidoP);
		$apellidoS = $omodelo->link->real_escape_string($apellidoS);
		$telefono = $omodelo->link->real_escape_string($telefono);
		$sexo = $omodelo->link->real_escape_string($sexo);
		$nacionalidad = $omodelo->link->real_escape_string($nacionalidad);
		$nombreDNI = $omodelo->link->real_escape_string($nombreDNI);
		$dni = $omodelo->link->real_escape_string($dni);
		$fechaN = $omodelo->link->real_escape_string($fechaN);
		$pais = $omodelo->link->real_escape_string($pais);
		$estado = $omodelo->link->real_escape_string($estado);
		$ciudad = $omodelo->link->real_escape_string($ciudad);
		$colonia = $omodelo->link->real_escape_string($colonia);
		$direccion = $omodelo->link->real_escape_string($direccion);
		$sexoBen = $omodelo->link->real_escape_string($sexoBen);
		$parentesco = $omodelo->link->real_escape_string($parentesco);
		$beneficiario = $omodelo->link->real_escape_string($beneficiario);
		$cp = $omodelo->link->real_escape_string($cp);
		$activo = $omodelo->link->real_escape_string($activo);
		$temporal = $omodelo->link->real_escape_string($temporal);
		$contrasena = $omodelo->link->real_escape_string($contrasena);
		$Referido = $omodelo->link->real_escape_string($Referido);
		$bono = $omodelo->link->real_escape_string($bono);


		$query = "UPDATE clientes SET Nombre='$nombre', Primer_Apellido='$apellidoP', Segundo_Apellido='$apellidoS', Telefono='$telefono', Nombre_DNI='$nombreDNI', DNI='$dni',Fecha_Nacimiento='$fechaN', Sexo='$sexo', Nacionalidad='$nacionalidad', Pais='$pais', Estado='$estado', Ciudad='$ciudad', Colonia='$colonia', Direccion='$direccion', CP='$cp', SexoBe = '$sexoBen', Parentesco = '$parentesco', Beneficiario = '$beneficiario' WHERE ID_Cliente = '$id' AND FK_Usuario = '$usuario' AND Tipo != 'Administrador'";
		$error = $omodelo->_insertar($query);

		if ($error == "si") {
			echo "Error 1: ".mysqli_error($omodelo->link);
		}else{
			$contra = '';
	 		if($cambiar == '1'){
	 			$contra = ", Tipo_Login = 1, Contrasena = MD5('$contrasena'), Intentos = 0";
	 		}

	 		$ref = '';
	 		if($Referido != '0'){
	 			$ref = ", FK_Referido = '$Referido'";
	 		}

	 		if ($bono > 0) {
	 			$queryreg = "UPDATE saldos SET Regalado = '$bono' WHERE FK_Cliente = '$id'";
				$errorreg = $omodelo->_insertar($queryreg);

				if ($errorreg == "si") {
					echo "Error bono: ".mysqli_error($omodelo->link);
				}
	 		}

			$query1 = "UPDATE usuarios SET Correo = '$correo', Activo = '$activo', Temporal = '$temporal' $contra $ref WHERE ID_Usuario = '$usuario'";
			$error1 = $omodelo->_insertar($query1);

			if ($error1 == "si") {
				echo "Error 2: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);	
		}
	}
}
?>