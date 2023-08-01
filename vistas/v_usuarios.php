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
					<button type="button" class="btn btn-success" id="botonNuevoUsuario" data-bs-toggle="modal" data-bs-target="#ModalUsuario"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarUsuarios').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaUsuarios" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 15%;">Foto</th>
		            <th style="width: 25%;">Nombre</th>
		            <th style="width: 20%;" orden="No">Usuario</th>
		            <th style="width: 15%;" orden="No">Estatus</th>
		            <th style="width: 15%;" orden="No">Permisos</th>
		          	<th style="width: 10%;" orden="No">Acciones</th>
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

<!--//////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalUsuario"></span> usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormUsuarios">
	      <div class="modal-body">
	      	<div class="row">
	      	 	<div class="offset-md-4 col-md-4 col-sm-12 text-center">
	      	 		<div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-new thumbnail" id="verfotoUsuario" style="width: 250px; height: 170px;cursor:pointer;border-radius:4px;border:2px solid grey;"><img src="vistas/assets/archivos/default.jpg">
						</div>	
					</div>
					<br>
					<input class="form-control" type="file" id="FotoUsuario" name="FotoUsuario">
	      	 	</div>
	      	</div>
	      	<br>
	      	<div class="row">
	      		<div class="col-md-4 col-sm-12 mb-3">
	      			<div class="form-floating">
		            <input type="text" class="form-control" id="NombreUsuario" name="NombreUsuario" placeholder="Ingresa el nombre del usuario">
		          	<label for="NombreUsuario">Nombre</label>
		          </div>
	      		</div>
	      		<div class="col-md-4 col-sm-12 mb-3">
	      			<div class="form-floating">
			            <input type="text" class="form-control" id="PrimerApellidoUsuario" name="PrimerApellidoUsuario" placeholder="Ingresa el primer apellido del usuario">
			          	<label for="PrimerApellidoUsuario">Primer apellido</label>
		          	</div>
				</div>
				<div class="col-md-4 col-sm-12 mb-3">
	      			<div class="form-floating">
		        	    <input type="text" class="form-control" id="SegundoApellidoUsuario" name="SegundoApellidoUsuario" placeholder="Ingresa el segundo apellido del usuario">
		          		<label for="SegundoApellidoUsuario">Segundo apellido</label>
		          	</div>
				</div>
				<hr>
				<b>Contraseña</b>
				<div class="row mt-2 mb-2">
					<div class="col-md-3 col-sm-12 campoMostrarContrasena">
						<div class="form-check form-switch">
						  <input class="form-check-input" type="checkbox" id="mostrarContrasena" name="mostrarContrasena">
						  <label class="form-check-label" for="mostrarContrasena">Cambiar contraseña</label>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-3 camposContrasena">
		      			<div class="form-floating">
			        		<input type="password" class="form-control contra" id="NuevaContrasena" name="NuevaContrasena" placeholder="Ingresa la nueva contraseña">
			          		<label for="NuevaContrasena">Nueva contraseña</label>
			          	</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-3 camposContrasena">
		      			<div class="form-floating">
			        	    <input type="password" class="form-control contra" id="RepetirNuevaContrasena" name="RepetirNuevaContrasena" placeholder="Ingresa la nueva contraseña otra vez">
			          		<label for="RepetirNuevaContrasena">Repetir contraseña</label>
			          	</div>
					</div>
					<div class="col-md-1 col-sm-12 mb-3 camposContrasena">
						<button class="btn btn-light VerContrasenas" type="button">
							<i class="fas fa-eye"></i>
						</button>
					</div>
				</div>
				<hr>
				<b class="mb-3">Datos de sesión</b>
				<div class="col-md-4 col-sm-12 mb-3">
	      			<div class="form-floating">
		        	    <input type="email" class="form-control" id="CorreoUsuario" name="CorreoUsuario" placeholder="Ingresa el correo del usuario">
		          		<label for="CorreoUsuario">Correo electrónico</label>
		          	</div>
				</div>
				<div class="col-md-4 col-sm-12 mb-3">
					<div class="form-floating">
		        	    <select class="form-control contra" id="TipoUsuario" name="TipoUsuario" placeholder="Ingresa el tipo de usuario">
		            		<option value="Normal">Normal</option>
		            		<option value="Administrador">Administrador</option>
		            	</select>
		          		<label for="TipoUsuario">Tipo de usuario</label>
		          	</div>
				</div>
				<div class="col-md-4 col-sm-12 mb-3">
					<div class="form-floating">
		        	    <select class="form-control contra" id="EstatusUsuario" name="EstatusUsuario" placeholder="Ingresa el tipo de usuario">
		            		<option value="0">Desbloqueado</option>
		            		<option value="1">Bloqueado</option>
		            	</select>
		          		<label for="EstatusUsuario">Estatus</label>
		          	</div>
				</div>
				<div class="col-md-4">
	            	<div class="form-floating mb-3">
						<select class="form-select" name="SucursalUsuario" id="SucursalUsuario" >
							<option value="">- Seleccione una opción -</option>
							#SucursalesUsuarios#
					    </select>
						<label for="SucursalUsuario">Sucursal</label>
					</div>
	            </div>
				<div class="col-md-4 col-sm-12 mb-3">
					<div class="form-floating">
		        	    <select class="form-control contra" id="EstatusCuenta" name="EstatusCuenta" placeholder="Ingresa el estatus de la cuenta">
		            		<option value="0">Inactivo</option>
		            		<option value="1">Activo</option>
		            	</select>
		          		<label for="EstatusCuenta">Estatus de la cuenta</label>
		          	</div>
				</div>
				<div class="col-md-4 col-sm-12 mb-3">
					<div class="form-floating">
		        	    <select class="form-control contra" id="ContraTemporal" name="ContraTemporal" placeholder="Ingresa el tipo de usuario">
		            		<option value="0">Inactiva</option>
		            		<option value="1">Activa</option>
		            	</select>
		          		<label for="ContraTemporal">Contraseña temporal</label>
		          	</div>
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

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalPermisos2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <button type="button" class="btn btn-light btn-sm" id="bResetearPer">Resetear Permisos <i class="fas fa-redo"></i></button>
                                </th>
                            </tr>
                            <tr>
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_sucursales">Sucursales</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_proveedores">Proveedores</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_clientes">Clientes</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_orden_compra">Ordenes de Compra</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
                                                <td>Ver Costos</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_compras">Compras</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Cancelar</td>
                                                <td>Eliminar</td>
                                                <td>Ticket</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_compras">Cajas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Modificar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_ventas">Ventas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Cancelar</td>
                                                <td>Eliminar</td>
                                                <td>Facturar</td>
                                                <td>Devoluciones</td>
                                                <td>Ticket</td>
                                                <td>Cerrar Caja</td>
                                                <td>Afectar balance</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_importes">Importes</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Modificar</td>
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
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
                                                <td>Agregar existencias</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_inventario">Inventario</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver inventario</td>
                                                <td>Ver merma</td>
                                                <td>Registrar merma</td>
                                                <td>Modificar merma</td>
                                                <td>Eliminar merma</td>
                                                <td>Ver conversiones</td>
                                                <td>Registrar conversiones</td>
                                                <td>Modificar conversiones</td>
                                                <td>Eliminar conversiones</td>
                                                <td>Ver traslados</td>
                                                <td>Registrar traslados</td>
                                                <td>Completar traslados</td>
                                                <td>Cancelar traslados</td>
                                                <td>Eliminar traslados</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_categorias">Categorias / Familias</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_zonas">Zonas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_areas">Áreas</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_precios">Precios</th>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_impuestos">Impuestos</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_tickets">Configuracion del ticket</th>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_facturacion">Facturación</th>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_usuarios">Usuarios</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Ver</td>
                                                <td>Agregar</td>
                                                <td>Modificar</td>
                                                <td>Eliminar</td>
                                                <td>Permisos</td>
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
                                <th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_reportes">Reportes</th>
                                <td class="table-responsive">
                                    <table class="table table-bordered text-center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>Reporte Balance Caja</td>
                                                <td>Reporte Productos</td>
                                                <td>Reporte Clientes</td>
                                                <td>Reporte Ventas</td>
                                                <td>Reporte Compras</td>
                                                <td>Reporte Finanazas</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input class="form-check-input checkPermisos" type="checkbox" id="bReporteCheck">
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


