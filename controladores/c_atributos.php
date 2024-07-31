<?php
class atributos {

	public function _consultar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		
		$buscar = $omodelo->link->real_escape_string($buscar);
	    $limit = $omodelo->link->real_escape_string($limit);
	    $pagina = $omodelo->link->real_escape_string($pagina);
	    $ordenColumna = $omodelo->link->real_escape_string($ordenColumna);
	    $orden = $omodelo->link->real_escape_string($orden);
	    $arreglo = array();

	    $busqueda = '';
	    if (trim($buscar) != '') {
	      	$separa = explode(' ', trim($buscar));
	      	$busqueda = 'WHERE ';
	      	for ($i = 0; $i < count($separa); $i++) {
	        	$busqueda .= "CONCAT(atributos.Nombre, atributos.Descripcion, valores.Nombre, Puntos) REGEXP '" . $separa[$i] . "'";
	        	if ($i < (count($separa) - 1)) {
	          		$busqueda .= ' AND ';
	        	}
	      	}
	    }

	    $query = "SELECT ID_Atributo, atributos.Nombre AS Nombre, atributos.Descripcion AS Descripcion, FK_Valor, atributos.Imagen AS Imagen, valores.Nombre AS Valor, Puntos, (SELECT COUNT(*) FROM atributos INNER JOIN valores ON FK_Valor = ID_Valor $busqueda) AS Num FROM atributos INNER JOIN valores ON FK_Valor = ID_Valor $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
	    $row = $omodelo->_consultar($query);
	    $numerofilas = $omodelo->numerofilas;

