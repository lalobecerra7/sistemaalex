<div class="row perIn" id="perInicio1">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
                <div class="row row-cols-auto justify-content-end">
                    <div class="col">
                        <button type="button" class="btn btn-light"  id="recargarPendientes" onclick="$('#cargaPedidos').trigger('click');">Recargar <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
				<div class="row" id="principalHead">
                    <div class="row">
                        <div class="col-md-12" style="overflow: scroll;">
                            <table class="table table-hover table-bordered table-striped text-center Datatable" id="TablaHistorialPedidos" width="100%">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Negocio</th>
                                        <th>Repartidor</th>
                                        <th>Totales</th>
                                        <th>Detalles</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>  
				</div>		
			</div>
		</div>
	</div>
</div>

<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerDetallesHistorialPedidos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Orden: <span id="folioOrdenH">#</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                       <h6>Subtotal: <span class="dinero" id="SubtotalPedidoH"></span></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                       <h6>Costo de envio: <span class="dinero" id="CostoEnvioPedidoH"></span></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                       <h3>Total: <span class="dinero" id="TotalPedidoH"></span></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <h6>Método de pago: <span id="MetodoPagoH"></span></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <h6>Fecha del pedido: <span id="FechaPedidoH"></span></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <h6>Hora del pedido: <span id="HoraPedidoH"></span></h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <h6>Información del pedido</h6>
                        <div id="InformacionPedidoH"></div>
                    </div>
                    <div class="col-md-6">
                        <h6>Detalles del pedido</h6>
                        <table class="table"> 
                            <tbody>
                                <div style="border-radius: 10px;border: 1px solid #e5e2e2;width:100%; height: 300px; overflow-y: scroll;padding: 10px;" id="tbodyDetallesProductoH">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" >Cerrar</button>
            </div>
        </div>
    </div>
</div>
