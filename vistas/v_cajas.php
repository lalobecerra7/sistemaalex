<div class="modal fade" id="ModalCajas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalCajas"></span> caja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormCajas">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreCaja" name="NombreCaja" placeholder="Ingresa el nombre de la caja">
		                <label for="NombreCaja">Nombre de la caja</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<select class="form-control" id="SucursalesCaja" name="SucursalesCaja" placeholder="Selecciona una caja">
		               	</select>
		                <label for="SucursalesCaja">Sucursal</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="DetallesCaja" name="DetallesCaja" placeholder="Ingresa los detalles de la caja">
		                <label for="DetallesCaja">Detalles</label>
		            </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarCaja" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
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
			    <li class="breadcrumb-item" aria-current="page">Productos a la venta</li>
			    <li class="breadcrumb-item active" aria-current="page">Cajas</li>
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
					<!-- <button type="button" class="btn btn-success" id="botonNuevaArea" onclick="$('#ModalAreas').appendTo('body').modal('show')"><i class="fa fa-file"></i> Nueva</button> -->
					<button type="button" class="btn btn-success" id="botonNuevaCaja" data-bs-toggle="modal" data-bs-target="#ModalCajas"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_cajas" titulo="Cajas"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaCajas" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 20%;">Caja</th>
		            <th style="width: 20%;" orden="No">Sucursal</th>
		            <th style="width: 20%;" orden="No">Detalles</th>
		            <th style="width: 20%;" orden="No">Estatus</th>
		            <th style="width: 20%;" orden="No">Usuario</th>
		            <th style="width: 20%;" orden="No">Acciones</th>
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
