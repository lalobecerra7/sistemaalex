<?php
//date_default_timezone_set('America/Mexico_City');
date_default_timezone_set('America/Chihuahua');

include 'modelo/m_modelo.php';
include "controladores/c_login.php";
include "controladores/c_sucursales.php";
include "controladores/c_clientes.php";
include "controladores/c_proveedores.php";
include "controladores/c_areas.php";
include "controladores/c_hacerventa.php";
// include "controladores/c_hacerventacaja.php";
include "controladores/c_categorias.php";
include "controladores/c_usuarios.php";
include "controladores/c_productos.php";
include "controladores/c_inventario.php";
include "controladores/c_cajas.php";
include "controladores/c_perfil.php";
include "controladores/c_impuestos.php";
include "controladores/c_tickets.php";
include "controladores/c_general.php";
include "controladores/c_zonas.php";
include "controladores/c_compras.php";
include "controladores/c_hacerCompra.php";
include "controladores/c_ventas.php";
include "controladores/c_facturacion.php";
include "controladores/c_importes.php";
include "controladores/c_precios.php";
include "controladores/c_reportes.php";
include "controladores/c_reporteCaja.php";
include "controladores/c_reporteVentas.php";
include "controladores/c_rutas.php";
include "controladores/c_vehiculos.php";
include "controladores/c_choferes.php";
include "controladores/c_cortesRuta.php";
include "controladores/c_tokens.php";
include "controladores/c_ventasxdia.php";
include "controladores/c_ventasxproducto.php";
include "controladores/c_ventasxusuario.php";
include "controladores/c_ventasxcliente.php";
include "controladores/c_recibos.php";
include "controladores/c_reporteinventario.php";
include "controladores/c_ventasxproveedor.php";
include "controladores/c_ventasxproveedorNuevo.php";
include "controladores/c_gastos.php";
include "controladores/c_depositos.php";
include "controladores/c_reporteImportes.php";
include "controladores/c_promociones.php";
include "controladores/c_reporteFacturasEmitidas.php";
include "controladores/c_reporteInventarioGeneral.php";


class controller {

