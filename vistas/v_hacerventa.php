<br>
<div id="content" class="card">
	<div class="card-body">
        <div class="section">
            <div class="Principal">
                <div class="row">
					<div class="col-md-3 text-center">
						#MostrarSucursal#
					</div>
                    <div class="col-md-3 d-grid mb-2">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ModalVerClientesVenta" id="CargarClientesModalVentas">
                            <i class="fas fa-user"></i> Seleccionar cliente
                        </button>
                    </div>
			    </div>
                <br>
                <form id="FormAgregarProductoVenta" class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-barcode"></i></span>
                            <input type="text" class="form-control" id="CodigoProductoVenta" name="CodigoProductoVenta" placeholder="Código del producto" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 d-grid mb-3">
                        <button type="submit" class="btn btn-outline-danger" id="AgregarProductoVenta">Agregar producto <i class="fas fa-check"></i></button>
                    </div>
                    <div class="col-md-3 col-sm-6 d-grid mb-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ModalVerProductosVenta" id="CargarProductosModalVentas">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="table-responsive" style="height: 300px; overflow-y: scroll;">
                        <table class="table table table-hover table-striped table-bordered text-center" id="TablaProductosAgregadoVenta" width="100%" style="font-size: 12px; vertical-align: middle;">
                            <thead>
                                <th style="width: 10%;">Codigo</th>
                                <th style="width: 15%;">Descripción</th>
                                <th style="width: 15%;">Precio</th>
                                <th style="width: 10%;">Cantidad</th>
                                <th style="width: 15%;">Impuestos</th>
                                <th style="width: 15%;">Descuento</th>
                                <th style="width: 15%;">Total</th>
                                <th style="width: 5%;"></th>
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
                        <h5 style="font-weight: bold;">Subtotal</h5>
                        <h4 style="font-weight: bold;" class="dinero" id="MostrarSubtotalVenta">0.00</h4>
                    </div>
                    <div class="col-md-3 text-center mb-2">
                        <h5 style="font-weight: bold;">Descuento</h5>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><b>$</b></span>
                            <input type="number" min="0" value="0" step="any" class="form-control" id="DescuentoVentaDinero" name="DescuentoVentaDinero" placeholder="$0.00">
                        </div>
                    </div>
                    <div class="col-md-6 text-center mb-2">
                        <div class="row" style="vertical-align: middle;">
                            <div class="col-md-6 d-grid">
                                <button class="btn btn-secondary" id="RealizarVenta" idProveedor="2" style="font-size: 25px;"><i style="font-size: 25px;" class="fas fa-cart-plus"></i> <b>Cobrar</b></button>
                            </div>
                            <div class="col-md-6">
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

<div class="modal fade" id="ModalPreciosProductoVenta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Precios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="AgregarPrecioProducto">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 table-responsive" id="divTablaProductos">
                            <table class="table table-responsive table-striped text-center myDataTable" id="TablaPreciosProductosVenta" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Precio Mayoreo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table> 
                        </div>
                    </div>    
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary" id="bAgregarPrecioProducto"><i class="fa fa-check-circle"></i> <strong>Agregar</strong></button>
                </div>
            </form>
        </div>    
    </div>
</div>

<div class="modal fade" id="ModalVerProductosVenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="VentaTablaProductos" width="100%" style="font-size: 12px;">
                        <thead>
                            <th style="width: 20%;" orden="No">Producto</th>
                            <th style="width: 20%;">Presentación</th>
                            <th style="width: 20%;">Precio</th>
                            <th style="width: 20%;">Mayoreo</th>
                            <th style="width: 20%;">Existencia</th>
                        </thead>
                        <tbody>
                               
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


<div class="modal fade" id="ModalVerClientesVenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaClienteVenta" width="100%" style="font-size: 12px;">
                        <thead>
                            <th style="width: 25%;">Nombre</th>
                            <th style="width: 25%;" orden="no">Dirección</th>
                            <th style="width: 25%;" orden="no">RFC</th>
                            <th style="width: 25%;" orden="no">Contacto</th>
                        </thead>
                        <tbody>
                               
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
                            <option value="" selected>--Seleccione una opción--</option>
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
		               	<input type="file" class="form-control" id="ComprobantePago" name="ComprobantePago" placeholder="Ingresa un comprobante de pago">
		                <label for="CmprobantePago">Comprobante de pago</label>
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