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
		            <th style="width: 20%;">Direcciones</th>
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
		            <input type="text" class="form-control" id="RFCCliente" name="RFCCliente" placeholder="Ingresa el RFC del cliente">
		            <label for="RFCCliente">RFC</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
            	<div class="form-floating">
              	<input type="number" min="0" step="any" class="form-control" id="DescuentoCliente" name="DescuentoCliente" placeholder="Ingresa el valor del descuento">
                <label for="DescuentoCliente">Descuento (%)</label>
              </div>
            </div>
            <div class="col-md-4">
            	<div class="form-floating mb-3">
								<select class="form-select" name="SucursalCliente" id="SucursalCliente" >
									<option value="0">- Seleccione una opción -</option>
										#SucursalesCliente#
								</select>
								<label for="SucursalCliente">Sucursal</label>
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
		        <hr>
		        <b class="mb-3">Dirección fiscal</b>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="CalleClienteGeneral" name="CalleClienteGeneral" placeholder="Ingresa la calle del cliente">
		            <label for="CalleClienteGeneral">Calle</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="NoExteriorClienteGeneral" name="NoExteriorClienteGeneral" placeholder="Ingresa el número exterior">
		            <label for="NoExteriorClienteGeneral">No. Exterior</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="NoInteriorClienteGeneral" name="NoInteriorClienteGeneral" placeholder="Ingresa el número interior">
		            <label for="NoInteriorClienteGeneral">No. Interior</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CPClienteGeneral" name="CPClienteGeneral" placeholder="Ingresa el codigo postal del cliente">
		          	<label for="CPClienteGeneral">Codigo postal</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="ColoniaClienteGeneral" name="ColoniaClienteGeneral" placeholder="Ingresa la colonia del cliente">
		          	<label for="ColoniaClienteGeneral">Colonia</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CiudadClienteGeneral" name="CiudadClienteGeneral" placeholder="Ingresa la ciudad del cliente">
		            <label for="CiudadClienteGeneral">Ciudad</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="EstadoClienteGeneral" name="EstadoClienteGeneral" placeholder="Ingresa el estado del cliente">
		            <label for="EstadoClienteGeneral">Estado</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="PaisClienteGeneral" name="PaisClienteGeneral" placeholder="Ingresa el país del cliente">
		            <label for="PaisClienteGeneral">País</label>
		          </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos bancarios</b>   
		        <br>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
								<select class="form-select" id="FacturarCliente" name="FacturarCliente">
							    	<option value="" selected> - Seleccione una opción - </option>
							    	<option value="1">Si</option>
							    	<option value="0">No</option>
							  	</select>
							 	<label for="FacturarCliente">Facturar ventas</label>
							</div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="TitularBancoCliente" name="TitularBancoCliente" placeholder="Ingresa el nombre del titular">
		            <label for="TitularBancoCliente">Titular</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="BancoCliente" name="BancoCliente" placeholder="Ingresa el nombre del banco">
		            <label for="BancoCliente">Banco</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CuentaBancoCliente" name="CuentaBancoCliente" placeholder="Ingresa el número de cuenta o clabe">
		            <label for="CuentaBancoCliente">No. Cuenta / CLABE</label>
		          </div>
		        </div>
		        <hr>
		        <div class="row mb-3">
		        	<div class="col-md-6 col-sm-12 text-start">
		        		<b class="mb-3">Datos de ubicación</b>
		        	</div>
		        	<div class="col-md-6 col-sm-12 text-end">
		        		<button type="button" class="btn btn-success" id="AgregarDireccionCliente" data-bs-toggle="modal" data-bs-target="#ModalNuevaDireccionCliente">Agregar dirección <i class="fas fa-plus"></i></button>
		        	</div>
		        </div>
		        <div class="col-md-12 col-sm-12">
		        	<div class="table-responsive">
		        		<table class="table table table-hover table-striped table-bordered text-center" id="TablaUbicacionClientes" width="100%" style="font-size: 12px;">
				          <thead>
				            <th style="width: 25%;">Domicilio</th>
				            <th style="width: 25%;">Ubicacion</th>
				          	<th style="width: 25%;">Contacto</th>
				          	<th style="width: 25%;">Acciones</th>
				          </thead>
				          <tbody>
				          </tbody>
				      	</table>
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


