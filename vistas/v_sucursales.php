<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Sucursales</li>
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
					<button type="button" class="btn btn-success" id="bontonNuevoSu" data-bs-toggle="modal" data-bs-target="#ModalSucursal"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarSucursales').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		    	<div class="col-12">
		      	<table class="table table table-hover table-bordered text-center myDataTable" id="TablaSucursales" width="100%" style="font-size: 12px;">
		        	<thead>
		          	<th style="width: 20%;">Nombre</th>
		            <th style="width: 20%;" orden="No">Dirección</th>
								<th style="width: 15%;" orden="No">Nombre gerente</th>
		            <th style="width: 15%;" orden="No">Correo</th>
		            <th style="width: 20%;" orden="No">Telefonos</th>
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

<!--////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalSucursal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalSucursal"></span> sucursal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormSucursalNueva">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
		          <div class="form-floating">
		          	<input type="text" class="form-control" id="NombreSucursal" name="NombreSucursal" placeholder="Ingresa el nombre de la sucursal">
		            <label for="NombreSucursal">Nombre de la sucursal</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="EncargadoSucursal" id="EncargadoSucursal" >
									<option value="">- Seleccione una opción -</option>
										#usuarios#
								</select>
								<label for="EncargadoSucursal">Gerente de la sucursal</label>
							</div>
		       </div>
		       <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		              <select class="form-select" name="ZonasSucursal" id="ZonasSucursal" >
										<option value="">- Seleccione una opción -</option>
										#ZonasSucursales#
									</select>
		              <label for="ZonasSucursal">Zonas</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		          <div class="form-floating">
		            <input type="text" class="form-control" id="CalleSucursal" name="CalleSucursal" placeholder="Ingresa la calle de la sucursal">
		           	<label for="CalleSucursal">Calle</label>
		          </div>
		        </div> 
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="NoExteriorSucursal" name="NoExteriorSucursal" placeholder="Ingresa el número exterior de la sucursal">
		            <label for="NoExteriorSucursal">No. Exterior</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="NoInteriorSucursal" name="NoInteriorSucursal" placeholder="Ingresa El número interior de la sucursal">
		            <label for="NoInteriorSucursal">No. Interior</label>
		          </div>
		        </div>
						<div class="col-md-6 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="ColoniaSucursal" name="ColoniaSucursal" placeholder="Ingresa la colonia de la sucursal">
		            <label for="ColoniaSucursal">Colonia</label>
		          </div>
		        </div>
						<div class="col-md-6 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="CPSucursal" name="CPSucursal" placeholder="Ingresa el código postal de la sucursal">
		            <label for="CPSucursal">Código postal</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="CiudadSucursal" name="CiudadSucursal" placeholder="Ingresa la ciudad de la sucursal">
		            <label for="CiudadSucursal">Ciudad</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="EstadoSucursal" name="EstadoSucursal" placeholder="Ingresa el estado de la sucursal">
		            <label for="EstadoSucursal">Estado</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="PaisSucursal" name="PaisSucursal" placeholder="Ingresa pais de la sucursal" value="México">
		            <label for="PaisSucursal">País</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="text" class="form-control" id="EmailSucursal" name="EmailSucursal" placeholder="Ingresa el email de la sucursal">
		            <label for="EmailSucursal">Correo</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="phone" class="form-control" id="TelefonoSucursal" name="TelefonoSucursal" placeholder="Ingresa el telefono de la sucursal" maxlength="30">
		            <label for="TelefonoSucursal">Telefono</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="phone" class="form-control" id="telefono2Sucursal" name="Telefono2Sucursal" placeholder="Ingresa otro telefono de la sucursal">
		            <label for="Telefono2Sucursal">Segundo telefono</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="phone" class="form-control" id="latitudSucursal" name="latitudSucursal" placeholder="Ingresa la latitud">
		            <label>Latitud</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="phone" class="form-control" id="longitudSucursal" name="longitudSucursal" placeholder="Ingresa la longitud">
		            <label>Longitud</label>
		          </div>
		        </div>
		        <div class="col-12" id="mapaSucursal">
		        	
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="bGuardarSucu" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>