<div class="modal fade" id="ModalCategorias" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalCategorias"></span> categoria / familia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormCategorias">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreCategoria" name="NombreCategoria" placeholder="Ingresa el nombre de la categoria o familia">
		                <label for="NombreCategoria">Nombre de la categoria o familia</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="DescripcionCategoria" name="DescripcionCategoria" placeholder="Ingresa la descripción de la categoria o familia">
		                <label for="DescripcionCategoria">Descripción de la categoria o familia</label>
		            </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarCategoria" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
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
			    <li class="breadcrumb-item" aria-current="page">Configuración</li>
			    <li class="breadcrumb-item active" aria-current="page">Tickets</li>
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
		    <div class="col-sm-5">
			<div class="row">
				<div class="col-sm-12">
					<h2>Datos del Ticket</h2>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-12">
					<form id="formTickets">
						<input type="hidden" id="nomPagina" value="v_ticket">
						<input type="hidden" name="metodo" value="modificar"> 
						<input type="hidden" name="accion" value="ticket"> 
						<div class="row">
							<div class="col-md-10 col-sm-12 mb-3">
								<div class="form-floating">
									<input type="text" class="form-control" id="NombreCategoria" name="NombreCategoria" placeholder="Ingresa el nombre de la categoria o familia">
									<label for="NombreCategoria">Nombre de la categoria o familia</label>
								</div>
							</div>
							<div class="col-md-2 col-sm-12 mb-3">
								<input type="checkbox" id='check' name='check' checked>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
