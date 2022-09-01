<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Clientes</li>
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
					<button type="button" class="btn btn-success" id="botonNuevoCliente" data-bs-toggle="modal" data-bs-target="#ModalCliente"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarClientes').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaClientes" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 15%;">Fecha</th>
		            <th style="width: 15%;">Nombre</th>
		            <th style="width: 20%;">Direccion</th>
		            <th style="width: 20%;" orden="No">Contacto</th>
		            <th style="width: 15%;" orden="No">Detalles</th>
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


<div class="modal fade" id="ModalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalCliente"></span> cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormClientes">
	      <div class="modal-body">
	      	<div class="row">
	      	 	<div class="offset-md-4 col-md-4 col-sm-12 text-center">
	      	 		<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" id="verfotoCliente" style="width: 250px; height: 170px;cursor:pointer;border-radius:4px;border:2px solid grey;"><img src="vistas/assets/archivos/default.jpg"></div>	
								</div>
								<br>
								<input class="form-control" type="file" id="FotoCliente" name="FotoCliente">
	      	 	</div>
	      	</div>
	      	<br>
	       	<div class="row">
	       		<b class="mb-3">Datos del cliente</b>
	       		<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreCliente" name="NombreCliente" placeholder="Ingresa el nombre del cliente">
		                <label for="NombreCliente">Nombre del cliente</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="TelefonoCliente" name="TelefonoCliente" placeholder="Ingresa el teléfono del cliente">
		                <label for="TelefonoCliente">Teléfono del cliente</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CelularCliente" name="CelularCliente" placeholder="Ingresa el celular del cliente">
		                <label for="CelularCliente">Celular del cliente</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CorreoCliente" name="CorreoCliente" placeholder="Ingresa el correo electrónico del cliente">
		                <label for="CorreoCliente">Correo electrónico del cliente</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="date" class="form-control" id="FechaNacimientoCliente" name="FechaNacimientoCliente" placeholder="Ingresa la fecha de nacimiento del cliente">
		                <label for="FechaNacimientoCliente">Fecha de nacimiento</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
			       	<div class="form-floating">
								<select class="form-select" id="SexoCliente" name="SexoCliente">
							    	<option value="" selected> - Seleccione una opción - </option>
							    	<option value="Masculino">Masculino</option>
							    	<option value="Femenino">Femenino</option>
							  	</select>
							 	<label for="SexoCliente">Sexo del cliente</label>
							</div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="RFCCliente" name="RFCCliente" placeholder="Ingresa el RFC del cliente">
		            <label for="RFCCliente">RFC</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="NombreEmpresaCliente" name="NombreEmpresaCliente" placeholder="Ingresa el nombre de la empresa del cliente">
		            <label for="NombreEmpresaCliente">Nombre de la empresa</label>
		          </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos de ubicación</b>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="DireccionCliente" name="DireccionCliente" placeholder="Ingresa la dirección del cliente">
		            <label for="DireccionCliente">Dirección del cliente</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CPCliente" name="CPCliente" placeholder="Ingresa el codigo postal del cliente">
		          	<label for="CPCliente">Codigo postal</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="ColoniaCliente" name="ColoniaCliente" placeholder="Ingresa la colonia del cliente">
		          	<label for="ColoniaCliente">Colonia</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CiudadCliente" name="CiudadCliente" placeholder="Ingresa la ciudad del cliente">
		            <label for="CiudadCliente">Ciudad</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="EstadoCliente" name="EstadoCliente" placeholder="Ingresa el estado del cliente">
		            <label for="EstadoCliente">Estado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="PaisCliente" name="PaisCliente" placeholder="Ingresa el país del cliente">
		            <label for="PaisCliente">País</label>
		          </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos adicionales</b>
		        <div class="col-md-4 col-sm-12 mb-3">
              <div class="form-floating">
              	<select class="form-select" id="TipoDescuentoCliente" name="TipoDescuentoCliente">
                	<option value="" selected> - Seleccione una opción - </option>
                  <option value="Porcentaje">Descuento por porcentaje</option>
                  <option value="Cantidad">Descuento por cantidad</option>
                </select>
                <label for="TipoDescuentoCliente">Tipo de descuento</label>
              </div>
            </div>
            <div class="col-md-4 col-sm-12 mb-3">
            	<div class="form-floating">
              	<input type="number" disabled="true" min="0" step="any" class="form-control" id="DescuentoCliente" name="DescuentoCliente" placeholder="Ingresa el valor del descuento">
                <label for="DescuentoCliente"><span id="TituloTipoDescuento"></span></label>
              </div>
            </div>
            <div class="col-md-4 text-center col-sm-12 mb-3">
            	<h6>Descuento</h6>
              <h4 id="LabelDescuentoCliente"><b class="cantidad">0</b></h4>
            </div>
            <hr>
		        <b class="mb-3">Datos bancarios</b>   
		        <br>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="TitularBancoCliente" name="TitularBancoCliente" placeholder="Ingresa el nombre del titular">
		            <label for="TitularBancoCliente">Titular</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="BancoCliente" name="BancoCliente" placeholder="Ingresa el nombre del banco">
		            <label for="BancoCliente">Banco</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CuentaBancoCliente" name="CuentaBancoCliente" placeholder="Ingresa el número de cuenta o clabe">
		            <label for="CuentaBancoCliente">No. Cuenta / CLABE</label>
		          </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarCliente" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
			<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>

