<div class="row perIn" id="perInicio1">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
                <div class="row row-cols-auto justify-content-end">
                    <div class="col">
                        <button type="button" class="btn btn-light"  id="recargarPendientes" onclick="$('#cargaPedidosAceptados').trigger('click');">Recargar <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <br>
				<div class="row" id="principalHead">
                    <div class="row">
                        <div class="col-md-12" style="overflow: scroll;">
                            <table class="table table-hover table-bordered table-striped text-center Datatable" id="TablaPedidosAceptados" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Cliente</th>
                                        <th style="width: 20%;">Negocio</th>
                                        <th style="width: 20%;">Totales</th>
                                        <th style="width: 15%;">Detalles</th>
                                        <th style="width: 15%;">Acciones</th>
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
<div class="modal fade" id="ModalAsignarPedidoAceptados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar pedido: <span id="folioOrdenAsignarAceptados">#</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Repartidor</label>  
                        <br>
                        <select class="form-control" name="RepartidorAsignarAceptados" id="RepartidorAsignarAceptados">
                            #RepartidoresSelect#
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success AsignarPedidoAceptados" id="AsignarPedidoModalAceptados" attrid="">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerDetallesAceptados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Orden: <span id="folioOrdenA">#</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row">
                        <div class="col-md-12 text-center">
                           <h6>Subtotal: <span class="dinero" id="SubtotalPedidoA"></span></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                           <h6>Costo de envio: <span class="dinero" id="CostoEnvioPedidoA"></span></h6>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                       <h3>Total: <span class="dinero" id="TotalPedidoA"></span></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <h6>Método de pago: <span id="MetodoPagoA"></span></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <h6>Fecha del pedido: <span id="FechaPedidoA"></span></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <h6>Hora del pedido: <span id="HoraPedidoA"></span></h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <h6>Información del cliente</h6>
                        <div id="InformacionPedidoCliente"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <h6>Información del negocio</h6>
                        <div id="InformacionPedidoNegocio"></div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Detalles del pedido</h6>
                        <table class="table"> 
                            <tbody>
                                <div style="width:100%; height: 200px; max-height: 300px; overflow: auto;padding: 10px;" id="tbodyDetallesProductoA">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalPedidoListo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Orden <span id="spanOrdenPedidoListo"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-12">
                        <select class="form-control" name="EstatusPedidoNegocio" id="EstatusPedidoNegocio">
                           <option value="TiempoPedido">El negocio ya está realizando el pedido</option>
                           <option value="OrdenarPedido">El repartidor debe de ir al negocio a realizar el pedido</option>
                        </select>
                   </div>
               </div>
               <br>
               <div class="row mostrarTiempoAprox" style="display: none;">
                   <div class="col-md-12">
                        <span>Tiempo aproximado para recoger el pedido</span>
                        <input type="number" class="form-control mt-2" id="TiempoPedidoListo" name="TiempoPedidoListo" placeholder="Tiempo para que el pedido este listo" min="0">
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success PedidoListoBoton" id="BotonPedidoListo" attrid="" folio="" idNegocio="" idCliente="" >Pedido listo</button>
            </div>
        </div>
    </div>
</div>
