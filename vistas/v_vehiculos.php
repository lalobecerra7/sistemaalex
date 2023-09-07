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
					<button type="button" class="btn btn-success" id="bNuevoVehiculo"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarRutas').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-bordered text-center myDataTable" id="tablaVehiculos" width="100%" style="font-size: 12px;">
		          <thead>
		          	<th>Fecha</th>
		            <th>Marca</th>
		            <th>Modelo</th>
		            <th>Matrícula</th>
		            <th>Descripción</th>
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
<div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" style="font-weight: bold;">Vehiculo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formVehiculos">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-12 mb-3">
		        	<div class="form-floating">
		              <input type="text" class="form-control" id="marcaVehiculo" name="marcaVehiculo" placeholder="Ingresa la marca">
		              <label>Marca</label>
		          </div>
		        </div>
	       		<div class="col-12 mb-3">
		        	<div class="form-floating">
		              <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" placeholder="Ingresa el modelo">
		              <label>Modelo</label>
		          </div>
		        </div>
		        <div class="col-12 mb-3">
		        	<div class="form-floating">
		              <input type="text" class="form-control" id="matriculaVehiculo" name="matriculaVehiculo" placeholder="Ingresa la matrícula">
		              <label>Matrícula</label>
		          </div>
		        </div>
		        <div class="col-12 mb-3">
		        	<div class="form-floating">
		              <input type="text" class="form-control" id="descripcionVehiculo" name="descripcionVehiculo" placeholder="Ingresala la descripción">
		              <label>Descripción</label>
		          </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="bGuardarVehiculo"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>

