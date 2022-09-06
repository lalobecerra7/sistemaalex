<div class="modal fade" id="ModalAgregarUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
        <div class="modal-content">
            <div class="modal-header bg-inverse bd-inverse-darken">
                <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalUsuarios"></span> usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormUsuarios">
                <div class="modal-body">
                    <div class='rounded mx-auto d-block' id="divImg" style="width: 250px; height: 170px; cursor:pointer; border-radius:4px; overflow:hidden;">
                    </div>
                    <br>
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/gif">
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="text" id="firstname" name="firstname" placeholder="Escribe el nombre" class="form-control" required>
                                <label for="firstname">Nombre</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="email" id="email" name="email" placeholder="Escribe el correo electrónico" class="form-control" required>
                                <label for="email">Correo electrónico</label>
                            </div>
                        </div>
                    </div>
                    <div id="modCon" style="display: none">
                        <span class="form-control">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="swMC" name="swMC" value="0">
                                <label class="form-check-label" for="swBD" id="labelMC">No modificar contraseña</label>
                            </div>
                        </span>
                    </div>
                    <div id="contrasenia" class="row mt-2">
                        <div class="col-md-6">
                            <label for="contrasena">Contraseña</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Escriba una contraseña" minlength="3" required>
                                <button type="button" class="input-group-append btn btn-outline-secondary" id="toggle-password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="repetirContrasena">Confirmar contraseña</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="repetirContrasena" name="repetirContrasena" placeholder="Confirme la contraseña" minlength="3" required>
                                <button type="button" class="input-group-append btn btn-outline-secondary" id="toggle-password2">
                                    <i class="far fa-eye"></i>
                            </button>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="Tipo_usuario">Tipo de usuario</label>
                            <select class="form-select" id="Tipo_usuario" name="Tipo_usuario">
                                <option disabled>Seleccione</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Normal">Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="Estado" class="labels">Estatus</label>
                            <span class="form-control form-floating">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="swBD" name="swBD" value="Desbloqueado">
                                    <label class="form-check-label" for="swBD" id="labelBD"><i class="fa fa-unlock" aria-hidden="true"></i> Desbloqueado</label>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="GuardarUsuario" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
                    <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>


<br>
<div class="mb-3 mt-2">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
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
                    <button type="button" class="btn btn-success" id="botonNuevoUsuario" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario"><i class="fa fa-file"></i> Nuevo</button>
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_usuarios" titulo="Usuarios"><i class="fa fa-retweet"></i></a>
                </div>
            </div>
            <br>
            <div class="Principal">
                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaUsuarios" width="100%" style="font-size: 12px;">
                            <thead>
                                <th style="width: 10%;" orden="No">Foto</th>
                                <th style="width: 25%;">Nombre</th>
                                <th style="width: 25%;" orden="No">Correo</th>
                                <!-- <th style="width: 15%;" orden="No">Tipo de usuario</th> -->
                                <th style="width: 10%;" orden="No">Estado</th>
                                <th style="width: 15%;" orden="No">Permisos</th>
                                <th style="width: 15%;" orden="No">Acciones</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="content" class="section">
    <div class="section-header">
        <h1 id="vistaTitulo"></h1>
    </div>

    <div class="Principal">
        <div class="row">
            <div class="col-6 text-start">
                <button type="button" class="btn btn-primary" id="bNuevaUsuario" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">
                    Agregar usuario&nbsp;<i class="fa fa-user-plus" aria-hidden="true"></i>
                </button>
            </div>
            <div class="col-6 text-end">
                <button type="button" class="btn btn-light" onclick="$('#cargarUsuarios').trigger('click')">
                    Recargar <i class="fas fa-rotate-right"></i>
                </button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaUsuarios" width="100%" style="font-size: 12px;">
                    <thead>
                        <th style="width: 5%;" orden="No">Foto</th>
                        <th style="width: 25%;">Nombre</th>
                        <th style="width: 20%;" orden="No">Correo</th>
                        <th style="width: 15%;" orden="No">Tipo de usuario</th>
                        <th style="width: 10%;" orden="No">Estado</th>
                        <th style="width: 15%;" orden="No">Permisos</th>
                        <th style="width: 10%;" orden="No">Acciones</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> -->


