<?php
class empleados {

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
	        	$busqueda .= "CONCAT(DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r'), areas.Nombre, Puesto, empleados.Nombre, Primer_Apellido, Segundo_Apellido, Email, Estatus, Puntos) REGEXP '" . $separa[$i] . "'";
	        	if ($i < (count($separa) - 1)) {
	          		$busqueda .= ' AND ';
	        	}
	      	}
	    }

	    $query = "SELECT ID_Empleado, areas.Nombre AS Area, Puesto, empleados.Nombre AS Nombre, Primer_Apellido, Segundo_Apellido, Email, Estatus, Foto, Puntos, Fecha_Registro AS Fecha, DATE_FORMAT(Fecha_Registro, '%d-%m-%Y %r') AS Fecha_Registro, (SELECT COUNT(*) FROM empleados INNER JOIN areas ON FK_Area = ID_Area $busqueda) AS Num FROM empleados INNER JOIN areas ON FK_Area = ID_Area $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
	    $row = $omodelo->_consultar($query);
	    $numerofilas = $omodelo->numerofilas;

	    if ($row == 'si') {
	      	echo "Error: " . mysqli_error($omodelo->link);
	    } else {
	      	if ($numerofilas > 0) {
	        	for ($i = 0; $i < $numerofilas; $i++) {
	        		$foto = '<a href="vistas/assets/img/default.png" data-fancybox="images"><div style="background-image: url(' . "'" . 'vistas/assets/img/default.png' . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
		            	</div></a>';

		          	if ($row[$i]['Foto'] != '' && file_exists('vistas/assets/img/empleados/' . $row[$i]['Foto'])) {
		            $foto = '<a href="vistas/assets/img/empleados/'.$row[$i]['Foto'].'" data-fancybox="images"><div style="background-image: url(' . "'" . 'vistas/assets/img/empleados/' . $row[$i]['Foto'] . "'" . '); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;">
		              </div></a>';
		        	}

	          		$bModificar = '';
	          		$bEliminar = '';
	          		
	            		$bModificar = '<button type="button" class="btn btn-sm btn-warning bModificarEmpleado" title="Modificar" attrID="'.$row[$i]['ID_Empleado'].'" foto="'.$row[$i]['Foto'].'"><i class="fas fa-pencil"></i></button>';
	          		
	          		
	            		$bEliminar = '<button type="button" class="btn btn-sm btn-danger bEliminarEmpleado" title="Eliminar" attrID="'.$row[$i]['ID_Empleado'].'" foto="'.$row[$i]['Foto'].'"><i class="fas fa-trash"></i></button>';
	          		

	          		$estatus = '<span class="badge badge-pill badge-success">Desbloqueado</span>';
	          		if($row[$i]['Estatus'] == 'Bloqueado'){
	            		$estatus = '<span class="badge badge-pill badge-danger">Bloqueado</span>';
	          		}

	          		$arreglo['data'][$i] = array(
	            		'ID' => $row[$i]['ID_Empleado'],
	            		'Fecha' => $foto.$row[$i]['Fecha_Registro'],
	            		'Nombre' => $row[$i]['Nombre'],
	            		'Primer_Apellido' => $row[$i]['Primer_Apellido'],
	            		'Segundo_Apellido' => $row[$i]['Segundo_Apellido'],
	            		'Email' => $row[$i]['Email'],
	            		'Area' => $row[$i]['Area'],
	            		'Puesto' => $row[$i]['Puesto'],
	            		'Estatus' => $estatus,
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
		
		$nombreEmpleado = $omodelo->link->real_escape_string(trim($nombreEmpleado));
      	$primerApellidoEmpleado = $omodelo->link->real_escape_string(trim($primerApellidoEmpleado));
      	$segundoApellidoEmpleado = $omodelo->link->real_escape_string(trim($segundoApellidoEmpleado));
      	$areaEmpleado = $omodelo->link->real_escape_string($areaEmpleado);
      	$puestoEmpleado = $omodelo->link->real_escape_string($puestoEmpleado);
      	$ID_PREmpleado = $omodelo->link->real_escape_string(trim($ID_PREmpleado));
      	$emailEmpleado = $omodelo->link->real_escape_string(trim($emailEmpleado));
      	$estatusEmpleado = $omodelo->link->real_escape_string($estatusEmpleado);
      	$puntosEmpleado = $omodelo->link->real_escape_string($puntosEmpleado);
      	$contraEmpleado = password_hash($omodelo->link->real_escape_string($contraEmpleado), PASSWORD_BCRYPT, ['cost' => 12]);

      	$omodelo->_insertar("START TRANSACTION;");
      	$query = "INSERT INTO empleados SET FK_Area = '$areaEmpleado', Puesto = '$puestoEmpleado', Nombre = '$nombreEmpleado', Primer_Apellido = '$primerApellidoEmpleado', Segundo_Apellido = '$segundoApellidoEmpleado', ID_PR = '$ID_PREmpleado', Email = '$emailEmpleado', Contrasena = '$contraEmpleado', Estatus = '$estatusEmpleado', Puntos = '$puntosEmpleado', Fecha_Registro = '$fecha'";
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
        	$carpeta = 'vistas/assets/img/empleados/';
        	if ($_FILES['fotoEmpleado']['size'] > 0 && $_FILES['fotoEmpleado']['error'] == 0) {
          		$file = $_FILES['fotoEmpleado'];
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
            		$query1 = "UPDATE empleados SET Foto = '" . $id . '_' . $nombreImg . "'  WHERE ID_Empleado = '$id'";
            		$error1 = $omodelo->_insertar($query1);
              
            		if ($error1 == 'si') {
              			echo "Error 4: " . mysqli_error($omodelo->link);
              			$status = 1;
            		} else {
              			move_uploaded_file($rutaProvisional, $ruta);
            		}
          		}
       		}

       		$agregarAreas = json_decode($agregarAreas, true);
       		foreach ($agregarAreas as $area) {
       			$area = $omodelo->link->real_escape_string($area);

       			$query1 = "INSERT INTO areas_empleados SET FK_Empleado = '$id', FK_Area = '$area'";
            	$error1 = $omodelo->_insertar($query1);
              
            	if ($error1 == 'si') {
              		echo "Error 5: " . mysqli_error($omodelo->link);
              		$status = 1;
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
		$
		
		$id = $omodelo->link->real_escape_string(trim($id));
		$nombreEmpleado = $omodelo->link->real_escape_string(trim($nombreEmpleado));
      	$primerApellidoEmpleado = $omodelo->link->real_escape_string(trim($primerApellidoEmpleado));
      	$segundoApellidoEmpleado = $omodelo->link->real_escape_string(trim($segundoApellidoEmpleado));
      	$areaEmpleado = $omodelo->link->real_escape_string($areaEmpleado);
      	$puestoEmpleado = $omodelo->link->real_escape_string($puestoEmpleado);
      	$ID_PREmpleado = $omodelo->link->real_escape_string(trim($ID_PREmpleado));
      	$emailEmpleado = $omodelo->link->real_escape_string(trim($emailEmpleado));
      	$contraEmpleado = isset($contraEmpleado) ? $omodelo->link->real_escape_string($contraEmpleado) : '';
      	$estatusEmpleado = $omodelo->link->real_escape_string($estatusEmpleado);
      	$puntosEmpleado = $omodelo->link->real_escape_string($puntosEmpleado);
      	$foto = isset($foto) ? $omodelo->link->real_escape_string($foto) : '';
      	$cambio = $omodelo->link->real_escape_string($cambio);

      	$contra = '';
      	if($cambio == 'si'){
			$encryptPassword = password_hash($contraEmpleado, PASSWORD_BCRYPT, ['cost' => 12]);
      		$contra = ", Contrasena = '$encryptPassword'";
      	}

      	$omodelo->_insertar("START TRANSACTION;");
      	$query = "UPDATE empleados SET FK_Area = '$areaEmpleado', Puesto = '$puestoEmpleado', Nombre = '$nombreEmpleado', Primer_Apellido = '$primerApellidoEmpleado', Segundo_Apellido = '$segundoApellidoEmpleado', ID_PR = '$ID_PREmpleado', Email = '$emailEmpleado', Estatus = '$estatusEmpleado', Puntos = '$puntosEmpleado' $contra WHERE ID_Empleado = '$id'";
      	$error = $omodelo->_insertar($query);
      	$status = 0;

      	if ($error == 'si') {
        	echo "Error: " . mysqli_error($omodelo->link);
        	$status = 1;
      	}else {
        	$nombreImg = '';
        	$ruta = '';
        	$rutaProvisional = '';
        	$carpeta = 'vistas/assets/img/empleados/';
        	if ($_FILES['fotoEmpleado']['size'] > 0 && $_FILES['fotoEmpleado']['error'] == 0) {
          		$file = $_FILES['fotoEmpleado'];
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
            		$query = "UPDATE empleados SET Foto = '" . $id . '_' . $nombreImg . "'  WHERE ID_Empleado = '$id'";
            		$error = $omodelo->_insertar($query);
              
            		if ($error == 'si') {
              			echo "Error 4: " . mysqli_error($omodelo->link);
              			$status = 1;
            		} else {
            			if (trim($foto) != '' && file_exists($carpeta.$foto)) {
				        	unlink($carpeta.$foto);
				      	}

              			move_uploaded_file($rutaProvisional, $ruta);
            		}
          		}
       		}

       		$eliminarAreas = json_decode($eliminarAreas, true);
       		foreach ($eliminarAreas as $area) {
       			$area = $omodelo->link->real_escape_string($area);

       			$query1 = "DELETE FROM areas_empleados WHERE FK_Empleado = '$id' AND FK_Area = '$area'";
            	$error1 = $omodelo->_insertar($query1);
              
            	if ($error1 == 'si') {
              		echo "Error 5: " . mysqli_error($omodelo->link);
              		$status = 1;
            	}
       		}

       		$agregarAreas = json_decode($agregarAreas, true);
       		foreach ($agregarAreas as $area) {
       			$area = $omodelo->link->real_escape_string($area);

       			$query1 = "INSERT INTO areas_empleados SET FK_Empleado = '$id', FK_Area = '$area'";
            	$error1 = $omodelo->_insertar($query1);
              
            	if ($error1 == 'si') {
              		echo "Error 6: " . mysqli_error($omodelo->link);
              		$status = 1;
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

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date("Y-m-d H:i:s");
		$tipo = $omodelo->link->real_escape_string($tipo);

		if($tipo == 'empleado'){
			$id = $omodelo->link->real_escape_string($id);
			$arreglo = array();

			$query = "SELECT ID_Empleado, FK_Area, Puesto, Nombre, Primer_Apellido, Segundo_Apellido, ID_PR, Email, Estatus, Foto, Puntos, Fecha_Registro FROM empleados WHERE ID_Empleado = '$id'";
		    $row = $omodelo->_consultar($query);
		    $numerofilas = $omodelo->numerofilas;

		    if ($row == 'si') {
		      	echo "Error: " . mysqli_error($omodelo->link);
		    } else {
		      	if ($numerofilas > 0) {
		      		$areas = '';
		      		$query1 = "SELECT ID_Area_Em, FK_Area, Nombre FROM areas_empleados INNER JOIN areas ON FK_Area = ID_Area WHERE FK_Empleado = '$id'";
				    $row1 = $omodelo->_consultar($query1);
				    $numerofilas1 = $omodelo->numerofilas;

				    if ($row1 == 'si') {
				      	echo "Error: " . mysqli_error($omodelo->link);
				    } else {
				      	if ($numerofilas1 > 0) {
				      		for ($i=0; $i < $numerofilas1; $i++) { 
				      			$areas = '<tr attrID="'.$row1[$i]['FK_Area'].'">
							  		<td>'.$row1[$i]['Nombre'].'</td>
							  		<td><button type="button" class="btn btn-danger btn-sm bQuitarAreaEm" attrID="'.$row1[$i]['FK_Area'].'"><i class="fas fa-trash"></i></button></td>
							  	</tr>';
				      		}
				      	}
				    }

		        	$arreglo = array(
		            	'ID_Empleado' => $row[0]['ID_Empleado'],
		            	'FK_Area' => $row[0]['FK_Area'],
		            	'Puesto' => $row[0]['Puesto'],
		            	'Nombre' => $row[0]['Nombre'],
		            	'Primer_Apellido' => $row[0]['Primer_Apellido'],
		            	'Segundo_Apellido' => $row[0]['Segundo_Apellido'],
		            	'ID_PR' => $row[0]['ID_PR'],
		            	'Email' => $row[0]['Email'],
		            	'Estatus' => $row[0]['Estatus'],
		            	'Foto' => $row[0]['Foto'],
		            	'Puntos' => $row[0]['Puntos'],
		            	'Fecha_Registro' => $row[0]['Fecha_Registro'],
		            	'Areas' => $areas
		          	);
		      	}
		    }

		    echo json_encode($arreglo);
		}else if($tipo == 'areas'){
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
		        	$busqueda .= "CONCAT(Nombre, Descripcion) REGEXP '" . $separa[$i] . "'";
		        	if ($i < (count($separa) - 1)) {
		          		$busqueda .= ' AND ';
		        	}
		      	}
		    }

		    $query = "SELECT ID_Area, Nombre, Descripcion, (SELECT COUNT(*) FROM areas $busqueda) AS Num FROM areas $busqueda ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
		    $row = $omodelo->_consultar($query);
		    $numerofilas = $omodelo->numerofilas;

		    if ($row == 'si') {
		      	echo "Error: " . mysqli_error($omodelo->link);
		    } else {
		      	if ($numerofilas > 0) {
		        	for ($i = 0; $i < $numerofilas; $i++) {
		          		$arreglo['data'][$i] = array(
		            		'ID' => $row[$i]['ID_Area'],
		            		'Nombre' => $row[$i]['Nombre'],
		            		'Descripcion' => $row[$i]['Descripcion']
		          		);
		        	}
		        
		        	$arreglo['totales'] = array('NumRows' => $row[0]['Num']);
		      	}
		    }

		    echo json_encode($arreglo);
		}
	}

	public function _eliminar() {
	    $omodelo = new m_modelo();
	    extract($_POST);
	    $fecha = date('Y-m-d H:i:s');

	    $id = $omodelo->link->real_escape_string($id);
	    $foto = isset($foto) ? $omodelo->link->real_escape_string($foto) : '';
	    $carpeta = 'vistas/assets/img/empleados/';

	    $query = "DELETE FROM empleados WHERE ID_Empleado = '$id'";
	    $error = $omodelo->_insertar($query);

	    if ($error == 'si') {
	      	echo "Error: " . mysqli_error($omodelo->link);
	    } else {
	      	if (trim($foto) != '' && file_exists($carpeta . $foto)) {
	        	unlink($carpeta . $foto);
	      	}

	      	echo 'Correcto';

	      	$omodelo->movimiento($query, $_SESSION['user_heroe_admin']['ID_Usuario']);
	    }
  	}
}
?>