<div>
	<div id="content" class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-12">
					<h1 style="font-weight: bold;" id="vistaTitulo"></h1>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12 text-end">
                    <!-- Boton exportar excel ventas -->
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelExportarVentas">Exportar ventas <i class="fas fa-file-excel"></i></a>
                    <!-- Fin boton -->
					<button type="button" class="btn btn-success" id="BotonNuevaVenta"><i class="fa fa-file"></i> Nueva</button> 
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_ventas" titulo="Ventas"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="FechaInicioModuloVentas" name="FechaInicioModuloVentas" placeholder="Fecha Inicio">
                        <label>Desde</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="FechaFinalModuloVentas" name="FechaFinalModuloVentas" placeholder="Fecha Final">
                        <label>Hasta</label>
                    </div>
                </div>
                <!-- Boton para abrir modal de filtro de sucursales por jorge luis cedillo de anda. -->
                <div class="col-md-3">
                        <button type="button" style="height: 54px; width: 100%;" class="btn btn-outline-secondary" id="CargarSucursalesModalMVentas">
	                        <i class="fas fa-search"></i> Sucursales
	                    </button>
                    <!-- <div class="form-floating">
                        <select class="form-select" name="SucursalModuloVentas" id="SucursalModuloVentas" required>
                            #SucursalesModuloVentas# 
                        </select>   
                        <label for="SucursalModuloVentas">Sucursal</label>
                    </div> -->
                </div>
                <!-- Filtro por clientes hecho por jorge luis cedillo de anda -->
                <div class="col-md-3">
                    <div class="form-floating" id="padreSelectFiltroClientes">
                        <select class="form-select" name="clientesModuloVentas" id="clientesModuloVentas" required>
                            #ClientesModuloVentas# 
                        </select>   
                        <label for="clientesModuloVentas">Cliente</label>
                    </div>
                </div>
            </div>
            <br>    
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteVentas" width="100%" style="font-size: 12px;">
                    <thead>
                        <th style="width: 20%;">Datos</th>
                        <th style="width: 25%;" orden="No">Cliente</th>
                        <th style="width: 20%;">Total</th>
                        <th style="width: 5%;">Facturada</th>
                        <th style="width: 15%;" orden="No">Detalles</th>
                        <th style="width: 15%;" orden="No">Acciones</th>
                    </thead>
                    <tbody>
                           
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td>Totales</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
		        </table>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
</div>

<!-- Modal para filtrar entre varias sucursales -->
<div class="modal fade" id="ModalSucursalesMVentas" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Sucursales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div class="col-12" id="divTablaSucursalesMVentas">
                        <table class="table text-center myDataTable" id="TablaSucursalesMVentas" width="100%">
                            <thead>
                                <tr>
                                    <th orden="No">Seleccionar</th>
                                    <th>Sucursal</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>
                </div>    
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn BotonDatosPrecioInvenGeneral" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="button" class="btn btn-primary" id="SeleccionarSucursalesMarcadasMVentas">
                	<i class="fa fa-check-circle"></i> <strong>Seleccionar Sucursales</strong>
                </button>
            </div>
        </div>    
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalReasignarCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reasignar cliente a la venta <span id="FolioVentaReasignar"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormReasignarClientes">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <label>Cliente actual:</label>
                            <label id="ClienteActualVenta" attrid></label>
                        </div>
                    </div>
                    <br>
                    <div class="row"  id="Padre">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="ClientesReasignar" id="ClientesReasignar" style="width: 100%">
                                    <!-- #ListaClientesReasignar# -->
                                </select>
                                <label>Cliente a reasignar</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="ReasignarVentaCliente" attrid=""><i class="fa fa-check-circle"></i> <strong>Reasignar</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerProductosReporteVenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos <span id="FolioVentasProductos"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                                <th>Impuestos</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyVerProductosVenta">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerImpuestosProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Impuestos <span id="NombreProductoImpuesto"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>Impuesto</th>
                                <th>Clave</th>
                                <th>Tasa</th>
                                <th>Tipo de factor</th>
                                <th>Tipo de impuesto</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyVerImpuestosProducto">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalDevolucionVenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Devolución de la venta <span id="FolioVentaDevolucion"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 table-responsive" id="divTablaProductos">
                        <table class="table table-responsive table-striped text-center myDataTable" id="TablaProductosDevolucion" width="100%">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th orden="No">Precio</th>
                                    <th orden="No">Total (Venta)</th>
                                    <th orden="No">Devuelto</th>
                                    <th orden="No">Total (Devuelto)</th>
                                    <th orden="No" style="width: 15%">Devolver</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>
                </div> 
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="DevolverTodaVenta" attrid=""><i class="fa fa-times-circle"></i> <strong>Devolver todo</strong></button>
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="button" class="btn btn-primary" id="GuardarDevolucion" attrid=""><i class="fa fa-check-circle"></i> <strong>Devolver productos</strong></button>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalAbrirCaja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Abrir Caja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="FormAbrirCaja">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <select class="form-select" name="SucursalCaja" id="SucursalCaja">
                                #SucursalesVentas#
                            </select>
                            <label>Sucursal</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="MontoInicialCaja" name="MontoInicialCaja" min="0" placeholder="Ingresa el monto inicial de la caja">
                            <label for="MontoInicialCaja">¿Cuánto dinero hay en caja?</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="bUsarCajaDif"><i class="fa fa-times-circle"></i> Usar otra caja</button>
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="submit" class="btn btn-primary" id="AbrirCajaVentas" attrid=""><i class="fa fa-check-circle"></i> <strong>Abrir caja</strong></button>
            </div>
        </form>
    </div>
  </div>
</div> 

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalCancelarFactura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Cancelar Venta y Factura</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formCancelarFactura">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-floating">
                            <select name="motivoCancelarFactura" id="motivoCancelarFactura" class="form-control">
                                <option value="">--Selecciona un motivo--</option>
                                <option value="01">01 - Comprobante emitido con errores con relación</option>
                                <option value="02">02 - Comprobante emitido con errores sin relación</option>
                                <option value="03">03 - No se llevó a cabo la operación</option>
                                <option value="04">04 - Operación nominativa relacionada en una factura global</option>
                            </select>
                            <label>Motivo</label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="folioSustituye" name="folioSustituye" placeholder="Folio fiscal que sustituye">
                            <label>Folio fiscal que sustituye</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
                <button type="submit" class="btn btn-primary" id="bFormCancelarFactura" attrid=""><i class="fa fa-check-circle"></i> <strong>Cancelar Factura</strong></button>
            </div>
        </form>
    </div>
  </div>
</div> 

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVerCajas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title">Cajas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-responsive table-striped text-center myDataTable" id="tablaCajasVenta" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Sucursal</th>
                                <th>Estatus</th>
                                <th orden="No">Usuario</th>
                                <th orden="No">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>     
                </div> 
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
            <button type="button" class="btn btn-primary" id="bAbrirNuevaCaja"><i class="fa fa-check"></i> Abrir una Caja</button>
        </div>
    </div>
  </div>
</div> 
