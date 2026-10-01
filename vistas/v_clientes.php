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
                    <!-- Boton exportar excel catalogo de clientes -->
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelReporteCatalogoClientes">Catálogo de clientes <i class="fas fa-file-excel"></i></a>
                    <!-- Fin boton -->
					<button type="button" class="btn btn-success" id="botonNuevoCliente" data-bs-toggle="modal" data-bs-target="#ModalCliente"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarClientes').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row mb-5">
					<div class="col-12">
						<table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaClientes" width="100%" style="font-size: 12px;">
							<thead>
								<th style="width: 7%;">ID</th>
								<th style="width: 10%;">Fecha</th>
								<th style="width: 15%;">Nombre</th>
								<th style="width: 20%;">Direcciones</th>
								<th style="width: 15%;" orden="No">Detalles</th>
								<th style="width: 8%;" orden="No">Acciones</th>
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

<!--/////////////////////////////////modal/////////////////////////////////////-->
<div class="modal fade" id="ModalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalCliente"></span> cliente</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="formSucursalesCliente"><button type="submit" id="bGuardarSucursal" hidden></button></form>
			<form id="FormClientes">
				<div class="modal-body">
					<div class="row">
						<div class="offset-md-4 col-md-4 col-sm-12 text-center">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" id="verfotoCliente" style="width: 250px; height: 170px;cursor:pointer;border-radius:4px;border:2px solid grey;"><img src="vistas/assets/archivos/default.jpg"></div>	
							</div>
							<br>
							<input class="form-control" type="file" id="FotoCliente" name="FotoCliente">
						</div>
					</div>
					<br>
					<div class="row">
						<b class="mb-3">Datos del cliente</b>
						<div class="col-md-4">
							<div class="form-floating mb-3">
								<select class="form-select" name="TipoPersona" id="TipoPersona" >
									<option value="Fisica">Fisica</option>
									<option value="Moral">Moral</option>
								</select>
								<label for="TipoPersona">Tipo de persona</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="NombreCliente" name="NombreCliente" placeholder="Ingresa el nombre del cliente">
								<label for="NombreCliente">Nombre</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3 camposFisica">
							<div class="form-floating">
								<input type="text" class="form-control " id="primerApellidoCliente" name="primerApellidoCliente" placeholder="Ingresa el primer apellido">
								<label for="NombreCliente">Primer Apellido</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3 camposFisica">
							<div class="form-floating">
								<input type="text" class="form-control " id="segundoApellidoCliente" name="segundoApellidoCliente" placeholder="Ingresa el segundo apellido">
								<label for="TelefonoCliente">Segundo Apellido</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="TelefonoCliente" name="TelefonoCliente" placeholder="Ingresa el teléfono">
								<label for="TelefonoCliente">Teléfono</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CelularCliente" name="CelularCliente" placeholder="Ingresa el celular del cliente">
								<label for="CelularCliente">Celular</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CorreoCliente" name="CorreoCliente" placeholder="Ingresa el correo electrónico del cliente">
								<label for="CorreoCliente">Correo electrónico</label>
							</div>
						</div>
						<!-- <div class="col-md-4">
							<div class="form-floating mb-3">
								<select class="form-select" name="SucursalCliente" id="SucursalCliente" >
									<option value="0">- Seleccione una opción -</option>
									#SucursalesCliente#
								</select>
								<label for="SucursalCliente">Sucursal</label>
							</div>
						</div> -->
						<div class="col-md-4 col-sm-12 mb-3 camposFisica">
							<div class="form-floating">
								<input type="date" class="form-control " id="FechaNacimientoCliente" name="FechaNacimientoCliente" placeholder="Ingresa la fecha de nacimiento del cliente">
								<label for="FechaNacimientoCliente">Fecha de nacimiento</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3 camposFisica">
							<div class="form-floating">
								<select class="form-select " id="SexoCliente" name="SexoCliente">
									<option value="" selected> - Seleccione una opción - </option>
									<option value="Masculino">Masculino</option>
									<option value="Femenino">Femenino</option>
								</select>
								<label for="SexoCliente">Sexo del cliente</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<select class="form-select" id="FacturarCliente" name="FacturarCliente">
									<option value="" selected> - Seleccione una opción - </option>
									<option value="1">Si</option>
									<option value="0">No</option>
								</select>
								<label for="FacturarCliente">Facturar ventas</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3 camposFisica">
							<div class="form-floating">
								<input type="text" class="form-control" id="INECliente" name="INECliente" placeholder="Ingresa código del INE">
								<label>INE</label>
							</div>
						</div>
						<hr>
						<b class="mb-3">Datos de contacto</b>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="contactoCliente" name="contactoCliente" placeholder="Ingresa el nombre del contacto">
								<label>Nombre del contacto</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="puestoContactoCliente" name="puestoContactoCliente" placeholder="Ingresa el puesto del contacto">
								<label>Puesto del contacto</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="correoContactoCliente" name="correoContactoCliente" placeholder="Ingresa el correo electrónico del contacto">
								<label>Correo electrónico del contacto</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="telefonoContactoCliente" name="telefonoContactoCliente" placeholder="Ingresa el telefono del contacto">
								<label>Teléfono del contacto</label>
							</div>
						</div>
						<hr>
						<b class="mb-3">Dirección fiscal</b>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CalleClienteGeneral" name="CalleClienteGeneral" placeholder="Ingresa la calle del cliente">
								<label for="CalleClienteGeneral">Calle</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="NoExteriorClienteGeneral" name="NoExteriorClienteGeneral" placeholder="Ingresa el número exterior">
								<label for="NoExteriorClienteGeneral">No. Exterior</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="NoInteriorClienteGeneral" name="NoInteriorClienteGeneral" placeholder="Ingresa el número interior">
								<label for="NoInteriorClienteGeneral">No. Interior</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CPClienteGeneral" name="CPClienteGeneral" placeholder="Ingresa el codigo postal del cliente">
								<label for="CPClienteGeneral">Codigo postal</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="ColoniaClienteGeneral" name="ColoniaClienteGeneral" placeholder="Ingresa la colonia del cliente">
								<label for="ColoniaClienteGeneral">Colonia</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CiudadClienteGeneral" name="CiudadClienteGeneral" placeholder="Ingresa la ciudad del cliente">
								<label for="CiudadClienteGeneral">Ciudad</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="EstadoClienteGeneral" name="EstadoClienteGeneral" placeholder="Ingresa el estado del cliente">
								<label for="EstadoClienteGeneral">Estado</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="PaisClienteGeneral" name="PaisClienteGeneral" placeholder="Ingresa el país del cliente" value="México">
								<label for="PaisClienteGeneral">País</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="LatitudClienteGeneral" name="LatitudClienteGeneral" placeholder="Latitud">
								<label for="LatitudClienteGeneral">Latitud</label>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="LongitudClienteGeneral" name="LongitudClienteGeneral" placeholder="Longitud">
								<label for="LongitudClienteGeneral">Longitud</label>
							</div>
						</div>
						<hr>
						<b class="mb-3">Datos de Facturación</b>   
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="RFCCliente" name="RFCCliente" placeholder="Ingresa el RFC del cliente">
								<label for="RFCCliente">RFC</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="razonCliente" name="razonCliente" placeholder="Nombre / Régimen Fiscal">
								<label for="RFCCliente">Nombre / Razón Social</label>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="regimenCliente" id="regimenCliente">
									<option value="">- Seleccione una opción -</option>
									<option value="601">601 - General de Ley Personas Morales</option>
									<option value="603">603 - Personas Morales con Fines no Lucrativos</option>
									<option value="605">605 - Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
									<option value="606">606 - Arrendamiento</option>
									<option value="607">607 - Régimen de Enajenación o Adquisición de Bienes</option>
									<option value="608">608 - Demás ingresos</option>
									<option value="610">610 - Residentes en el Extranjero sin Establecimiento Permanente en México</option>
									<option value="611">611 - Ingresos por Dividendos (socios y accionistas)</option>
									<option value="612">612 - Personas Físicas con Actividades Empresariales y Profesionales</option>
									<option value="614">614 - Ingresos por intereses</option>
									<option value="615">615 - Régimen de los ingresos por obtención de premios</option>
									<option value="616">616 - Sin obligaciones fiscales</option>
									<option value="620">620 - Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
									<option value="621">621 - Incorporación Fiscal</option>
									<option value="622">622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
									<option value="623">623 - Opcional para Grupos de Sociedades</option>
									<option value="624">624 - Coordinados</option>
									<option value="625">625 - Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas</option>
									<option value="626">626 - Régimen Simplificado de Confianza</option>
								</select>
								<label>Régimen Fiscal</label>
							</div>
						</div>
						<hr>
						<b class="mb-3">Datos bancarios</b>   
						<br>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="TitularBancoCliente" name="TitularBancoCliente" placeholder="Ingresa el nombre del titular">
								<label for="TitularBancoCliente">Titular</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="BancoCliente" name="BancoCliente" placeholder="Ingresa el nombre del banco">
								<label for="BancoCliente">Banco</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CuentaBancoCliente" name="CuentaBancoCliente" placeholder="Ingresa el número de cuenta o clabe">
								<label for="CuentaBancoCliente">No. Cuenta / CLABE</label>
							</div>
						</div>
						<hr>
						<div class="row mb-3">
							<div class="col-md-6 col-sm-12 text-start">
								<b class="mb-3">Sucursales</b>
							</div>
						</div>



						<div class="col-md-12 col-sm-12">
					      	<div class="table-responsive">
					       		<table class="table table table-hover table-striped table-bordered text-center" id="tablaSucursalesCliente" width="100%" style="font-size: 12px;">
							        <thead>
							          <th>Nombre</th>
							        	<th>Acciones</th>
							        </thead>
							        <tbody id="verSucursalesCliente">

							        </tbody>
							        <tfoot>
							        	<tr>
							        		<td>
							        			<!-- <select form="formProveedoresProd" class="form-select" name="proveedorProducto" id="proveedorProducto" required>
				                      				<option value="">--Seleccione una opción--</option>  
				                      				#proveedores# 
			                    				</select> -->	
			                    				<div class="form-floating mb-3">
													<select form="formSucursalesCliente" class="form-select" name="SucursalCliente" id="SucursalCliente" required>
														<option value="">- Seleccione una opción -</option>
														#SucursalesCliente#
													</select>
													<label for="SucursalCliente">Sucursal</label>
												</div>
					                		</td>
					                		<td>
					                			<button  type="button" class="btn btn-sm btn-success" id="bAgregarSucursal"><i class="fas fa-plus"></i></button>
					                		</td>
							        	</tr>
							        </tfoot>
							    </table>
					      	</div>
					    </div>


						<div class="row mb-3">
							<div class="col-md-6 col-sm-12 text-start">
								<b class="mb-3">Direcciones</b>
							</div>
							<div class="col-md-6 col-sm-12 text-end">
								<button type="button" class="btn btn-success" id="AgregarDireccionCliente">Agregar dirección <i class="fas fa-plus"></i></button>
							</div>
						</div>
						<div class="col-md-12 col-sm-12">
							<div class="table-responsive">
								<table class="table table table-hover table-striped table-bordered text-center" id="TablaUbicacionClientes" width="100%" style="font-size: 12px;">
									<thead>
										<th style="width: 30%;">Domicilio</th>
										<th style="width: 30%;">Ubicacion</th>
										<th style="width: 30%;">Contacto</th>
										<th style="width: 10%;">Acciones</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<hr>

						<div class="row mb-3">
							<div class="col-md-6 col-sm-12 text-start">
								<b class="mb-3">Rutas</b>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating mb-3">
									<select class="form-select" name="rutasCliente" id="rutasCliente">
										<option value="">- Seleccione una opción -</option>
										#RutasCliente#
									</select>
									<label>Ruta</label>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="number" class="form-control" id="ordenRuta" name="ordenRuta" placeholder="Orden de la Ruta">
									<label for="ordenRuta">Orden</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="GuardarCliente" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="ModalDetallesDireccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Datos de la dirección</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12">
						Calle: <span style="font-weight: bold;" id="DatosCalle"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						No. Exterior: <span style="font-weight: bold;" id="DatosExt"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						No. Interior: <span style="font-weight: bold;" id="DatosInt"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						CP: <span style="font-weight: bold;" id="DatosCP"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						Colonia: <span style="font-weight: bold;" id="DatosColonia"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						Ciudad: <span style="font-weight: bold;" id="DatosCiudad"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						Estado: <span style="font-weight: bold;" id="DatosEstado"></span>
					</div>
					<div class="col-md-12 col-sm-12 mb-2">
						País: <span style="font-weight: bold;" id="DatosPais"></span>
					</div>
					<div class="col-md-12 col-sm-12 mb-2">
						Referencia visual / Detalles: <span style="font-weight: bold;" id="DatosReferencia"></span>
					</div>
					<div class="col-md-12 col-sm-12 mb-2">
						Latitud: <span style="font-weight: bold;" id="DatosLatitud"></span>
					</div>
					<div class="col-md-12 col-sm-12 mb-2">
						Longitud: <span style="font-weight: bold;" id="DatosLongitud"></span>
					</div>
					<div class="col-md-12 col-sm-12 mb-2">
						Entre que calles se encuentra: <span style="font-weight: bold;" id="DatosEntreCalles"></span>
					</div>
					<hr>
					<h6>Datos del contacto</h6>
					<div class="col-md-12 col-sm-12 mt-2">
						Nombre del contacto: <span style="font-weight: bold;" id="DatosNombre"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						Puesto: <span style="font-weight: bold;" id="DatosPuesto"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						Correo electrónico: <span style="font-weight: bold;" id="DatosCorreo"></span>
					</div>
					<div class="col-md-12 col-sm-12">
						Teléfono: <span style="font-weight: bold;" id="DatosTelefono"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			</div>
		</div>
	</div>
</div>