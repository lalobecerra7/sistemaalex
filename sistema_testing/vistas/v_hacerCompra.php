<div id="content" class="card">
	<div class="card-body">
        <div class="section">
            <div class="Principal">
                <div class="row">
					<div class="col-md-3 text-center">
                        #sucursal#
					</div>
                    <div class="col-md-4 d-grid mb-2">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ModalVerProveedoresC" id="CargarProveedoresModalC">
                            <i class="fas fa-user"></i> Proveedor
                        </button>
                    </div>
                    <div class="col-md-1 BotonLimpiarProveedor oculto">
                        <button type="button" class="btn btn-outline-danger btn-sm" id="LimpiarProveedorSeleccionado" attrid="">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="col-md-4 mb-2 text-end">
                        <button type="button" class="btn btn-outline-secondary" id="bVerOrdenes">
                            <i class="fas fa-file"></i> Ver Ordenes
                        </button>
                        <br>
                        <button type="button" class="btn btn-outline-secondary btn-sm oculto mt-3" id="bFolioOrdenCompra">
                            <i class="fas fa-trash"></i> <span id="folioOrdenCompra"></span>
                        </button>
                    </div>
			    </div>
                <br>
                <form id="FormAgregarProductoC" class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-barcode"></i></span>
                            <input type="text" class="form-control" id="CodigoProductoC" name="CodigoProductoC" placeholder="Código del producto" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 d-grid mb-3">
                        <button type="button" class="btn btn-outline-danger" id="AgregarProductoCodigoC">Agregar producto <i class="fas fa-check"></i></button>
                    </div>
                    <div class="col-md-3 col-sm-6 d-grid mb-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ModalVerProductosCompra" id="CargarProductosModalC">
                            <i class="fas fa-search"></i> Buscar - (F2)
                        </button>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-floating" id="cambiarTipoCompra">
                            <select class="form-select" id="TipoCompra" name="TipoCompra">
                                <option value="Contado" selected>Contado</option>
                                <option value="Credito">Crédito</option>
                            </select>
                            <label for="TipoCompra">Tipo</label>
                        </div>
                    </div>
                    <div class="col-md-3" id='FechaLimiteCredito' hidden>
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fechaCredito" name="fechaCredito" placeholder="Ingresa la fecha límite del crédito">
                            <label for="fechaCredito">Fecha límite de crédito</label>
                        </div>
                    </div>
                    <div class="col-md-3" id="LimiteCredito" hidden>
                        Límite de crédito: <h5 id="MostrarCreditoProveedor" style="font-weight: bold;">No ofrece crédito</h5>
                    </div>
                    <div class="col-md-3" id="LimiteCreditoRestante" hidden>
                        Crédito restante: <h5 id="MostrarCreditoRestante" style="font-weight: bold;">$0</h5>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-4">
                        Nombre del proveedor: <h5 id="MostrarNombreProveedor" style="font-weight: bold;">Proveedor General</h5>
                    </div>
                </div> -->
                <div class="row mt-3">
                    <div class="table-responsive" style="height: 300px; overflow-y: scroll;">
                        <table class="table table table-hover table-striped table-bordered text-center" id="TablaProductosAgregados" width="100%" style="font-size: 12px; vertical-align: middle;">
                            <thead>
                                <th style="width: 15%;">Codigo</th>
                                <th style="width: 15%;">Descripción</th>
                                <th style="width: 15%;" class="costoOculto">Costo Bruto</th>
                                <th style="width: 15%;">Cantidad</th>
                                <th style="width: 15%;">Descuento (%)</th>
                                <th style="width: 15%;">Impuesto</th>
                                <th style="width: 15%;" class="costoBrutoOculto">Costo Neto</th>
                                <th style="width: 15%;" class="costoOculto">Total</th>
                                <th style="width: 10%;"></th>
                            </thead>
                            <tbody id="tbodyTablaProductosAgregados">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-start">
                        <span id="cantidadProductosSpan">0</span> productos en la compra actual
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3 text-center">
                        <div id="verSubtotal">
                            <h5 style="font-weight: bold;">Subtotal</h5>
                            <h4 style="font-weight: bold;" id="MostrarSubtotal">0.00</h4>
                        </div>    
                    </div>
                    <div class="col-md-3 text-center mb-2">
                        <div id="ponerDescuento">
                            <h5 style="font-weight: bold;">Descuento</h5>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><b>$</b></span>
                                <input type="number" min="0" value="0" step="any" class="form-control" id="DescuentoCompraDinero" name="DescuentoCompraDinero" placeholder="$0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center mb-2">
                        <div class="row" style="vertical-align: middle;">
                            <div class="col-md-6 d-grid">
                                <button class="btn btn-secondary" id="RealizarCompra" idProveedor="1" style="font-size: 18px;"><i style="font-size: 25px;" class="fas fa-cart-plus"></i> <b>Guardar Compra</b></button>
                                <br>
                                <button class="btn btn-secondary" id="bGuardarOrden" idProveedor="1" style="font-size: 18px;"><i style="font-size: 25px;" class="fas fa-cart-plus"></i> <b>Guardar Orden</b></button>
                            </div>
                            <div class="col-md-6" id="verTotal">
                                <h5 style="font-weight: bold;">Total</h5>
                                <h4 style="font-weight: bold;" id="TotalCompra">0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerProductosCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaProductosCompra" width="100%" style="font-size: 12px;">
                        <thead>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th orden="No">Presentación</th>
                        </thead>
                        <tbody>
                               
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerProveedoresC" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Proveedores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaProveedoresCompra" width="100%" style="font-size: 12px;">
                        <thead>
                            <th style="width: 25%;">Nombre</th>
                            <th style="width: 25%;" orden="no">Dirección</th>
                            <th style="width: 25%;" orden="no">Empresa</th>
                            <th style="width: 25%;" orden="no">Credito</th>
                        </thead>
                        <tbody>
                               
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalCobrarCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Cobrar compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormCobrarCompra">
	      <div class="modal-body">
	       	<div class="row">
                <div class="col-md-12 col-sm-12 mb-1" id='SubtotalCompra'hidden>
                    <center><h4 style="font-weight: bold;">Subtotal: <br><span id="Subtotal"></span></h4></center>
		        </div>
                <div class="col-md-12 col-sm-12 mb-1" id='CreditoCompra' hidden>
                    <center><h4 style="font-weight: bold;">Credito disponible: <br><span id="Credito"></span></h4></center>
                </div>
                <div class="col-md-12 col-sm-12 mb-1" id='DescuentoCompra'hidden>
                    <center><h4 style="font-weight: bold;">Descuento: <br><span id="Descuento"></span></h4></center>
		        </div>
                <div class="col-md-12 col-sm-12 mb-1" id='TotalDeCompra'hidden>
                    <center><h4 style="font-weight: bold;">Total: <br><span id="Total"></span></h4></center>
		        </div>
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" min='1' step="any" id="ImportePagadoCompra" name="ImportePagadoCompra" placeholder="Ingresa el importe a pagar">
		                <label for="ImportePagadoCompra">Importe pagado</label>
		            </div>
		        </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="TipoPago" name="TipoPago">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Deposito">Depósito</option>
                            <option value="Cheque">Cheque</option>
                            <option value="TransferenciaBancaria">Transferencia bancaria</option>
                            <option value="TarjetaCreditoDebito">Tarjeta de crédito o débito</option>
                        </select>
                        <label for="TipoPago">Tipo de pago</label>
                    </div>
                </div>
		        <div class="col-md-12 col-sm-12 mb-3" id='Detalles'>
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="DetallesPago" name="DetallesPago" placeholder="Ingresa los datos del pago">
		                <label for="DetallesPago">Detalles</label>
		            </div>
		        </div>
                <div class="col-md-12 col-sm-12 mb-3" id='Archivo'>
		        	<div class="form-floating">
		               	<input type="file" class="form-control" id="ComprobantePagoHC" name="ComprobantePagoHC" placeholder="Ingresa un comprobante de pago">
		                <label for="ComprobantePagoHC">Comprobante de pago</label>
		            </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary" id="GuardarCompra" attrid=""><i class="fa fa-check-circle"></i> <strong>Aceptar</strong></button>
			<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVerPresentaciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center" width="100%" style="font-size: 12px;">
                        <thead>
                            <th>Nombre</th>
                            <th>Abreviatura</th>
                            <th class="costoOculto">Costo</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody id="verTablaPrese">
                               
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVerOrdenes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="tablaOrdenesCompra" width="100%" style="font-size: 12px;">
                    <thead>
                        <th>Datos</th>
                        <th>Proveedor</th>
                        <th>Total</th>
                        <th>Detalles</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                               
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVerProductosOrden" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center" width="100%" style="font-size: 12px;">
                        <thead>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th class="costoOculto">Costo Bruto</th>
                            <th class="costoOculto">Descuento</th>
                            <th class="costoOculto">Impuesto</th>
                            <th class="costoOculto">Costo Neto</th>
                            <th class="costoOculto">Total</th>
                        </thead>
                        <tbody id="verProdOrden">
                               
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>
