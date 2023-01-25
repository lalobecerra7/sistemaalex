<div class="">
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
					<button type="button" class="btn btn-success" id="BotonNuevaVenta"><i class="fa fa-file"></i> Nueva</button> 
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_ventas" titulo="Ventas"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
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
                                <th>
                                    Producto
                                </th>
                                <th>
                                    Precio
                                </th>
                                <th>
                                    Cantidad
                                </th>
                                <th>
                                    Descuento
                                </th>
                                <th>
                                    Subtotal
                                </th>
                                <th>
                                    Impuestos
                                </th>
                                <th>
                                    Total
                                </th>
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
                                <th>
                                    Impuesto
                                </th>
                                <th>
                                    Clave
                                </th>
                                <th>
                                    Tasa
                                </th>
                                <th>
                                    Tipo de factor
                                </th>
                                <th>
                                    Tipo de impuesto
                                </th>
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
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="MontoInicialCaja" name="MontoInicialCaja" min="0" placeholder="Ingresa el monto inicial de la caja">
                            <label for="MontoInicialCaja">¿Cuánto dinero hay en caja?</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="submit" class="btn btn-primary" id="AbrirCajaVentas" attrid=""><i class="fa fa-check-circle"></i> <strong>Abrir caja</strong></button>
            </div>
        </form>
    </div>
  </div>
</div> 
