<div class="">
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
					<button type="button" class="btn btn-success" id="botonNuevoRecibo" data-bs-toggle="modal" data-bs-target="#ModalRecibos"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_recibos" titulo="Recibos"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="tablaRecibos" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 10%;">Folio</th>
		            <th style="width: 10%;">Fecha</th>
		            <th style="width: 20%;">Monto</th>
		            <th style="width: 20%;">Concepto</th>
		            <th style="width: 20%;">Proveedor</th>
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

<!--//////////////////////////Modal////////////////////////////-->
<div class="modal fade" id="ModalRecibos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Recibos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormRecibos">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
               	<input type="date" class="form-control" id="FechaRecibo" name="FechaRecibo" placeholder="Ingresa la fecha del recibo">
                <label for="FechaRecibo">Fecha del recibo</label>
	            </div>
		        </div>
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
               	<input type="text" class="form-control" id="MontoRecibo" name="MontoRecibo" placeholder="Ingresa el monto del recibo">
                <label for="MontoRecibo">Monto</label>
	            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
               	<input type="text" class="form-control" id="DescripcionRecibo" name="DescripcionRecibo" placeholder="Ingresa la descripción del recibo">
                <label for="DescripcionRecibo">Concepto</label>
		          </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
               	<input type="text" class="form-control" id="NombreProveedorRecibo" name="NombreProveedorRecibo" placeholder="Ingresa la descripción del recibo">
                <label for="NombreProveedorRecibo">Nombre del proveedor</label>
		          </div>
		        </div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarRecibo" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 