	    if ($row == 'si') {
	      	echo "Error: " . mysqli_error($omodelo->link);
	    } else {
	      	if ($numerofilas > 0) {
	        	for ($i = 0; $i < $numerofilas; $i++) {
	        		$img = '<a href="vistas/assets/img/fondo.jpg" data-fancybox="images"><div style="background-image: url(' . "'" . 'vistas/assets/img/fondo.jpg' . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
		            	</div></a>';

		          	if ($row[$i]['Imagen'] != '' && file_exists('vistas/assets/img/atributos/' . $row[$i]['Imagen'])) {
		            	$img = '<a href="vistas/assets/img/atributos/'.$row[$i]['Imagen'].'" data-fancybox="images"><div style="background-image: url(' . "'" . 'vistas/assets/img/atributos/' . $row[$i]['Imagen'] . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
		              </div></a>';
		        	}

	          		$bModificar = '';
	          		$bEliminar = '';
	          		if ($_SESSION['user_heroe_admin']['Tipo_Usuario'] == 'Admin' || $_SESSION['user_heroe_admin']['Permisos']['Atributos'][3] == '1') {
	            		$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarAtributo" title="Modificar" attrID="'.$row[$i]['ID_Atributo'].'"><i class="fas fa-pencil"></i></button>';
	          		}
	          		if ($_SESSION['user_heroe_admin']['Tipo_Usuario'] == 'Admin' || $_SESSION['user_heroe_admin']['Permisos']['Atributos'][4] == '1') {
	            		$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarAtributo" title="Eliminar" attrID="'.$row[$i]['ID_Atributo'].'"><i class="fas fa-trash"></i></button>';
	          		}

	          		$arreglo['data'][$i] = array(
	            		'ID' => $row[$i]['ID_Atributo'],
	            		'Nombre' => $img.$row[$i]['Nombre'],
	            		'Descripcion' => $row[$i]['Descripcion'],
	            		'Valor' => '<span attrID="'.$row[$i]['FK_Valor'].'">'.$row[$i]['Valor'].'</span>',
	            		'Puntos' => '<span class="cantidad">'.$row[$i]['Puntos'].'</span>',
	            		'Acciones' => $bModificar.' '.$bEliminar
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
		$fecha = date("Y-m-d H:i:s");
		
		$nombreAtributo = trim($omodelo->link->real_escape_string($nombreAtributo));
      	$valorAtributo = $omodelo->link->real_escape_string($valorAtributo);
      	$descripcionAtributo = trim($omodelo->link->real_escape_string($descripcionAtributo));
      	$puntosAtributo = $omodelo->link->real_escape_string($puntosAtributo);

      	$omodelo->_insertar("START TRANSACTION;");
      	$query = "INSERT INTO atributos SET Nombre = '$nombreAtributo', Descripcion = '$descripcionAtributo', FK_Valor = '$valorAtributo', Puntos = '$puntosAtributo', Imagen = ''";
      	$error = $omodelo->_insertar($query);
      	$status = 0;

      	if ($error == 'si') {
        	echo "Error: " . mysqli_error($omodelo->link);
        	$status = 1;
      	}else {
        	$id = $omodelo->link->insert_id;

        	$nombreImg = '';
        	$ruta = '';
        	$rutaProvisional = '';
        	$carpeta = 'vistas/assets/img/atributos/';
        	if ($_FILES['imagenAtributo']['size'] > 0 && $_FILES['imagenAtributo']['error'] == 0) {
          		$file = $_FILES['imagenAtributo'];
          		$nombreImg = $file['name'];
          		$tipoImg = $file['type'];
          		$rutaProvisional = $file['tmp_name'];
          		$sizeImg = $file['size'];

          		if ($tipoImg != 'image/jpeg' && $tipoImg != 'image/jpg' && $tipoImg != 'image/png' && $tipoImg != '') {
            		echo 'Error 2 formato';
            		$status = 1;
          		} else if($sizeImg > (1024*1024*10)){
            		echo 'Error 3 peso';
            		$status = 1;
          		}else {
            		$ruta = $carpeta . $id . '_' . $nombreImg;
          		}

          		if ($status == 0 && $nombreImg != '') {
            		$query1 = "UPDATE atributos SET Imagen = '" . $id . '_' . $nombreImg . "'  WHERE ID_Atributo = '$id'";
            		$error1 = $omodelo->_insertar($query1);
              
            		if ($error1 == 'si') {
              			echo "Error 4: " . mysqli_error($omodelo->link);
              			$status = 1;
            		} else {
              			move_uploaded_file($rutaProvisional, $ruta);
            		}
          		}
       		}

        	if ($status == 0) {
          		echo 'Correcto';

          		$omodelo->_insertar("COMMIT;");
          		$omodelo->movimiento($query, $_SESSION['user_heroe_admin']['ID_Usuario']);
        	}else{
          		$omodelo->_insertar("ROLLBACK;");
        	}
      	}
	}

	public function _modificar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date("Y-m-d H:i:s");
		
		$id = $omodelo->link->real_escape_string(trim($id));
		$nombreAtributo = trim($omodelo->link->real_escape_string($nombreAtributo));
      	$valorAtributo = $omodelo->link->real_escape_string($valorAtributo);
      	$descripcionAtributo = trim($omodelo->link->real_escape_string($descripcionAtributo));
      	$puntosAtributo = $omodelo->link->real_escape_string($puntosAtributo);

      	$omodelo->_insertar("START TRANSACTION;");
      	$query = "UPDATE atributos SET Nombre = '$nombreAtributo', Descripcion = '$descripcionAtributo', FK_Valor = '$valorAtributo', Puntos = '$puntosAtributo' WHERE ID_Atributo = '$id'";
      	$error = $omodelo->_insertar($query);
      	$status = 0;

      	if ($error == 'si') {
        	echo "Error: " . mysqli_error($omodelo->link);
        	$status = 1;
      	}else {
        	$nombreImg = '';
        	$ruta = '';
        	$rutaProvisional = '';
        	$carpeta = 'vistas/assets/img/atributos/';
        	if ($_FILES['imagenAtributo']['size'] > 0 && $_FILES['imagenAtributo']['error'] == 0) {
          		$file = $_FILES['imagenAtributo'];
          		$nombreImg = $file['name'];
          		$tipoImg = $file['type'];
          		$rutaProvisional = $file['tmp_name'];
          		$sizeImg = $file['size'];

          		if ($tipoImg != 'image/jpeg' && $tipoImg != 'image/jpg' && $tipoImg != 'image/png' && $tipoImg != '') {
            		echo 'Error 2 formato';
            		$status = 1;
          		} else if($sizeImg > (1024 * 1024)){
            		echo 'Error 3 peso';
            		$status = 1;
          		}else {
            		$ruta = $carpeta . $id . '_' . $nombreImg;
          		}

          		if ($status == 0 && $nombreImg != '') {
          			$query1 = "SELECT Imagen FROM atributos WHERE ID_Atributo = '$id'";
				    $row = $omodelo->_consultar($query1);
				    $numerofilas = $omodelo->numerofilas;

				    if ($row == 'si') {
				      	echo "Error 4: " . mysqli_error($omodelo->link);
				      	$status = 1;
				    } else {
				      	if ($numerofilas > 0 && trim($row[0]['Imagen']) != '' && file_exists($carpeta.trim($row[0]['Imagen']))) {
					        unlink($carpeta.trim($row[0]['Imagen']));
				      	}

	            		$query2 = "UPDATE atributos SET Imagen = '" . $id . '_' . $nombreImg . "'  WHERE ID_Atributo = '$id'";
	            		$error1 = $omodelo->_insertar($query2);
	              
	            		if ($error1 == 'si') {
	              			echo "Error 5: " . mysqli_error($omodelo->link);
	              			$status = 1;
	            		} else {
	              			move_uploaded_file($rutaProvisional, $ruta);
	            		}
	            	}
          		}
       		}else if(trim($img) == 'vistas/assets/img/fondo.jpg'){
       			$query1 = "SELECT Imagen FROM atributos WHERE ID_Atributo = '$id'";
				$row = $omodelo->_consultar($query1);
				$numerofilas = $omodelo->numerofilas;

				if ($row == 'si') {
				    echo "Error 6: " . mysqli_error($omodelo->link);
				    $status = 1;
				} else {
				    if ($numerofilas > 0 && trim($row[0]['Imagen']) != '' && file_exists($carpeta.trim($row[0]['Imagen']))){
					    unlink($carpeta.trim($row[0]['Imagen']));
				    }
				}
       		}

        	if ($status == 0) {
          		echo 'Correcto';

          		$omodelo->_insertar("COMMIT;");
          		$omodelo->movimiento($query, $_SESSION['user_heroe_admin']['ID_Usuario']);
        	}else{
          		$omodelo->_insertar("ROLLBACK;");
        	}
      	}
	}

	public function _eliminar() {
	    $omodelo = new m_modelo();
	    extract($_POST);

	    $id = $omodelo->link->real_escape_string($id);
	    $carpeta = 'vistas/assets/img/atributos/';

	    $query = "SELECT Imagen FROM atributos WHERE ID_Atributo = '$id'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;

		if ($row == 'si') {
			echo "Error 1: " . mysqli_error($omodelo->link);
		} else {
			if ($numerofilas > 0 && trim($row[0]['Imagen']) != '' && file_exists($carpeta.trim($row[0]['Imagen']))){
				unlink($carpeta.trim($row[0]['Imagen']));
			}

			$query1 = "DELETE FROM atributos WHERE ID_Atributo = '$id'";
		    $error = $omodelo->_insertar($query1);

		    if ($error == 'si') {
		      	echo "Error 2: " . mysqli_error($omodelo->link);
		    } else {
		      	echo 'Correcto';

		      	$omodelo->movimiento($query1, $_SESSION['user_heroe_admin']['ID_Usuario']);
		    }
		}
  	}
}
?>