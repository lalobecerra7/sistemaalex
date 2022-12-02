<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item" aria-current="page">Productos</li>
			    <li class="breadcrumb-item active" aria-current="page">Compras</li>
			  </ol>
			</nav>
		</div>
	</div>
	<br>
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
					<!-- <button type="button" class="btn btn-success" id="botonNuevaArea" onclick="$('#ModalAreas').appendTo('body').modal('show')"><i class="fa fa-file"></i> Nueva</button> -->
					<button type="button" class="btn btn-primary botonNuevaCompra" onclick="$('#cargarHacerCompra').trigger('click')">Nueva compra <i class="fa fa-file"></i></button>
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_compras" titulo="Compras"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteCompras" width="100%" style="font-size: 12px;">
                    <thead>
                        <th style="width: 20%;" orden="No">Datos</th>
                        <th style="width: 25%;" orden="No">Proveedor</th>
                        <th style="width: 25%;">Total</th>
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

<div class="modal fade" id="ModalNuevaCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" tipo="insertar" attrid="" id="GuardarProveedor">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalVerProductosCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos <span id="FolioCompraProductos"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>
                                    Producto
                                </th>
                                <th>
                                    Descripción
                                </th>
                                <th>
                                    Costo
                                </th>
                                <th>
                                    Cantidad
                                </th>
                                <th>
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tbodyVerProductosCompra">
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

<div class="modal fade" id="ModalVerHistorialPagos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos <span id="FolioCompraPagos"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>
                                    Fecha
                                </th>
                                <th>
                                    Concepto
                                </th>
                                <th>
                                    Tipo de pago
                                </th>
                                <th>
                                    Monto
                                </th>
                                <th>
                                    Detalles
                                </th>
                                <th>
                                    Comprobante
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tbodyVerHistorialPagos">
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

<div class="modal fade" id="ModalPagoCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Hacer pago</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormPagoCompra">
	      <div class="modal-body">
	       	<div class="row">
                <div class="col-md-12 col-sm-12 mb-1">
                    <center><h4 style="font-weight: bold;">Proveedor: <br><span id="Proveedor"></span></h4></center>
		        </div>
                <div class="col-md-12 col-sm-12 mb-1">
                    <center><h4 style="font-weight: bold;">Total de la compra: <br><span id="TotalCompra"></span></h4></center>
                </div>
                <div class="col-md-12 col-sm-12 mb-1">
                    <center><h4 style="font-weight: bold;">Total de pagos: <br><span id="Pagos"></span></h4></center>
		        </div>
                <div class="col-md-12 col-sm-12 mb-1">
                    <center><h4 style="font-weight: bold;">Restante: <br><span id="Restante"></span></h4></center>
		        </div>
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" min='1' step="any" id="ImportePagoCompra" name="ImportePagoCompra" placeholder="Ingresa el importe a pagar">
		                <label for="ImportePagoCompra">Importe a pagar: </label>
		            </div>
		        </div>
                <div class="col-md-12 col-sm-12 mb-3" id='Concepto'>
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="ConceptoPago" name="ConceptoPago" placeholder="Ingresa el concepto del pago">
		                <label for="ConceptoPago">Concepto: </label>
		            </div>
		        </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="TipoDePago" name="TipoDePago">
                            <option value="" selected>--Seleccione una opción--</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Deposito">Depósito</option>
                            <option value="Cheque">Cheque</option>
                            <option value="TransferenciaBancaria">Transferencia bancaria</option>
                            <option value="TarjetaCreditoDebito">Tarjeta de crédito o débito</option>
                        </select>
                        <label for="TipoDePago">Tipo de pago</label>
                    </div>
                </div>
		        <div class="col-md-12 col-sm-12 mb-3" id='Detalles'>
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="DetallesPago" name="DetallesPago" placeholder="Ingresa los datos del pago">
		                <label for="DetallesPago">Detalles: </label>
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
	        <button type="submit" class="btn btn-primary" id="GuardarPago" attrid=""><i class="fa fa-check-circle"></i> <strong>Aceptar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 
