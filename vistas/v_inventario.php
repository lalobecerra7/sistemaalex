<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
					<li class="breadcrumb-item active" aria-current="page">Inventario</li>
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
					<button type="button" class="btn btn-success" id="botonNuevoInventario" data-bs-toggle="modal" data-bs-target="#ModalInventario"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarInventario').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row mb-5">
					<div class="col-12">
						<table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaInventario" width="100%" style="font-size: 12px;">
							<thead>
								<th style="width: 5%;" orden="No">Foto</th>
								<th style="width: 30%;">Descripción</th>
								<th>Cantidad</th>
								<th>Costo</th>
								<th>Costo total</th>
								<th>Precio</th>
								<th>Precio total</th>
								<th orden="No">Merma</th>
								<th orden="No">Distribución</th>
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


<div class="modal fade" id="ModalInventario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalInventario"></span> inventario</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="FormInventario">
				<div class="modal-body">
					<div class="row">
						<div class="offset-md-4 col-md-4 col-sm-12 text-center">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" id="verfotoInventario" style="width: 250px; height: 170px;cursor:pointer;border-radius:4px;border:2px solid grey;"><img src="vistas/assets/archivos/fotosInventario/default.jpg"></div>
							</div>
							<br>
							<input class="form-control" type="file" id="FotoInventario" name="FotoInventario">
						</div>
					</div>
					<br>
					<div class="row">
						<b class="mb-3">Datos del inventario</b>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="NombreInventario" name="NombreInventario" placeholder="Ingresa el nombre del inventario">
								<label for="NombreInventario">Nombre del inventario</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="TelefonoInventario" name="TelefonoInventario" placeholder="Ingresa el teléfono del inventario">
								<label for="TelefonoInventario">Teléfono del inventario</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CelularInventario" name="CelularInventario" placeholder="Ingresa el celular del inventario">
								<label for="CelularInventario">Celular del inventario</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CorreoInventario" name="CorreoInventario" placeholder="Ingresa el correo electrónico del inventario">
								<label for="CorreoInventario">Correo electrónico del inventario</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="date" class="form-control" id="FechaNacimientoInventario" name="FechaNacimientoInventario" placeholder="Ingresa la fecha de nacimiento del inventario">
								<label for="FechaNacimientoInventario">Fecha de nacimiento</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<select class="form-select" id="SexoInventario" name="SexoInventario">
									<option value="" selected> - Seleccione una opción - </option>
									<option value="Masculino">Masculino</option>
									<option value="Femenino">Femenino</option>
								</select>
								<label for="SexoInventario">Sexo del inventario</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="RFCInventario" name="RFCInventario" placeholder="Ingresa el RFC del inventario">
								<label for="RFCInventario">RFC</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="NombreEmpresaInventario" name="NombreEmpresaInventario" placeholder="Ingresa el nombre de la empresa del inventario">
								<label for="NombreEmpresaInventario">Nombre de la empresa</label>
							</div>
						</div>
						<hr>
						<b class="mb-3">Datos de ubicación</b>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="DireccionInventario" name="DireccionInventario" placeholder="Ingresa la dirección del inventario">
								<label for="DireccionInventario">Dirección del inventario</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CPInventario" name="CPInventario" placeholder="Ingresa el codigo postal del inventario">
								<label for="CPInventario">Codigo postal</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="ColoniaInventario" name="ColoniaInventario" placeholder="Ingresa la colonia del inventario">
								<label for="ColoniaInventario">Colonia</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CiudadInventario" name="CiudadInventario" placeholder="Ingresa la ciudad del inventario">
								<label for="CiudadInventario">Ciudad</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="EstadoInventario" name="EstadoInventario" placeholder="Ingresa el estado del inventario">
								<label for="EstadoInventario">Estado</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="PaisInventario" name="PaisInventario" placeholder="Ingresa el país del inventario">
								<label for="PaisInventario">País</label>
							</div>
						</div>
						<hr>
						<b class="mb-3">Datos adicionales</b>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<select class="form-select" id="TipoDescuentoInventario" name="TipoDescuentoInventario">
									<option value="" selected> - Seleccione una opción - </option>
									<option value="Porcentaje">Descuento por porcentaje</option>
									<option value="Cantidad">Descuento por cantidad</option>
								</select>
								<label for="TipoDescuentoInventario">Tipo de descuento</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="number" disabled="true" min="0" step="any" class="form-control" id="DescuentoInventario" name="DescuentoInventario" placeholder="Ingresa el valor del descuento">
								<label for="DescuentoInventario"><span id="TituloTipoDescuento"></span></label>
							</div>
						</div>
						<div class="col-md-4 text-center col-sm-12 mb-3">
							<h6>Descuento</h6>
							<h4 id="LabelDescuentoInventario"><b class="cantidad">0</b></h4>
						</div>
						<hr>
						<b class="mb-3">Datos bancarios</b>
						<br>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="TitularBancoInventario" name="TitularBancoInventario" placeholder="Ingresa el nombre del titular">
								<label for="TitularBancoInventario">Titular</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="BancoInventario" name="BancoInventario" placeholder="Ingresa el nombre del banco">
								<label for="BancoInventario">Banco</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="CuentaBancoInventario" name="CuentaBancoInventario" placeholder="Ingresa el número de cuenta o clabe">
								<label for="CuentaBancoInventario">No. Cuenta / CLABE</label>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="GuardarInventario" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
				</div>
			</form>
		</div>
	</div>
</div>