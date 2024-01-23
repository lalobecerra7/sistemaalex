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
					<button type="button" class="btn btn-success" id="bNuevoChofer"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarChoferes').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-bordered text-center myDataTable" id="tablaChoferes" width="100%" style="font-size: 12px;">
		          <thead>
		          	<th>Fecha</th>
		            <th>Nombre</th>
		            <th>Primer Apellido</th>
		            <th>Segundo Apellido</th>
		            <th>Vehiculo</th>
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
<div class="modal fade" id="modalChofer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" style="font-weight: bold;">Chofer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formChoferes">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-12 mb-3">
		        	<div class="form-floating">
		              <input type="text" class="form-control" id="nombreChofer" name="nombreChofer" placeholder="Ingresa la marca">
		              <label>Nombre</label>
		          </div>
		        </div>
	       		<div class="col-12 mb-3">
		        	<div class="form-floating">
		              <input type="text" class="form-control" id="primerApellidoChofer" name="primerApellidoChofer" placeholder="Ingresa el modelo">
		              <label>Primer Apellido</label>
		          </div>
		        </div>
		        <div class="col-12 mb-3">
		        	<div class="form-floating">
		              <input type="text" class="form-control" id="segundoApellidoChofer" name="segundoApellidoChofer" placeholder="Ingresa la matrícula">
		              <label>Segundo Apellido</label>
		          </div>
		        </div>
		        <div class="col-12 mb-3">
					<div class="form-floating mb-3">
						<select class="form-select" name="vehiculosChofer" id="vehiculosChofer">
							<option value="">- Seleccione una opción -</option>
							#VehiculosChofer#
						</select>
						<label>Vehiculo</label>
					</div>
				</div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="bGuardarChofer"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
			<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>