<!-- <div class="modal fade" id="modalAgregarUsuario" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <span id="TipoTituloUsuario">Nuevo </span>usuario
                </h5>
                <button type="button" class="btn-close cerrarmodal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormUsuario">
                    <div class='rounded mx-auto d-block' id="divImg" style="width: 250px; height: 170px; cursor:pointer; border-radius:4px; overflow:hidden;">
                    </div>
                    <br>
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/gif">
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="text" id="firstname" name="firstname" placeholder="Escribe el nombre" class="form-control" required>
                                <label for="firstname">Nombre</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="email" id="email" name="email" placeholder="Escribe el correo electrónico" class="form-control" required>
                                <label for="email">Correo electrónico</label>
                            </div>
                        </div>
                    </div>
                    <div id="modCon" style="display: none">
                        <span class="form-control">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="swMC" name="swMC" value="0">
                                <label class="form-check-label" for="swBD" id="labelMC">No modificar contraseña</label>
                            </div>
                        </span>
                    </div>
                    <div id="contrasenia" class="row mt-2">
                        <div class="col-md-6">
                            <label for="contrasena">Contraseña</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Escriba una contraseña" minlength="3" required>
                                <button type="button" class="input-group-append btn btn-outline-secondary" id="toggle-password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="repetirContrasena">Confirmar contraseña</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="repetirContrasena" name="repetirContrasena" placeholder="Confirme la contraseña" minlength="3" required>
                                <button type="button" class="input-group-append btn btn-outline-secondary" id="toggle-password2">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="Tipo_usuario">Tipo de usuario</label>
                            <select class="form-select" id="Tipo_usuario" name="Tipo_usuario">
                                <option disabled>Seleccione</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Normal">Normal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="Estado" class="labels">Estatus</label>
                            <span class="form-control form-floating">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="swBD" name="swBD" value="Desbloqueado">
                                    <label class="form-check-label" for="swBD" id="labelBD"><i class="fa fa-unlock" aria-hidden="true"></i> Desbloqueado</label>
                                </div>
                            </span>
                        </div>
                    </div>
                    <br>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cerrarmodal" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="GuardarUsuario">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->


<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalPermisos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPermisos" class="table-responsive">
                    <input type="hidden" id="idUsuPer" name="idUsuPer">
                    <table class="table text-center" width="100%">
                        <tbody>
                            <tr class="table-secondary">
                                <th>Puedes seleccionar un perfil</th>
                                <th style="vertical-align: middle;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input checkPerfil" type="radio" name="radiosPerfil" id="perfil1" value="option1">
                                        <label for="perfil1" class="form-check-label">Capturista</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input checkPerfil" type="radio" name="radiosPerfil" id="perfil2" value="option2">
                                        <label for="perfil2" class="form-check-label" for="inlineRadio2">Vendedor</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input checkPerfil" type="radio" name="radiosPerfil" id="perfil3" value="option3">
                                        <label for="perfil3" class="form-check-label" for="inlineRadio2">Supervisor</label>
                                    </div>
                                    <button type="button" class="btn btn-light btn-sm" id="bResetearPer">Resetear Permisos <i class="fas fa-redo"></i></button>
                                </th>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_inventario">Inventario</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Ver merma</td>
                                                <td>Registrar merma</td>
                                                <td>Eliminar merma</td>
                                                <td>Aumentar existencias</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_ventas">Ventas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Nueva venta</td>
                                                <td>Eliminar venta</td>
                                                <td>Cancelar venta</td>
                                                <td>Imprimir tickets</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_salidas">Salidas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Nueva salida</td>
                                                <td>Eliminar salidas</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_clientes">Clientes</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Nuevo cliente</td>
                                                <td>Modificar cliente</td>
                                                <td>Eliminar cliente</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_etiquetas">Etiquetas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Imprimir etiquetas</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_productos">Productos</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar producto</td>
                                                <td>Modificar producto</td>
                                                <td>Eliminar producto</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_reportesalidas">Reporte de salidas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_vendedores">Vendedores</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar vendedor</td>
                                                <td>Modificar vendedor</td>
                                                <td>Eliminar vendedor</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_vehiculos">Vehiculos</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar vehiculo</td>
                                                <td>Modificar vehiculo</td>
                                                <td>Eliminar vehiculo</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_usuarios">Usuarios</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar usuario</td>
                                                <td>Modificar usuario</td>
                                                <td>Eliminar usuario</td>
                                                <td>Permisos del usuario</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>
</div>