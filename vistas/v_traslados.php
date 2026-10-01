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
                    <button type="button" class="btn btn-sm btn-outline-primary" id="VerMovimientosTraslados">Movimientos <i class="fas fa-list"></i></button>
                    #bNuevoTraslado#
                    <a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarTraslados').trigger('click')"><i class="fa fa-retweet"></i></a>
                </div>
            </div>
            <br>
            <div class="Principal">
                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table-hover table-striped table-bordered text-center myDataTable" id="TablaTraslados" width="100%" style="font-size: 12px;">
                            <thead>
                                <th>Fecha Registro</th>
                                <th>Fecha Traslado</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th orden="No">Concentrado</th>
                                <th>Estatus</th>
                                <th orden="No">Ver Detalles</th>
                                <th orden="No">Acciones</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalDetallesTraslados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="tablaDetallesTraslados" width="100%" style="font-size: 12px;">
                    <thead>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Presentacion</th>
                        <th>Cantidad</th>
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

<!--///////////////////////////////////////////////////////-->
<!-- MODAL: Crear/Editar Traslado (Solicitud) -->
<div class="modal fade" id="modalTraslado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-inverse bd-inverse-darken">
                <h5 class="modal-title" style="font-weight: bold;" id="tituloModalTraslado">Agregar Traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTraslado">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-3">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="fechaInicioSugerido" name="fechaInicioSugerido" placeholder="Fecha inicio">
                                <label>Fecha inicio (pedido sugerido)</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="fechaFinSugerido" name="fechaFinSugerido" placeholder="Fecha fin">
                                <label>Fecha fin (pedido sugerido)</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3 d-flex align-items-center">
                            <button type="button" class="btn btn-primary w-100" id="bGenerarPedidoSugerido"><i class="fas fa-magic"></i> Generar pedido sugerido</button>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3"></div>

                        <div class="col-md-3 col-sm-12 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="sucursalOrigenTraslado" name="sucursalOrigenTraslado">
                                    <option value="">- Seleccione -</option>
                                    #sucursales#
                                </select>
                                <label>Sucursal origen</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="sucursalDestinoTraslado" name="sucursalDestinoTraslado">
                                    <option value="">- Seleccione -</option>
                                    #sucursales#
                                </select>
                                <label>Sucursal destino</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fechaTraslado" name="fechaTraslado" placeholder="Fecha traslado">
                                <label>Fecha de traslado</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="estatusTraslado" name="estatusTraslado" disabled>
                                    <option value="Solicitud">Solicitud</option>
                                </select>
                                <label>Estatus</label>
                            </div>
                            <small class="text-muted">El estatus lo gestiona el sistema automáticamente.</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                <input type="text" class="form-control" id="codigoProductoTraslado" placeholder="Código del producto">
                                <button type="button" class="btn btn-outline-primary" id="bAgregarProductoTraslado">Agregar producto <i class="fas fa-check"></i></button>
                                <button type="button" class="btn btn-outline-secondary" id="bBuscarProductoTraslado"><i class="fas fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-hover table-striped table-bordered text-center" id="tablaProductosTraslado" width="100%" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Presentación</th>
                                        <th>Existencia</th>
                                        <th>Cantidad solicitada</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductosTraslado"></tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="bGuardarTraslado"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////-->
<!-- MODAL: Concentrado (verificación de productos por lote - origen surte) -->
<div class="modal fade" id="modalConcentradoTraslado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-inverse bd-inverse-darken">
                <h5 class="modal-title" style="font-weight: bold;">Salida traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-12">
                        <p class="mb-0"><strong>Orden de traslado:</strong> <span id="folioConcentrado" class="text-primary fw-bold"></span></p>
                        <p class="mb-0"><strong>Origen:</strong> <span id="origenConcentrado"></span></p>
                        <p class="mb-0"><strong>Destino:</strong> <span id="destinoConcentrado"></span></p>
                    </div>
                </div>
                <hr>
                <!-- Buscador por código para verificar -->
                <div class="row mb-3" id="rowBuscadorConcentrado">
                    <div class="col-md-8">
                        <form id="formVerificarTrasladoSalida">
                            <div class="input-group">
                                <input type="text" class="form-control" id="codigoVeridicarSalida" name="codigoVeridicarSalida" placeholder="Escanea o escribe el código...">
                                <button type="submit" class="btn btn-outline-dark" id="bVerificarTrasladoSalida"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table text-center" width="100%" style="font-size: 15px;">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Existencia</th>
                                    <th>Verificados</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyConcentradoTraslado"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerConcentrado">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////-->
