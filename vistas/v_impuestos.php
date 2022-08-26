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
					<div class="form-group">
						<label class="control-label">Clave CFDI</label>
						<select name="ClaveImpuesto" id="ClaveImpuesto" class="form-control" >
							<option selected disabled value=""> - Seleccione - </option>
							<option value="001">(001) ISR</option>
							<option value="002">(002) IVA</option>
							<option value="003">(003) IEPS</option>
						</select>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 mb-3">
					<div class="form-group">
						<label class="control-label">Clase</label>
						<select name="ClaseImpuesto" id="ClaseImpuesto" class="form-control" >
							<option selected disabled value=""> - Seleccione - </option>
							<option value="Trasladado">Trasladado</option>
							<option value="Retenido">Retenido</option>
						</select>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 mb-3">
					<div class="form-group">
						<label class="control-label">Tipo de Factor</label>
						<select name="TipoFactorImpuesto" id="TipoFactorImpuesto" class="form-control" >
							<option selected value="" disabled> - Seleccione - </option>
							<option value="Taza">Taza</option>
							<option value="Cuota">Cuota</option>
							<option value="Excento">Excento</option>
						</select>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 mb-3">
					<div class="form-group">
						<label class="control-label">Tipo de Impuesto</label>
						<select name="TipoImpuesto" id="TipoImpuesto" class="form-control" required>
							<option value="1">Porcentaje</option>
							<option value="2">Monto</option>
						</select>
					</div>
				</div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CantidadImpuesto" name="CantidadImpuesto" placeholder="Ingresa la cantidad del impuesto">
		                <label for="CantidadImpuesto">Cantidad</label>
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
			    <li class="breadcrumb-item active" aria-current="page">Impuestos</li>
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
					<button type="button" class="btn btn-success" id="botonNuevoImpuesto" data-bs-toggle="modal" data-bs-target="#ModalImpuestos"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_impuestos" titulo="Impuestos"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaImpuestos" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 15%;">Nombre</th>
		            <th style="width: 15%;">Clave CFDI</th>
		            <th style="width: 15%;">Clase</th>
		            <th style="width: 15%;">Tipo de Factor</th>
		            <th style="width: 15%;">Tipo de Impuesto</th>
		            <th style="width: 15%;">Cantidad</th>
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
