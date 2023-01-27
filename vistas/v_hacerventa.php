<div id="content" class="card">
	<div class="card-body">
        <div class="section">
            <div class="Principal">
                <div class="row">
                    <div class="col text-end mb-2">
                        <button type="button" class="btn btn-outline-danger oculto" data-bs-toggle="modal" data-bs-target="#ModalCerrarCaja" id="BotonCerrarCaja" attrid="">
                            <i class="fas fa-times"></i> Hacer corte de caja
                        </button>
                    </div>
                </div>
                <div class="row">
                   <div class="col-md-3 text-center">
                      #MostrarSucursal#
                  </div>
                  <div class="col-md-3 d-grid mb-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ModalVerClientesVenta" id="CargarClientesModalVentas" attrid="">
                        <i class="fas fa-user"></i> Seleccionar cliente
                    </button>
                </div>
                <div class="col-md-2 BotonLimpiarCliente oculto d-grid mb-2">
                    <button type="button" class="btn btn-outline-primary" id="CargarClientesModalDirecciones" attrid="">
                        <i class="fas fa-map-marker"></i> Dirección
                    </button>
                </div>
                <div class="col-md-1 BotonLimpiarCliente oculto">
                    <button type="button" class="btn btn-outline-danger btn-sm" id="LimpiarClienteSeleccionado" attrid="">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="col-md-3 text-end BotonSeleccionarPedido d-grid mb-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ModalVerPedidosVenta" id="CargaPedidosModalVentas" folio="" attrid="">
                        <i class="fas fa-arrow-down"></i> Seleccionar pedido
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
                            <th style="width: 20%;">Descripción</th>
                            <th style="width: 10%;">Precio</th>
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
                    <span id="cantidadProductosSpanVenta">0</span> productos en la venta actual
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3 text-center">
                    <h5 style="font-weight: bold;">Subtotal (Sin impuestos)</h5>
                    <h4 style="font-weight: bold;" class="dinero" id="MostrarSubtotalVenta">0.00</h4>
                       <!--  <br>
                        <h5 style="font-weight: bold;">Subtotal (Importes)</h5>
                        <h4 style="font-weight: bold;" class="dinero" id="MostrarSubtotalImportes">0.00</h4> -->
                    </div>
                    <div class="col-md-9 text-center mb-2">
                        <div class="row" style="vertical-align: middle;">
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-outline-primary" id="GuardarPedido" total="" style="font-size: 15px;"><b>Guardar como pedido</b></button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-outline-primary" id="CobrarFacturar" total="" style="font-size: 15px;"><b>Cobrar y facturar</b></button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-outline-primary" id="RealizarVenta" total="" style="font-size: 15px;"><b>Finalizar venta</b></button>
                            </div>
                            <div class="col-md-3">
                                <h5 style="font-weight: bold;">Total</h5>
                                <h4 style="font-weight: bold;" id="TotalVentaFinal" class="dinero">0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalPreciosProductoVenta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Precios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 table-responsive" id="divTablaProductos">
                        <table class="table table-responsive table-striped text-center myDataTable" id="TablaPreciosProductosVenta" width="100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>
                </div>    
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn BotonDatosPrecio" producto="" presentacion=""  data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
            </div>
        </div>    
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalPresentacionesProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Presentaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 table-responsive" id="divTablaProductos">
                        <table class="table table-responsive table-striped text-center myDataTable" id="TablaPresentacionesProducto" width="100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Abreviatura</th>
                                    <th>Existencia</th>
                                    <th>Acción</th>
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
            </div>
        </div>    
    </div>
</div>

<!--/////////////////////////////////////////////////////////////-->
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
                            <th style="width: 20%;">Descripcion</th>
                            <th style="width: 20%;">Presentación</th>
                            <th style="width: 20%;" orden="No">Nombre del precio</th>
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

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerPedidosVenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pedidos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaCargarPedidos" width="100%" style="font-size: 12px;">
                        <thead>
                            <th style="width: 20%;" orden="No">Datos</th>
                            <th style="width: 25%;" orden="No">Cliente</th>
                            <th style="width: 25%;">Total</th>
                            <th style="width: 15%;" orden="No">Detalles</th>
                            <th style="width: 15%;" orden="No">Acciones</th>
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

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerProductosReportePedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos <span id="FolioPedidoProductos"></span></h5>
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
                        <tbody id="tbodyVerProductosPedido">
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
<div class="modal fade" id="ModalVerImpuestosProductoPedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Impuestos <span id="NombreProductoImpuestoPedido"></span></h5>
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