<!-- MODAL: Concentrado (verificación de productos por lote - origen surte) -->
<div class="modal fade" id="modalConcentradoRecepcion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-inverse bd-inverse-darken">
                <h5 class="modal-title" style="font-weight: bold;">Recepción traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-12">
                        <p class="mb-0"><strong>Orden de traslado:</strong> <span id="folioRecepcion" class="text-primary fw-bold"></span></p>
                        <p class="mb-0"><strong>Origen:</strong> <span id="origenRecepcion"></span></p>
                        <p class="mb-0"><strong>Destino:</strong> <span id="destinoRecepcion"></span></p>
                    </div>
                </div>
                <hr>
                <!-- Buscador por código para verificar -->
                <div class="row mb-3" id="rowBuscadorConcentrado">
                    <div class="col-md-8">
                        <form id="formVerificarTrasladoRecepcion">
                            <div class="input-group">
                                <input type="text" class="form-control" id="codigoVeridicarRecepcion" name="codigoVeridicarRecepcion" placeholder="Escanea o escribe el código...">
                                <button type="submit" class="btn btn-outline-dark" id="bVerificarTrasladoRecepcion"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table text-center" width="100%" style="font-size: 15px;">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Existencia</th>
                                    <th>Verificados</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyConcentradoRecepcion"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerRecepcion">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////-->
<!-- MODAL: Buscar producto para agregar al traslado -->
<div class="modal fade" id="modalBuscarProductoTraslado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buscar producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-hover table-striped table-bordered text-center myDataTable" id="tablaProductosBuscarTraslado" width="100%" style="font-size: 12px;">
                    <thead>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Presentación</th>
                        <th>Existencia</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Concentrado general -->
<div class="modal fade" id="modalConcentradoGeneral" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-inverse bd-inverse-darken">
                <h5 class="modal-title" style="font-weight: bold;">Concentrado de traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        <p class="mb-0"><strong>Orden de traslado:</strong> <span id="folioConcentradoGeneral" class="text-primary fw-bold"></span></p>
                        <p class="mb-0"><strong>Origen:</strong> <span id="origenConcentradoGeneral"></span></p>
                        <p class="mb-0"><strong>Destino:</strong> <span id="destinoConcentradoGeneral"></span></p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-info" id="bImprimirConcentradoGeneral">
                            <i class="fas fa-print"></i> Imprimir concentrado
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table text-center" width="100%" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cant. pedida</th>
                                    <th>Salida verificada</th>
                                    <th>Entrada verificada</th>
                                    <th>Diferencia</th>
                                    <th>Estatus Acción</th>
                                    <th>Acción</th>
                                    <th orden="No">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyConcentradoGeneral"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Acción por producto -->
<div class="modal fade" id="modalAccionProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning"></i> Registrar acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="textoAccionProducto" class="mb-3"></p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Observación:</label>
                    <textarea class="form-control" id="observacionAccion" rows="2" placeholder="Ej: producto dañado, faltó en empaque..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Acción a tomar:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="accionProducto" id="accionMermaG" value="Merma">
                        <label class="form-check-label" for="accionMermaG">Registrar como merma</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="accionProducto" id="accionDevolverG" value="Devolucion" checked>
                        <label class="form-check-label" for="accionDevolverG">Devolver al CEDIS</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="accionProducto" id="accionReposicionG" value="Reposicion">
                        <label class="form-check-label" for="accionReposicionG">Solicitar reposición</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="bGuardarAccionProducto">
                    <i class="fa fa-check-circle"></i> Guardar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerMovimientos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Movimientos de los traslados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaMovimientosTraslados" width="100%" style="font-size: 12px;">
                    <thead>
                        <th>Traslado</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
						<th>Origen</th>
						<th>Destino</th>
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