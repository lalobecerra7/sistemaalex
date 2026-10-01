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
					<button type="button" class="btn btn-success" id="botonNuevoImpuesto" data-bs-toggle="modal" data-bs-target="#ModalImpuestos"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_impuestos" titulo="Impuestos"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="tablaImpuestos" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 20%;">Nombre</th>
		            <th style="width: 20%;">Porcentaje</th>
		            <th style="width: 20%;">Clave CFDI</th>
		            <th style="width: 20%;">Tipo Factor</th>
		            <th style="width: 10%;">Clase</th>
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
<div class="modal fade" id="ModalImpuestos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalImpuestos"></span> Impuestos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormImpuestos">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreImpuesto" name="NombreImpuesto" placeholder="Ingresa el nombre del impuesto">
		                <label for="NombreImpuesto">Nombre del impuesto</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
				    	<div class="form-floating">
								<select name="ClaveImpuesto" id="ClaveImpuesto" class="form-control" >
									<option selected disabled value=""> - Seleccione - </option>
									<option value="001">(001) ISR</option>
									<option value="002">(002) IVA</option>
									<option value="003">(003) IEPS</option>
								</select>
								<label for="ClaseProducto">Clave CFDI</label>
							</div>
				    </div>
				    <div class="col-md-12 col-sm-12 mb-3">
				    	<div class="form-floating">
								<select name="ClaseImpuesto" id="ClaseImpuesto" class="form-control" >
									<option selected disabled value=""> - Seleccione - </option>
									<option value="Trasladado">Trasladado</option>
									<option value="Retenido">Retenido</option>
								</select>
								<label for="ClaseImpuesto">Clase</label>
							</div>
				    </div>	

				    <div class="col-md-12 col-sm-12 mb-3">
				    	<div class="form-floating">
								<select name="TipoFactorImpuesto" id="TipoFactorImpuesto" class="form-control" >
									<option selected disabled value=""> - Seleccione - </option>
									<option value="Tasa">Tasa</option>
									<option value="Cuota">Cuota</option>
									<option value="Exento">Exento</option>
								</select>
								<label for="TipoFactorImpuesto">Tipo de Factor</label>
							</div>
				    </div>
						<div class="col-md-12 col-sm-12 mb-3">
				    	<div class="form-floating">
				      	<input type="number" min="0" step="any" class="form-control" id="PorcentajeImpuesto" name="PorcentajeImpuesto" placeholder="Ingresa el porcentaje del impuesto">
				        <label for="PorcentajeImpuesto">Porcentaje</label>
				      </div>
				    </div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarImpuesto" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 