<!--  ///////////////////////////Modal//////////////////////////////  -->
<div class="modal" id="ModalPermisos" tabindex="-2">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <p class="h5 modal-title">Permisos</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body table-responsive">
        <table class="table table-hover table-bordered text-center" id="tablaPermisos">
          <thead>
            <tr>
              <th>Menú</th>
              <th>Permisos</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td vista="v_sucursales">Sucursales</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_proveedores">Proveedores</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_clientes">Clientes</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_orden_compra">Ordenes de compra</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver costos</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_compras">Compras</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ticket</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_compras">Cajas</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_ventas">Ventas</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Cancelar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Facturar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Devoluciones</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ticket</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Cerrar caja</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Afectar balance</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_importes">Importes</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_productos">Productos</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Agregar existencias</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_inventario">Inventario</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver inventario</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver merma</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Registrar merma</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar merma</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar merma</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver conversiones</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Registrar conversiones</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar conversiones</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar conversiones</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Agregar existencias</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver traslados</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Registrar traslados</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar traslados</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar traslados</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_categorias">Categorias / Familias</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_zonas">Zonas</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_areas">Areas</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_precios">Precios</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_impuestos">Impuestos</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_tickets">Configuración del ticket</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_facturacion">Facturación</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_usuarios">Usuarios</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Ver</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Insertar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Modificar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Eliminar</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Permisos</label>
                </div>
              </td>
            </tr>
            <tr>
              <td vista="v_reportes">Reportes</td>
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Reporte Balance Caja</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Reporte productos</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Reporte clientes</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Reporte ventas</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Reporte compras</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input checkPermisos">
                  <label class="form-check-label">Reporte finanzas</label>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>