<div class="modal fade" id="ModalEmpleados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalEmpleados"></span> empleado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormEmpleados">
	      <div class="modal-body">
	      	<div class="row">
	      	 	<div class="offset-md-4 col-md-4 col-sm-12 text-center">
	      	 		<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" id="verfotoEmpleado" style="width: 250px; height: 170px;cursor:pointer;border-radius:4px;border:2px solid grey;"><img src="vistas/assets/archivos/fotosClientes/default.jpg"></div>	
								</div>
								<br>
								<input class="form-control" type="file" id="FotoEmpleado" name="FotoEmpleado">
	      	 	</div>
	      	</div>
	      	<br>
	       	<div class="row">
	       		<b class="mb-3">Datos personales</b>
	       		<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreEmpleado" name="NombreEmpleado" placeholder="Ingresa el nombre del empleado">
		                <label for="NombreEmpleado">Nombre del empleado</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="TelefonoEmpleado" name="TelefonoEmpleado" placeholder="Ingresa el teléfono del empleado">
		                <label for="TelefonoEmpleado">Teléfono del empleado</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CelularEmpleado" name="CelularEmpleado" placeholder="Ingresa el celular del empleado">
		                <label for="CelularEmpleado">Celular del empleado</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CorreoEmpleado" name="CorreoEmpleado" placeholder="Ingresa el correo electrónico del empleado">
		                <label for="CorreoEmpleado">Correo electrónico del empleado</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="date" class="form-control" id="FechaNacimientoEmpleado" name="FechaNacimientoEmpleado" placeholder="Ingresa la fecha de nacimiento del empleado">
		                <label for="FechaNacimientoEmpleado">Fecha de nacimiento</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="LugarNacimientoEmpleado" name="LugarNacimientoEmpleado" placeholder="Ingresa el lugar de nacimiento del empleado">
		                <label for="LugarNacimientoEmpleado">Lugar de nacimiento</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
			       	<div class="form-floating">
								<select class="form-select" id="SexoEmpleado" name="SexoEmpleado">
							    	<option value="" selected> - Seleccione una opción - </option>
							    	<option value="Masculino">Masculino</option>
							    	<option value="Femenino">Femenino</option>
							  	</select>
							 	<label for="SexoEmpleado">Sexo del empleado</label>
							</div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="EstadoCivilEmpleado" name="EstadoCivilEmpleado" placeholder="Ingresa el estado civil del empleado">
		                <label for="EstadoCivilEmpleado">Estado civil</label>
		            </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos de ubicación</b>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="DireccionEmpleado" name="DireccionEmpleado" placeholder="Ingresa la dirección del empleado">
		            <label for="DireccionEmpleado">Dirección del empleado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CPEmpleado" name="CPEmpleado" placeholder="Ingresa el codigo postal del empleado">
		          	<label for="CPEmpleado">Codigo postal</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="ColoniaEmpleado" name="ColoniaEmpleado" placeholder="Ingresa la colonia del empleado">
		          	<label for="ColoniaEmpleado">Colonia</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CiudadEmpleado" name="CiudadEmpleado" placeholder="Ingresa la ciudad del empleado">
		            <label for="CiudadEmpleado">Ciudad</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="EstadoEmpleado" name="EstadoEmpleado" placeholder="Ingresa el estado del empleado">
		            <label for="EstadoEmpleado">Estado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="PaisEmpleado" name="PaisEmpleado" placeholder="Ingresa el país del empleado">
		            <label for="PaisEmpleado">País</label>
		          </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos laborales</b>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="RFCEmpleado" name="RFCEmpleado" placeholder="Ingresa el RFC del empleado">
		            <label for="RFCEmpleado">RFC del empleado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="NoSeguroSocialEmpleado" name="NoSeguroSocialEmpleado" placeholder="Ingresa el NSS del empleado">
		            <label for="NoSeguroSocialEmpleado">NSS del empleado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="text" class="form-control" id="CURPEmpleado" name="CURPEmpleado" placeholder="Ingresa la CURP del empleado">
		            <label for="CURPEmpleado">CURP del empleado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="date" class="form-control" id="FechaIngreso" name="FechaIngreso" placeholder="Ingresa la fecha de ingreso del empleado">
		            <label for="FechaIngreso">Fecha de ingreso</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<select class="form-control" id="PuestoEmpleado" name="PuestoEmpleado" placeholder="Selecciona el puesto del empleado">
		           	</select>
		            <label for="PuestoEmpleado">Puesto</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<select class="form-control" id="AreasEmpleado" name="AreasEmpleado" placeholder="Selecciona el área del empleado">
		           	</select>
		            <label for="AreasEmpleado">Área</label>
		          </div>
		        </div>
		        <label class="text-center mb-3">Horarios</label>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="time" class="form-control" id="HoraEntrada" name="HoraEntrada" placeholder="Ingresa la hora de entrada del empleado">
		            <label for="HoraEntrada">Hora de entrada (Lunes - Viernes)</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="time" class="form-control" id="HorarioSalida" name="HorarioSalida" placeholder="Ingresa la hora de salida del empleado">
		            <label for="HorarioSalida">Hora de salida (Lunes - Viernes)</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="time" class="form-control" id="HoraEntradaSabado" name="HoraEntradaSabado" placeholder="Ingresa la hora de entrada del empleado">
		            <label for="HoraEntradaSabado">Hora de entrada (Sabado)</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="time" class="form-control" id="HorarioSalidaSabado" name="HorarioSalidaSabado" placeholder="Ingresa la hora de salida del empleado">
		            <label for="HorarioSalidaSabado">Hora de salida (Sabado)</label>
		          </div>
		        </div>
		        <label class="text-center mb-3">Sueldo</label>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<select class="form-control" id="TipoSueldo" name="TipoSueldo" placeholder="Selecciona el tipo de sueldo del empleado">
		           		<option value=""> Seleccione una opción </option>
		           		<option value="Semanal"> Semanal </option>
		           		<option value="Quincenal"> Quincenal </option>
		           	</select>
		            <label for="TipoSueldo">Tipo de sueldo</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="number" min="0" step="any" class="form-control" id="SueldoEmpleado" name="SueldoEmpleado" placeholder="Ingresa el sueldo del empleado">
		            <label for="SueldoEmpleado">Sueldo</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="number" class="form-control" id="SDIEmpleado" name="SDIEmpleado" placeholder="Ingresa el SDI del empleado" readonly>
		            <label for="SDIEmpleado">Salario diario integro</label>
		          </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="number" class="form-control" id="SPHEmpleado" name="SPHEmpleado" placeholder="Ingresa el SPH del empleado" readonly>
		            <label for="SPHEmpleado">Sueldo por hora</label>
		          </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos de contacto por emergencias</b>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="ContactoEmergencia" name="ContactoEmergencia" placeholder="Ingresa el nombre del contacto de emergencias del empleado">
		            <label for="ContactoEmergencia">Nombre del contacto</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="TelefonoEmergencia" name="TelefonoEmergencia" placeholder="Ingresa el telefono del contacto de emergencias del empleado">
		            <label for="TelefonoEmergencia">Teléfono del contacto</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="TipoSangreEmpleado" name="TipoSangreEmpleado" placeholder="Ingresa el tipo de sangre del empleado">
		            <label for="TipoSangreEmpleado">Tipo de sangre</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="AlergiasEmpleado" name="AlergiasEmpleado" placeholder="Ingresa las alergias del empleado">
		            <label for="AlergiasEmpleado">Alergias</label>
		          </div>
		        </div>
		        <hr>
		        <b class="mb-3">Estatus del empleado</b>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <select type="text" class="form-control" id="EstatusEmpleado" name="EstatusEmpleado" placeholder="Ingresa el nombre del contacto de emergencias del empleado">
		            	<option value="Activo" selected>Activo</option>
		            	<option value="Inactivo">Inactivo</option>
		            </select>
		            <label for="EstatusEmpleado">Estatus actual del empleado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="date" class="form-control" id="FechaTerminoContrato" name="FechaTerminoContrato" placeholder="Ingresa la fecha de termino del contrato del empleado">
		            <label for="FechaTerminoContrato">Fecha de termino del contrato</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="date" class="form-control" id="FechaBajaEmpleado" name="FechaBajaEmpleado" placeholder="Ingresa la fecha de baja del empleado">
		            <label for="FechaBajaEmpleado">Fecha de baja</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="MotivoBajaEmpleado" name="MotivoBajaEmpleado" placeholder="Ingresa el motivo de baja del emplead">
		            <label for="MotivoBajaEmpleado">Motivo de baja del empleado</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="date" class="form-control" id="FechaReingresoEmpleado" name="FechaReingresoEmpleado" placeholder="Ingresa la fecha de reingreso del empleado">
		            <label for="FechaReingresoEmpleado">Fecha de reingreso</label>
		          </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarEmpleado" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
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
			    <li class="breadcrumb-item active" aria-current="page">Personal</li>
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
					<button type="button" class="btn btn-success" id="botonNuevoEmpleado" data-bs-toggle="modal" data-bs-target="#ModalEmpleados"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarPersonal').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaPersonal" width="100%" style="font-size: 12px;">
		          <thead>
		          	<th style="width: 20%;">Fecha</th>
		            <th style="width: 20%;">Empleado</th>
		            <th style="width: 20%;" orden="No">Dirección</th>
		            <th style="width: 20%;" orden="No">Contacto</th>
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
