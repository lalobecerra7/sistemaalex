<?php
class notificaciones {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$arreglo = null;

		$query = "SELECT ID_Notificacion, clientes.Nombre AS NombreCliente, clientes.Primer_Apellido AS Primer_Apellido, clientes.Segundo_Apellido AS Segundo_Apellido, tipo_notificaciones.Nombre AS Tipo, notificaciones.Nombre AS Titulo, notificaciones.Descripcion AS Descripcion, notificaciones.Estatus AS Estatus, Enviado, Fecha_Registro AS toDate, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, Correo FROM notificaciones INNER JOIN clientes ON FK_Cliente = ID_Cliente INNER JOIN usuarios ON FK_Usuario = ID_Usuario INNER JOIN tipo_notificaciones ON FK_Tipo = ID_Tipo_Notificacion WHERE (DATE_FORMAT(Fecha_Registro, '%Y-%m-%d') >= '".$_SESSION['user_admin']['FechaIni']."' AND DATE_FORMAT(Fecha_Registro, '%Y-%m-%d') <= '".$_SESSION['user_admin']['FechaFin']."')";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas; 
			
		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$estatus = ""; $envio = "";
					if($row[$i]['Estatus'] == '1'){
						$estatus = '<span class="badge rounded-pill bg-success">Leida</span>';
					}else if($row[$i]['Estatus'] == '0'){
						$estatus = '<span class="badge rounded-pill bg-warning">Activa</span>';
					}

					if($row[$i]['Enviado'] == '0'){
						$envio = '<span class="badge rounded-pill bg-danger">Pendiente</span>';
					}else if($row[$i]['Enviado'] == '1'){
						$envio = '<span class="badge rounded-pill bg-primary">Enviada</span>';
					}

					$b1 = '';
					if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_notificaciones'][3] == '1'){
						$b1 = '<button type="button" class="btn btn-danger btn-sm bEliminarNotificacion" attrID="'.$row[$i]['ID_Notificacion'].'"><i class="fas fa-trash"></i></button>';
					}else{
						$b1 = '<button type="button" class="btn btn-light"> ... </button>';
					}

					$arreglo['data'][$i] = array("DT_RowId"=> 'NOTI'.$row[$i]['ID_Notificacion'], 'ID' => $row[$i]['ID_Notificacion'], 'Date' => strtotime($row[$i]['toDate']), 'Fecha' => $row[$i]['Fecha_Registro'], 'Usuario' => '<b>Nombre: </b>'.$row[$i]['NombreCliente'].' '.$row[$i]['Primer_Apellido'].' '.$row[$i]['Segundo_Apellido'].'<br><b>Correo: </b>'.$row[$i]['Correo'], 'Tipo' => $row[$i]['Tipo'], 'Titulo' => $row[$i]['Titulo'], 'Descripcion' => utf8_encode($row[$i]['Descripcion']), 'Estatus' => $estatus, 'Enviada' => $envio, 'Accion' => $b1);
				}
			}	
		}
		
		echo json_encode($arreglo);
	}

	public function _eliminar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == '1'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "DELETE FROM notificaciones WHERE ID_Notificacion='$id' AND FK_Cliente = '".$_SESSION['user_admin']['ID_Cliente']."'";
			$error= $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);;
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
			}
		}else if($tipo == '2'){
			$query = "DELETE FROM notificaciones WHERE FK_Cliente = '".$_SESSION['user_admin']['ID_Cliente']."'";
			$error= $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);;
			}else{
				echo "Correcto";
				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
			}
		}if($tipo == '3'){
			$id = $omodelo->link->real_escape_string($id);

			$query = "DELETE FROM notificaciones WHERE ID_Notificacion='$id'";
			$error= $omodelo->_insertar($query);

			if($error == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);;
			}else{
				echo "Correcto";

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
			}
		}
	}

	public function _detalles(){
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);
		$arreglo = null;

		if($tipo == '1'){
			$query = "SELECT ID_Cliente, CONCAT(Nombre,' ',Primer_Apellido,' ',Segundo_Apellido) AS Cliente, Telefono, Fecha_Alta AS toDate,DATE_FORMAT(Fecha_Alta, '%d-%m-%Y %r') AS Fecha_Alta, ID_Usuario, Correo, Foto FROM clientes INNER JOIN usuarios ON FK_Usuario = ID_Usuario AND Tipo != 'Administrador'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 

			if ($row == "si") {
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$foto = "<span class='imagenesCliente' style='background-image: url(vistas/assets/media/users/Default/user3.png);'></span>";
						if($row[$i]['Foto'] != ""){
							$foto = '<span class="imagenesCliente" style="background-image: url('."'".'../inicio/vistas/assets/media/users/'.$row[$i]['ID_Usuario'].'_'.$row[$i]['Foto']."'".');"></span>';
						}

						$arreglo['data'][$i] = array("DT_RowId"=> $row[$i]['ID_Cliente'], 'Date' => strtotime($row[$i]['toDate']), 'Fecha' => $row[$i]['Fecha_Alta'], 'Foto' => $foto, 'Cliente' => $row[$i]['Cliente'], 'Correo' => $row[$i]['Correo'], 'Telefono' => $row[$i]['Telefono'], 'Seleccionar' => '<div class="text-center"><input class="form-check-input bCheckNoti" type="checkbox" attrID="'.$row[$i]['ID_Cliente'].'"></div>');
					}	

					echo json_encode($arreglo);	
				}
			}	
		}
	}

	public function _insertar(){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d H:i:s');
		$todos = $omodelo->link->real_escape_string($todos);
		$titulo = $omodelo->link->real_escape_string($titulo);
		$descripcion = $omodelo->link->real_escape_string($descripcion);

		if($todos == '0'){
			$clientes = json_decode($clientes);

			foreach($clientes as $cli){
			    $query = "INSERT INTO notificaciones SET FK_Cliente = '$cli', FK_Tipo = '6', Nombre = '$titulo', Descripcion = '$descripcion', Enviado = '1', Fecha_Registro = '$fecha'";
			    $error = $omodelo->_insertar($query);

			    if ($error == "si") {
					echo "Error: ".mysqli_error($omodelo->link);
				}
			}

			echo "Correcto";
		}else{
			$query = "SELECT ID_Cliente FROM clientes WHERE Tipo = 'Cliente'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas; 
				
			if ($row == "si") {
				echo "Error 1: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for($i=0; $i<$numerofilas; $i++){
						$query1 = "INSERT INTO notificaciones SET FK_Cliente = '".$row[$i]['ID_Cliente']."', FK_Tipo = '6', Nombre = '$titulo', Descripcion = '$descripcion', Enviado = '1', Fecha_Registro = '$fecha'";
					    $error1 = $omodelo->_insertar($query1);

					    if ($error1 == "si") {
							echo "Error 2: ".mysqli_error($omodelo->link);
						}	

						echo "Correcto";
					}
				}
			}
		}
	}

	public function _modificar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$query = "UPDATE notificaciones SET Estatus='0' WHERE FK_Cliente='".$_SESSION['user_admin']['ID_Cliente']."'";
		$error= $omodelo->_insertar($query);

		if($error == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);;
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Cliente']);
		}
	}
}
?>
