<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
					<li class="breadcrumb-item" aria-current="page">Configuración</li>
					<li class="breadcrumb-item active" aria-current="page">General</li>
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
			<div class="row col-sm-12">
				<div class="col-sm-12">
					<form id="formDatosGeneral">
					<div class="row">
							<div class="col-sm-12">
								<h5>Datos generales del ticket</h5>
							</div>
						</div>
						<div class="row">
							<div class="offset-md-4 col-md-4 col-sm-12 text-center">
								<div class='rounded mx-auto d-block'>
									<div class='rounded mx-auto d-block' id="verImagenGeneral" style="width: 250px; height: 170px; cursor:pointer; border-radius:4px; overflow:hidden;"><img src="vistas/assets/archivos/fotosProductos/default.jpg" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;"></div>	
								</div>
								<br>
								<input class="form-control" type="file" id="ImagenGeneral" name="ImagenGeneral" accept="image/png, image/jpeg, image/gif">
								<br>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12 mb-3">
								<div class="form-floating">
									<select class="form-select" name="PonerImgGeneral" id="PonerImgGeneral" >
										<option value="">- Seleccione una opción -</option>
										<option value="1">Sí</option>
										<option value="0">No</option>
									</select>
									<label for="PonerImgGeneral">Poner imagen en el ticket</label>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 mb-3">
								<div class="form-floating">
									<select class="form-select" name="PonerTodosGeneral" id="PonerTodosGeneral" >
										<option value="">- Seleccione una opción -</option>
										<option value="1">Sí</option>
										<option value="0">No</option>
									</select>
									<label for="PonerTodosGeneral">Poner imagen en TODOS los tickets</label>
								</div>	
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="MonedaGeneral" name="MonedaGeneral" placeholder="Ingresa la moneda utilizada">
									<label for="MonedaGeneral">Moneda</label>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="SimboloGeneral" name="SimboloGeneral" placeholder="Ingresa el simbolo de la moneda">
									<label for="SimboloGeneral">Simbolo</label>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="OrigenGeneral" name="OrigenGeneral" placeholder="Ingresa el Origen de la moneda">
									<label for="OrigenGeneral">Origen</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h5>Datos generales de la empresa</h5>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="CalleGeneral" name="CalleGeneral" placeholder="Ingresa la calle de la empresa">
									<label for="CalleGeneral">Calle</label>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="NoExtGeneral" name="NoExtGeneral" placeholder="Ingresa el número esterior">
									<label for="NoExtGeneral">No. Exterior</label>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="NoIntGeneral" name="NoIntGeneral" placeholder="Ingresa el número interior">
									<label for="NoIntGeneral">No. Interior</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="ColoniaGeneral" name="ColoniaGeneral" placeholder="Ingresa la colonia de la empresa">
									<label for="ColoniaGeneral">Colonia</label>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="CPGeneral" name="CPGeneral" placeholder="Ingresa el código postal">
									<label for="CPGeneral">Código Postal</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="CiudadGeneral" name="CiudadGeneral" placeholder="Ingresa la ciudad">
									<label for="CiudadGeneral">Ciudad</label>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="EstadoGeneral" name="EstadoGeneral" placeholder="Ingresa el estado">
									<label for="EstadoGeneral">Estado</label>
								</div>
							</div>
							<div class="col-md-4 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="PaisGeneral" name="PaisGeneral" placeholder="Ingresa el pais">
									<label for="PaisGeneral">Pais</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="phone" class="form-control" id="TelefonoGeneral" name="TelefonoGeneral" placeholder="Ingresa el telefono de la empresa">
									<label for="TelefonoGeneral">Teléfono</label>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="email" class="form-control" id="EmailGeneral" name="EmailGeneral" placeholder="Ingresa correo de la empresa">
									<label for="EmailGeneral">Correo</label>
								</div>
							</div>
						</div>
						<div class="col-12 text-center">
							<button type="button" class="btn btn-primary" id="GuardarGeneral" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>