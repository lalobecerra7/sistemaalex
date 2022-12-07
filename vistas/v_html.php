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
                    <div class="spinner-border text-primary" style="width: 8rem; height: 8rem;" role="status">
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

          <!-- ///////////////////////////Modal Precios Producto/////////////////////////////////// -->  
          <div class="modal fade" id="ModalPreciosProd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            <button type="submit" class="btn btn-primary" id="bAgregarPrecioProducto"><i class="fa fa-check-circle"></i> <strong>Agregar</strong></button>
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

            #MenuCompras#

            <li class="menu-item cargarVista" carga="v_ventas" titulo="Ventas" id="cargarVentas">
              <a href="javascript:void(0)"  class="menu-link">
                <i class="menu-icon fas fa-shopping-cart"></i>
                <div data-i18n="Ventas">Ventas</div>
              </a>
            </li>

           

            #MenuCajas#

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

                <li class="dropdown dropdown-list-toggle" hidden>
                  <a class="nav-link notification-toggle nav-link-lg cargarVista" href="javascript:void(0)" carga="v_hacerCompra" titulo="Compra" id="cargarHacerCompra">
                    </a>
                </li>

                #MenuZonas#

                #MenuAreas#
                
                <!--#MenuMovimientos#-->
              </ul>
            </li>

            #MenuImpuestos#

            #MenuConfiguracion#

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

    <!--/////////////////////////Modal///////////////////////////////////-->
    <div class="modal fade" id="modalFacturar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered" style="z-index: 9999 !important;">
        <div class="modal-content">
          <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Factura 4.0</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="formFacturar">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-3 mb-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="serieCFDI" name="serieCFDI" placeholder="Serie" disabled>
                    <label>Serie</label>
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="folioCFDI" name="folioCFDI" placeholder="Folio" disabled>
                    <label>Folio</label>
                  </div>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                  <div class="form-floating mb-3">
                    <select class="form-select" name="formaPagoCFDI" id="formaPagoCFDI">
                      <option value="">--Seleccione una opción--</option>
                      <option value="01">01 - Efectivo</option>
                      <option value="02">02 - Cheque nominativo</option>
                      <option value="03">03 - Transferencia electrónica de fondos</option>
                      <option value="04">04 - Tarjeta de crédito</option>
                      <option value="05">05 - Monedero electrónico</option>
                      <option value="06">06 - Dinero electrónico</option>
                      <option value="08">08 - Vales de despensa</option>
                      <option value="12">12 - Dación en pago</option>
                      <option value="13">13 - Pago por subrogación</option>
                      <option value="14">14 - Pago por consignación</option>
                      <option value="15">15 - Condonación</option>
                      <option value="17">17 - Compensación</option>
                      <option value="23">23 - Novación</option>
                      <option value="24">24 - Confusión</option>
                      <option value="25">25 - Remisión de deuda</option>
                      <option value="26">26 - Prescripción o caducidad</option>
                      <option value="27">27 - A satisfacción del acreedor</option>
                      <option value="28">28 - Tarjeta de débito</option>
                      <option value="29">29 - Tarjeta de servicios</option>
                      <option value="30">30 - Aplicación de anticipos</option>
                      <option value="31">31 - Intermediario pagos</option>
                      <option value="99">99 - Por definir</option>
                    </select>
                    <label>Forma de pago</label>
                  </div>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                  <div class="form-floating mb-3">
                    <select class="form-select" name="monedaCFDI" id="monedaCFDI" disabled>
                      <option value="MXN">MXN - Peso Mexicano</option>
                      <option value="AED">AED - Dirham de EAU</option>
                      <option value="AFN">AFN - Afghani</option>
                      <option value="ALL">ALL - Lek</option>
                      <option value="AMD">AMD - Dram armenio</option>
                      <option value="ANG">ANG - Florín antillano neerlandés</option>
                      <option value="AOA">AOA - Kwanza</option>
                      <option value="ARS">ARS - Peso Argentino</option>
                      <option value="AUD">AUD - Dólar Australiano</option>
                      <option value="AWG">AWG - Aruba Florin</option>
                      <option value="AZN">AZN - Azerbaijanian Manat</option>
                      <option value="BAM">BAM - Convertibles marca</option>
                      <option value="BBD">BBD - Dólar de Barbados</option>
                      <option value="BDT">BDT - Taka</option>
                      <option value="BGN">BGN - Lev búlgaro</option>
                      <option value="BHD">BHD - Dinar de Bahrein</option>
                      <option value="BIF">BIF - Burundi Franc</option>
                      <option value="BMD">BMD - Dólar de Bermudas</option>
                      <option value="BND">BND - Dólar de Brunei</option>
                      <option value="BOB">BOB - Boliviano</option>
                      <option value="BOV">BOV - Mvdol</option>
                      <option value="BRL">BRL - Real brasileño</option>
                      <option value="BSD">BSD - Dólar de las Bahamas</option>
                      <option value="BTN">BTN - Ngultrum</option>
                      <option value="BWP">BWP - Pula</option>
                      <option value="BYR">BYR - Rublo bielorruso</option>
                      <option value="BZD">BZD - Dólar de Belice</option>
                      <option value="CAD">CAD - Dólar Canadiense</option>
                      <option value="CDF">CDF - Franco congoleño</option>
                      <option value="CHE">CHE - WIR Euro</option>
                      <option value="CHF">CHF - Franco Suizo</option>
                      <option value="CHW">CHW - Franc WIR</option>
                      <option value="CLF">CLF - Unidad de Fomento</option>
                      <option value="CLP">CLP - Peso chileno</option>
                      <option value="CNY">CNY - Yuan Renminbi</option>
                      <option value="COP">COP - Peso Colombiano</option>
                      <option value="COU">COU - Unidad de Valor real</option>
                      <option value="CRC">CRC - Colón costarricense</option>
                      <option value="CUC">CUC - Peso Convertible</option>
                      <option value="CUP">CUP - Peso Cubano</option>
                      <option value="CVE">CVE - Cabo Verde Escudo</option>
                      <option value="CZK">CZK - Corona checa</option>
                      <option value="DJF">DJF - Franco de Djibouti</option>
                      <option value="DKK">DKK - Corona danesa</option>
                      <option value="DOP">DOP - Peso Dominicano</option>
                      <option value="DZD">DZD - Dinar argelino</option>
                      <option value="EGP">EGP - Libra egipcia</option>
                      <option value="ERN">ERN - Nakfa</option>
                      <option value="ETB">ETB - Birr etíope</option>
                      <option value="EUR">EUR - Euro</option>
                      <option value="FJD">FJD - Dólar de Fiji</option>
                      <option value="FKP">FKP - Libra malvinense</option>
                      <option value="GBP">GBP - Libra Esterlina</option>
                      <option value="GEL">GEL - Lari</option>
                      <option value="GHS">GHS - Cedi de Ghana</option>
                      <option value="GIP">GIP - Libra de Gibraltar</option>
                      <option value="GMD">GMD - Dalasi</option>
                      <option value="GNF">GNF - Franco guineano</option>
                      <option value="GTQ">GTQ - Quetzal</option>
                      <option value="GYD">GYD - Dólar guyanés</option>
                      <option value="HKD">HKD - Dólar De Hong Kong</option>
                      <option value="HNL">HNL - Lempira</option>
                      <option value="HRK">HRK - Kuna</option>
                      <option value="HTG">HTG - Gourde</option>
                      <option value="HUF">HUF - Florín</option>
                      <option value="IDR">IDR - Rupia</option>
                      <option value="ILS">ILS - Nuevo Shekel Israelí</option>
                      <option value="INR">INR - Rupia india</option>
                      <option value="IQD">IQD - Dinar iraquí</option>
                      <option value="IRR">IRR - Rial iraní</option>
                      <option value="ISK">ISK - Corona islandesa</option>
                      <option value="JMD">JMD - Dólar Jamaiquino</option>
                      <option value="JOD">JOD - Dinar jordano</option>
                      <option value="JPY">JPY - Yen</option>
                      <option value="KES">KES - Chelín keniano</option>
                      <option value="KGS">KGS - Som</option>
                      <option value="KHR">KHR - Riel</option>
                      <option value="KMF">KMF - Franco Comoro</option>
                      <option value="KPW">KPW - Corea del Norte ganó</option>
                      <option value="KRW">KRW - Won</option>
                      <option value="KWD">KWD - Dinar kuwaití</option>
                      <option value="KYD">KYD - Dólar de las Islas Caimán</option>
                      <option value="KZT">KZT - Tenge</option>
                      <option value="LAK">LAK - Kip</option>
                      <option value="LBP">LBP - Libra libanesa</option>
                      <option value="LKR">LKR - Rupia de Sri Lanka</option>
                      <option value="LRD">LRD - Dólar liberiano</option>
                      <option value="LSL">LSL - Loti</option>
                      <option value="LYD">LYD - Dinar libio</option>
                      <option value="MAD">MAD - Dirham marroquí</option>
                      <option value="MDL">MDL - Leu moldavo</option>
                      <option value="MGA">MGA - Ariary malgache</option>
                      <option value="MKD">MKD - Denar</option>
                      <option value="MMK">MMK - Kyat</option>
                      <option value="MNT">MNT - Tugrik</option>
                      <option value="MOP">MOP - Pataca</option>
                      <option value="MRO">MRO - Ouguiya</option>
                      <option value="MUR">MUR - Rupia de Mauricio</option>
                      <option value="MVR">MVR - Rupia</option>
                      <option value="MWK">MWK - Kwacha</option>
                      <option value="MXV">MXV - México Unidad de Inversión (UDI)</option>
                      <option value="MYR">MYR - Ringgit malayo</option>
                      <option value="MZN">MZN - Mozambique Metical</option>
                      <option value="NAD">NAD - Dólar de Namibia</option>
                      <option value="NGN">NGN - Naira</option>
                      <option value="NIO">NIO - Córdoba Oro</option>
                      <option value="NOK">NOK - Corona noruega</option>
                      <option value="NPR">NPR - Rupia nepalí</option>
                      <option value="NZD">NZD - Dólar de Nueva Zelanda</option>
                      <option value="OMR">OMR - Rial omaní</option>
                      <option value="PAB">PAB - Balboa</option>
                      <option value="PEN">PEN - Nuevo Sol</option>
                      <option value="PGK">PGK - Kina</option>
                      <option value="PHP">PHP - Peso filipino</option>
                      <option value="PKR">PKR - Rupia de Pakistán</option>
                      <option value="PLN">PLN - Zloty</option>
                      <option value="PYG">PYG - Guaraní</option>
                      <option value="QAR">QAR - Qatar Rial</option>
                      <option value="RON">RON - Leu rumano</option>
                      <option value="RSD">RSD - Dinar serbio</option>
                      <option value="RUB">RUB - Rublo ruso</option>
                      <option value="RWF">RWF - Franco ruandés</option>
                      <option value="SAR">SAR - Riyal saudí</option>
                      <option value="SBD">SBD - Dólar de las Islas Salomón</option>
                      <option value="SCR">SCR - Rupia de Seychelles</option>
                      <option value="SDG">SDG - Libra sudanesa</option>
                      <option value="SEK">SEK - Corona sueca</option>
                      <option value="SGD">SGD - Dólar De Singapur</option>
                      <option value="SHP">SHP - Libra de Santa Helena</option>
                      <option value="SLL">SLL - Leona</option>
                      <option value="SOS">SOS - Chelín somalí</option>
                      <option value="SRD">SRD - Dólar de Suriname</option>
                      <option value="SSP">SSP - Libra sudanesa Sur</option>
                      <option value="STD">STD - Dobra</option>
                      <option value="SVC">SVC - Colon El Salvador</option>
                      <option value="SYP">SYP - Libra Siria</option>
                      <option value="SZL">SZL - Lilangeni</option>
                      <option value="THB">THB - Baht</option>
                      <option value="TJS">TJS - Somoni</option>
                      <option value="TMT">TMT - Turkmenistán nuevo manat</option>
                      <option value="TND">TND - Dinar tunecino</option>
                      <option value="TOP">TOP - Pa'anga</option>
                      <option value="TRY">TRY - Lira turca</option>
                      <option value="TTD">TTD - Dólar de Trinidad y Tobago</option>
                      <option value="TWD">TWD - Nuevo dólar de Taiwán</option>
                      <option value="TZS">TZS - Shilling tanzano</option>
                      <option value="UAH">UAH - Hryvnia</option>
                      <option value="UGX">UGX - Shilling de Uganda</option>
                      <option value="USD">USD - Dólar americano</option>
                      <option value="USN">USN - Dólar estadounidense (día siguiente)</option>
                      <option value="UYI">UYI - Peso Uruguay en Unidades Indexadas (URUIURUI)</option>
                      <option value="UYU">UYU - Peso Uruguayo</option>
                      <option value="UZS">UZS - Uzbekistán Sum</option>
                      <option value="VEF">VEF - Bolívar</option>
                      <option value="VND">VND - Dong</option>
                      <option value="VUV">VUV - Vatu</option>
                      <option value="WST">WST - Tala</option>
                      <option value="XAF">XAF - Franco CFA BEAC</option>
                      <option value="XAG">XAG - Plata</option>
                      <option value="XAU">XAU - Oro</option>
                      <option value="XBA">XBA - Unidad de Mercados de Bonos Unidad Europea Composite (EURCO)</option>
                      <option value="XBB">XBB - Unidad Monetaria de Bonos de Mercados Unidad Europea (UEM-6)</option>
                      <option value="XBC">XBC - Mercados de Bonos Unidad Europea unidad de cuenta a 9 (UCE-9)</option>
                      <option value="XBD">XBD - Mercados de Bonos Unidad Europea unidad de cuenta a 17 (UCE-17)</option>
                      <option value="XCD">XCD - Dólar del Caribe Oriental</option>
                      <option value="XDR">XDR - DEG (Derechos Especiales de Giro)</option>
                      <option value="XOF">XOF - Franco CFA BCEAO</option>
                      <option value="XPD">XPD - Paladio</option>
                      <option value="XPF">XPF - Franco CFP</option>
                      <option value="XPT">XPT - Platino</option>
                      <option value="XSU">XSU - Sucre</option>
                      <option value="XTS">XTS - Códigos reservados específicamente para propósitos de prueba</option>
                      <option value="XUA">XUA - Unidad ADB de Cuenta</option>
                      <option value="XXX">XXX - Los códigos asignados para las transacciones en que intervenga ninguna moneda</option>
                      <option value="YER">YER - Rial yemení</option>
                      <option value="ZAR">ZAR - Rand</option>
                      <option value="ZMW">ZMW - Kwacha zambiano</option>
                      <option value="ZWL">ZWL - Zimbabwe Dólar</option>
                    </select>
                    <label>Moneda</label>
                  </div>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                  <div class="form-floating mb-3">
                    <select class="form-select" name="tipoCFDI" id="tipoCFDI" disabled>
                      <option value="I">I - Ingreso</option>
                      <option value="E">E - Egreso</option>
                      <option value="T">T - Traslado</option>
                      <option value="N">N - Nómina</option>
                      <option value="P">P - Pago</option>
                    </select>
                    <label>Tipo de comprobante</label>
                  </div>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                  <div class="form-floating mb-3">
                    <select class="form-select" name="exportacionCFDI" id="exportacionCFDI" disabled>
                      <option value="01">01 - No aplica</option>
                      <option value="02">02 - Definitiva con clave A1</option>
                      <option value="03">03 - Temporal</option>
                      <option value="04">04 - Definitiva con clave distinta a A1 o cuando no existe enajenación en términos del CFF</option>
                    </select>
                    <label>Exportación</label>
                  </div>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                  <div class="form-floating mb-3">
                    <select class="form-select" name="metodoCFDI" id="metodoCFDI" disabled>
                       <option value="PUE">PUE - Pago en una sola exhibición</option>
                       <option value="PPD">PPD - Pago en parcialidades o diferido</option>
                    </select>
                    <label>Método de Pago</label>
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="lugarCFDI" name="lugarCFDI" placeholder="Lugar Expedición" disabled>
                    <label>Lugar Expedición (CP Sucursal)</label>
                  </div>
                </div>
              </div>  
              <hr>
              <div class="row">
                <div class="col-12" id="datosEmisorCFDI">
                  
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12" id="datosReceptoCFDI">
                  
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 col-sm-12 mb-3">
                  <div class="form-floating mb-3">
                    <select class="form-select" name="usoCFDI" id="usoCFDI">
                      <option value="">--Selecciona una opción--</option>
                      <option value="G01">G01 - Adquisición de mercancías.</option>
                      <option value="G02">G02 - Devoluciones, descuentos o bonificaciones.</option>
                      <option value="G03">G03 - Gastos en general.</option>
                      <option value="I01">I01 - Construcciones.</option>
                      <option value="I02">I02 - Mobiliario y equipo de oficina por inversiones.</option>
                      <option value="I03">I03 - Equipo de transporte.</option>
                      <option value="I04">I04 - Equipo de computo y accesorios.</option>
                      <option value="I05">I05 - Dados, troqueles, moldes, matrices y herramental.</option>
                      <option value="I06">I06 - Comunicaciones telefónicas.</option>
                      <option value="I07">I07 - Comunicaciones satelitales.</option>
                      <option value="I08">I08 - Otra maquinaria y equipo.</option>
                      <option value="D01">D01 - Honorarios médicos, dentales y gastos hospitalarios.</option>
                      <option value="D02">D02 - Gastos médicos por incapacidad o discapacidad.</option>
                      <option value="D03">D03 - Gastos funerales.</option>
                      <option value="D04">D04 - Donativos.</option>
                      <option value="D05">D05 - Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).</option>
                      <option value="D06">D06 - Aportaciones voluntarias al SAR.</option>
                      <option value="D07">D07 - Primas por seguros de gastos médicos.</option>
                      <option value="D08">D08 - Gastos de transportación escolar obligatoria.</option>
                      <option value="D09">D09 - Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.</option>
                      <option value="D10">D10 - Pagos por servicios educativos (colegiaturas).</option>
                      <option value="S01">S01 - Sin efectos fiscales.</option>  
                      <option value="CP0">CP0 -1  Pagos</option>
                      <option value="CN0">CN0 -1  Nómina</option>
                    </select>
                    <label>Uso de CFDI</label>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-hover table-striped text-center" width="100%" style="font-size: 12px;">
                    <thead>
                      <tr>
                        <th>Clave Prod./Serv.</th> 
                        <th>No. Identificación</th> 
                        <th>Descripción</th>  
                        <th>Clave Unidad</th> 
                        <th>Unidad</th> 
                        <th>Cantidad</th> 
                        <th>Valor Unitario</th>  
                        <th>Subtotal</th>
                        <th>Descuento</th>
                        <th>Impuestos</th> 
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody id="conceptosCFDI">
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="7" class="text-end">Subtotal</th>
                        <td colspan="3" class="dinero" id="subtotalCFDI">0</td>
                      </tr>
                      <tr>
                        <th colspan="7" class="text-end">Descuento</th>
                        <td colspan="3" class="dinero" id="totalDescuentoCFDI">0</td>
                      </tr>
                      <tr>
                        <th colspan="7" class="text-end">Total Impuestos Trasladados</th>
                        <td colspan="3" class="dinero" id="impuetosTrasCFDI">0</td>
                      </tr>
                      <tr>
                        <th colspan="7" class="text-end">Total Impuestos Retenidos</th>
                        <td colspan="3" class="dinero" id="impuestosRetCFDI">0</td>
                      </tr>
                      <tr>
                        <th colspan="7" class="text-end">Total</th>
                        <td colspan="3" class="dinero" id="totalCFDI">0</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
              <button type="submit" class="btn btn-primary" id="bTimbrarFactura"><i class="fa fa-check-circle"></i> <strong>Facturar</strong></button>
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
    <script type="text/javascript" src="vistas/assets/js/hacerventa.js"></script>
    <script type="text/javascript" src="vistas/assets/js/hacerventacaja.js"></script>
    <script type="text/javascript" src="vistas/assets/js/categorias.js"></script>
    <script type="text/javascript" src="vistas/assets/js/impuestos.js"></script>
    <script type="text/javascript" src="vistas/assets/js/usuarios.js"></script>
    <script type="text/javascript" src="vistas/assets/js/productos.js"></script>
    <script type="text/javascript" src="vistas/assets/js/inventario.js"></script>
    <script type="text/javascript" src="vistas/assets/plugins/JsBarcode.all.min.js"></script>
    <script type="text/javascript" src="vistas/assets/js/cajas.js"></script>
    <script type="text/javascript" src="vistas/assets/js/perfil.js"></script>
    <script type="text/javascript" src="vistas/assets/js/tickets.js"></script>
    <script type="text/javascript" src="vistas/assets/js/zonas.js"></script>
    <script type="text/javascript" src="vistas/assets/js/compras.js"></script>
    <script type="text/javascript" src="vistas/assets/js/hacerCompra.js"></script>
    <script type="text/javascript" src="vistas/assets/js/ventas.js"></script>
    <script type="text/javascript" src="vistas/assets/js/facturacion.js"></script>
  </body>
</html>