<!--/////////////////////////////////////////////////////////////-->
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
                            <th style="width: 20%;">Nombre</th>
                            <th style="width: 20%;" orden="no">Dirección</th>
                            <th style="width: 20%;" orden="no">RFC</th>
                            <th style="width: 20%;" orden="no">Contacto</th>
                            <th style="width: 20%;" orden="no">Facturar</th>
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

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerDireccionesCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Direcciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaDireccionesClientes" width="100%" style="font-size: 12px;">
                        <thead>
                            <th style="width: 20%;">Domicilio</th>
                            <th style="width: 20%;">Colonia</th>
                            <th style="width: 20%;">Ubicación</th>
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

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalPermisoAdministrador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Solicita los datos a un administrador para poder continuar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="FormAdmin">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="correoAdmin" name="correoAdmin" placeholder="Ingresa el correo del administrador">
                            <label for="correoAdmin">Correo electrónico</label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="contraAdmin" name="contraAdmin" placeholder="Ingresa la contraseña del administrador">
                            <label for="contraAdmin">Contraseña</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="submit" class="btn btn-primary" id="ValidarAdministrador" attrid=""><i class="fa fa-check-circle"></i> <strong>Aceptar</strong></button>
            </div>
        </form>
    </div>
</div>
</div> 

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalRealizarVenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Finalizar venta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <div class="row">
            <div class="col-md-12 col-sm-12 mb-3">
                <div class="form-floating">
                    <select class="form-select" id="TipoPagoVenta" name="TipoPagoVenta">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Deposito">Depósito</option>
                        <option value="Cheque">Cheque</option>
                        <option value="TransferenciaBancaria">Transferencia bancaria</option>
                        <option value="TarjetaCreditoDebito">Tarjeta de crédito o débito</option>
                        <option value="PagoOnline">Pago online</option>
                    </select>
                    <label for="TipoPagoVenta">Tipo de pago</label>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 mb-3">
                <div class="form-floating">
                    <input type="number" class="form-control" min='0.1' step="any" id="ImportePagadoVenta" name="ImportePagadoVenta" placeholder="Ingresa el importe a pagar">
                    <label for="ImportePagadoVenta">Importe pagado</label>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 mb-3">
                <h5>Cambio</h5>
                <h5 class="dinero" id="verCambio">$0.00</h5>
            </div>
        </div>
    </div>
    <div class="modal-footer">
     <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
     <button type="button" class="btn btn-primary" id="GuardarVenta" attrid=""><i class="fa fa-check-circle"></i> <strong>Aceptar</strong></button>
 </div>
</div>
</div>
</div> 

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalCerrarCaja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Cerrar Caja / Hacer corte de caja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="FormCerrarCaja">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="MontoCierreCaja" name="MontoCierreCaja" min="1" placeholder="Ingresa el monto de cierre de la caja">
                            <label for="MontoCierreCaja">¿Cuánto dinero hay en caja?</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="submit" class="btn btn-primary" id="CerrarCajaVentas" attrid=""><i class="fa fa-check-circle"></i> <strong>Cerrar caja</strong></button>
            </div>
        </form>
    </div>
</div>
</div> 

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalBalanceCaja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Cerrar Caja / Hacer corte de caja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                    Monto de apertura: <span id="spanMontoApertura"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                    Monto de cierre: <span id="spanMontoCierre"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                    Ingresos en Efectivo: <span id="spanIngresoEfectivo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                    Ingresos en Transferencia bancaria: <span id="spanIngresoTransferencia"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                    Total Ingresos: <span id="spanTotalIngresos"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                    Total Egresos: <span id="spanTotalEgresos"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                    Utilidad: <span id="spanUtilidadBalance"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
            <button type="button" class="btn btn-primary" id="ImprimirBalance" attrid=""><i class="fa fa-print"></i> <strong>Imprimir balance</strong></button>
        </div>
    </div>
</div>
</div> 