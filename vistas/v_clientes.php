<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
              	<div class="row row-cols-auto justify-content-end">
                    <div class="col">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalModificarCompra" id="ModificarCompra">Modificar precio de venta <i class="fas fa-edit"></i></button>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalGenerarCompras" id="GenerarCompra">Generar compras <i class="fas fa-money-bill-wave"></i></button>
                    </div>
                  	<div class="col">
                        <button type="button" class="btn btn-light"  id="recargarUsuarios" onclick="$('#cargaClientes').trigger('click');">Recargar <i class="fas fa-sync-alt"></i></button>
                  	</div>
              	</div>
              	<br>
              	<div class="row">
                  	<div class="col-12 table-responsive" style="font-size: 13px;">
                    	<table class="table table-hover table-bordered table-striped text-center Datatable tablaDatatable" id="tablaClientes" width="100%">
                          	<thead>
                              	<tr>
                                	<th>Fecha de Registro</th>
                  					<th>Usuario</th>
                  					<th>Estatus</th>
                  					<th>Tiempo de sesión</th>
                  					<th>Login</th>
                                    <th>Vendedor</th>
                  					<th>Acción</th>
                				</tr>
                          	</thead>
                      	</table>
                  </div>
              	</div>
            </div>
        </div>
    </div>
</div>
<br>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalClientes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formClientes">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nombreCliente" id="nombreCliente" placeholder="Nombre">
                                <label for="nombrePerfil">Nombre</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="pApellidoCliente" id="pApellidoCliente" placeholder="Primer Apellido">
                                <label for="floatingInput">Primer Apellido</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="sApellidoCliente" id="sApellidoCliente" placeholder="Segundo Apellido">
                                <label for="floatingInput">Segundo Apellido</label>
                            </div>  
                        </div>
                    </div>  
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fechaNaCliente" id="fechaNaCliente" placeholder="Fecha de Nacimiento">
                                <label for="nombrePerfil">Fecha de Nacimiento</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-control" name="sexoCliente" id="sexoCliente">
                                    <option value="">-- Selecciona una opción --</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                                <label for="floatingSelect">Sexo</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" name="nacionalidadCliente" id="nacionalidadCliente" placeholder="Ej. Mexicana">
                                <label for="floatingInput">Nacionalidad</label>
                            </div>  
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nomDniCliente" placeholder="Ej. CURP/DNI" id="nomDniCliente">
                                <label for="floatingInput">Nombre del no. de identificación</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="dniCliente" id="dniCliente" placeholder="No. de identificación">
                                <label for="floatingInput">Numero de Identificación Oficial</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" name="telCliente" id="telCliente" placeholder="Telefono">
                                <label for="floatingInput">Teléfono</label>
                            </div>  
                        </div>
                    </div>  
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="cpCliente" id="cpCliente" placeholder="Código Postal">
                                <label for="floatingInput">Código Postal</label>
                            </div>  
                        </div>
                        <div class="col-md-5">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="direccionCliente" id="direccionCliente" placeholder="Dirección">
                                <label for="floatingInput">Dirección</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="coloniaCliente" id="coloniaCliente" placeholder="Colonia">
                                <label for="floatingInput">Colonia</label>
                            </div>  
                        </div>
                    </div>  
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="ciudadCliente" id="ciudadCliente" placeholder="Ciudad">
                                <label for="floatingInput">Ciudad</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="estadoCliente" id="estadoCliente" placeholder="Estado">
                                <label for="floatingInput">Estado</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="paisCliente" id="paisCliente" placeholder="País">
                                <label for="floatingInput">País</label>
                            </div>  
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-control" name="sexoBCliente" id="sexoBCliente">
                                    <option value="">-- Selecciona una opción --</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                                <label for="floatingSelect">Sexo Beneficiario</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="parentescoBCliente" id="parentescoBCliente" placeholder="Parentesco">
                                <label for="floatingInput">Parentesco</label>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nombreBCliente" id="nombreBCliente" placeholder="Nombre">
                                <label for="floatingInput">Nombre Beneficiario</label>
                            </div>  
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 offset-md-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input checkbox-lg" type="checkbox" id="activorCliente">
                                <label class="form-check-label label-lg">Activo</label>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input checkbox-lg" type="checkbox" id="temporalCliente">
                                <label class="form-check-label label-lg">Temporal</label>
                            </div> 
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 offset-md-2">  
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="emailCliente" id="emailCliente" placeholder="Email" required>
                                <label for="floatingInput">Email</label>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input checkbox-lg" type="checkbox" id="bloquearCliente">
                                <label class="form-check-label label-lg">Bloquear</label>
                            </div>
                        </div>    
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input checkbox-lg" type="checkbox" id="checkCambiarContrasC">
                                <label class="form-check-label label-lg">Cambiar Contraseña</label>
                            </div>
                        </div>  
                    </div>
                    <br>
                    <div class="row oculto" id="camContras">
                        <div class="col-md-5">  
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control contras" name="contraCliente" id="contraCliente" placeholder="Contraseña" required>
                                <label for="floatingInput">Contraseña</label>
                            </div>
                        </div>  
                        <div class="col-md-5">  
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control contras" name="contraRCliente" id="contraRCliente" placeholder="Contraseña" required>
                                <label for="floatingInput">Repite Contraseña</label>
                            </div>
                        </div>
                        <div class="col-md-2 text-center" style="margin-top: 15px;">
                            <button type="button" class="btn btn-light verPass2" attrForm="formClientes"><i class="fas fa-eye"></i></button>
                        </div>
                    </div> 
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="" class="form-label">Referido de:</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="VendedorReferido" id="VendedorReferido" disabled>
                                <button type="button" class="btn btn-outline-secondary" id="bBuscarVendedorReferido" data-bs-toggle="modal" data-bs-target="#ModalVerVendedores"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label">Saldo regalado</label>
                            <input type="text" class="form-control" name="SaldoRegalado" id="SaldoRegalado" disabled>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input checkbox-lg" type="checkbox" id="AgregarBono">
                                <label class="form-check-label label-lg">Agregar bono</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                    <button type="submit" class="btn btn-primary" id="bGuardarCliente">Guardar <i class="fas fa-save"></i></button>
                </div>
            </form> 
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerVendedores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vendedores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-light btn-sm" id="recargarClientesTran">Recargar <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-bordered table-striped text-center" width="100%" id="TablaVendedoresR">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Foto</th>
                                    <th style="width: 50%">Nombre</th>
                                    <th style="width: 40%">Correo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalGenerarCompras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generar compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="form-label">Cliente</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="ClienteGenerar" id="ClienteGenerar" attrid="0" disabled>
                            <button type="button" class="btn btn-outline-secondary" id="bBuscarClienteVenta" data-bs-toggle="modal" data-bs-target="#ModalVerClientesVenta"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Ventas</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="VentasGenerar" id="VentasGenerar" attrid="0" disabled>
                            <button type="button" class="btn btn-outline-secondary" id="bBuscarVentasVenta" data-bs-toggle="modal" data-bs-target="#ModalVerVentasVenta"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Plantas a comprar</label>
                         <input type="number" class="form-control" name="PlantasComprar" id="PlantasComprar" min="0">
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Saldo</label>
                         <input type="text" class="form-control" name="SaldoGenerar" id="SaldoGenerar" disabled>
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label">Regalado</label>
                         <input type="text" class="form-control" name="RegaladoGenerar" id="RegaladoGenerar" disabled>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered table-striped text-center" width="100%" id="TablaVentasSeleccionadas">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Vendedor</th>
                                    <th>Predio</th>
                                    <th>Planta disponible</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="GenerarCompraCliente">Guardar venta <i class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalModificarCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modificar precio de ventas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="form-label">Cliente</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="ClienteModificar" id="ClienteModificar" disabled attrid="0">
                            <button type="button" class="btn btn-outline-secondary" id="bBuscarClientesModificar" data-bs-toggle="modal" data-bs-target="#ModalVerClientesMod"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-label">Venta</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="VentaModificar" id="VentaModificar" disabled  attrid="0">
                            <button type="button" class="btn btn-outline-secondary" id="bBuscarVentaModificar"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-label">Nuevo Precio</label>
                        <input type="number" min="0" step="any" class="form-control" name="PrecioNuevoVenta" id="PrecioNuevoVenta">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered table-striped text-center" width="100%" id="TablaVentasCliente">
                            <thead>
                                <tr>
                                    <th>Fecha de Registro</th>
                                    <th>Vendedor</th>
                                    <th>Venta</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="ModificarPrecioVenta">Modificar precio <i class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>


<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerClientesMod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-light btn-sm" id="recargarClientesMod">Recargar <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-bordered table-striped text-center" width="100%" id="TablaClientesMod">
                            <thead>
                                <tr>
                                    <th>Fecha de Registro</th>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerVentasMod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ventas enviadas a <span id="NombreCliente"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-bordered table-striped text-center" width="100%" id="TablaSeleccionarVentas">
                            <thead>
                                <tr>
                                    <th>Fecha de Registro</th>
                                    <th>Vendedor</th>
                                    <th>Venta</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerClientesVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-light btn-sm" id="recargarClientesVen">Recargar <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-bordered table-striped text-center" width="100%" id="TablaClientesVentas">
                            <thead>
                                <tr>
                                    <th>Fecha de Registro</th>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--/////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerVentasVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ventas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-bordered table-striped text-center" width="100%" id="TablaVentas">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Vendedor</th>
                                    <th>Predio</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>