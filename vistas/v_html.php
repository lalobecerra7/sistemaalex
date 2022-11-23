<!DOCTYPE html>

<html
  lang="es"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="vistas/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>CREMASI</title>
    <meta name="description" content="" />
    <link rel="shortcut icon" href="vistas/assets/img/favicon/favicon.ico" /> 
    <link rel="stylesheet" href="vistas/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="vistas/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vistas/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="vistas/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="vistas/assets/css/demo.css" />
    <link rel="stylesheet" href="vistas/assets/css/css.css" />
    <link  href="vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.css" rel="stylesheet">
    <link href="vistas/assets/plugins/fontawesome/css/all.css" rel="stylesheet">
    <link href="vistas/assets/plugins/sweetalert/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="vistas/assets/vendor/js/helpers.js"></script>
    <link rel="stylesheet" href="vistas/assets/plugins/myDataTable/css/myDataTable.css">
  </head>

  <body>
    <div id="carga">
        <div class="container" style="min-height: 100vh;">
            <div class="row align-items-center" style="min-height: 100vh;">
                <div class="col-12 text-center">
                    <div class="spinner-border text-danger" style="width: 8rem; height: 8rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <!-- Layout wrapper -->
    <div id="caja">
      <div class="container-fluid" style="height: 100vh; overflow-y: auto; ">
        <div class="row" style="height: 100vh;">
          <div class="col-12">
            <div class="row">
              <div class="col-12 text-end">
                <button type="button" class="btn" id="bCerrarVenCaja"><i class="fas fa-times"></i></button>
              </div>     
            </div>
            <div class="row" id="verCaja">
            </div>
          </div>



          <!-- ///////////////////////////Modal Abrir Caja/////////////////////////////////// -->  
          <div class="modal fade" id="MAbrirCaja" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Abrir Caja</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="formAbrirCaja">
                          <div class="modal-body text-justify">
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="montoAperCaja" id="montoAperCaja" placeholder="Monto" value="0.00">
                                  <label for="floatingInput">Monto inicial en Caja</label>
                              </div> 
                          </div>
                          <div class="modal-footer text-center">
                              <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                              <button type="submit" class="btn btn-primary" id="bAbrirCaja">Abrir <i class="fa fa-check-circle"></i></button>
                          </div>
                      </form>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Modal Granel/////////////////////////////////// -->  
          <div class="modal fade" id="MGranel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Cantidad</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <div id="datosGranel">
                              
                          </div>
                          <br>
                          <form id="formGranel" autocomplete="off">
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="cantidadGranel" id="cantidadGranel" placeholder="Cantidad" value="1.00">
                                  <label for="floatingInput">Cantidad</label>
                              </div>                        
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="importeGranel" id="importeGranel" placeholder="Importe">
                                  <label for="floatingInput">Importe</label>
                              </div> 
                          </form>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                          <button type="button" class="btn btn-primary" id="bAgregarGranel">Agregar <i class="fa fa-check-circle"></i></button>
                      </div>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Modal Int. Varios/////////////////////////////////// -->  
          <div class="modal fade" id="MIntVarios" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Varios Productos</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form id="formIntVarios" autocomplete="off">
                              <div class="input-group mb-3">
                                  <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                  <input type="text" class="form-control" placeholder="Código" name="barCodeIntVatios" id="barCodeIntVatios">
                              </div>                         
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="cantidadIntVatios" id="cantidadIntVatios" placeholder="Cantidad">
                                  <label for="floatingInput">Cantidad</label>
                              </div> 
                          </form>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                          <button type="button" class="btn btn-primary" id="bAgregarVarios">Agregar <i class="fa fa-check-circle"></i></button>
                      </div>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Modal Prod. Común/////////////////////////////////// -->  
          <div class="modal fade" id="MProdComun" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Producto Común</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form id="formProdComun" autocomplete="off">
                              <div class="form-floating mb-3">
                                  <input type="text" class="form-control" name="descripcionProdComun" id="descripcionProdComun" placeholder="Descripción">
                                  <label for="floatingInput">Descripción</label>
                              </div> 
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="cantidadProdComun" id="cantidadProdComun" placeholder="Cantidad" value="1.00">
                                  <label for="floatingInput">Cantidad</label>
                              </div>                        
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="precioProdComun" id="precioProdComun" placeholder="Precio">
                                  <label for="floatingInput">Precio</label>
                              </div>
                          </form>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                          <button type="button" class="btn btn-primary" id="bAgregarProdComun">Agregar <i class="fa fa-check-circle"></i></button>
                      </div>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Modal Descuentos/////////////////////////////////// -->  
          <div class="modal fade" id="ModalDescuentoProd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Descuento</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form id="formDescuentoProd" autocomplete="off">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-floating mb-3">
                                      <input type="number" step="any" class="form-control" name="CantidadDescuento" id="CantidadDescuento" placeholder="Cantidad de descuento">
                                      <label for="floatingInput">Cantidad ($)</label>
                                  </div> 
                                </div>
                                <div class="col-md-6">
                                  <div class=" form-floating mb-3">
                                      <input type="number" step="any" class="form-control" name="PorcentajeDescuento" id="PorcentajeDescuento" max="100" placeholder="Porcentaje de descuento">
                                      <label for="floatingInput">Porcentaje (%)</label>
                                  </div>
                                </div>
                              </div> 
                          </form>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                          <button type="button" class="btn btn-primary" id="bAgregarDescuento">Agregar <i class="fa fa-check-circle"></i></button>
                      </div>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Modal Descuentos/////////////////////////////////// -->  
          <div class="modal fade" id="ModalPreciosProd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Precios</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="AgregarProdTabla">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 table-responsive" id="divTablaProductos">
                                    <table class="table table-responsive table-striped text-center myDataTable" id="TablaPreciosProductos" width="100%">
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
                            <button type="submit" class="btn btn-primary" id="bAgregarBuscarProd"><i class="fa fa-check-circle"></i> <strong>Agregar</strong></button>
                        </div>
                      </form>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Buscar Producto/////////////////////////////////// -->  
          <div class="modal fade" id="MBuscarProd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Buscar</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="AgregarProdTabla">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 table-responsive" id="divTablaProductos">
                                    <table class="table table-responsive table-striped text-center myDataTable" id="TablaProductosVenta" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Presentación</th>
                                                <th>Precio</th>
                                                <th>Precio Mayoreo</th>
                                                <th>Area</th>
                                                <th>Existencia</th>
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
                            <button type="submit" class="btn btn-primary" id="bAgregarBuscarProd"><i class="fa fa-check-circle"></i> <strong>Agregar</strong></button>
                        </div>
                      </form>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Modal Entrada/////////////////////////////////// -->  
          <div class="modal fade" id="ModalEntradaDinero" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Entrada</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form id="formEntradaDinero" autocomplete="off">                        
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="CantidadEntrada" id="CantidadEntrada" placeholder="Cantidad de descuento">
                                  <label for="CantidadEntrada">Cantidad</label>
                              </div> 
                              <div class="form-floating mb-3">
                                  <input type="text" value="Entrada de dinero" class="form-control" name="MotivoEntrada" id="MotivoEntrada" placeholder="Cantidad de descuento">
                                  <label for="MotivoEntrada">Motivo</label>
                              </div>
                          </form>
                          <div class="row text-end">
                            <div class="col-12 mb-1">
                              <button class="btn btn-link" id="CargarEntradasRecientes"><span>Entradas realizadas en este turno <i class="fas fa-arrow-down"></i></span></button>
                            </div>
                          </div>
                          <div class="row MostrarTablaEntradas oculto">
                            <div class="col-12 table-responsive">
                              <table class="table table-responsive table-striped text-center myDataTable" id="TablaEntradasRecientes" width="100%">
                                <thead>
                                  <tr>
                                    <th>Fecha</th>
                                    <th>Motivo</th>
                                    <th>Cantidad</th>
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
                          <button type="button" class="btn btn-primary" id="bAgregarEntrada">Agregar <i class="fa fa-check-circle"></i></button>
                      </div>
                  </div>    
              </div>
          </div>

          <!-- ///////////////////////////Modal Salida/////////////////////////////////// -->  
          <div class="modal fade" id="ModalSalidaDinero" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Salida</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form id="formSalidaDinero" autocomplete="off">                        
                              <div class="form-floating mb-3">
                                  <input type="number" step="any" class="form-control" name="CantidadSalida" id="CantidadSalida" placeholder="Cantidad de salida">
                                  <label for="CantidadSalida">Cantidad</label>
                              </div> 
                              <div class="form-floating mb-3">
                                  <input type="text" value="Salida de dinero" class="form-control" name="MotivoSalida" id="MotivoSalida" placeholder="Motivo de la salida">
                                  <label for="MotivoSalida">Motivo</label>
                              </div>
                          </form>
                          <div class="row text-end">
                            <div class="col-12 mb-1">
                              <button class="btn btn-link" id="CargarSalidasRecientes"><span>Salidas realizadas en este turno <i class="fas fa-arrow-down"></i></span></button>
                            </div>
                          </div>
                          <div class="row MostrarTablaSalidas oculto">
                            <div class="col-12 table-responsive">
                              <table class="table table-responsive table-striped text-center myDataTable" id="TablaSalidasRecientes" width="100%">
                                <thead>
                                  <tr>
                                    <th>Fecha</th>
                                    <th>Motivo</th>
                                    <th>Cantidad</th>
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
                          <button type="button" class="btn btn-primary" id="bAgregarSalida">Agregar <i class="fa fa-check-circle"></i></button>
                      </div>
                  </div>    
              </div>
          </div>

          <div class="modal fade" id="ModalImpuestosVenta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Agregar impuestos</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form id="formAgregarImpuestos" autocomplete="off">
                              <div class="row">
                                <div class="table-responsive">
                                  <table class="table table-responsive table-striped text-center myDataTable" id="TablaImpuestosProductos" width="100%">
                                      <thead>
                                        <tr>
                                          <th orden="no">Aplicar</th>
                                          <th>Nombre</th>
                                          <th>Porcentaje</th>
                                          <th orden="no">Detalles</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                  </table> 
                                </div>
                              </div> 
                          </form>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                          <button type="button" class="btn btn-primary" id="bAgregarImpuesto">Agregar <i class="fa fa-check-circle"></i></button>
                      </div>
                  </div>    
              </div>
          </div>

          <div class="modal fade" id="ModalAsignarCliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Seleccione al cliente para asignar</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form id="formAgregarCliente" autocomplete="off">
                              <div class="row">
                                <div class="table-responsive">
                                  <table class="table table-responsive table-striped text-center myDataTable" id="TablaClientesVenta" width="100%">
                                      <thead>
                                        <tr>
                                          <th orden="no">Foto</th>
                                          <th>Nombre</th>
                                          <th>Contacto</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                  </table> 
                                </div>
                              </div> 
                          </form>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                      </div>
                  </div>    
              </div>
          </div>

          <div class="modal fade" id="ModalCambiarTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Seleccione un ticket</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <div class="col-md-12 text-center" id="DivTickets" style="height: 200px; overflow-y: scroll;">
                          </div>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                      </div>
                  </div>    
              </div>
          </div>

          <div id="noEncontrado" class="mensajeError">
              <div class="container-fluid" style="height: 100vh; overflow-y: auto;">
                  <div class="row align-items-center justify-content-center" style="height: 100vh;">
                      <div class="col-10 text-center" style="background-color: #E74C3C;">
                          <h1 style="color: #FFF; padding: 30px 0px;">Producto No Encontrado</h1>
                      </div>
                  </div>
              </div>
          </div>

          <div id="noMayoreo" class="mensajeError">
              <div class="container-fluid" style="height: 100vh; overflow-y: auto;">
                  <div class="row align-items-center justify-content-center" style="height: 100vh;">
                      <div class="col-10 text-center" style="background-color: #ffc107;">
                          <h1 style="color: #FFF; padding: 30px 0px;">El producto NO tiene precio de mayoreo</h1>
                      </div>
                  </div>
              </div>
          </div>

          <div id="noNegativos" class="mensajeError">
              <div class="container-fluid" style="height: 100vh; overflow-y: auto;">
                  <div class="row align-items-center justify-content-center" style="height: 100vh;">
                      <div class="col-10 text-center" style="background-color: #ffc107;">
                          <h1 style="color: #FFF; padding: 30px 0px;">No puede haber valores menores a 0</h1>
                      </div>
                  </div>
              </div>
          </div>

          <div id="noPrincipal" class="mensajeError">
              <div class="container-fluid" style="height: 100vh; overflow-y: auto;">
                  <div class="row align-items-center justify-content-center" style="height: 100vh;">
                      <div class="col-10 text-center" style="background-color: #ffc107;">
                          <h1 style="color: #FFF; padding: 30px 0px;">No se puede eliminar el ticket principal</h1>
                      </div>
                  </div>
              </div>
          </div>

          <div id="noHayTickets" class="mensajeError">
              <div class="container-fluid" style="height: 100vh; overflow-y: auto;">
                  <div class="row align-items-center justify-content-center" style="height: 100vh;">
                      <div class="col-10 text-center" style="background-color: #ffc107;">
                          <h1 style="color: #FFF; padding: 30px 0px;">No hay mas tickets para seleccionar</h1>
                      </div>
                  </div>
              </div>
          </div>

        </div>
      </div>
    </div>

    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.php">
              <!-- //<img src="vistas/assets/img/logos/icon.png" style="width:100%;"> -->
              <h1>CREMASI</h1>
            </a>

            <a href="index.php;" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>



          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1" style="overflow-x: hidden; overflow-y: hidden;">
            <!-- Dashboard -->
            <li class="menu-item active cargarVista mt-4" aria-current="page" carga="v_inicio" titulo="Inicio" id="cargarInicio">
              <a class="menu-link" href="javascript:void(0)">
                <i class="menu-icon fas fa-home"></i>
                <div data-i18n="Inicio">Inicio </div>
              </a>
            </li>

            #MenuSucursales#

            #MenuProveedores#

            #MenuClientes#

            

            <!-- Layouts -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-boxes-stacked"></i>
                <div data-i18n="Layouts">Productos</div>
              </a>

              <ul class="menu-sub">
                #MenuProductos#
                
                #MenuInventario#

                #MenuCategorias#
                
                <!-- #MenuCajas# -->
                
                <!-- #MenuImpuestos# -->

                #MenuZonas#

                #MenuAreas#

              </ul>
            </li>

            <!-- <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon fas fa-cogs"></i>
                <div data-i18n="Layouts">Configuración</div>
              </a>

              <ul class="menu-sub">
                #MenuTickets#
              </ul>

              <ul class="menu-sub">
                <li class="menu-item cargarVista" carga="v_general" titulo="General" id="cargarGeneral">
                  <a href="javascript:void(0)"  class="menu-link">
                    <div data-i18n="General">General</div>
                  </a>
                </li>
              </ul>
            </li> -->

            #MenuUsuarios#

          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <div id="DivPedidosPendientes">
                <a href="javascript:void(0)" style="font-size: 25x" id="cargarVenta" ><i class="fas fa-shopping-cart"></i></a>
              </div> 
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="#ImagenPerfil#" alt class="imagenPerfilChica" style="width: 100%; height: 100%; border-radius: 100%;" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item cargarVista" href="javascript:void(0)" carga="v_perfil" titulo="Perfil" id="cargarPerfil">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="#ImagenPerfil#" class="imagenPerfilChica" style="width: 100%; height: 100%; border-radius: 100%;"/>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block" id="bUsuario" attrUsuario="#IDUsuario#">#NombreUsuario#</span>
                            <small class="text-muted">#PermisosUsuario#</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0)" id="CambiarContra" data-bs-toggle="modal" data-bs-target="#ModalCambiarContrasena">
                        <i class="fas fa-key me-2"></i>
                        <span class="align-middle">Cambiar contraseña</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0)" id="CerrarSesion">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Cerrar sesión</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>

          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Cargar las vistas -->
            <div class="container-fluid" >
              <div class="row">
                <div class="col-12" id="verVista">
                  
                </div>
              </div>
            </div>

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <div class="modal fade" id="ModalCambiarContrasena" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
        <div class="modal-content">
          <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Nueva contraseña</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="FormNuevaContrasena">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 col-sm-12 mb-3">
                  <div class="form-floating">
                        <input type="password" class="form-control contraCampo" id="ContrasenaActual" name="ContrasenaActual" placeholder="Ingresa tu contraseña actual">
                        <label for="ContrasenaActual">Contraseña actual</label>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                  <div class="form-floating">
                        <input type="password" class="form-control contraCampo" id="ContrasenaNueva" name="ContrasenaNueva" placeholder="Ingresa tu contraseña actual">
                        <label for="ContrasenaNueva">Contraseña nueva</label>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                  <div class="form-floating">
                        <input type="password" class="form-control contraCampo" id="ContrasenaRepetir" name="ContrasenaRepetir" placeholder="Ingresa tu contraseña actual">
                        <label for="ContrasenaRepetir">Repetir contraseña nueva</label>
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" id="VerContrasenas"><i class="fas fa-eye"></i></button>
              <button type="submit" class="btn btn-primary" id="GuardarNuevaContrasena" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
              <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
            </div>
          </form>
        </div>
      </div>
    </div> 



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="vistas/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="vistas/assets/vendor/libs/popper/popper.js"></script>
    <!-- <script src="vistas/assets/plugins/bootstrap/js/bootstrap.min.js"></script> -->
    <script src="vistas/assets/vendor/js/bootstrap.js"></script>
    <script src="vistas/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vistas/assets/vendor/js/menu.js"></script>
    <script src="vistas/assets/js/main.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/jquery-validation/jquery.validate.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/jquery-validation/additional-methods.js" ></script>
    <script src="vistas/assets/plugins/jquery-validation/jquery-validation.init.js" type="text/javascript"></script>
    <script type="text/javascript" src="vistas/assets/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/sweetalert/dist/sweetalert2.min.js"></script>
    <!-- <script src="https://cdn.socket.io/4.5.0/socket.io.min.js" integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous"></script> -->
    <script async defer src="vistas/assets/vendor/js/buttons.js"></script>
    <script src="vistas/assets/plugins/myDataTable/js/myDataTable.js"></script>
    <script type="text/javascript" src="vistas/assets/js/script.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/general.js"></script>
    <script type="text/javascript" src="vistas/assets/js/sucursales.js"></script>
    <script type="text/javascript" src="vistas/assets/js/clientes.js"></script>
    <script type="text/javascript" src="vistas/assets/js/proveedores.js"></script>
    <script type="text/javascript" src="vistas/assets/js/areas.js"></script>
    <script type="text/javascript" src="vistas/assets/js/personal.js"></script>
    <script type="text/javascript" src="vistas/assets/js/hacerventa.js"></script>
    <script type="text/javascript" src="vistas/assets/js/categorias.js"></script>
    <script type="text/javascript" src="vistas/assets/js/impuestos.js"></script>
    <script type="text/javascript" src="vistas/assets/js/usuarios.js"></script>
    <script type="text/javascript" src="vistas/assets/js/productos.js"></script>
    <script type="text/javascript" src="vistas/assets/js/inventario.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/JsBarcode.all.min.js"></script>
    <script type="text/javascript" src="vistas/assets/js/cajas.js"></script>
    <script type="text/javascript" src="vistas/assets/js/perfil.js"></script>
    <script type="text/javascript" src="vistas/assets/js/tickets.js"></script>
    <script type="text/javascript" src="vistas/assets/js/general.js"></script>
    <script type="text/javascript" src="vistas/assets/js/zonas.js"></script>
  </body>
</html>