<div class="modal fade" id="ModalNuevaDireccionCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Agregar nueva dirección</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormDireccion">
	      <div class="modal-body">
	      	<div class="row">
			   	 	<div class="col-md-3 col-sm-12 mb-3"> 
			      	<div class="form-floating">
			        	<input type="text" class="form-control" id="CalleCliente" name="CalleCliente" placeholder="Ingresa la calle del cliente">
			          <label for="CalleCliente">Calle</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3"> 
			      	<div class="form-floating">
			        	<input type="text" class="form-control" id="NoExteriorCliente" name="NoExteriorCliente" placeholder="Ingresa el número exterior">
			          <label for="NoExteriorCliente">No. Exterior</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3"> 
			      	<div class="form-floating">
			        	<input type="text" class="form-control" id="NoInteriorCliente" name="NoInteriorCliente" placeholder="Ingresa el número interior">
			          <label for="NoInteriorCliente">No. Interior</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			        	<input type="text" class="form-control" id="CPCliente" name="CPCliente" placeholder="Ingresa el codigo postal del cliente">
			          <label for="CPCliente">Codigo postal</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="ColoniaCliente" name="ColoniaCliente" placeholder="Ingresa la colonia del cliente">
			         	<label for="ColoniaCliente">Colonia</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="CiudadCliente" name="CiudadCliente" placeholder="Ingresa la ciudad del cliente">
			          <label for="CiudadCliente">Ciudad</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="EstadoCliente" name="EstadoCliente" placeholder="Ingresa el estado del cliente">
			          <label for="EstadoCliente">Estado</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="PaisCliente" name="PaisCliente" placeholder="Ingresa el país del cliente">
			          <label for="PaisCliente">País</label>
			        </div>
			      </div>
			      <hr>
			      <b class="mb-3">Datos de contacto</b>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="NombreContactoCliente" name="NombreContactoCliente" placeholder="Ingresa nombre del contacto">
			          <label for="NombreContactoCliente">Nombre</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="PuestoContactoCliente" name="PuestoContactoCliente" placeholder="Ingresa el puesto del contacto">
			          <label for="PuestoContactoCliente">Puesto</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="CorreoContactoCliente" name="CorreoContactoCliente" placeholder="Ingresa el correo electrónico">
			          <label for="CorreoContactoCliente">Correo electrónico</label>
			        </div>
			      </div>
			      <div class="col-md-3 col-sm-12 mb-3">
			      	<div class="form-floating">
			          <input type="text" class="form-control" id="TelefonoContactoCliente" name="TelefonoContactoCliente" placeholder="Ingresa el teléfono del contacto">
			          <label for="TelefonoContactoCliente">Teléfono</label>
			        </div>
			      </div>
		      </div>
		    </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarDireccionCliente"><i class="fa fa-check-circle"></i> <strong>Agregar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>

<div class="modal fade" id="ModalDetallesDireccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Datos de la dirección</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
	    <div class="modal-body">
	      	<div class="row">
			   	 	<div class="col-md-12 col-sm-12">
			   	 		Calle: <span style="font-weight: bold;" id="DatosCalle"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		No. Exterior: <span style="font-weight: bold;" id="DatosExt"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		No. Interior: <span style="font-weight: bold;" id="DatosInt"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		CP: <span style="font-weight: bold;" id="DatosCP"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		Colonia: <span style="font-weight: bold;" id="DatosColonia"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		Ciudad: <span style="font-weight: bold;" id="DatosCiudad"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		Estado: <span style="font-weight: bold;" id="DatosEstado"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12 mb-2">
			   	 		País: <span style="font-weight: bold;" id="DatosPais"></span>
			   	 	</div>
			   	 	<hr>
			   	 	<h6>Datos del contacto</h6>
			   	 	<div class="col-md-12 col-sm-12 mt-2">
			   	 		Nombre del contacto: <span style="font-weight: bold;" id="DatosNombre"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		Puesto: <span style="font-weight: bold;" id="DatosPuesto"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		Correo electrónico: <span style="font-weight: bold;" id="DatosCorreo"></span>
			   	 	</div>
			   	 	<div class="col-md-12 col-sm-12">
			   	 		Teléfono: <span style="font-weight: bold;" id="DatosTelefono"></span>
			   	 	</div>
		      </div>
		  </div>
	    <div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
	    </div>
    </div>
  </div>
</div>