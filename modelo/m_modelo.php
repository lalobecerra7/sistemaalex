<?php
date_default_timezone_set('America/Mexico_City');
include "modelo/config/conexion.php";

class m_modelo extends conexion{
	public $link;
	public $numerofilas;
	public $error;

	public function __construct()
    {
        $this->link = conexion::__construct();
    }
	// METODO PARA INSERTAR, MODIFICAR Y ELIMINAR REGISTROS
	public function _insertar($query){
		$result = $this->link->query($query);
		$this->numerofilas = $this->link->affected_rows;
		if (!$result){
			$error = 'si';
		}else{
			$error = 'no';
		}
		return $error;

		$this->link->$con->close();
	}

	// METODO PARA OBTENER RESULTADOS DE LA BD
	public function _consultar($query){
		$result = $this->link->query($query);
		$this->numerofilas = $result->num_rows;
		if (!$result) {
			$this->error = 'si';
			echo "Se produjo un error en el modelo: ".mysqli_error($this->link);
		}
        else{
			$this->error = 'no';
			while ($resultado[] = $result->fetch_array());
		}
		return $resultado;

		$this->link->$con->close();
	}

	
	public function permisos(){
		$permisosMo = null;
		$query = "SELECT Permisos, Tipo FROM usuarios_administrador WHERE ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
		$row = $this->_consultar($query);
		$numerofilas = $this->numerofilas;

		if ($row == "si") {
			echo "Error: ".mysqli_error($this->link);
		}else{
			if($numerofilas > 0){
				if($row[0]['Tipo'] == 'Administrador'){
					$permisosMo = 'Administrador';
				}else{
					$modulos = explode('~', $row[0]['Permisos']);
					for ($i=0; $i < count($modulos); $i++) {
						$cadena = explode(',', $modulos[$i]);
						$nombreModu = $cadena[0];
						unset($cadena[0]);
						$permisosMo[$nombreModu] = $cadena;
					}
				}
			}
		}
		return $permisosMo;
	}

	public function movimiento($sql, $id){
		$sql = $this->link->real_escape_string($sql);

		$query="INSERT INTO movimientos SET Descripcion = 'Admin: $sql', FK_Usuario = '$id'";
		//$query="INSERT INTO movimientos SET Descripcion = '$sql', '$dataArray->geoplugin_request', '$dataArray->geoplugin_countryName', '$dataArray->geoplugin_regionName', '$user_browser', '$os_platform', '$fecha', '$_SERVER[HTTP_USER_AGENT]', '$id')";
		$error = $this->_insertar($query);

		if($error == 'si'){
			echo "Error Movimientos: ".mysqli_error($this->link);
		}
	}

	public function _notificacion($destino, $titulo, $mensaje)
	{
		$payload = array(
			'to' => $destino,
			'sound' => 'default',
			'title' => $titulo,
			'body' => $mensaje,
		);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($payload),
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"Accept-Encoding: gzip, deflate",
				"Content-Type: application/json",
				"cache-control: no-cache",
				"host: exp.host"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "CURL Error #:" . $err;
		} /*else {
			echo $response;
		}*/
	}
}

?>
