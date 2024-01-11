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
					<button type="button" class="btn btn-success" id="botonNuevoToken" data-bs-toggle="modal" data-bs-target="#ModalTokens"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_tokens" titulo="Tokens"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="tablaTokens" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 30%;">Codigo</th>
		            <th style="width: 30%;">Cantidad</th>
		            <th style="width: 30%;">Estatus</th>
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

<!--//////////////////////////Modal////////////////////////////-->
<div class="modal fade" id="ModalTokens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalImpuestos"></span> Token de descuento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormTokens">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CodigoToken" name="CodigoToken" placeholder="Ingresa el código del token">
		                <label for="CodigoToken">Código</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" min="0" class="form-control" id="CantidadToken" name="CantidadToken" placeholder="Ingresa la cantidad de descuento">
		                <label for="CantidadToken">Cantidad</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<b style="color: red;">El token es de un solo uso, una vez utilizado en alguna venta no se podrá volver a aplicar.</b>
		        </div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarToken" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 
