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
					<button type="button" class="btn btn-success" id="bNuevoCorteRuta"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarCortesRuta').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row mb-5">
					<div class="col-12">
						<table class="table table table-hover table-bordered text-center myDataTable" id="tablaCortesRuta" width="100%" style="font-size: 12px;">
							<thead>
								<th>Fecha</th>
								<th>Ruta</th>
								<th>Fecha Inicio</th>
								<th>Fecha Fin</th>
								<th>Total</th>
								<th orden="No">Concentrado</th>
								<th orden="No">Cubetas</th>
								<th>Estatus</th>
								<th orden="No">Acciones</th>
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

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalCorteRuta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Corte De Ruta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body row">
				<form id="formCorteDeRuta" class="col-12">
					<div class="row">
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="rutasCorte" id="rutasCorte">
									<option value="">- Seleccione una opción -</option>
									#RutasCorte#
								</select>
								<label>Ruta corte</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<input type="date" class="form-control " id="FechaInicioCorte" name="FechaInicioCorte" placeholder="Fecha Inicio">
								<label>Fecha inicio</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<input type="date" class="form-control " id="FechaFinCorte" name="FechaFinCorte" placeholder="Fecha Fin">
								<label>Fecha Fin</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="selectChofer" id="selectChofer">
									<option value="">- Seleccione una opción -</option>
									#SelectChofer#
								</select>
								<label>Chofer</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="selectVehiculo" id="selectVehiculo">
									<option value="">- Seleccione una opción -</option>
									#SelectVehiculo#
								</select>
								<label>Vehiculo</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="selectSucursal" id="selectSucursal" aria-describedby="selectSucursal-error" aria-invalid="false">
									<option value="">- Seleccione una opción -</option>
									#selectSucursal#
								</select>
								<label>Sucursal</label>
							</div>
						</div>
					</div>
				</form>
				<br>
				<hr>
				<br>
				<div class="col-12 mb-3">
					<div class="row oculto verExtras">
						<div class="col-12 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="bAgregarVentaRuta">Agregar <i class="fas fa-plus"></i></button>
						</div>
					</div>
					<div class="row">
						<div class="col-12 table-responsive">
							<table class="table table table-hover table-bordered text-center myDataTable" id="tablaVentasRuta" width="100%" style="font-size: 12px;">
								<thead>
									<th>Orden Ruta</th>
									<th>Folio</th>
									<th>Cliente</th>
									<th>Domicilio</th>
									<th>Total</th>
									<th>Fecha Registro</th>
									<th>Estatus</th>
									<th orden="No">Acciones</th>
								</thead>
								<tbody>

								</tbody>
								<tfoot>

								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<div class="col-12">
					<hr>
					<div class="row mx-2 justify-content-between verBalancesRuta">
						<div class="col-5">
							<div class="row text-start">
								<h5><b>Balance de corte</b></h5>
							</div>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Total</p>
									<p class="p-0 m-0 fs-5 dinero" id="total_corte_bruto">0</p>
								</div>
							</div>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Gastos</p>
									<p class="p-0 m-0 fs-5 dinero" id="total_gastos_corte">0</p>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Total neto</p>
									<p class="p-0 m-0 fs-5 dinero" id="total_neto_corte">0</p>
								</div>
							</div>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Dinero recaudado</p>
									<div id="contenedor_recaudado">
										<input type="number" class="form-control" step="any" value="0" id="montoCorte" name="montoCorte">
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="d-flex gap-3 justify-content-center">
									<p class="p-0 m-0 fs-5">Balance:</p>
									<p class="p-0 m-0 fs-5 col-2 dinero" id="balance_final">0</p>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="row text-end">
								<h5><b>Gastos del corte</b></h5>
							</div>
							<div class="row align-items-end">
								<form id="gastosFormulario" class="row align-items-end">
									<div class="col-md-6 col-sm-12 mb-3">
										<label>Descripcion</label>
										<input type="text" class="form-control" id="gastoDescripcion" placeholder="Descripcion" required>
									</div>
									<div class="col-md-3 col-sm-12 mb-3">
										<label>Monto</label>
										<input type="number" step="any" class="form-control" id="gastoCoste" required>
									</div>
									<div class="col-3 mb-3 justify-content-center align-items-center">
										<button type="submit" class="btn btn-sm btn-primary py-2" id="añadirGastoACorte">
											<i class="fa-solid fa-plus"></i> Añadir Gasto
										</button>
									</div>
								</form>
							</div>
							<div class="row mb-3">
								<div class="col-12 table-responsive">
									<table class="table table table-hover table-bordered text-center" id="tablaCostesRuta" width="100%" style="font-size: 12px;">
										<thead>
											<th>Descripcion</th>
											<th>Monto</th>
											<th orden="No">Acciones</th>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row verBalancesRuta">
						<hr>
						<div class="col-12 d-flex justify-content-center gap-2">
							<button class="btn btn-info" type="button" id="cerrarCorteRuta"><i class="fa-solid fa-file-export" style="margin-right: 10px;"></i> <strong>Cerrar Corte</strong></button>
							<!-- <button class="btn btn-success d-none" type="button" id="reabrirCorteRuta"><i class="fa-solid fa-file-import" style="margin-right: 10px;"></i> <strong>Reabrir Corte</strong></button>-->
							<!--#botonSubirArchivo#
							<a class="d-none" data-fancybox id="downloadTheFile">
								<button class="btn btn-danger" type="button"><i class="fa-solid fa-file-contract" style="margin-right: 10px;"></i> <strong>Ver archivo</strong></button>
							</a>
							<form style="display: none;" id="imageUploadForm">
								<input type="file" name="imageInput" id="imageInput" accept="image/*,application/pdf">
							</form>-->
						</div>
					</div>
				</div>
				<div class="col-12 oculto verExtras">
					<div class="row">
						<div class="col-12 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="bAgregarClienteExtra">Agregar Cliente <i class="fas fa-plus"></i></button>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-12 table-responsive">
							<table class="table table-bordered table-hover table-striped text-center" width="100%" style="font-size: 12px">
								<thead>
									<tr>
										<th>Cliente Extra</th>
										<th>Orden</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody id="verClientesExtras">

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-12 oculto verExtras">
					<div class="row">
						<div class="col-12 text-end">
							<button type="button" class="btn btn-primary btn-sm" id="bQuitarClienteRuta">Quitar Cliente <i class="fas fa-minus"></i></button>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-12 table-responsive">
							<table class="table table-bordered table-hover table-striped text-center" width="100%" style="font-size: 12px">
								<thead>
									<tr>
										<th>Cliente</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody id="verClientesQuitarRuta">

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" id="cerrarModalCorteRuta"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
				<button type="button" class="btn btn-primary" id="bGuardarCorte"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
			</div>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVerificar" tabindex="-1">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Verificar corte de ruta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<form id="formVerificar">
							<div class="row mb-3 sm-3">
								<div class="col-9">
									<input type="text" class="form-control" name="codigoProducto" id="codigoProducto" type="text" placeholder="Codigo...">
								</div>
								<div class="col-3">
									<button type="submit" class="btn btn-outline-dark"><i class="fa-solid fa-magnifying-glass"></i></button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12" id="verDatosVentaCorte">

					</div>
				</div>
				<br>
				<div class="row">
					<div class="mb-3 col-12 table-responsive">
						<table class="table text-center" width="100%" style="font-size: 17px">
							<thead>
								<tr>
									<th>Codigo</th>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Verificados</th>
									<th>Estatus</th>
								</tr>
							</thead>
							<tbody id="tbodyVerificar">

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			</div>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalCubeta" tabindex="-1">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Verificar cubetas del corte</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<br>
				<div class="row">
					<div class="col-12">
						<form id="formVerificarCubeta">
							<div class="row mb-3 sm-3">
								<div class="col-7">
									<input type="text" class="form-control" name="codigoProductoCubeta" id="codigoProductoCubeta" type="text" placeholder="Codigo...">
								</div>
								<div class="col-3">
									<button type="submit" class="btn btn-outline-dark"><i class="fa-solid fa-magnifying-glass"></i></button>
								</div>
								<div class="col-2">
									<button type="button" class="btn btn-outline-dark" id="bOrdenCubetas"><i class="fas fa-up-long"></i></button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="mb-3 col-12" id="tbodyCubetas">

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			</div>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalExtra" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cliente extra</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="formExtra">
				<div class="modal-body">
					<div class="input-group mb-3">
						<input type="text" class="form-control" name="clienteExtra" id="clienteExtra" placeholder="Cliente" readonly>
						<button type="button" class="btn btn-outline-secondary" id="bBuscarClienteExtra"><i class="fas fa-search"></i></button>
					</div>
					<div class="form-floating mb-3">
						<input type="number" class="form-control" name="ordenExtra" id="ordenExtra" placeholder="name@example.com">
						<label for="ordenExtra">Orden</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Agregar <i class="fas fa-plus"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalClientesExtra" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Clientes</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 table-responsive">
						<table class="table table table-hover table-bordered text-center myDataTable" id="tablaClientesExtra" width="100%" style="font-size: 12px;">
							<thead>
								<th>Nombre</th>
								<th>Ruta</th>
								<th>Orden</th>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalConcentradoRuta" tabindex="-1">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Concentrado</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 text-end">
						<a class="btn btn-sm btn-info" id="bImprimirVerificacion" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="mb-3 col-12 table-responsive">
						<table class="table text-center" width="100%" style="font-size: 17px">
							<thead>
								<tr>
									<th>Codigo</th>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Verificados</th>
									<th>Estatus</th>
								</tr>
							</thead>
							<tbody id="tbodyConcentradoRuta">

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			</div>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVentasExtra" tabindex="-1">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Ventas</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 table-responsive">
						<table class="table table table-hover table-bordered text-center myDataTable" id="tablaVentasExtra" width="100%" style="font-size: 12px;">
							<thead>
								<th>Datos</th>
								<th>Estatus</th>
								<th>Cliente</th>
								<th>Total</th>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!-- //////////////Modal//////////////////////-->
