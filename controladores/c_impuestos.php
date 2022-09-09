
<?php
class impuestos {

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
				$busqueda .= "CONCAT(ID_Impuesto, Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase, Ticket, Producto, Predeterminado) REGEXP '".$separa[$i]."'";
				if($i < (count($separa)-1)){
					$busqueda .= ' AND ';
				}
			}
		}

		$query = "SELECT ID_Impuesto, Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase, Ticket, Producto, Predeterminado, (SELECT COUNT(*) FROM impuestos $busqueda) AS Num FROM impuestos $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET ".(($pagina * $limit) - $limit);
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if($row == 'si'){
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			if($numerofilas > 0){
				for($i=0; $i<$numerofilas; $i++){
					$detalles = ""; $tipo = "";$predeterminado = "";

					if ($row[$i]['Clave_CFDI'] != "") {
						$detalles .= "Clave CFDI: ".$row[$i]['Clave_CFDI']."<br>"; 
					}

					if ($row[$i]['Tipo_Factor'] != "") {
						$detalles .= "Tipo de factor: ".$row[$i]['Tipo_Factor']."<br>"; 
					}

					if ($row[$i]['Clase'] != "") {
						$detalles .= "Clase: ".$row[$i]['Clase']."<br>"; 
					}

					if ($detalles == "") {
						$detalles = "No hay datos registrados"; 
					}

					if ($row[$i]['Ticket'] == 1) {
						$tipo .= "Ticket <br>";
					}

					if ($row[$i]['Producto'] == 1) {
						$tipo .= "Producto <br>";
					}

					if ($tipo == "") {
						$tipo = "No hay datos seleccionados";
					}

					if ($row[$i]['Predeterminado'] == "1") {
						$predeterminado = "checked";
					}
					
					$arreglo['data'][$i] = array(
						'ID' => $row[$i]['ID_Impuesto'],
						'Nombre' => $row[$i]['Nombre'],
						'Porcentaje' => number_format($row[$i]['Porcentaje'], 2)."%",
						'Detalles' => $detalles,
						'Tipo de Impuesto' => $tipo,
						'Predeterminado' => '<input class="form-check-input" type="checkbox" id="ImpuestoPredeterminado" name="ImpuestoPredeterminado" '.$predeterminado.' attrid="'.$row[$i]['ID_Impuesto'].'">',
						'Acciones' => '<button class="btn btn-primary btn-sm mb-2" id="ModificarImpuesto" attrid="'.$row[$i]['ID_Impuesto'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm" id="EliminarImpuesto" attrid="'.$row[$i]['ID_Impuesto'].'" nombre="'.$row[$i]['Nombre'].'"><i class="fas fa-trash"></i></button>',
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
		$Nombre = $omodelo->link->real_escape_string($Nombre);
		$Porcentaje = $omodelo->link->real_escape_string($Porcentaje);
		$Clave = $omodelo->link->real_escape_string($Clave);
		$Clase = $omodelo->link->real_escape_string($Clase);
		$Tipo = $omodelo->link->real_escape_string($Tipo);
		$Ticket = $omodelo->link->real_escape_string($Ticket);
		$Producto = $omodelo->link->real_escape_string($Producto);
		$query = "INSERT INTO impuestos SET Nombre = '$Nombre', Porcentaje = '$Porcentaje', Clave_CFDI = '$Clave', Tipo_Factor = '$Tipo', Clase = '$Clase', Ticket = '$Ticket', Producto = '$Producto', Predeterminado = '0'";
		$row = $omodelo->_insertar($query);

		if ($row == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$IDImpuesto = $omodelo->link->real_escape_string($IDImpuesto);
		$Nombre = $omodelo->link->real_escape_string($Nombre);
		$Porcentaje = $omodelo->link->real_escape_string($Porcentaje);
		$Clave = $omodelo->link->real_escape_string($Clave);
		$Clase = $omodelo->link->real_escape_string($Clase);
		$Tipo = $omodelo->link->real_escape_string($Tipo);
		$Ticket = $omodelo->link->real_escape_string($Ticket);
		$Producto = $omodelo->link->real_escape_string($Producto);
		$query = "UPDATE impuestos SET Nombre = '$Nombre', Porcentaje = '$Porcentaje', Clave_CFDI = '$Clave', Tipo_Factor = '$Tipo', Clase = '$Clase', Ticket = '$Ticket', Producto = '$Producto'WHERE ID_Impuesto = '$IDImpuesto'";
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
		$IDImpuesto =  $omodelo->link->real_escape_string($IDImpuesto);

		$query = "DELETE FROM impuestos WHERE ID_Impuesto='$IDImpuesto'";
		$error = $omodelo->_insertar($query);
			
		if ($error == "si") {
			echo "Error: ".mysqli_error($omodelo->link);
		}else{
			echo "Correcto";
			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
		}
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		if ($tipo == "ConsultarImpuesto") {
			$IDImpuesto =  $omodelo->link->real_escape_string($IDImpuesto);

			$query = "SELECT ID_Impuesto, Nombre, Porcentaje, Clave_CFDI, Tipo_Factor, Clase, Ticket, Producto, Predeterminado FROM impuestos WHERE ID_Impuesto = '$IDImpuesto'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					echo json_encode($row[0]);
				}
			}
		}else if($tipo == "ImpuestoPredeterminado"){
			$IDImpuesto =  $omodelo->link->real_escape_string($IDImpuesto);
			$Valor =  $omodelo->link->real_escape_string($Valor);

			$query = "UPDATE impuestos SET Predeterminado = '$Valor' WHERE ID_Impuesto = '$IDImpuesto'";
			$error = $omodelo->_insertar($query);
			if($error == 'si'){
				echo "Error: ".mysqli_error($omodelo->link);
			}else{
				echo "Correcto";
			}
		}
	}

}
?>
