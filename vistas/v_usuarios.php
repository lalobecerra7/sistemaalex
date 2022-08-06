<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
              	<div class="row row-cols-auto justify-content-end">
                  	<div class="col">
                  		<button type="button" class="btn btn-primary" id="bNuevoUsuario" data-bs-toggle="modal" data-bs-target="#ModalUsuarios">Agregar Usuario <i class="fas fa-plus"></i></button>
                  	</div>
                  	<div class="col">
                  		<button type="button" class="btn btn-light"  id="recargarUsuarios" onclick="$('#cargaUsuarios').trigger('click');">Recargar <i class="fas fa-sync-alt"></i></button>
                  	</div>
              	</div>
              	<br>
              	<div class="row">
                  	<div class="col-12 table-responsive" style="font-size: 13px;">
                    	<table class="table table-hover table-bordered table-striped text-center Datatable tablaDatatable" id="tablaUsuarios" width="100%">
                          	<thead>
                              	<tr>
                              		<th>Fecha de Registro</th>
									<th>Nombre</th>
									<th>Correo</th>
									<th>Estatus</th>
									<th>Tipo</th>
									<th>Permisos</th>
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
<div class="modal fade" id="ModalUsuarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-centered">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title">Usuarios</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<form id="formUsuarios">
	      		<div class="modal-body">
	      			<div class="row">
		      			<div class="col-md-6 mb-3">
		      				<div class="form-floating">
						      	<input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" placeholder="Nombre" required>
						      	<label for="">Nombre</label>
						    </div>	
						</div>	
						<div class="col-md-6 mb-3">
		      				<div class="form-floating">
						      	<input type="email" class="form-control" id="correoUsuario" name="correoUsuario" placeholder="Correo" required>
						      	<label for="">Correo</label>
						    </div>	
						</div>	
						<div class="col-12 mb-3 oculto" id="checkCC">
							<div class="form-check form-switch">
							  	<input class="form-check-input checkbox-lg" type="checkbox" id="checkCambiarContras">
							  	<label class="form-check-label label-lg">Cambiar Contraseña</label>
							</div>
						</div>
						<div class="col-12 oculto" id="ocultarContras">
							<div class="row">
								<div class="col-md-5 mb-3">
				      				<div class="form-floating">
								      	<input type="password" class="form-control contras" id="contraUsuario" name="contraUsuario" placeholder="Contraseña" disabled="true" required>
								      	<label for="">Contraseña</label>
								    </div>	
								</div>	
								<div class="col-md-5 mb-3">
				      				<div class="form-floating">
								      	<input type="password" class="form-control contras" id="contraRUsuario" name="contraRUsuario" placeholder="Contraseña" disabled="true" required>
								      	<label for="">Repite Contraseña</label>
								    </div>	
								</div>
								<div class="col-md-2 text-center" style="padding-top: 10px;">
									<button type="button" class="btn btn-light verPass2" attrForm="formUsuarios"><i class="fas fa-eye"></i></button>
								</div>
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<div class="form-floating">
							  	<select class="form-select" name="tipoUsuario" id="tipoUsuario" required>
							    	<option value="">- Seleccione una opción -</option>
									<option value="Normal">Normal</option>
									<option value="Administrador">Administrador</option>
							  	</select>
							  	<label for="">Tipo</label>
							</div>
						</div>
						<div class="col-md-6 mb-3">
						    <div class="form-check form-switch">
							  	<input class="form-check-input checkbox-lg" type="checkbox" id="checkBloquearUsu">
							  	<label class="form-check-label label-lg">Bloquear</label>
							</div>
						</div>
					</div>
	      		</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
	        		<button type="submit" class="btn btn-primary" id="bGuardarUsuario">Guardar <i class="fas fa-save"></i></button>
	      		</div>
	      	</form>	
    	</div>
  	</div>
</div>

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
					<table class="table table-hover text-center" width="100%">
						<tbody>
							<tr class="table-secondary">
								<th>Puedes seleccionar un perfil</th>
								<th style="vertical-align: middle;">
									<div class="form-check form-check-inline">
									  	<input class="form-check-input ckeckPerfil" type="radio" name="radiosPerfil" id="perfil1" value="option1">
									  	<label class="form-check-label">Capturista</label>
									</div>
									<div class="form-check form-check-inline">
									  	<input class="form-check-input ckeckPerfil" type="radio" name="radiosPerfil" id="perfil2" value="option2">
									  	<label class="form-check-label" for="inlineRadio2">Supervisor</label>
									</div>

									<button type="button" class="btn btn-light btn-sm" id="bResetearPer">Resetear Permisos <i class="fas fa-redo"></i></button>
								</th>
							</tr>
							<tr>
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_inicio">Inicio</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver Totales</td>
												<td>Ver Gráfica Predios</td>
												<td>Ver Gráfica Totales</td>
												<td>Ver Tabla Clientes</td>
												<td>Ver Tabla Vendedores</td>
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_predios">Predios</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Poner en Venta</td>
												<td>Modificar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_plantas">Plantas</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
												<td>Modificar Precios</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_tabuladores">Tabuladores</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Modificar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_pagos">Pagos</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_comisiones">Comisiones</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Aceptar</td>
												<td>Cancelar</td>
												<td>Modificar</td>
											</tr>
											<tr class="NormalP">
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
												<td>Modificar</td>
												<td>Eliminar</td>
												<td>Modificar precio</td>
												<td>Generar ventas</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_transacciones">Transacciones</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Aprobar</td>
												<td>Modificar</td>
												<td>Cancelar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_traslados">Traslados</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_gastos">Gastos</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_contacto">Contacto</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Contestar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_sugerencias">Sugerencias</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Contestar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_landing">Landing</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Contestar</td>
												<td>Modificar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_landing2">Landing 2</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Contestar</td>
												<td>Modificar</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_notificaciones">Notificaciones</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
												<td>Saldos</td>
												<td>Compras</td>
												<td>Ventas</td>
												<td>Pagos</td>
												<td>Gastos</td>
												<td>Movimientos</td>
												<td>Egresos</td>
												<td>Ingresos</td>
												<td>Finanzas</td>
												<td>Estadísticas</td>
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
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_empleados">Empleados</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
												<td>Ver Asistencias</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_nomina">Nomina</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
												<td>Imprimir recibos</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_usuariosGastos">Usuarios Gastos</th>
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
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_productosR">Productos</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
												<td>Nueva compra</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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
												<td>Ver</td>
												<td>Merma</td>
												<td>Transferir</td>
											</tr>
											<tr class="NormalP">
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
								<th width="10%" style="vertical-align: middle;" class="permisoMo" id="v_lugar">Ubicacion</th>
								<td class="table-responsive">
									<table class="table table-bordered text-center" width="100%">
										<tbody>
											<tr>
												<td>Ver</td>
												<td>Agregar</td>
												<td>Modificar</td>
												<td>Eliminar</td>
											</tr>
											<tr class="NormalP">
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


