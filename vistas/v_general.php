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
				<div class="row">
					<div class="col-sm-12">
						<h5>Datos generales de la empresa</h5>
					</div>
				</div>
				<div class="col-sm-12">
					<form id="formGeneral">
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
									<input type="text" class="form-control" id="NoIntGeneral" name="NoIntGeneral" placeholder="Ingresa el número interior de la empresa">
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
						<div class="col-12 text-center">
							<button type="submit" class="btn btn-primary" id="bGuardarSucu" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Aplicar</strong></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>