<?php
require_once("vendor/autoload.php");

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class cartaPorte
{

	private function _distanciaKm($lat1, $lon1, $lat2, $lon2)
	{
		if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
			return 0;
		}

		$radioTierra = 6371; // km
		$dLat = deg2rad($lat2 - $lat1);
		$dLon = deg2rad($lon2 - $lon1);

		$a = sin($dLat / 2) * sin($dLat / 2) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
			sin($dLon / 2) * sin($dLon / 2);

		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$factorCarretera = 1.3;

		return round($radioTierra * $c * $factorCarretera, 2);
	}

	private function _generarFolioCCP()
	{
		$hex = strtoupper(bin2hex(random_bytes(15))); // 30 hex chars, tomamos 29
		return 'CCC' . substr($hex, 0, 5) . '-' . substr($hex, 5, 4) . '-' . substr($hex, 9, 4) . '-' . substr($hex, 13, 4) . '-' . substr($hex, 17, 12);
	}

	public function _consultar()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$idCorte = $omodelo->link->real_escape_string($idCorte);

		// ===== CORTE =====
		$query = "SELECT * FROM cortes_ruta WHERE ID_Corte = '$idCorte' LIMIT 1";
		$corte = $omodelo->_consultar($query);

		if ($corte == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: Corte no encontrado';
			return;
		}
		$cr = $corte[0];

		$resultado = array();

		// ===== EMISOR =====
		$query = "SELECT RFC, Nombre, TRIM(SUBSTRING_INDEX(Regimen, '-', 1)) AS Regimen FROM general LIMIT 1";
		$emisor = $omodelo->_consultar($query);
		$e = $emisor[0];
		$resultado['emisor'] = array('RFC' => $e['RFC'], 'Nombre' => $e['Nombre'], 'Regimen' => $e['Regimen']);

		// ===== ORIGEN (SUCURSAL) =====
		$query = "SELECT * FROM sucursales WHERE ID_Sucursal = '{$cr['FK_Sucursal']}' LIMIT 1";
		$sucursal = $omodelo->_consultar($query);
		$s = $sucursal[0];

		$resultado['origen'] = array(
			'Nombre' => $e['Nombre'],
			'RFC' => $e['RFC'],
			'Calle' => $s['Calle'],
			'NumeroExterior' => $s['No_Exterior'],
			'NumeroInterior' => $s['No_Interior'],
			'Colonia' => $s['Colonia'],
			'CodigoPostal' => $s['CP'],
			'Municipio' => $s['Ciudad'],
			'Estado' => $s['Estado'],
			'Pais' => $s['Pais'],
			'Latitud' => $s['Latitud'],
			'Longitud' => $s['Longitud'],
			'Domicilio' => $s['Calle'] . ' ' . $s['No_Exterior'] . ($s['No_Interior'] != '' ? ' Int. ' . $s['No_Interior'] : '') . ', ' . $s['Colonia'] . ', CP ' . $s['CP'] . ', ' . $s['Ciudad'] . ', ' . $s['Estado']
		);

		// ===== CHOFER =====
		$query = "SELECT * FROM choferes WHERE ID_Chofer = '{$cr['FK_Chofer']}' LIMIT 1";
		$chofer = $omodelo->_consultar($query);
		$ch = $omodelo->numerofilas > 0 ? $chofer[0] : array('Nombre' => '', 'Primer_Apellido' => '', 'Segundo_Apellido' => '', 'RFC' => '', 'No_Licencia' => '', 'Tipo' => '01');

		$resultado['chofer'] = array(
			'Nombre' => trim($ch['Nombre'] . ' ' . $ch['Primer_Apellido'] . ' ' . $ch['Segundo_Apellido']),
			'RFC' => $ch['RFC'],
			'No_Licencia' => $ch['No_Licencia'],
			'Tipo' => $ch['Tipo'] != '' ? $ch['Tipo'] : '01'
		);

		// ===== VEHICULO =====
		$query = "SELECT * FROM vehiculos WHERE ID_Vehiculo = '{$cr['FK_Vehiculo']}' LIMIT 1";
		$vehiculo = $omodelo->_consultar($query);
		$v = $omodelo->numerofilas > 0 ? $vehiculo[0] : array('Matricula' => '', 'Tipo' => 'VL', 'Peso' => '', 'Ano' => '', 'Aseguradora' => '', 'Poliza' => '', 'SICT' => '');

		$resultado['vehiculo'] = array(
			'Placa' => $v['Matricula'],
			'Tipo' => $v['Tipo'] != '' ? $v['Tipo'] : 'VL',
			'Peso' => $v['Peso'],
			'Ano' => $v['Ano'],
			'Aseguradora' => $v['Aseguradora'],
			'Poliza' => $v['Poliza'],
			'SICT' => $v['SICT']
		);

		// ===== VENTAS DEL CORTE (misma lógica que en c_cortesRuta) =====
		$query = "SELECT v.ID_Venta, v.FK_Cliente, v.FK_Direccion 
		FROM ventas v INNER JOIN clientes c ON v.FK_Cliente = c.ID_Cliente 
		WHERE v.Estatus = 'Completada' 
		AND v.FK_Sucursal = '{$cr['FK_Sucursal']}' 
		AND (DATE_FORMAT(v.Fecha_Registro, '%Y-%m-%d') >= '{$cr['Fecha_Inicio']}' AND DATE_FORMAT(v.Fecha_Registro, '%Y-%m-%d') <= '{$cr['Fecha_Fin']}') 
		AND (c.FK_Ruta = '{$cr['FK_Ruta']}' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = v.FK_Cliente AND FK_Ruta = '{$cr['FK_Ruta']}' AND FK_Corte = '$idCorte') > 0) 
		AND ((SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = v.FK_Cliente AND FK_Corte = '$idCorte') = 0) 
		AND (IFNULL((SELECT COUNT(*) FROM ventas_extras_ruta WHERE FK_Venta = v.ID_Venta AND Tipo = 'Agregar'), 0) >= 0 AND IFNULL((SELECT COUNT(*) FROM ventas_extras_ruta WHERE FK_Venta = v.ID_Venta AND Tipo = 'Quitar'), 0) = 0)";
		$ventas = $omodelo->_consultar($query);

		if ($ventas == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: El corte no tiene ventas completadas para trasladar';
			return;
		}

		// ===== DESTINOS ÚNICOS (Cliente + Direccion) =====
		$destinosMapa = array();
		$destinos = array();
		$idsVentaPorDestino = array();

		foreach ($ventas as $vt) {
			if ($vt == null) continue;

			$clave = $vt['FK_Cliente'] . '-' . $vt['FK_Direccion'];
			$idsVentaPorDestino[$clave][] = $vt['ID_Venta'];

			if (!isset($destinosMapa[$clave])) {
				$query = "SELECT 
					ID_Cliente, 
					Nombre, 
					Primer_Apellido, 
					Segundo_Apellido, 
					RFC, 
					Calle, 
					No_Exterior, 
					No_Interior, 
					Colonia, 
					Ciudad, 
					Codigo_Postal, 
					Estado, 
					Pais, 
					Latitud, 
					Longitud,
					Orden_Ruta,
					ClaveEstado,
					ClaveMunicipio,
					ClaveColonia  
				FROM clientes WHERE ID_Cliente = '{$vt['FK_Cliente']}' LIMIT 1";
				$clienteRow = $omodelo->_consultar($query);
				$cl = $clienteRow[0];

				$domicilio = array(
					'Calle' => $cl['Calle'],
					'NumeroExterior' => $cl['No_Exterior'],
					'NumeroInterior' => $cl['No_Interior'],
					'Colonia' => $cl['Colonia'],
					'CodigoPostal' => $cl['Codigo_Postal'],
					'Municipio' => $cl['Ciudad'],
					'Estado' => $cl['Estado'],
					'Pais' => $cl['Pais'],
					'Latitud' => $cl['Latitud'],
					'Longitud' => $cl['Longitud'],
					'ClaveEstado' => $cl['ClaveEstado'],
					'ClaveMunicipio' => $cl['ClaveMunicipio'],
					'ClaveColonia' => $cl['ClaveColonia']
				);

				if ($vt['FK_Direccion'] != '0' && $vt['FK_Direccion'] != '') {
					$query2 = "SELECT * FROM detalles_clientes WHERE ID_Detalle_Cliente = '{$vt['FK_Direccion']}' LIMIT 1";
					$dirRow = $omodelo->_consultar($query2);
					if ($omodelo->numerofilas > 0) {
						$dc = $dirRow[0];
						$domicilio = array(
							'Calle' => $dc['Calle'],
							'NumeroExterior' => $dc['No_Exterior'],
							'NumeroInterior' => $dc['No_Interior'],
							'Colonia' => $dc['Colonia'],
							'CodigoPostal' => $dc['Codigo_Postal'],
							'Municipio' => $dc['Ciudad'],
							'Estado' => $dc['Estado'],
							'Pais' => $dc['Pais'],
							'Latitud' => $dc['Latitud'],
							'Longitud' => $dc['Longitud'],
							'ClaveEstado' => $dc['ClaveEstado'],
							'ClaveMunicipio' => $dc['ClaveMunicipio'],
							'ClaveColonia' => $dc['ClaveColonia']
						);
					}
				}

				$distancia = $this->_distanciaKm($s['Latitud'], $s['Longitud'], $domicilio['Latitud'], $domicilio['Longitud']);

				$destinos[] = array_merge(array(
					'FK_Cliente' => $vt['FK_Cliente'],
					'FK_Direccion' => $vt['FK_Direccion'],
					'Nombre' => trim($cl['Nombre'] . ' ' . $cl['Primer_Apellido'] . ' ' . $cl['Segundo_Apellido']),
					'RFC' => $cl['RFC'],
					'Orden_Ruta' => (int) $cl['Orden_Ruta'],
					'DistanciaRecorrida' => $distancia
				), $domicilio);

				$destinosMapa[$clave] = count($destinos) - 1;
			}
		}

		usort($destinos, function ($a, $b) {
			return $a['Orden_Ruta'] <=> $b['Orden_Ruta'];
		});

		// Reconstruir el mapa de índices ya que el orden cambió
		$destinosMapa = array();
		foreach ($destinos as $idx => $d) {
			$destinosMapa[$d['FK_Cliente'] . '-' . $d['FK_Direccion']] = $idx;
		}

		$resultado['destinos'] = $destinos;

		// ===== MERCANCIAS (agrupadas por Producto/Presentacion + Destino) =====
		$mercancias = array();

		foreach ($idsVentaPorDestino as $clave => $idsVenta) {
			if ($idsVenta == null) continue;

			$idsVentaStr = implode(',', $idsVenta);
			$indiceDestino = $destinosMapa[$clave];

			$query = "SELECT dv.FK_Producto, dv.FK_Presentacion, SUM(dv.Cantidad) AS Cantidad,
			IFNULL(pd.Clave_ProdServ_CFDI, '') AS ClaveProdServ,
			CONCAT(
				dv.Descripcion, 
				' ',
				(CASE WHEN FK_Presentacion <> 0 THEN (pr.Nombre) ELSE '' END) 
			) AS Descripcion,
			pd.Clave_Unidad_CFDI AS ClaveUnidad,
			IFNULL(pr.Peso, pd.Peso) AS Peso
			FROM detalles_ventas dv
			INNER JOIN productos pd ON dv.FK_Producto = pd.ID_Producto
			LEFT JOIN presentaciones pr ON dv.FK_Presentacion = pr.ID_Presentacion
			WHERE dv.FK_Venta IN ($idsVentaStr)
			GROUP BY dv.FK_Producto, dv.FK_Presentacion";
			$detalles = $omodelo->_consultar($query);

			if ($detalles != 'si' && $omodelo->numerofilas > 0) {
				foreach ($detalles as $d) {
					if ($d == null) continue;

					$pesoUnitario = ($d['Peso'] !== null && $d['Peso'] !== '') ? (float) $d['Peso'] : 0;
					$cantidad = (float) $d['Cantidad'];

					$mercancias[] = array(
						'FK_Producto' => $d['FK_Producto'],
						'FK_Presentacion' => $d['FK_Presentacion'],
						'ClaveProdServ' => $d['ClaveProdServ'],
						'Descripcion' => $d['Descripcion'],
						'ClaveUnidad' => $d['ClaveUnidad'],
						'Cantidad' => $d['Cantidad'],
						'PesoUnitario' => $pesoUnitario,
						'PesoTotal' => round($cantidad * $pesoUnitario, 3),
						'IndiceDestino' => $indiceDestino,
						'Destino' => $destinos[$indiceDestino]['Nombre']
					);
				}
			}
		}

		$resultado['mercancias'] = $mercancias;

		echo json_encode($resultado);
	}

	public function _detalles()
	{
		$omodelo = new m_modelo();
		extract($_POST);
		$tipo = $omodelo->link->real_escape_string($tipo);

		if ($tipo == 'peso') {
			$idProducto = $omodelo->link->real_escape_string($idProducto);
			$idPresentacion = $omodelo->link->real_escape_string($idPresentacion);
			$peso = $omodelo->link->real_escape_string($peso);

			if ($idPresentacion != '' && $idPresentacion != '0') {
				$query = "UPDATE presentaciones SET Peso = '$peso' WHERE ID_Presentacion = '$idPresentacion'";
			} else {
				$query = "UPDATE productos SET Peso = '$peso' WHERE ID_Producto = '$idProducto'";
			}
			$error = $omodelo->_insertar($query);

			if ($error == 'si') {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				echo 'Correcto';

				$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);
			}
		} elseif ($tipo == 'buscarClave') {
			$subtipo = $omodelo->link->real_escape_string($subtipo);
			$texto = $omodelo->link->real_escape_string($texto);
			$extra = $omodelo->link->real_escape_string($extra);

			if ($subtipo == 'estado') {
				$query = "SELECT ClaveEstado FROM sat_estados WHERE NombreEstado LIKE '%$texto%' LIMIT 1";
				$campo = 'ClaveEstado';
			} elseif ($subtipo == 'municipio') {
				$query = "SELECT ClaveMunicipio FROM sat_municipios WHERE Descripcion LIKE '%$texto%'";
				if ($extra != '') {
					$query .= " AND ClaveEstado = '$extra'";
				}
				$query .= " LIMIT 1";
				$campo = 'ClaveMunicipio';
			} elseif ($subtipo == 'colonia') {
				$query = "SELECT ClaveColonia FROM sat_colonias WHERE NombreAsentamiento LIKE '%$texto%' OR CodigoPostal LIKE '%$texto%' LIMIT 1";
				$campo = 'ClaveColonia';
			} else {
				echo 'null';
				return;
			}

			$resultado = $omodelo->_consultar($query);

			if ($resultado == 'si' || $omodelo->numerofilas == 0) {
				echo 'null';
			} else {
				echo $resultado[0][$campo];
			}
		} elseif ($tipo == 'listaClaves') {
			$subtipo = $omodelo->link->real_escape_string($subtipo);
			$buscar = $omodelo->link->real_escape_string($buscar);
			$limit = $omodelo->link->real_escape_string($limit);
			$pagina = $omodelo->link->real_escape_string($pagina);
			$ordenColumna = $omodelo->link->real_escape_string($ordenColumna);
			$orden = $omodelo->link->real_escape_string($orden);
			$arreglo = array();

			if ($subtipo == 'estado') {
				$tabla = 'sat_estados';
				$campoDescripcion = 'NombreEstado';
				$campoClave = 'ClaveEstado';
			} elseif ($subtipo == 'municipio') {
				$tabla = 'sat_municipios';
				$campoDescripcion = "CONCAT(Descripcion, ' (', ClaveEstado, ')')";
				$campoClave = 'ClaveMunicipio';
			} elseif ($subtipo == 'colonia') {
				$tabla = 'sat_colonias';
				$campoDescripcion = "CONCAT(NombreAsentamiento, ' (CP ', CodigoPostal, ')')";
				$campoClave = 'ClaveColonia';
			} else {
				echo json_encode($arreglo);
				return;
			}

			$busqueda = '';
			if (trim($buscar) != '') {
				$separa = explode(' ', trim($buscar));
				$busqueda = 'WHERE ';
				for ($i = 0; $i < count($separa); $i++) {
					$busqueda .= "$campoDescripcion REGEXP '" . $separa[$i] . "'";
					if ($i < (count($separa) - 1)) {
						$busqueda .= ' AND ';
					}
				}
			}

			$query = "SELECT $campoClave AS Clave, $campoDescripcion AS Descripcion, 
			(SELECT COUNT(*) FROM $tabla $busqueda) AS Num 
			FROM $tabla $busqueda 
			ORDER BY $ordenColumna $orden LIMIT $limit OFFSET " . (($pagina * $limit) - $limit);
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == 'si') {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$arreglo['data'][$i] = array(
							'Descripcion' => $row[$i]['Descripcion'],
							'Clave' => $row[$i]['Clave']
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

		$idCorte = $omodelo->link->real_escape_string($idCorte);
		$origenFechaSalida = $omodelo->link->real_escape_string($origenFechaSalida);
		$origenClaveEstado = $omodelo->link->real_escape_string($origenClaveEstado);
		$origenClaveMunicipio = $omodelo->link->real_escape_string($origenClaveMunicipio);
		$origenClaveColonia = $omodelo->link->real_escape_string($origenClaveColonia);
		$vehiculo = json_decode($vehiculo, true);
		$chofer = json_decode($chofer, true);
		$destinos = json_decode($destinos, true);

		// ===== CORTE =====
		$query = "SELECT *, IFNULL((SELECT Estatus FROM traslados_cfdi_ruta WHERE FK_Corte = '$idCorte'), '') AS Estatus_Traslado FROM cortes_ruta WHERE ID_Corte = '$idCorte' LIMIT 1";
		$corte = $omodelo->_consultar($query);
		if ($corte == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: Corte no encontrado';
			return;
		}
		$cr = $corte[0];

		if ($cr['FK_Traslado'] != '' && $cr['FK_Traslado'] != '0') {
			if ($cr['Estatus_Traslado'] == 'Timbrada') {
				echo 'Error: Este corte ya tiene un traslado generado y timbrado';

				return;
			} else if ($cr['Estatus_Traslado'] == 'Pendiente') {
				$queryEliminar = "DELETE FROM traslados_cfdi_ruta WHERE FK_Corte = '$idCorte' AND Estatus = 'Pendiente'";
				$error = $omodelo->_insertar($queryEliminar);

				if ($error == 'si') {
					echo "Error al eliminar el traslado: " . mysqli_error($omodelo->link);
					return;
				}
			} else {
				echo 'Error: Este corte ya tiene un traslado generado';

				return;
			}
		}

		// ===== EMISOR =====
		$query = "SELECT RFC, Nombre, TRIM(SUBSTRING_INDEX(Regimen, '-', 1)) AS Regimen, Certificado, Key_Cer, Contrasena FROM general LIMIT 1";
		$emisor = $omodelo->_consultar($query);
		if ($emisor == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: No se encontraron los datos del emisor';
			return;
		}
		$e = $emisor[0];

		// ===== SUCURSAL (ORIGEN) =====
		$query = "SELECT * FROM sucursales WHERE ID_Sucursal = '{$cr['FK_Sucursal']}' LIMIT 1";
		$sucursal = $omodelo->_consultar($query);
		if ($sucursal == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: No se encontró la sucursal de origen';
			return;
		}
		$s = $sucursal[0];

		// ===== ESCAPAR DATOS DE ENTRADA =====
		$placaVehiculo = $omodelo->link->real_escape_string($vehiculo['Placa']);
		$tipoVehiculo = $omodelo->link->real_escape_string($vehiculo['Tipo']);
		$anioVehiculo = $omodelo->link->real_escape_string($vehiculo['Ano']);
		$pesoVehiculo = $omodelo->link->real_escape_string($vehiculo['Peso']);
		$sictVehiculo = $omodelo->link->real_escape_string($vehiculo['SICT']);
		$aseguradoraVehiculo = $omodelo->link->real_escape_string($vehiculo['Aseguradora']);
		$polizaVehiculo = $omodelo->link->real_escape_string($vehiculo['Poliza']);

		$nombreChofer = $omodelo->link->real_escape_string($chofer['Nombre']);
		$rfcChofer = $omodelo->link->real_escape_string($chofer['RFC']);
		$licenciaChofer = $omodelo->link->real_escape_string($chofer['No_Licencia']);
		$tipoChofer = $omodelo->link->real_escape_string($chofer['Tipo']);

		// ===============================
		// INICIA TRANSACCIÓN
		// ===============================
		$omodelo->link->autocommit(false);
		$omodelo->link->begin_transaction();

		try {

			// ===== ACTUALIZAR VEHICULO SI APLICA =====
			if ((int) $vehiculo['Actualizar'] == 1 && $cr['FK_Vehiculo'] != '') {
				$query = "UPDATE vehiculos SET 
				Matricula = '$placaVehiculo', Tipo = '$tipoVehiculo', Ano = '$anioVehiculo', 
				Peso = '$pesoVehiculo', SICT = '$sictVehiculo', 
				Aseguradora = '$aseguradoraVehiculo', Poliza = '$polizaVehiculo' 
				WHERE ID_Vehiculo = '{$cr['FK_Vehiculo']}'";
				$error = $omodelo->_insertar($query);
				if ($error == 'si') {
					throw new Exception('Error al actualizar el vehículo: ' . mysqli_error($omodelo->link));
				}
			}

			// ===== ACTUALIZAR CHOFER SI APLICA =====
			if ((int) $chofer['Actualizar'] == 1 && $cr['FK_Chofer'] != '') {
				$query = "UPDATE choferes SET RFC = '$rfcChofer', No_Licencia = '$licenciaChofer', Tipo = '$tipoChofer' WHERE ID_Chofer = '{$cr['FK_Chofer']}'";
				$error = $omodelo->_insertar($query);
				if ($error == 'si') {
					throw new Exception('Error al actualizar el chofer: ' . mysqli_error($omodelo->link));
				}
			}

			$query = "SELECT * FROM choferes WHERE ID_Chofer = '{$cr['FK_Chofer']}' LIMIT 1";
			$choferRow = $omodelo->_consultar($query);
			$ch = $omodelo->numerofilas > 0 ? $choferRow[0] : array('Nombre' => $nombreChofer, 'Primer_Apellido' => '', 'Segundo_Apellido' => '');
			$nombreChofer = $omodelo->link->real_escape_string(trim($ch['Nombre'] . ' ' . $ch['Primer_Apellido'] . ' ' . $ch['Segundo_Apellido']));

			// ===== ACTUALIZAR DESTINOS SI APLICA =====
			foreach ($destinos as $d) {
				if ((int) $d['Actualizar'] != 1) continue;

				$fkCliente = $omodelo->link->real_escape_string($d['FK_Cliente']);
				$fkDireccion = $omodelo->link->real_escape_string($d['FK_Direccion']);
				$rfc = $omodelo->link->real_escape_string($d['RFC']);
				$calle = $omodelo->link->real_escape_string($d['Calle']);
				$numExt = $omodelo->link->real_escape_string($d['NumeroExterior']);
				$numInt = $omodelo->link->real_escape_string($d['NumeroInterior']);
				$colonia = $omodelo->link->real_escape_string($d['Colonia']);
				$cp = $omodelo->link->real_escape_string($d['CodigoPostal']);
				$municipio = $omodelo->link->real_escape_string($d['Municipio']);
				$estado = $omodelo->link->real_escape_string($d['Estado']);
				$claveEstado = $omodelo->link->real_escape_string($d['ClaveEstado']);
				$claveMunicipio = $omodelo->link->real_escape_string($d['ClaveMunicipio']);
				$claveColonia = $omodelo->link->real_escape_string($d['ClaveColonia']);

				if ($fkDireccion != '0' && $fkDireccion != '') {
					$query = "UPDATE detalles_clientes SET 
						Calle = '$calle', No_Exterior = '$numExt', No_Interior = '$numInt', 
						Colonia = '$colonia', ClaveColonia = '$claveColonia', 
						Codigo_Postal = '$cp', Ciudad = '$municipio', ClaveMunicipio = '$claveMunicipio', 
						Estado = '$estado', ClaveEstado = '$claveEstado' 
					WHERE ID_Detalle_Cliente = '$fkDireccion'";
					$error = $omodelo->_insertar($query);
					if ($error == 'si') {
						throw new Exception('Error al actualizar domicilio del cliente: ' . mysqli_error($omodelo->link));
					}

					$error = $omodelo->_insertar("UPDATE clientes SET RFC = '$rfc' WHERE ID_Cliente = '$fkCliente'");
					if ($error == 'si') {
						throw new Exception('Error al actualizar RFC del cliente: ' . mysqli_error($omodelo->link));
					}
				} else {
					$query = "UPDATE clientes SET 
						RFC = '$rfc', Calle = '$calle', No_Exterior = '$numExt', No_Interior = '$numInt', 
						Colonia = '$colonia', ClaveColonia = '$claveColonia', 
						Codigo_Postal = '$cp', Ciudad = '$municipio', ClaveMunicipio = '$claveMunicipio', 
						Estado = '$estado', ClaveEstado = '$claveEstado' 
					WHERE ID_Cliente = '$fkCliente'";
					$error = $omodelo->_insertar($query);
					if ($error == 'si') {
						throw new Exception('Error al actualizar el cliente: ' . mysqli_error($omodelo->link));
					}
				}
			}

			// ===== CREAR REGISTRO PRINCIPAL =====
			$folioCCP = $this->_generarFolioCCP();
			$regimenEmisor = $omodelo->link->real_escape_string($e['Regimen']);
			$nombreEmisor = $omodelo->link->real_escape_string($e['Nombre']);
			$rfcEmisor = $omodelo->link->real_escape_string($e['RFC']);

			$query = "INSERT INTO traslados_cfdi_ruta SET 
			FK_Corte = '$idCorte', 
			Nombre_Emisor = '$nombreEmisor', 
			RFC_Emisor = '$rfcEmisor', 
			Regimen_Emisor = '$regimenEmisor', 
			Version_CFDI = '4.0', 
			Tipo_Comprobante = 'T', 
			Uso_CFDI = 'S01', 
			Moneda = 'XXX', 
			SubTotal = 0, 
			Total = 0, 
			Version_CartaPorte = '3.1', 
			TranspInternac = 'No', 
			FolioCCP = '$folioCCP', 
			FK_Chofer = '{$cr['FK_Chofer']}', 
			FK_Vehiculo = '{$cr['FK_Vehiculo']}', 
			Estatus = 'Pendiente', 
			FK_Sucursal = '{$cr['FK_Sucursal']}', 
			Fecha_Registro = NOW()";
			$error = $omodelo->_insertar($query);
			if ($error == 'si') {
				throw new Exception('Error al crear el traslado: ' . mysqli_error($omodelo->link));
			}

			$idTraslado = $omodelo->link->insert_id;

			$query = "UPDATE traslados_cfdi_ruta SET Folio = 'T$idTraslado' WHERE ID_Traslado = '$idTraslado'";
			$error = $omodelo->_insertar($query);
			if ($error == 'si') {
				throw new Exception('Error al asignar folio: ' . mysqli_error($omodelo->link));
			}

			// ===== UBICACION ORIGEN =====
			$fechaSalida = str_replace('T', ' ', $origenFechaSalida) . ':00';

			$query = "INSERT INTO traslados_ubicaciones SET 
			FK_Traslado = '$idTraslado', IDUbicacion = 'OR000001', TipoUbicacion = 'Origen', 
			RFCRemitenteDestinatario = '$rfcEmisor', NombreRemitenteDestinatario = '$nombreEmisor', 
			Calle = '{$s['Calle']}', NumeroExterior = '{$s['No_Exterior']}', NumeroInterior = '{$s['No_Interior']}', 
			Colonia = '{$s['Colonia']}', ClaveColonia = '$origenClaveColonia', 
			Municipio = '{$s['Ciudad']}', ClaveMunicipio = '$origenClaveMunicipio', 
			Estado = '{$s['Estado']}', ClaveEstado = '$origenClaveEstado', 
			Pais = 'MEX', CodigoPostal = '{$s['CP']}', FechaHoraSalidaLlegada = '$fechaSalida'";
			$error = $omodelo->_insertar($query);
			if ($error == 'si') {
				throw new Exception('Error al guardar la ubicación de origen: ' . mysqli_error($omodelo->link));
			}

			// ===== UBICACIONES DESTINO =====
			$idsUbicacionDestino = array();

			foreach ($destinos as $i => $d) {
				$fkCliente = $omodelo->link->real_escape_string($d['FK_Cliente']);
				$fkDireccion = $omodelo->link->real_escape_string($d['FK_Direccion']);
				$nombre = $omodelo->link->real_escape_string($d['Nombre']);
				$rfc = $omodelo->link->real_escape_string($d['RFC']);
				$calle = $omodelo->link->real_escape_string($d['Calle']);
				$numExt = $omodelo->link->real_escape_string($d['NumeroExterior']);
				$numInt = $omodelo->link->real_escape_string($d['NumeroInterior']);
				$colonia = $omodelo->link->real_escape_string($d['Colonia']);
				$cp = $omodelo->link->real_escape_string($d['CodigoPostal']);
				$municipio = $omodelo->link->real_escape_string($d['Municipio']);
				$estado = $omodelo->link->real_escape_string($d['Estado']);
				$claveEstado = $omodelo->link->real_escape_string($d['ClaveEstado']);
				$claveMunicipio = $omodelo->link->real_escape_string($d['ClaveMunicipio']);
				$claveColonia = $omodelo->link->real_escape_string($d['ClaveColonia']);
				$distancia = $omodelo->link->real_escape_string($d['DistanciaRecorrida']);
				$fechaLlegada = str_replace('T', ' ', $omodelo->link->real_escape_string($d['FechaLlegada'])) . ':00';
				$idUbicacionStr = 'DE' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);

				$query = "INSERT INTO traslados_ubicaciones SET 
				FK_Traslado = '$idTraslado', IDUbicacion = '$idUbicacionStr', TipoUbicacion = 'Destino', 
				FK_Cliente = '$fkCliente', FK_Direccion = '$fkDireccion', 
				RFCRemitenteDestinatario = '$rfc', NombreRemitenteDestinatario = '$nombre', 
				Calle = '$calle', NumeroExterior = '$numExt', NumeroInterior = '$numInt', 
				Colonia = '$colonia', ClaveColonia = '$claveColonia', 
				Municipio = '$municipio', ClaveMunicipio = '$claveMunicipio', 
				Estado = '$estado', ClaveEstado = '$claveEstado', 
				Pais = 'MEX', CodigoPostal = '$cp', FechaHoraSalidaLlegada = '$fechaLlegada', 
				DistanciaRecorrida = '$distancia'";
				$error = $omodelo->_insertar($query);
				if ($error == 'si') {
					throw new Exception('Error al guardar el destino "' . $d['Nombre'] . '": ' . mysqli_error($omodelo->link));
				}

				$idsUbicacionDestino[$fkCliente . '-' . $fkDireccion] = $omodelo->link->insert_id;
			}

			// ===== MERCANCIAS (agrupadas por producto + destino) =====
			$query = "SELECT v.ID_Venta, v.FK_Cliente, v.FK_Direccion 
			FROM ventas v INNER JOIN clientes c ON v.FK_Cliente = c.ID_Cliente 
			WHERE v.Estatus = 'Completada' 
			AND v.FK_Sucursal = '{$cr['FK_Sucursal']}' 
			AND (DATE_FORMAT(v.Fecha_Registro, '%Y-%m-%d') >= '{$cr['Fecha_Inicio']}' AND DATE_FORMAT(v.Fecha_Registro, '%Y-%m-%d') <= '{$cr['Fecha_Fin']}') 
			AND (c.FK_Ruta = '{$cr['FK_Ruta']}' OR (SELECT COUNT(*) FROM clientes_extras_ruta WHERE FK_Cliente = v.FK_Cliente AND FK_Ruta = '{$cr['FK_Ruta']}' AND FK_Corte = '$idCorte') > 0) 
			AND ((SELECT COUNT(*) FROM quitar_clientes_ruta WHERE FK_Cliente = v.FK_Cliente AND FK_Corte = '$idCorte') = 0) 
			AND (IFNULL((SELECT COUNT(*) FROM ventas_extras_ruta WHERE FK_Venta = v.ID_Venta AND Tipo = 'Agregar'), 0) >= 0 AND IFNULL((SELECT COUNT(*) FROM ventas_extras_ruta WHERE FK_Venta = v.ID_Venta AND Tipo = 'Quitar'), 0) = 0)";
			$ventas = $omodelo->_consultar($query);

			if ($ventas == 'si' || $omodelo->numerofilas == 0) {
				throw new Exception('El corte no tiene ventas completadas para trasladar');
			}

			$idsVentaPorDestino = array();
			foreach ($ventas as $vt) {
				if ($vt == null) continue;

				$idsVentaPorDestino[$vt['FK_Cliente'] . '-' . $vt['FK_Direccion']][] = $vt['ID_Venta'];
			}

			foreach ($idsVentaPorDestino as $clave => $idsVenta) {
				if (!isset($idsUbicacionDestino[$clave])) continue;

				$idsVentaStr = implode(',', $idsVenta);
				$idUbicacionDestino = $idsUbicacionDestino[$clave];

				$query = "SELECT dv.FK_Producto, dv.FK_Presentacion, SUM(dv.Cantidad) AS Cantidad,
				IFNULL(pd.Clave_ProdServ_CFDI, '') AS ClaveProdServ,
				IFNULL(pr.Nombre, pd.Descripcion) AS Descripcion,
				IFNULL(pd.Clave_Unidad_CFDI, '') AS ClaveUnidad,
				IFNULL(pr.Peso, pd.Peso) AS Peso
				FROM detalles_ventas dv
				INNER JOIN productos pd ON dv.FK_Producto = pd.ID_Producto
				LEFT JOIN presentaciones pr ON dv.FK_Presentacion = pr.ID_Presentacion
				WHERE dv.FK_Venta IN ($idsVentaStr)
				GROUP BY dv.FK_Producto, dv.FK_Presentacion";
				$detalles = $omodelo->_consultar($query);

				if ($detalles == 'si') {
					throw new Exception('Error al consultar mercancías: ' . mysqli_error($omodelo->link));
				}

				if ($omodelo->numerofilas > 0) {
					foreach ($detalles as $d) {
						if ($d == null) continue;

						$pesoUnitario = ($d['Peso'] !== null && $d['Peso'] !== '') ? (float) $d['Peso'] : 0;
						$cantidad = (float) $d['Cantidad'];
						$pesoTotal = round($cantidad * $pesoUnitario, 3);

						$claveProdServ = $omodelo->link->real_escape_string($d['ClaveProdServ']);
						$descripcion = $omodelo->link->real_escape_string($d['Descripcion']);
						$claveUnidad = $omodelo->link->real_escape_string($d['ClaveUnidad']);

						$query1 = "INSERT INTO traslados_mercancias SET 
						FK_Traslado = '$idTraslado', FK_Ubicacion_Destino = '$idUbicacionDestino', 
						FK_Producto = '{$d['FK_Producto']}', FK_Presentacion = '{$d['FK_Presentacion']}', 
						BienesTransp = '$claveProdServ', Descripcion = '$descripcion', 
						Cantidad = '$cantidad', ClaveUnidad = '$claveUnidad', PesoEnKg = '$pesoTotal'";
						$error = $omodelo->_insertar($query1);
						if ($error == 'si') {
							throw new Exception('Error al guardar mercancía "' . $d['Descripcion'] . '": ' . mysqli_error($omodelo->link));
						}
					}
				}
			}

			// ===== AUTOTRANSPORTE =====
			$query = "INSERT INTO traslados_autotransporte SET 
			FK_Traslado = '$idTraslado', PermSCT = 'TPAF01', NumPermisoSCT = '$sictVehiculo', 
			ConfigVehicular = '$tipoVehiculo', PesoBrutoVehicular = '$pesoVehiculo', 
			Placa = '$placaVehiculo', AnioModeloVM = '$anioVehiculo', 
			AseguraRespCivil = '$aseguradoraVehiculo', PolizaRespCivil = '$polizaVehiculo'";
			$error = $omodelo->_insertar($query);
			if ($error == 'si') {
				throw new Exception('Error al guardar el autotransporte: ' . mysqli_error($omodelo->link));
			}

			// ===== FIGURA DE TRANSPORTE =====
			$query = "INSERT INTO traslados_figura_transporte SET 
			FK_Traslado = '$idTraslado', TipoFigura = '$tipoChofer', RFCFigura = '$rfcChofer', 
			NombreFigura = '$nombreChofer', NumLicencia = '$licenciaChofer'";
			$error = $omodelo->_insertar($query);
			if ($error == 'si') {
				throw new Exception('Error al guardar la figura de transporte: ' . mysqli_error($omodelo->link));
			}

			// ===== ACTUALIZAR CORTE =====
			$query = "UPDATE cortes_ruta SET FK_Traslado = '$idTraslado' WHERE ID_Corte = '$idCorte'";
			$error = $omodelo->_insertar($query);
			if ($error == 'si') {
				throw new Exception('Error al actualizar el corte de ruta: ' . mysqli_error($omodelo->link));
			}

			// ===== TODO SALIÓ BIEN, CONFIRMAR =====
			$omodelo->link->commit();
			$omodelo->link->autocommit(true);

			$omodelo->movimiento($query, $_SESSION['user_admin']['ID_Usuario']);

			// El timbrado siempre se ejecuta después de guardar correctamente
			$this->_timbrarCartaPorte($idTraslado);
		} catch (Exception $ex) {
			$omodelo->link->rollback();
			$omodelo->link->autocommit(true);
			echo 'Error: ' . $ex->getMessage();
			return;
		}
	}

	private function _timbrarCartaPorte($idTraslado)
	{
		$omodelo = new m_modelo();

		// ===============================
		// TOKEN FIJO FACTUROPOR TI
		// ===============================				    		
		//Real
		$token = 'eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoiMUtJMTNaZjFFUUh5TDIyZ0FyZ0c4QT09IiwibmJmIjoxNzAyNDk5ODkzLCJleHAiOjE3MDUwOTE4OTMsImlzcyI6IlNjYWZhbmRyYVNlcnZpY2lvcyIsImF1ZCI6IlNjYWZhbmRyYSBTZXJ2aWNpb3MiLCJJZEVtcHJlc2EiOiIxS0kxM1pmMUVRSHlMMjJnQXJnRzhBPT0iLCJJZFVzdWFyaW8iOiJrLzB5WVl1Ly9oVXNtT3l1emw4aUVBPT0ifQ.AnVt_GPxZ-L38biL3tCxP-0VSE6peI1IVs6qgllDkwQ';
		//Pruebas
		//$token = 'eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoialYrdVVUYmtWNmUxRmNZb2cvNWtGQT09IiwibmJmIjoxNjY5NzY1MTM1LCJleHAiOjE2NzIzNTcxMzUsImlzcyI6IlNjYWZhbmRyYVNlcnZpY2lvcyIsImF1ZCI6IlNjYWZhbmRyYSBTZXJ2aWNpb3MiLCJJZEVtcHJlc2EiOiJqVit1VVRia1Y2ZTFGY1lvZy81a0ZBPT0iLCJJZFVzdWFyaW8iOiJidXlaYzFMWUl5VURaSGhGR3NqaGdRPT0ifQ.7NfXWvnQSy_2PtWEnzItEtZseWV0VqahTuAS3YPG8TE';

		// ===============================
		// DATOS TRASLADO
		// ===============================
		$idTraslado = $omodelo->link->real_escape_string($idTraslado);

		$query = "SELECT * FROM traslados_cfdi_ruta WHERE ID_Traslado = '$idTraslado' AND Estatus = 'Pendiente' LIMIT 1";
		$traslado = $omodelo->_consultar($query);

		if ($traslado == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: Traslado no encontrado o ya timbrado';
			return;
		}

		$t = $traslado[0];

		// ===============================
		// DATOS EMISOR (GENERAL)
		// ===============================
		$query = "SELECT *, '44790' AS CodigoPostal FROM general LIMIT 1";
		$config = $omodelo->_consultar($query);

		if ($config == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: No existe configuración del emisor';
			return;
		}

		$c = $config[0];
		$regimenEmisor = trim(substr($c['Regimen'], 0, 3));

		// ===============================
		// ORIGEN
		// ===============================
		$query = "SELECT * FROM traslados_ubicaciones WHERE FK_Traslado = '$idTraslado' AND TipoUbicacion = 'Origen' LIMIT 1";
		$origenRow = $omodelo->_consultar($query);

		if ($origenRow == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: El traslado no tiene ubicación de origen';
			return;
		}

		$origen = $origenRow[0];

		// ===============================
		// DESTINOS
		// ===============================
		$query = "SELECT * FROM traslados_ubicaciones 
		WHERE FK_Traslado = '$idTraslado' AND TipoUbicacion = 'Destino' 
		ORDER BY ID_Ubicacion ASC";
		$destinosRows = $omodelo->_consultar($query);

		if ($destinosRows == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: El traslado no tiene ubicaciones de destino';
			return;
		}

		$ubicaciones = [];

		$ubicaciones[] = [
			"Tipo" => "1",
			"IdOrigenDestino" => "1",
			"Nombre" => $origen['NombreRemitenteDestinatario'],
			"ResidenciaFiscal" => "MEX",
			"RFC" => $origen['RFCRemitenteDestinatario'],
			"TaxId" => "",
			"FechaSalidaLlegada" => str_replace(" ", "T", $origen['FechaHoraSalidaLlegada']),
			"Domicilio" => [
				"ClaveEstado" => $origen['ClaveEstado'],
				"ClaveMunicipio" => $origen['ClaveMunicipio'],
				"ClaveColonia" => $origen['ClaveColonia'],
				"ClavePais" => "MEX",
				"Calle" => $origen['Calle'],
				"NumeroExterior" => $origen['NumeroExterior'],
				"NumeroInterior" => $origen['NumeroInterior'],
				"CodigoPostal" => $origen['CodigoPostal'],
				"Estado" => $origen['Estado'],
				"Municipio" => $origen['Municipio'],
				"Colonia" => $origen['Colonia'],
				"ConsultaClavesSAT" => false
			]
		];

		$idOrigenDestinoPorUbicacion = ["OR000001" => "1"]; // mapa IDUbicacion => IdOrigenDestino (FacturoPorTi)

		foreach ($destinosRows as $i => $d) {
			if ($d == null) continue;

			$idOrigenDestino = (string) ($i + 2);
			$idOrigenDestinoPorUbicacion[$d['IDUbicacion']] = $idOrigenDestino;

			$ubicaciones[] = [
				"Tipo" => "2",
				"IdOrigenDestino" => $idOrigenDestino,
				"Nombre" => $d['NombreRemitenteDestinatario'],
				"ResidenciaFiscal" => "MEX",
				"RFC" => $d['RFCRemitenteDestinatario'],
				"TaxId" => "",
				"FechaSalidaLlegada" => str_replace(" ", "T", $d['FechaHoraSalidaLlegada']),
				"DistanciaRecorrida" => $d['DistanciaRecorrida'],
				"Domicilio" => [
					"ClaveEstado" => $d['ClaveEstado'],
					"ClaveMunicipio" => $d['ClaveMunicipio'],
					"ClaveColonia" => $d['ClaveColonia'],
					"ClavePais" => "MEX",
					"Calle" => $d['Calle'],
					"NumeroExterior" => $d['NumeroExterior'],
					"NumeroInterior" => $d['NumeroInterior'],
					"CodigoPostal" => $d['CodigoPostal'],
					"Estado" => $d['Estado'],
					"Municipio" => $d['Municipio'],
					"Colonia" => $d['Colonia'],
					"ConsultaClavesSAT" => false
				]
			];
		}

		// ===============================
		// MERCANCIAS (detalle por destino)
		// ===============================
		$query = "SELECT tm.*, tu.IDUbicacion AS IDUbicacionDestino 
		FROM traslados_mercancias tm 
		INNER JOIN traslados_ubicaciones tu ON tm.FK_Ubicacion_Destino = tu.ID_Ubicacion 
		WHERE tm.FK_Traslado = '$idTraslado'";
		$mercanciasRows = $omodelo->_consultar($query);

		if ($mercanciasRows == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: El traslado no tiene mercancías';
			return;
		}

		$mercancias = [];
		foreach ($mercanciasRows as $m) {
			if ($m == null) continue;

			$mercancias[] = [
				"BienesTransportados" => $m['BienesTransp'],
				"CodigoUnidad" => $m['ClaveUnidad'],
				"Unidad" => "",
				"Cantidad" => $m['Cantidad'],
				"Descripcion" => $m['Descripcion'],
				"Dimension" => "",
				"ValorMercancia" => "1",
				"PesoEnKg" => $m['PesoEnKg'],
				"Moneda" => "MXN",
				"FraccionArancelaria" => "",
				"IdOrigen" => "1",
				"IdDestino" => $idOrigenDestinoPorUbicacion[$m['IDUbicacionDestino']]
			];
		}

		// ===============================
		// CONCEPTOS (agrupados solo por producto, sin separar por destino)
		// ===============================
		$query = "SELECT BienesTransp, Descripcion, ClaveUnidad, SUM(Cantidad) AS Cantidad 
		FROM traslados_mercancias 
		WHERE FK_Traslado = '$idTraslado' 
		GROUP BY FK_Producto, FK_Presentacion";
		$conceptosRows = $omodelo->_consultar($query);

		$conceptos = [];
		foreach ($conceptosRows as $cc) {
			if ($cc == null) continue;

			$conceptos[] = [
				"Cantidad" => $cc['Cantidad'],
				"CodigoUnidad" => $cc['ClaveUnidad'],
				"Unidad" => "",
				"CodigoProducto" => $cc['BienesTransp'],
				"Producto" => $cc['Descripcion'],
				"PrecioUnitario" => "0.00",
				"Importe" => "0.00",
				"ObjetoDeImpuesto" => "01"
			];
		}

		// ===============================
		// AUTOTRANSPORTE
		// ===============================
		$query = "SELECT * FROM traslados_autotransporte WHERE FK_Traslado = '$idTraslado' LIMIT 1";
		$autotransporteRow = $omodelo->_consultar($query);

		if ($autotransporteRow == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: El traslado no tiene datos de autotransporte';
			return;
		}

		$at = $autotransporteRow[0];

		// ===============================
		// FIGURA DE TRANSPORTE
		// ===============================
		$query = "SELECT * FROM traslados_figura_transporte WHERE FK_Traslado = '$idTraslado' LIMIT 1";
		$figuraRow = $omodelo->_consultar($query);

		if ($figuraRow == 'si' || $omodelo->numerofilas == 0) {
			echo 'Error: El traslado no tiene datos del operador';
			return;
		}

		$fig = $figuraRow[0];

		// ===============================
		// CERTIFICADOS
		// ===============================
		$rutaCerts = __DIR__ . "/../vistas/assets/archivos/certificados/";

		$cerPath = $rutaCerts . $c['Certificado'];
		$keyPath = $rutaCerts . $c['Key_Cer'];
		$csdPass = $c['Contrasena'];

		if (!file_exists($cerPath) || !file_exists($keyPath)) {
			echo 'Error: No se encontraron los certificados';
			return;
		}

		$cerBase64 = base64_encode(file_get_contents($cerPath));
		$keyBase64 = base64_encode(file_get_contents($keyPath));

		// ===============================
		// JSON CFDI
		// ===============================
		$logotipo_base64 = '';
		$logoPath = __DIR__ . '/../vistas/assets/img/favicon/favicon.jpg';
		if (file_exists($logoPath)) {
			$logotipo_base64 = base64_encode(file_get_contents($logoPath));
		}

		$fechaEmisionTraslado = new DateTime('now', new DateTimeZone('-06:00'));

		$data = [
			"DatosGenerales" => [
				"Version" => "4.0",
				"CSD" => $cerBase64,
				"LlavePrivada" => $keyBase64,
				"CSDPassword" => $csdPass,
				"CFDI" => "CartaPorte",
				"GeneraPDF" => true,
				"Logotipo" => $logotipo_base64,
				"TipoCFDI" => "Traslado",
				"OpcionDecimales" => "2",
				"NumeroDecimales" => "2"
			],
			"Encabezado" => [
				"Emisor" => [
					"RFC" => $c['RFC'],
					"NombreRazonSocial" => $c['Nombre'],
					"RegimenFiscal" => $regimenEmisor,
					"Direccion" => [
						[
							"Calle" => "Dionisio Rodriguez",
							"NumeroExterior" => "3369",
							"NumeroInterior" => "",
							"Colonia" => "Jardines de San Francisco",
							"Localidad" => "",
							"Municipio" => "Guadalajara",
							"Estado" => "Jalisco",
							"Pais" => "Mexico",
							"CodigoPostal" => "44790"
						]
					]
				],
				"Receptor" => [
					"RFC" => $c['RFC'],
					"NombreRazonSocial" => $c['Nombre'],
					"UsoCFDI" => "S01",
					"RegimenFiscal" => $regimenEmisor,
					"Direccion" => [
						"Calle" => "Dionisio Rodriguez",
						"NumeroExterior" => "3369",
						"NumeroInterior" => "",
						"Colonia" => "Jardines de San Francisco",
						"Localidad" => "",
						"Municipio" => "Guadalajara",
						"Estado" => "Jalisco",
						"Pais" => "Mexico",
						"CodigoPostal" => "44790"
					]
				],
				"Fecha" => $fechaEmisionTraslado->format('Y-m-d\TH:i:s'),
				"Folio" => $t['Folio'],
				"Moneda" => "XXX",
				"LugarExpedicion" => $origen['CodigoPostal'],
				"SubTotal" => 0.00,
				"Total" => 0.00
			],
			"Conceptos" => $conceptos,
			"Complemento" => [
				"TipoComplemento" => 27,
				"CartaPorteV3" => [
					"Version" => "3.1",
					"FolioCCP" => $t['FolioCCP'],
					"TransporteInternacional" => "No",
					"EntradaSalidaMercancia" => "",
					"ViaEntradaSalida" => "",
					"PaisOrigenDestino" => "",
					"Ubicaciones" => $ubicaciones,
					"Mercancias" => $mercancias,
					"AutoTransporteFederal" => [
						"PermisoSCT" => $at['PermSCT'],
						"TipoAutoTransporte" => $at['ConfigVehicular'],
						"NumeroPermisoSCT" => $at['NumPermisoSCT'],
						"Placa" => $at['Placa'],
						"Anio" => $at['AnioModeloVM'],
						"SubTipoRemolque1" => "",
						"PlacaRemolque1" => "",
						"SubTipoRemolque2" => "",
						"PlacaRemolque2" => "",
						"NombreAseguradoraResponsabilidadCivil" => $at['AseguraRespCivil'],
						"NumeroPolizaResponsabilidadCivil" => $at['PolizaRespCivil'],
						"NombreAseguradoraCarga" => "",
						"NumeroPolizaCarga" => "",
						"NombreAseguradoraMedioAmbiente" => "",
						"NumeroPolizaMedioAmbiente" => "",
						"PrimaSeguro" => null,
						"PesoBrutoVehicular" => $at['PesoBrutoVehicular']
					],
					"FiguraTransporte" => [
						"TiposFiguras" => [
							[
								"Tipo" => $fig['TipoFigura'],
								"ResidenciaFiscal" => "MEX",
								"Nombre" => $fig['NombreFigura'],
								"RFC" => $fig['RFCFigura'],
								"TaxId" => "",
								"NumeroLicencia" => $fig['NumLicencia']
							]
						]
					]
				]
			]
		];

		// ===============================
		// ENVIAR AL PAC
		// ===============================

		//print_r($ubicaciones);

		$client = new Client();

		$response = $client->post("https://api.facturoporti.com.mx/servicios/timbrar/json", [
			"headers" => [
				"authorization" => "Bearer $token",
				"accept" => "application/json",
				"content-type" => "application/json"
			],
			"body" => json_encode($data, JSON_UNESCAPED_UNICODE),
			"timeout" => 60,
			"http_errors" => false
		]);

		$res = json_decode($response->getBody()->getContents(), true);

		if (!isset($res['cfdiTimbrado']['respuesta']['uuid'])) {
			echo json_encode(['errorPAC' => $res]);
			return;
		}

		// ===============================
		// GUARDAR XML / PDF
		// ===============================
		$uuid = $res['cfdiTimbrado']['respuesta']['uuid'];
		$xml  = $res['cfdiTimbrado']['respuesta']['cfdixml'];
		$pdf  = base64_decode($res['cfdiTimbrado']['respuesta']['pdf']);

		$rutaTraslados = __DIR__ . "/traslados/";
		if (!file_exists($rutaTraslados)) {
			mkdir($rutaTraslados, 0777, true);
		}

		file_put_contents($rutaTraslados . $uuid . ".xml", $xml);
		file_put_contents($rutaTraslados . $uuid . ".pdf", $pdf);

		// ===============================
		// ACTUALIZAR TRASLADO
		// ===============================
		$query = "UPDATE traslados_cfdi_ruta SET 
			Estatus = 'Timbrada',
			Folio_Fiscal = '$uuid',
			Fecha_Timbrado = NOW(),
			XML = '$uuid.xml',
			PDF = '$uuid.pdf'
		WHERE ID_Traslado = '$idTraslado'";
		$error = $omodelo->_insertar($query);

		if ($error == 'si') {
			echo "Error al actualizar traslado: " . mysqli_error($omodelo->link);
		} else {
			echo 'Correcto';
		}
	}

	/*
	"Emisor" => [
		"RFC" => $c['RFC'],
		"NombreRazonSocial" => $c['Nombre'],
		"RegimenFiscal" => $regimenEmisor,
		"Direccion" => [
			[
				"Calle" => $origen['Calle'],
				"NumeroExterior" => $origen['NumeroExterior'],
				"NumeroInterior" => $origen['NumeroInterior'],
				"Colonia" => $origen['Colonia'],
				"Localidad" => "",
				"Municipio" => $origen['Municipio'],
				"Estado" => $origen['Estado'],
				"Pais" => "Mexico",
				"CodigoPostal" => $c['CodigoPostal']
			]
		]
	],
	"Receptor" => [
		"RFC" => $c['RFC'],
		"NombreRazonSocial" => $c['Nombre'],
		"UsoCFDI" => "S01",
		"RegimenFiscal" => $regimenEmisor,
		"Direccion" => [
			"Calle" => $origen['Calle'],
			"NumeroExterior" => $origen['NumeroExterior'],
			"NumeroInterior" => $origen['NumeroInterior'],
			"Colonia" => $origen['Colonia'],
			"Localidad" => "",
			"Municipio" => $origen['Municipio'],
			"Estado" => $origen['Estado'],
			"Pais" => "Mexico",
			"CodigoPostal" => $origen['CodigoPostal']
		]
	],
	*/
}