<div class="modal text-left" id="modalCartaPorte" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title white">Carta Porte</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid p-0 m-0">

					<!-- ENCABEZADO -->
					<div class="d-flex justify-content-between align-items-center mb-3">
						<div>
							<div class="brand fs-4"><i class="fa-solid fa-file-invoice"></i> Facturación — Traslado con Carta Porte</div>
						</div>
					</div>

					<form id="factForm">

						<!-- ======== SECCIÓN COMPLETA ARRIBA ======== -->
						<div class="row g-3 mb-3">

							<!-- EMISOR -->
							<div class="col-md-6">
								<div class="card" style="box-shadow: none; border: 1px solid #EEE;">
									<div class="card-body">
										<h3>Emisor</h3>
										<div class="row">
											<div class="col-md-6">
												<label class="form-label small">Nombre</label>
												<h5>#nombreEmisor#</h5>
											</div>
											<div class="col-md-6">
												<label class="form-label small">RFC</label>
												<h5>#rfcEmisor#</h5>
											</div>
											<div class="col-md-6">
												<label class="form-label small">Régimen</label>
												<h5>#regimenEmisor#</h5>
											</div>
											<div class="col-md-6">
												<label class="form-label small">Código Postal</label>
												<h5>#cpEmisor#</h5>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- RECEPTOR -->
							<div class="col-md-6">
								<div class="card" style="box-shadow: none; border: 1px solid #EEE;">
									<div class="card-body">
										<h3>Receptor</h3>
										<div class="row">
											<div class="col-md-6">
												<label class="form-label small">RFC</label>
												<h5>#rfcEmisor#</h5>
											</div>
											<div class="col-md-6">
												<label class="form-label small">Nombre</label>
												<h5>#nombreEmisor#</h5>
											</div>
											<div class="col-md-6">
												<label class="form-label small">Uso CFDI</label>
												<h5>S01 - Sin efectos fiscales</h5>
											</div>
											<div class="col-md-6">
												<label class="form-label small">Régimen</label>
												<h5>616 - Sin obligaciones fiscales</h5>
											</div>
											<div class="col-md-6">
												<label class="form-label small">Código Postal</label>
												<h5>#cpEmisor#</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- ======== ORIGEN ======== -->
						<div class="card mb-3" style="box-shadow: none; border: 1px solid #EEE;">
							<div class="card-header">Origen (Sucursal)</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<label class="form-label small">Nombre</label>
										<h5 id="origenNombre">#origenNombre#</h5>
									</div>
									<div class="col-md-4">
										<label class="form-label small">RFC</label>
										<h5 id="origenRFC">#origenRFC#</h5>
									</div>
									<div class="col-md-4">
										<label class="form-label small">Código Postal</label>
										<h5 id="origenCP">#origenCP#</h5>
									</div>
									<div class="col-md-12">
										<label class="form-label small">Domicilio</label>
										<h5 id="origenDomicilio">#origenDomicilio#</h5>
									</div>
									<div class="col-md-4">
										<div class="form-floating">
											<input type="datetime-local" class="form-control" id="origenFechaSalida">
											<label>Fecha y hora de salida</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 mt-3">
										<hr>
										<label class="form-label small">Claves SAT del domicilio (requeridas para Carta Porte)</label>
										<div class="row g-2">
											<div class="col-md-4">
												<div class="input-group">
													<span class="input-group-text">Clave Estado</span>
													<input type="text" class="form-control" id="origenClaveEstado">
													<button type="button" class="btn btn-outline-primary bBuscarClaveEstadoOrigen" title="Buscar Clave Estado"><i class="fas fa-search"></i></button>
												</div>
											</div>
											<div class="col-md-4">
												<div class="input-group">
													<span class="input-group-text">Clave Municipio</span>
													<input type="text" class="form-control" id="origenClaveMunicipio">
													<button type="button" class="btn btn-outline-primary bBuscarClaveMunicipioOrigen" title="Buscar Clave Municipio"><i class="fas fa-search"></i></button>
												</div>
											</div>
											<div class="col-md-4">
												<div class="input-group">
													<span class="input-group-text">Clave Colonia</span>
													<input type="text" class="form-control" id="origenClaveColonia">
													<button type="button" class="btn btn-outline-primary bBuscarClaveColoniaOrigen" title="Buscar Clave Colonia"><i class="fas fa-search"></i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- ======== VEHÍCULO / AUTOTRANSPORTE ======== -->
						<div class="card mb-3" style="box-shadow: none; border: 1px solid #EEE;">
							<div class="card-header">Vehículo</div>
							<div class="card-body">
								<div class="row g-3">
									<div class="col-md-3">
										<div class="form-floating">
											<input type="text" class="form-control" id="vehiculoPlaca">
											<label>Placa</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-floating">
											<select class="form-select" id="vehiculoConfig">
												<option value="VL">VL - Vehículo ligero de carga</option>
												<option value="C2">C2 - Camión Unitario (2 ejes)</option>
												<option value="C3">C3 - Camión Unitario (3 ejes)</option>
											</select>
											<label>Configuración</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-floating">
											<input type="number" class="form-control" id="vehiculoAnio">
											<label>Año</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-floating">
											<input type="number" step="0.01" class="form-control" id="vehiculoPesoBruto">
											<label>Peso Bruto (Kg)</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-floating">
											<input type="text" class="form-control" id="vehiculoSICT">
											<label>No. Permiso SICT</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-floating">
											<input type="text" class="form-control" id="vehiculoAseguradora">
											<label>Aseguradora (Resp. Civil)</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-floating">
											<input type="text" class="form-control" id="vehiculoPoliza">
											<label>No. Póliza</label>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="vehiculoActualizar" checked>
											<label class="form-check-label" for="vehiculoActualizar">Actualizar estos datos en el vehículo</label>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- ======== CHOFER / FIGURA DE TRANSPORTE ======== -->
						<div class="card mb-3" style="box-shadow: none; border: 1px solid #EEE;">
							<div class="card-header">Operador (Chofer)</div>
							<div class="card-body">
								<div class="row g-3">
									<div class="col-md-4">
										<div class="form-floating">
											<input type="text" class="form-control" id="choferNombre">
											<label>Nombre</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-floating">
											<input type="text" class="form-control" id="choferRFC">
											<label>RFC</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-floating">
											<input type="text" class="form-control" id="choferLicencia">
											<label>No. Licencia</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-floating">
											<select class="form-select" id="choferTipo">
												<option value="01">01 - Operador</option>
												<option value="02">02 - Propietario</option>
												<option value="03">03 - Arrendador</option>
												<option value="04">04 - Notificado</option>
											</select>
											<label>Tipo</label>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="choferActualizar" checked>
											<label class="form-check-label" for="choferActualizar">Actualizar estos datos en el chofer</label>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- ======== DESTINOS ======== -->
						<div class="card mb-3" style="box-shadow: none; border: 1px solid;">
							<div class="card-header">Destinos (Clientes de la ruta)</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-sm table-bordered align-middle" id="tablaDestinos" style="min-width: 2000px;">
										<thead class="table-light">
											<tr>
												<th>Cliente</th>
												<th>RFC</th>
												<th>Calle</th>
												<th>No. Ext</th>
												<th>No. Int</th>
												<th>Colonia</th>
												<th>C.P.</th>
												<th>Municipio</th>
												<th>Estado</th>
												<th>Distancia Recorrida (km)</th>
												<th>Fecha/Hora Llegada</th>
												<th>Actualizar domicilio del cliente</th>
											</tr>
										</thead>
										<tbody>
											<!-- Se llena por JS, una fila por cada IdOrigenDestino (Destino) -->
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="card mb-3" style="box-shadow: none; border: 1px solid #EEE;">
							<div class="card-header d-flex justify-content-between align-items-center">
								<div>Mercancías</div>
							</div>

							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-sm table-hover table-striped table-bordered text-center" id="tablaConceptos">
										<thead class="table-light">
											<tr>
												<th>Clave ProdServ</th>
												<th>Descripción</th>
												<th>Unidad</th>
												<th>Cantidad</th>
												<th>Peso Unitario (Kg)</th>
												<th>Peso Total (Kg)</th>
												<th>Destino</th>
											</tr>
										</thead>
										<tbody>
											<!-- Se llena por JS -->
										</tbody>
										<tfoot>
											<tr class="table-light">
												<td colspan="5" class="text-end"><b>Peso total de la mercancía (Kg):</b></td>
												<td id="pesoTotalMercancia"><b>0</b></td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>

					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
				<button type="button" class="btn btn-primary ml-1" id="bTimbrarCartaPorte">Generar y Timbrar <i class="fas fa-check"></i></button>
			</div>
		</div>
	</div>
</div>

<!-- //////////////Modal//////////////////////-->
<div class="modal text-left" id="modalBuscarClaveSAT" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title white" id="tituloBuscarClaveSAT">Buscar clave</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-sm table-hover table-bordered text-center myDataTable" id="tablaClavesSAT" style="cursor:pointer;">
						<thead class="table-light">
							<tr>
								<th>Descripción</th>
								<th>Clave</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>