	function _layouts(){
			$omodelo = new m_modelo();
			$_SESSION['user_admin']['FechaFin'] = date('Y-m-d');
			$_SESSION['user_admin']['FechaIni'] = date("Y-m-d", strtotime($_SESSION['user_admin']['FechaFin']."- 30 days")); 

			$fechahoy = date('Y-m-d H:i:s');
			$pagina = file_get_contents('vistas/v_html.php');
			$pagina = str_replace('#NombreUsuario#',$_SESSION['user_admin']['Nombre'],$pagina);
			$pagina = str_replace('#NombreUsuarioNavBar#',$_SESSION['user_admin']['Nombre'],$pagina);
			$pagina = str_replace('#PermisosUsuario#',$_SESSION['user_admin']['Tipo_Usuario'],$pagina);
			$pagina = str_replace('#IDUsuario#',$_SESSION['user_admin']['ID_Usuario'],$pagina);
			$pagina = str_replace('#usuario#',$_SESSION['user_admin']['Nombre'],$pagina);
			$pagina = str_replace('#prl#',substr($_SESSION['user_admin']['Nombre'], 0, 1),$pagina);
			$pagina = str_replace('#fechahoy#',$fechahoy,$pagina);
			$pagina = str_replace('#fechaIni#',$_SESSION['user_admin']['FechaIni'],$pagina);
			$pagina = str_replace('#fechaFin#',$_SESSION['user_admin']['FechaFin'],$pagina);
			
			if ($_SESSION['user_admin']['Foto'] == "") {
					$pagina = str_replace('#ImagenPerfil#', 'vistas/assets/archivos/default.jpg', $pagina);
			}else{
					$pagina = str_replace('#ImagenPerfil#', 'vistas/assets/archivos/fotosUsuarios/'.$_SESSION['user_admin']['Foto'], $pagina);
			}     

			$alertas = '';
			//QUERY ORIGINAL, MANDARLO A JUANCHO PARA QUE LO CHEQUE MAÑANA, SE AGREGO EL SUM
			//SELECT ID_Stock, stock_productos.FK_Producto, Descripcion, Nombre_Unidad, FK_Presentacion, FK_Sucursal, Minimo, Maximo, Nombre, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Producto = stock_productos.FK_Producto AND FK_Presentacion = FK_Presentacion AND FK_Sucursal = FK_Sucursal), 0) AS Cantidad FROM stock_productos INNER JOIN productos ON stock_productos.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion";
			$query = "SELECT ID_Stock, stock_productos.FK_Producto, Descripcion, Nombre_Unidad, FK_Presentacion, FK_Sucursal, Minimo, Maximo, Nombre, IFNULL((SELECT Cantidad FROM inventario WHERE FK_Producto = stock_productos.FK_Producto AND FK_Presentacion = stock_productos.FK_Presentacion AND FK_Sucursal = stock_productos.FK_Sucursal), 0) AS Cantidad FROM stock_productos INNER JOIN productos ON stock_productos.FK_Producto = ID_Producto LEFT JOIN presentaciones ON FK_Presentacion = ID_Presentacion WHERE FK_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				if ($numerofilas > 0) {
					$icono ='<i class="fa-solid fa-bell fa-beat iconoConAlertas" style="--fa-beat-scale: 2.0; color: white;"></i>';
					$pagina = str_replace('#MostrarIcono#', $icono, $pagina);
					for ($i = 0; $i < $numerofilas; $i++) {
						$presentacion = 'Sin presentación';
						if($row[$i]['FK_Presentacion'] == 0){
							if(trim($row[$i]['Nombre_Unidad']) != ''){
								$presentacion = trim($row[$i]['Nombre_Unidad']);
							}
						}else{
							$presentacion = trim($row[$i]['Nombre']);
						}

						if($row[$i]['Cantidad'] > $row[$i]['Maximo']){
							$alertas .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
							  <strong>Se alcanzó el stock máximo de '.$row[$i]['Descripcion'].' '.$presentacion.'</strong>,la cantidad actual es '.$row[$i]['Cantidad'].', el mínimo es '.$row[$i]['Minimo'].'.
							  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>';
						}else if($row[$i]['Cantidad'] < $row[$i]['Minimo']){
							$alertas .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
							  <strong>Se alcanzó el stock mínimo de '.$row[$i]['Descripcion'].' '.$presentacion.'</strong>, la cantidad actual es '.$row[$i]['Cantidad'].', el mínimo es '.$row[$i]['Minimo'].'.
							  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>';
						}
					}
				}else{
					$icono ='<i class="fas fa-bell iconoSinAlertas" style="color: white;"></i>';
					$pagina = str_replace('#MostrarIcono#', $icono, $pagina);
				}
			}

			$pagina = str_replace('#alertas#', $alertas, $pagina);

		///************************* PERMISOS DEL MENU **************************///
		$botonSucursales = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_sucursales'][1] == '1') {
			$botonSucursales = '<li class="menu-item cargarVista" carga="v_sucursales" titulo="Sucursales" id="cargarSucursales">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-map-marker"></i>
                <div data-i18n="Sucursales">Sucursales</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuSucursales#', $botonSucursales, $pagina);

		$botonProveedores = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_proveedores'][1] == '1') {
			$botonProveedores = '<li class="menu-item cargarVista" carga="v_proveedores" titulo="Proveedores" id="cargarProveedores">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-truck-fast"></i>
                <div data-i18n="Proveedores">Proveedores</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuProveedores#', $botonProveedores, $pagina);

		$botonClientes = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_clientes'][1] == '1') {
			$botonClientes = '<li class="menu-item cargarVista" carga="v_clientes" titulo="Clientes" id="cargarClientes">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-face-grin"></i>
                <div data-i18n="Clientes">Clientes</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuClientes#', $botonClientes, $pagina);

		$botonAreas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_areas'][1] == '1') {
			$botonAreas = '<li class="menu-item cargarVista" carga="v_areas" titulo="Áreas" id="cargarAreas">
              <a href="javascript:void(0)"  class="menu-link">
                <div data-i18n="Áreas">Áreas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuAreas#', $botonAreas, $pagina);

		$botonZonas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_zonas'][1] == '1') {
			$botonZonas = '<li class="menu-item cargarVista" carga="v_zonas" titulo="Zonas" id="cargarZonas">
              <a href="javascript:void(0)"  class="menu-link">
                <div data-i18n="Zonas">Zonas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuZonas#', $botonZonas, $pagina);
		

		$botonProductos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_productos'][1] == '1') {
			$botonProductos = '<li class="menu-item cargarVista" carga="v_productos" titulo="Productos" id="cargarProductos">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Productos">Productos</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuProductos#', $botonProductos, $pagina);

		$botonInventario = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][1] == '1') {
			$botonInventario = '<li class="menu-item cargarVista" carga="v_inventario" titulo="Inventario" id="cargarInventario">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Inventario">Inventario</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuInventario#', $botonInventario, $pagina);

		$botonCompras = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_compras'][1] == '1') {
			$botonCompras = '<li class="menu-item cargarVista" carga="v_compras" titulo="Compras" id="cargarCompras">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-basket-shopping"></i>
                <div data-i18n="Compras">Compras</div>
              </a>
            </li>';
		}
        // else if(@$omodelo->permisos()['v_orden_compra'][1] == '1'){
		// 		$botonCompras = '<li class="menu-item cargarVista" carga="v_hacerCompra" titulo="Hacer Compra" id="cargarHacerCompra">
        //     <a href="javascript:void(0)"  class="menu-link">
        //       <i class="menu-icon fas fa-basket-shopping"></i>
        //       <div data-i18n="Compras">Compras</div>
        //     </a>
       	// </li>';
		// }

		$pagina = str_replace('#MenuCompras#', $botonCompras, $pagina);

		$botonVentas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][1] == '1') {
			$botonVentas = '<li class="menu-item cargarVista" carga="v_ventas" titulo="Ventas" id="cargarVentas">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-shopping-cart"></i>
                <div data-i18n="Ventas">Ventas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuVentas#', $botonVentas, $pagina);

		$botonImportes = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_importes'][1] == '1') {
			$botonImportes = '<li class="menu-item cargarVista" carga="v_importes" titulo="Importes" id="cargarImportes">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-money-check"></i>
                <div data-i18n="Importes">Importes</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuImportes#', $botonImportes, $pagina);

		$botonRutas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_rutas'][1] == '1') {
			$botonRutas = '<li class="menu-item cargarVista" carga="v_rutas" titulo="Rutas" id="cargarRutas">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon fas fa-road"></i>
                <div data-i18n="Rutas">Rutas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuRutas#', $botonRutas, $pagina);

		$botonGastos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_gastos'][1] == '1') {
			$botonGastos = '<li class="menu-item cargarVista" carga="v_gastos" titulo="Gastos" id="cargarGastos">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon fas fa-dollar"></i>
                <div data-i18n="Gastos">Gastos</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuGastos#', $botonGastos, $pagina);

		$botonDepositos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_rutas'][1] == '1') {
			$botonDepositos = '<li class="menu-item cargarVista" carga="v_depositos" titulo="Depositos" id="cargarDepositos">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon fas fa-money-bill"></i>
                <div data-i18n="Depositos">Depositos</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuDepositos#', $botonDepositos, $pagina);

		$botonChoferes = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_choferes'][1] == '1') {
			$botonChoferes = '<li class="menu-item cargarVista" carga="v_choferes" titulo="Choferes" id="cargarChoferes">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon fas fa-user-tie"></i>
                <div data-i18n="Choferes">Choferes</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuChoferes#', $botonChoferes, $pagina);

		$botonVehiculos= '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_vehiculos'][1] == '1') {
			$botonVehiculos = '<li class="menu-item cargarVista" carga="v_vehiculos" titulo="Vehículos" id="cargarVehiculos">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon fas fa-truck"></i>
                <div data-i18n="Vehículos">Vehículos</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuVehiculos#', $botonVehiculos, $pagina);


		$botonCortesRuta= '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][1] == '1') {
			$botonCortesRuta = '<li class="menu-item cargarVista" carga="v_cortesRuta" titulo="Cortes Ruta" id="cargarCortesRuta">
              <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon fas fa-clipboard-list"></i>
                <div data-i18n="Cortes Ruta">Cortes Ruta</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuCortesRuta#', $botonCortesRuta, $pagina);

		$botonPrecios = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_precios'][1] == '1') {
			$botonPrecios = '<li class="menu-item cargarVista" carga="v_precios" titulo="Precios" id="cargarPrecios">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Precios">Precios</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuPrecios#', $botonPrecios, $pagina);

		$botonPromociones = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_promociones'][1] == '1') {
			$botonPromociones = '<li class="menu-item cargarVista" carga="v_promociones" titulo="Promociones" id="cargarPromociones">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Promociones">Promociones</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuPromociones#', $botonPromociones, $pagina);

		$botonCategorias = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_categorias'][1] == '1') {
			$botonCategorias = '<li class="menu-item cargarVista" carga="v_categorias" titulo="Familias" id="cargarCategorias">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Categorias">Familias (Categorias)</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#MenuCategorias#', $botonCategorias, $pagina);

		$botonCajas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cajas'][1] == '1') {
			$botonCajas = '<li class="menu-item cargarVista" carga="v_cajas" titulo="Cajas" id="cargarCajas">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-cash-register"></i>
                <div data-i18n="Cajas">Cajas</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuCajas#', $botonCajas, $pagina);

		$botonImpuestos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_impuestos'][1] == '1') {
			$botonImpuestos = '<li class="menu-item cargarVista" carga="v_impuestos" titulo="Impuestos" id="cargarImpuestos">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fa-solid fa-money-bill-transfer"></i>
                <div data-i18n="Impuestos">Impuestos</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuImpuestos#', $botonImpuestos, $pagina);

		$botonUsuarios = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_usuarios'][1] == '1') {
			$botonUsuarios = '<li class="menu-item cargarVista" carga="v_usuarios" titulo="Usuarios" id="cargarUsuarios">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-user"></i>
                <div data-i18n="Usuarios">Usuarios</div>
              </a>
            </li>';
		}
		$pagina = str_replace('#MenuUsuarios#', $botonUsuarios, $pagina);

		$botonTicket = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_tickets'][1] == '1') {
			$botonTicket = '<ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_tickets" titulo="Ticket" id="cargarTicket">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Tickets">Ticket</div>
                  </a>
                </li>
              </ul>';
		}
		$pagina = str_replace('#MenuTicket#', $botonTicket, $pagina);

		$botonFacturacion = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_facturacion'][1] == '1') {
			$botonFacturacion = ' <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_facturacion" titulo="Facturación 4.0" id="cargarFacturacion">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Facturación">Facturación 4.0</div>
                  </a>
                </li>
              </ul>';
		}
		$pagina = str_replace('#MenuFacturacion#', $botonFacturacion, $pagina);

		$botonProd = '';
		if($botonProductos != '' || $botonInventario != '' || $botonCategorias != '' || $botonAreas != '' || $botonPrecios != ''){
			  $botonProd = '<a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon fas fa-boxes-stacked"></i>
            <div data-i18n="Layouts">Productos</div>
        </a>';
    }
    $pagina = str_replace('#menuProd#', $botonProd, $pagina);

    $botonRecibos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_recibos'][1] == '1') {
			$botonRecibos = ' <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_recibos" titulo="Recibos" id="cargarRecibos">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Recibos">Recibos</div>
                  </a>
                </li>
              </ul>';
		}
		$pagina = str_replace('#MenuRecibos#', $botonRecibos, $pagina);

		$reportesInventario = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][10] == '1') {
			$reportesInventario = '<li class="menu-item cargarVista" carga="v_reporteinventario" titulo="Reporte de inventario" id="cargarReporteVentasxCliente">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Reporte de inventario">Reporte de inventario</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesInventario#', $reportesInventario, $pagina);

		$ventasXProveedor = '';
		if ($omodelo->permisos() == 'Administrador') {
			$ventasXProveedor = '<li class="menu-item cargarVista" carga="v_ventasxproveedor" titulo="Total de ventas por proveedor" id="cargarReporteVentasxProveedor">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Total de ventas por proveedor">Total de ventas por proveedor</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#ventasXProveedor#', $ventasXProveedor, $pagina);

		$ventasXProveedorNuevo = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][9] == '1') {
			$ventasXProveedorNuevo = '<li class="menu-item cargarVista" carga="v_ventasxproveedorNuevo" titulo="Ventas por Proveedor" id="cargarReporteVentasxProveedor">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Ventas por proveedor">Ventas por proveedor</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#ventasXProveedorNuevo#', $ventasXProveedorNuevo, $pagina);

		$reporteImportes = '';
		if ($omodelo->permisos() == 'Administrador') {
			$reporteImportes = '<li class="menu-item cargarVista" carga="v_reporteImportes" titulo="Reporte de Importes" id="cargarReporteImportes">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Reporte de Importes">Reporte de Importes</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reporteImportes#', $reporteImportes, $pagina);

		$botonConfi = '';
		if($botonFacturacion != '' || $botonTicket != '' || $botonRecibos != ''){
			  $botonConfi = '<a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon fas fa-cogs"></i>
            <div data-i18n="Layouts">Configuración</div>
        </a>';
    }
    $pagina = str_replace('#menuConfi#', $botonConfi, $pagina);

    $botonReporteCaja = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][1] == '1') {
			$botonReporteCaja = '<li class="menu-item cargarVista" carga="v_reporteCaja" titulo="Reporte de caja" id="cargaReporteCaja">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Reporte de caja">Reporte de caja</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesCaja#', $botonReporteCaja, $pagina);
    
		$botonReporteProductos = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][2] == '1') {
			$botonReporteProductos = '<li class="menu-item cargarVista" carga="v_reporteProductos" titulo="Reporte productos" id="cargarReporteProductos">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Productos">Productos</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesProductos#', $botonReporteProductos, $pagina);

    $botonReporteClientes = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][3] == '1') {
			$botonReporteClientes = '<li class="menu-item cargarVista" carga="v_reporteClientes" titulo="Reporte clientes" id="cargarReporteClientes">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Clientes">Clientes</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesClientes#', $botonReporteClientes, $pagina);

		$botonReporteVentas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][4] == '1') {
			$botonReporteVentas = '<li class="menu-item cargarVista" carga="v_reporteVentas" titulo="Reporte ventas" id="cargarReporteVentas">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Ventas">Vendedores</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesVentas#', $botonReporteVentas, $pagina);

		$botonReporteCompras = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][5] == '1') {
			$botonReporteCompras = '<li class="menu-item cargarVista" carga="v_reporteCompras" titulo="Reporte compras" id="cargarReporteCompras">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Compras">Compras</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesCompras#', $botonReporteCompras, $pagina);

		$botonReporteFinanzas = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][6] == '1') {
			$botonReporteFinanzas = '<li class="menu-item cargarVista" carga="v_reporteFinanzas" titulo="Reporte finanzas" id="cargarReporteFinanzas">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Finanzas">Finanzas</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reportesFinanzas#', $botonReporteFinanzas, $pagina);

		$botonReporteVentasxDia = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][7] == '1') {
			$botonReporteVentasxDia = '<li class="menu-item cargarVista" carga="v_ventasxdia" titulo="Ventas por día" id="cargarReporteVentasxDia">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Ventas por día">Ventas por día</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#ventasxDia#', $botonReporteVentasxDia, $pagina);

		$botonReporteVentasxProducto = '';
		if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_reportes'][8] == '1') {
			$botonReporteVentasxProducto = '<li class="menu-item cargarVista" carga="v_ventasxproducto" titulo="Ventas por producto" id="cargarReporteVentasxProducto">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Ventas por producto">Ventas por producto</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#ventasxProducto#', $botonReporteVentasxProducto, $pagina);

		$botonReporteVentasxUsuario = '';
		if ($omodelo->permisos() == 'Administrador') {
			$botonReporteVentasxUsuario = '<li class="menu-item cargarVista" carga="v_ventasxusuario" titulo="Ventas por Usuario" id="cargarReporteVentasxUsuario">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Ventas por Usuario">Ventas por Usuario</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#ventasxUsuario#', $botonReporteVentasxUsuario, $pagina);

		$botonReporteVentasxCliente = '';
		if ($omodelo->permisos() == 'Administrador') {
			$botonReporteVentasxCliente = '<li class="menu-item cargarVista" carga="v_ventasxcliente" titulo="Ventas por Cliente" id="cargarReporteVentasxCliente">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="Ventas por Cliente">Ventas por Cliente</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#ventasxCliente#', $botonReporteVentasxCliente, $pagina);

        // Opción menú reporte por facturas emitidas.
        $botonReporteFacturasEmitidas = '';
		if ($omodelo->permisos() == 'Administrador') {
			$botonReporteFacturasEmitidas = '<li class="menu-item cargarVista" carga="v_reporteFacturasEmitidas" titulo="Facturas emitidas" id="cargarReporteFacturasEmitidas">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="jajaj">Facturas emitidas</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#facturasEmitidas#', $botonReporteFacturasEmitidas, $pagina);

        // Opción menú reporte de inventario general.
        $botonReporteInventarioGeneral = '';
		if ($omodelo->permisos() == 'Administrador') {
			$botonReporteInventarioGeneral = '<li class="menu-item cargarVista" carga="v_reporteInventarioGeneral" titulo="Inventario general" id="cargarReporteInventarioGeneral">
                  <a href="javascript:void(0)" class="menu-link">
                    <div data-i18n="jajaj">Reporte Inventario general</div>
                  </a>
                </li>';
		}
		$pagina = str_replace('#reporteInventarioGeneral#', $botonReporteInventarioGeneral, $pagina);

		$botonReportes = '';
		if($reportesInventario != "" || $botonReporteCaja != '' || $botonReporteProductos != '' || $botonReporteClientes != '' || $botonReporteVentas != '' || $botonReporteCompras != '' || $botonReporteFinanzas != '' || $botonReporteVentasxDia != '' || $botonReporteVentasxProducto != '' || $botonReporteVentasxUsuario != '' || $botonReporteVentasxCliente != '' || $botonReporteFacturasEmitidas != '' || $botonReporteInventarioGeneral != ''){
    		$botonReportes = '<a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon fas fa-chart-line"></i>
                  <div data-i18n="Layouts">Reportes</div>
              </a>';
    }
    $pagina = str_replace('#menuReportes#', $botonReportes, $pagina);



    $botonTokens = '';
		if ($omodelo->permisos() == 'Administrador') {
			$botonTokens = ' <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_tokens" titulo="Tokens" id="cargarTokens">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Tokens">Tokens</div>
                  </a>
                </li>
              </ul>';
		}
		$pagina = str_replace('#MenuTokens#', $botonTokens, $pagina);

		$botonRecibos = '';
		if ($omodelo->permisos() == 'Administrador') {
			$botonRecibos = ' <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_recibos" titulo="Recibos" id="cargarRecibos">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="Recibos">Recibos</div>
                  </a>
                </li>
              </ul>';
		}
		$pagina = str_replace('#MenuRecibos#', $botonRecibos, $pagina);

    $venta = '';
    /*if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][2] == '1') {
    	$venta = file_get_contents('vistas/v_hacerventa.php');
    	$venta = $this->remplazar($venta, 'v_hacerventa');
    }*/

    $pagina = str_replace('#verVista#', $venta, $pagina);

		return $pagina;
	}

	function _contenido($vista){
		$pagina = file_get_contents("vistas/$vista.php");
		$pagina = $this->remplazar($pagina, $vista);

		return $pagina;
	}

	function _consultar($metodo){
		$objeto = new $metodo();
		$objeto->_consultar();
	}

	function _insertar($metodo){
		$objeto = new $metodo();
		$objeto->_insertar();
	}

	function _modificar($metodo){
		$objeto = new $metodo();
		$objeto->_modificar();
	}

	function _eliminar($metodo){
		$objeto = new $metodo();
		$objeto->_eliminar();
	}

	function _detalles($metodo){
		$objeto = new $metodo();
		$objeto->_detalles();
	}

	function _validar($pagina,$campo,$valo){
		$oobjeto = new $pagina();
		$oobjeto->_consultaValidar($campo,$valo);
	}

	function remplazar($pagina, $nombre){
		$omodelo = new m_modelo();
		extract($_POST);
		$fecha = date('Y-m-d');

		if($nombre == "v_inicio"){

		}else if($nombre == "v_productos"){

			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_productos'][2] == '0') {
				echo '<script>$("#botonNuevoProductos").remove();</script>';
			}

			$query = "SELECT ID_Categoria, Nombre FROM categorias";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Categoria'] . '">' . $row[$i]['Nombre'] . '</option>';
					}
				}
			}

			$pagina = str_replace('#categorias#', $opciones, $pagina);

			$query = "SELECT ID_Area, Nombre FROM areas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Area'] . '">' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#areas#', $opciones, $pagina);

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#sucursales#', $opciones, $pagina);
			$pagina = str_replace('#sucursalesModificar#', $opciones, $pagina);

			$query = "SELECT ID_Zona, Nombre FROM zonas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Zona'].'">'.$row[$i]['Nombre'].'</option>';
					}
				}
			}

			$pagina = str_replace('#zonas#', $opciones, $pagina);
			$pagina = str_replace('#CargarZonasPrecioNuevo#', $opciones, $pagina);

			$query = "SELECT ID_Proveedor, Nombre, Empresa FROM proveedores WHERE ID_Proveedor != '1'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="'.$row[$i]['ID_Proveedor'].'">'.$row[$i]['Empresa'].'/'.$row[$i]['Nombre'].'</option>';
					}
				}
			}

			$pagina = str_replace('#proveedores#', $opciones, $pagina);

		}else if($nombre == "v_inventario"){
			$botonPermisosTraslados = "";
			if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][10] == '1') {
					$botonPermisosTraslados = '<button type="button" class="btn btn-primary" id="bVerTraslados"><i class="fa-solid fa-arrows-left-right"></i> Traslados</button>';
			}

			$pagina = str_replace('#botonTraslados#', $botonPermisosTraslados, $pagina);

			$botonAgregarTras = "";
			if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_inventario'][11] == '1') {
					$botonAgregarTras = '<button type="button" class="btn btn-primary" id="bAgregarTraslado"><i class="fas fa-plus"></i> Agregar</button>';
			}

			$pagina = str_replace('#bAgregarTraslado#', $botonAgregarTras, $pagina);
					
			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#sucursales#', $opciones, $pagina);

		}else if($nombre == "v_perfil"){
			if ($_SESSION['user_admin']['Foto'] == "") {
				$pagina = str_replace('#RutaImagenPerfil#', 'vistas/assets/archivos/default.jpg', $pagina);
			}else{
				$pagina = str_replace('#RutaImagenPerfil#', 'vistas/assets/archivos/fotosUsuarios/'.$_SESSION['user_admin']['Foto'], $pagina);
			}

			$pagina = str_replace('#CorreoActual#', $_SESSION['user_admin']['Correo'], $pagina);

		}else if($nombre == "v_sucursales"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_sucursales'][2] == '0') {
				echo '<script>$("#bontonNuevoSu").remove();</script>';
			}

			$query = "SELECT ID_Usuario, Nombre, Primer_Apellido, Segundo_Apellido FROM usuarios";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Usuario'] . '" >' . $row[$i]['Nombre'].' '. $row[$i]['Primer_Apellido'].' '. $row[$i]['Segundo_Apellido']. '</option>';
					}
				}
			}

			$pagina = str_replace('#usuarios#', $opciones, $pagina);

			$query = "SELECT ID_Zona, Nombre FROM zonas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Zona'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#ZonasSucursales#', $opciones, $pagina);

		}else if($nombre == "v_proveedores"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_proveedores'][2] == '0') {
				echo '<script>$("#bontonNuevoProve").remove();</script>';
			}
		}else if($nombre == "v_clientes"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_clientes'][2] == '0') {
				echo '<script>$("#botonNuevoCliente").remove();</script>';
			}

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesCliente#', $opciones, $pagina);

			$opcionesRutas = '';
			$query = "SELECT ID_Ruta, Nombre FROM rutas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == "si"){
				echo "Error: ". mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opcionesRutas .= '<option value="'.$row[$i]['ID_Ruta'].'">'.$row[$i]['Nombre'].'</option>';
					}
				}
			}

			$pagina = str_replace('#RutasCliente#', $opcionesRutas, $pagina);

		}else if($nombre == "v_areas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_areas'][2] == '0') {
				echo '<script>$("#botonNuevaArea").remove();</script>';
			}
			
		}else if($nombre == "v_ventas"){
			if ($omodelo->permisos() != 'Administrador') {
				echo '<script>$("#bUsarCajaDif").remove();</script>';
			}

            if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_ventas'][2] == '0') {
				echo '<script>$("#bExcelExportarVentas").remove();</script>';
			}

			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_ventas'][3] == '0') {
				echo '<script>$("#BotonNuevaVenta").remove();</script>';
			}

			$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
			
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal AND cajas.Estado = 0 $tipo ORDER BY (ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."') DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesVentas#', $opciones, $pagina);

			// $tipo = '';
			// if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
			// 		$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			// }
			
			// $query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal $tipo ORDER BY (ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."') DESC";
			// $row = $omodelo->_consultar($query);
			// $numerofilas = $omodelo->numerofilas;
			// $opciones2 = "";

			// if ($row == "si") {
			// 	echo "Error: " . mysqli_error($omodelo->link);
			// } else {
			// 	if ($numerofilas > 0) {
			// 		for ($i = 0; $i < $numerofilas; $i++) {
			// 			$opciones2 .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
			// 		}
			// 	}
			// }
			// $pagina = str_replace('#SucursalesModuloVentas#', $opciones2, $pagina);

            // Consulta de clientes para el select de filtro por cliente en el modulo ventas.
			$tipo = '';
			// if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
			// 		$tipo = "WHERE detalles_clientes_sucursal.FK_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			// }

			$query = "SELECT clientes.ID_Cliente AS ID_Cliente, CONCAT(clientes.Nombre, ' ', clientes.Primer_Apellido, ' ', clientes.Segundo_Apellido) AS Nombre_Cliente FROM clientes LEFT JOIN detalles_clientes_sucursal ON clientes.ID_Cliente = detalles_clientes_sucursal.FK_Cliente $tipo GROUP BY clientes.ID_Cliente, Nombre_Cliente";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones3 = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
                    $opciones3 = '<option value="todos" >Todos los clientes</option>';
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones3 .= '<option value="' . $row[$i]['ID_Cliente'] . '" >' . $row[$i]['Nombre_Cliente']. '</option>';
					}
				}
			}
			$pagina = str_replace('#ClientesModuloVentas#', $opciones3, $pagina);
			
		}else if($nombre == "v_rutas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_rutas'][2] == '0') {
				echo '<script>$("#botonNuevaRuta").remove();</script>';
			}
		}else if($nombre == "v_vehiculos"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_vehiculos'][2] == '0') {
				echo '<script>$("#bNuevoVehiculo").remove();</script>';
			}
		}else if($nombre == "v_zonas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_zonas'][2] == '0') {
				echo '<script>$("#botonNuevaZona").remove();</script>';
			}
			
		}else if($nombre == "v_compras"){
			// print_r($omodelo->permisos());
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_compras'][2] == '0') {
				echo '<script>$(".botonNuevaCompra").remove();</script>';
			}
			
		}else if($nombre == "v_categorias"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_categorias'][2] == '0') {
				echo '<script>$("#botonNuevaCategoria").remove();</script>';
			}
		}else if($nombre == "v_cajas"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_cajas'][2] == '0') {
				echo '<script>$("#botonNuevaCaja").remove();</script>';
			}
		}else if($nombre == "v_recibos"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_recibos'][2] == '0') {
				echo '<script>$("#botonNuevoRecibo").remove();</script>';
			}
		}else if($nombre == "v_impuestos"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_impuestos'][2] == '0') {
				echo '<script>$("#botonNuevoImpuesto").remove();</script>';
			}
		}else if($nombre == "v_usuarios"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_usuarios'][2] == '0') {
				echo '<script>$("#botonNuevoUsuario").remove();</script>';
			}

			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_productos'][7] == '0') {
				echo '<script>$("#botonAgregarNuevoPrecio").remove();</script>';
			}

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesUsuarios#', $opciones, $pagina);
		}else if($nombre == "v_tickets"){

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#sucursales#', $opciones, $pagina);
		}else if($nombre == "v_hacerCompra"){
			if ($omodelo->permisos() != 'Administrador' && @$omodelo->permisos()['v_compras'][2] == '0'){
					echo '<script>
						$("#RealizarCompra").addClass("oculto");
						$("#cambiarTipoCompra").addClass("oculto");
						$("#ponerDescuento").addClass("oculto");
						$("#verTotal").addClass("oculto");
						$("#verSubtotal").addClass("oculto");
					</script>';

				// if(@$omodelo->permisos()['v_compras'][2] == '0' || @$omodelo->permisos()['v_ordenes_compra'][5] == '0'){
				// 	echo '<script>$(".costoOculto").addClass("oculto")</script>
				// 	<style>
				// 		.costoP, .totalP{
				// 			display: none;
				// 		}
				// 	</style>';
				// }
			}

            $sucursal = "";
            $query = "";
            if ($omodelo->permisos() != 'Administrador') {
                $query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales, usuarios WHERE ID_Sucursal = FK_Sucursal AND ID_Usuario = '".$_SESSION['user_admin']['ID_Usuario']."'";
                $row = $omodelo->_consultar($query);
                $numerofilas = $omodelo->numerofilas;

                if ($row == "si") {
                    echo "Error: " . mysqli_error($omodelo->link);
                } else {
                    if ($numerofilas > 0) {
                        $sucursal = 'Sucursal: <br><b id="SucursalHacerCompra" attrID="' .$row[0]['ID_Sucursal']. '" value="'.$row[0]["ID_Sucursal"].'">'.$row[0]["Nombre"].'</b>';
                    }
                }
            } else {
                $query = "SELECT sucursales.ID_Sucursal AS ID_Sucursal, sucursales.Nombre AS NombreSucursal FROM sucursales";
                $row = $omodelo->_consultar($query);
                $numerofilas = $omodelo->numerofilas;

                if ($row == "si") {
                    echo "Error: " . mysqli_error($omodelo->link);
                } else {
                    if ($numerofilas > 0) {
                        $opciones = '';
                        for ($i = 0; $i < $numerofilas; $i++) {
                            $opciones .= '<option value="' .$row[$i]['ID_Sucursal']. '" name="' .$row[$i]['NombreSucursal']. '">' . $row[$i]['NombreSucursal']. '</option>';
                        }
                        $sucursal = '<div class="form-floating">
                                        <select class="form-select" attrID="' .$row[0]['ID_Sucursal']. '" name="SucursalHacerCompra" id="SucursalHacerCompra">
                                        ' .$opciones. '
                                        </select>	
                                        <label for="SucursalHacerCompra">Sucursal</label>
                                    </div>';
                    }
                }
                // // codigo raro
                // if($numerofilas == 0){
                //     $query2 = "SELECT ID_Sucursal, Nombre FROM sucursales";
                //     $row2 = $omodelo->_consultar($query2);
                //     $numerofilas2 = $omodelo->numerofilas;
                //     $opciones = '';

                //     if ($row2 == "si") {
                //         echo "Error: " . mysqli_error($omodelo->link);
                //     } else {
                //         if ($numerofilas2 > 0) {
                //             for ($i = 0; $i < $numerofilas2; $i++) {
                //                 $opciones .='<option value="' . $row2[$i]['ID_Sucursal'] . '" >' . $row2[$i]['Nombre']. '</option>';
                //             }
                //             $sucursal = '<div class="form-floating">
                //                         <select class="form-select" id="Sucursales" name="Sucursales">
                //                             '.$opciones.'
                //                         </select>
                //                         <label for="Sucursales">Sucursal</label>
                //                         </div>';
                //         }
                //     }
                // }
            }

			$pagina = str_replace('#sucursal#', $sucursal, $pagina);
                    
		}else if($nombre == "v_hacerventa"){
			$idSucursal = $_SESSION['user_admin']['FK_Sucursal'];
			if($atri != ''){
				$idSucursal = $atri;
			}

			$query = "SELECT ID_Sucursal, Nombre FROM sucursales WHERE ID_Sucursal = '$idSucursal'";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$sucursal = '';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					$sucursal = 'Sucursal: <br><b id="SucursalVenta" attrid="'.$row[0]["ID_Sucursal"].'">'.$row[0]["Nombre"].'</b>';
				/*}else{
					$query2 = "SELECT ID_Sucursal, Nombre FROM sucursales";
					$row2 = $omodelo->_consultar($query2);
					$numerofilas2 = $omodelo->numerofilas;
					$opciones = "";
					if ($row2 == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
					} else {
						for ($i=0; $i < $numerofilas2; $i++) { 
							$opciones .= '<option value="'.$row2[$i]["ID_Sucursal"].'">'.$row2[$i]["Nombre"].'</option>';
						}
						$sucursal = '
						<div class="form-floating">
		         	<select class="form-select" id="SucursalVenta" name="SucursalVenta" attrid="'.$row2[0]["ID_Sucursal"].'">
		          	'.$opciones.'
		          </select>
		        	<label for="SucursalVenta">Sucursal</label>
						</div>';
					}*/
				}
			}
			$pagina = str_replace('#MostrarSucursal#', $sucursal, $pagina);    

			$botonAfectarBalance = "";
			if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][9] == '1') {
				$botonAfectarBalance = '
						<div class="col-md-12 col-sm-12 mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="ContarVenta" name="ContarVenta">
                  <label class="form-check-label" for="ContarVenta">
                    Esta venta no cuenta para el balance ni afecta el inventario
                  </label>
                </div>
            </div>';
			}

			$pagina = str_replace('#BotonAfectarBalance#', $botonAfectarBalance, $pagina); 


		  $botonCorteCaja = '';
			if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][8] == '1') {
		 		$botonCorteCaja = '<button type="button" style="height: 40px;" class="btn btn-outline-danger oculto" id="BotonCerrarCaja" attrid="">
		                  <i class="fas fa-times"></i> Hacer corte
		                </button>';
	    }

	    $pagina = str_replace('#BotonCorteCaja#', $botonCorteCaja, $pagina);
   
	    $BotonModificarVentas = '';
	    if ($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_ventas'][11] == '1') {
	    	$BotonModificarVentas = '<button type="button" style="height: 40px;" attrid class="btn btn-outline-secondary" id="CargarModalModificarVentas">Ventas</button>';
	    }
   
	    $pagina = str_replace('#BotonModificarVentas#', $BotonModificarVentas, $pagina);

		}else if($nombre == "v_facturacion"){
				$query = "SELECT RFC, Nombre, Regimen FROM general WHERE ID_General = '1'";
				$row = $omodelo->_consultar($query);
				$numerofilas = $omodelo->numerofilas;
					
				if ($row == "si") {
						echo "Error: " . mysqli_error($omodelo->link);
				}else{
						if($numerofilas > 0){ 
								$pagina = str_replace('#rfc#', $row[0]['RFC'], $pagina);
								$pagina = str_replace('#nombre#', $row[0]['Nombre'], $pagina);
								echo '<script>$("#regimenFacturacion").val('.$row[0]['Regimen'].');</script>';
						}
				}		
		}else if($nombre == "v_precios"){
				$query = "SELECT ID_Zona, Nombre, Descripcion FROM zonas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			
			$zonas = '';	
			if ($row == "si") {
					echo "Error: " . mysqli_error($omodelo->link);
			}else{
					if($numerofilas > 0){ 
							for ($i=0; $i < $numerofilas; $i++) { 
									$zonas .= '<option value="'.$row[$i]['ID_Zona'].'">'.$row[$i]['Nombre'].'</option>';
							}
					}
			}			

			$pagina = str_replace('#zonas#', $zonas, $pagina);
			$pagina = str_replace('#CargarZonasPrecio#', $zonas, $pagina);
			$pagina = str_replace('#CargarZonasPrecioNuevo#', $zonas, $pagina);
		}else if($nombre == "v_choferes"){
			$query = "SELECT ID_Vehiculo, Modelo, Marca, Descripcion FROM vehiculos";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			$opcionesVehiculos = '';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opcionesVehiculos .= '<option value="'.$row[$i]['ID_Vehiculo'].'">Modelo: '.$row[$i]['Modelo'].', Marca: '.$row[$i]['Marca']. ( $row[$i]['Descripcion'] ? ', Descripcion: '.$row[$i]['Descripcion'].'' : '' ) .'</option>';
					}
				}
			}
			$pagina = str_replace('#VehiculosChofer#', $opcionesVehiculos, $pagina);
		}else if($nombre == "v_cortesRuta"){
			$opcionesRutasCorte = '';
			$query = "SELECT ID_Ruta, Nombre FROM rutas";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if($row == "si"){
				echo "Error: ". mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opcionesRutasCorte .= '<option value="'.$row[$i]['ID_Ruta'].'">'.$row[$i]['Nombre'].'</option>';
					}
				}
			}

			$pagina = str_replace('#RutasCorte#', $opcionesRutasCorte, $pagina);
			$pagina = str_replace('#OpcionesFiltroRutas#', $opcionesRutasCorte, $pagina);


			$query = "SELECT ID_Chofer, Nombre, Primer_Apellido, Segundo_Apellido FROM choferes";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			$opcionesChoferesCorte = '';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opcionesChoferesCorte .= '<option value="'.$row[$i]['ID_Chofer'].'">'.$row[$i]['Nombre'] .' '.$row[$i]['Primer_Apellido'] .' '.$row[$i]['Segundo_Apellido'] .'</option>';
					}
				}
			}
			$pagina = str_replace('#SelectChofer#', $opcionesChoferesCorte, $pagina);


			$query = "SELECT ID_Vehiculo, Modelo, Marca, Descripcion FROM vehiculos";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			$opcionesVehiculosCorte = '';
			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($i=0; $i < $numerofilas; $i++) { 
						$opcionesVehiculosCorte .= '<option value="'.$row[$i]['ID_Vehiculo'].'">Modelo: '.$row[$i]['Modelo'].', Marca: '.$row[$i]['Marca']. ( $row[$i]['Descripcion'] ? ', Descripcion: '.$row[$i]['Descripcion'].'' : '' ) .'</option>';
					}
				}
			}
			$pagina = str_replace('#SelectVehiculo#', $opcionesVehiculosCorte, $pagina);

			$opcionesSucursalesCorte = "";
			$query = "SELECT ID_Sucursal, Nombre, Calle, Ciudad, Estado, Pais FROM sucursales";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			}else{
				if($numerofilas > 0){
					for ($j=0; $j < $numerofilas; $j++) { 
						$opcionesSucursalesCorte .= '<option value="'.$row[$j]['ID_Sucursal'].'">'.$row[$j]['Nombre'].', '.$row[$j]['Calle'].', '.$row[$j]['Ciudad'].', '.$row[$j]['Estado'].', '.$row[$j]['Pais'].'</option>';
					}
				}
			}
			$pagina = str_replace('#selectSucursal#', $opcionesSucursalesCorte, $pagina);
			$pagina = str_replace('#OpcionesFiltroSucursales#', $opcionesSucursalesCorte, $pagina);

			$botonSubirFile = '';
			if($omodelo->permisos() == 'Administrador' || @$omodelo->permisos()['v_cortesRuta'][6] == '1'){
				$botonSubirFile = '<button class="btn btn-warning" type="button" id="uploadImgBtn"><i class="fa-solid fa-upload" style="margin-right: 10px;"></i> <strong>Subir archivo</strong></button>';
			}
			$pagina = str_replace('#botonSubirArchivo#', $botonSubirFile, $pagina);
		}else if($nombre == "v_ventasxdia"){

			$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
			
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal $tipo ORDER BY (ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."') DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesVentasXDia#', $opciones, $pagina);
		}else if($nombre == "v_reporteFacturasEmitidas"){

			$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
			
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal $tipo ORDER BY (ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."') DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesFacturasEmitidas#', $opciones, $pagina);
		}else if($nombre == "v_ventasxproducto"){

			$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
			
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal $tipo ORDER BY (ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."') DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesVentasXProducto#', $opciones, $pagina);
		}else if($nombre == "v_ventasxusuario"){

			$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
			
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal $tipo ORDER BY (ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."') DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesVentasXUsuario#', $opciones, $pagina);
		}else if($nombre == "v_ventasxcliente"){

			$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
			
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal $tipo ORDER BY (ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."') DESC";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#SucursalesVentasXCliente#', $opciones, $pagina);
		}else if($nombre == "v_reporteinventario"){

			/*$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}*/
			
			$query = "SELECT ID_Proveedor, IF(Empresa = '', Nombre, Empresa) AS Empresa FROM proveedores";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Proveedor'] . '" >' . $row[$i]['Empresa']. '</option>';
					}
				}
			}

			$pagina = str_replace('#ProveedoresReporteInventario#', $opciones, $pagina);
		}else if($nombre == "v_reporteCaja"){

			$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}
			
			$query = "SELECT ID_Sucursal, sucursales.Nombre AS Nombre FROM sucursales INNER JOIN cajas ON FK_Sucursal = ID_Sucursal $tipo";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Sucursal'] . '" >' . $row[$i]['Nombre']. '</option>';
					}
				}
			}

			$pagina = str_replace('#OpcionesSucursalesCorteCaja#', $opciones, $pagina);
		}else if($nombre == "v_ventasxproveedor"){

			/*$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}*/
			
			$query = "SELECT ID_Proveedor, IF(Empresa = '', Nombre, Empresa) AS Empresa FROM proveedores";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Proveedor'] . '" >' . $row[$i]['Empresa']. '</option>';
					}
				}
			}

			$pagina = str_replace('#ProveedoresReporteProducto#', $opciones, $pagina);
		}else if($nombre == "v_ventasxproveedorNuevo"){

			/*$tipo = '';
			if($_SESSION['user_admin']['Tipo_Usuario'] == 'Normal'){
					$tipo = "WHERE ID_Sucursal = '".$_SESSION['user_admin']['FK_Sucursal']."'";
			}*/
			
			$query = "SELECT ID_Proveedor, IF(Empresa = '', Nombre, Empresa) AS Empresa FROM proveedores";
			$row = $omodelo->_consultar($query);
			$numerofilas = $omodelo->numerofilas;
			$opciones = "";

			if ($row == "si") {
				echo "Error: " . mysqli_error($omodelo->link);
			} else {
				if ($numerofilas > 0) {
					for ($i = 0; $i < $numerofilas; $i++) {
						$opciones .= '<option value="' . $row[$i]['ID_Proveedor'] . '" >' . $row[$i]['Empresa']. '</option>';
					}
				}
			}

			$pagina = str_replace('#ProveedoresReporteProducto#', $opciones, $pagina);
		}
		
		
		return $pagina;
	}
}
?>