<div class="modal fade" id="ModalAreas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalArea"></span> área</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormAreas">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreArea" name="NombreArea" placeholder="Ingresa el nombre del área">
		                <label for="NombreArea">Nombre del área</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NivelArea" name="NivelArea" placeholder="Ingresa el nombre del nivel">
		                <label for="NivelArea">Nivel</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="DescripcionArea" name="DescripcionArea" placeholder="Ingresa la descripción del área, productos, proveedores">
		                <label for="DescripcionArea">Descripción del área (Proveedores, productos)</label>
		            </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarArea" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 


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
					<!-- <button type="button" class="btn btn-success" id="botonNuevaArea" onclick="$('#ModalAreas').appendTo('body').modal('show')"><i class="fa fa-file"></i> Nueva</button> -->
					<button type="button" class="btn btn-success" id="botonNuevaArea" data-bs-toggle="modal" data-bs-target="#ModalAreas"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_areas" titulo="Áreas"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaAreas" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 25%;">Nombre</th>
		            <th style="width: 25%;">Nivel</th>
		            <th style="width: 25%;">Descripción</th>
		            <th style="width: 25%;" orden="No">Acciones</th>
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
