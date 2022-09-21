<div class="modal fade" id="ModalCategorias" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalCategorias"></span> categoria / familia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormCategorias">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreCategoria" name="NombreCategoria" placeholder="Ingresa el nombre de la categoria o familia">
		                <label for="NombreCategoria">Nombre de la categoria o familia</label>
		            </div>
		        </div>
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="DescripcionCategoria" name="DescripcionCategoria" placeholder="Ingresa la descripción de la categoria o familia">
		                <label for="DescripcionCategoria">Descripción de la categoria o familia</label>
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
			    <li class="breadcrumb-item" aria-current="page">Configuración</li>
			    <li class="breadcrumb-item active" aria-current="page">Tickets</li>
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
		    <div class="row col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<h5>Datos del Ticket</h5>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="col-sm-12">
						<form id="formTickets">
							<input type="hidden" id="nomPagina" value="v_ticket">
							<input type="hidden" name="metodo" value="modificar"> 
							<input type="hidden" name="accion" value="ticket"> 
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating mb-3">
										<select class="form-select" name="SucursalTicket" id="SucursalTicket" >
											<option value="">- Seleccione una opción -</option>
												#sucursales#
										</select>
										<label for="SucursalTicket">Sucursal</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkNombre' name='checkNombre'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<label for="DireccionTicket">Dirección</label>
								</div>
								<div class="col-md-2 col-sm-12 mb-3">
									<input type="checkbox" id='checkDireccion' name='checkDireccion'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="CalleSucTicket" name="CalleSucTicket" placeholder="Ingresa la calle de la sucursal">
										<label for="CalleSucTicket">Calle</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkCalle' name='checkCalle'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="NoExtTicket" name="NoExtTicket" placeholder="Ingresa el número exterior de la sucursal">
										<label for="NoExtTicket">No. Exterior</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkNoExt' name='checkNoExt'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="NoIntTicket" name="NoIntTicket" placeholder="Ingresa el número interior de la sucursal">
										<label for="NoIntTicket">No. Interior</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkNoInt' name='checkNoInt'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="ColoniaTicket" name="ColoniaTicket" placeholder="Ingresa la colonia de la sucursal">
										<label for="ColoniaTicket">Colonia</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkColonia' name='checkColonia'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="CPTicket" name="CPTicket" placeholder="Ingresa el código postal">
										<label for="CPTicket">Código Postal</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkCP' name='checkCP'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="CiudadTicket" name="CiudadTicket" placeholder="Ingresa la ciudad de la sucursal">
										<label for="CiudadTicket">Ciudad</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkCiudad' name='checkCiudad'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="EstadoTicket" name="EstadoTicket" placeholder="Ingresa el estado ">
										<label for="EstadoTicket">Estado</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkEstado' name='checkEstado'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="PaisTicket" name="PaisTicket" placeholder="Ingresa el pais">
										<label for="PaisTicket">Pais</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkPais' name='checkPais'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="phone" class="form-control" id="TelefonoTicket" name="TelefonoTicket" placeholder="Ingresa el telefono de la sucursal">
										<label for="TelefonoTicket">Telefono</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkTelefono' name='checkTelefono'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="EmailTicket" name="EmailTicket" placeholder="Ingresa el email de la sucursal">
										<label for="EmailTicket">Correo</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkEmail' name='checkEmail'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<div class="form-floating">
										<input type="text" class="form-control" id="MensajeTicket" name="MensajeTicket" placeholder="Ingresa el mensaje">
										<label for="MensajeTicket">Mensaje</label>
									</div>
								</div>
								<div class="col-md-2 col-sm-12 mb-3" style="margin: 20px auto;">
									<input type="checkbox" id='checkMensaje' name='checkMensaje'>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-sm-12 mb-3">
									<label for="TotalLetraTicket">Total con letra</label>
								</div>
								<div class="col-md-2 col-sm-12 mb-3">
									<input type="checkbox" id='checkTotalLetra' name='checkTotalLetra'>
								</div>
							</div>
							<div class="col-12 text-center">
								<button type="submit" class="btn btn-primary" id="bGuardarSucu" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Aplicar</strong></button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-sm-6" style="padding: 50px; border: dashed; box-sizing: border-box;">
					<br>
					<div class="row">
						<div class="col-sm-12 text-center" id="imgTicket" style="padding: 30px 60px; box-sizing: border-box;">
							<p><img src="" style="max-width: 100%; max-height: 200px;"></p>
						</div>
					</div>
					<div class="row">
						<form enctype="multipart/form-data" id="formImgTicket">
							<input type="hidden" name="metodo" value="contranueva"> 
							<input type="hidden" name="accion" value="ticket">
							<input type="hidden" name="imagenElimina" id="imagenElimina"> 
							<div class="col-sm-12">
								<input type="file" class="form-control" name='archivo' id="imagenTicket">
							</div>
							<div class="col-sm-3" style="display: none;">
								<button type="submit" class="btn btn-default btn-block" id="cambiarImagenTicket">Cambiar</button>
							</div>
						</form>
					</div>
					<div class="row" id="datosTicket">
							
					</div>
					<hr><hr>
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<table width="100%" class="text-center">
								<thead>
									<tr id="tablaTitulos">
											
									</tr>
								</thead>
								<tbody>
									<tr id="tablaCuerpo">
										
									</tr>
								</tbody>
								<tfoot id="numArticulos">
									
								</foot>
							</table> 
						</div>
					</div>
					<hr><hr>
					<div class="row" id="totalesTicket">
							
					</div>
					<br>
					<div class="row" id="impuestosTiket">
						<div class="col-sm-12">
							<div id="fReportes" class="elfondo recargaElContenido row">
								#ImpuestosTabla# <!-- 	PROHIBIDO ELIMINAR ESTA LINEA 	-->
							</div>	
						</div>		
					</div>
					<br>
					<div class="row" id="cambioTicket">
						
					</div>
					<br>
					<div class="row" id="extrasTicket">
							
					</div>
					<br><br>
					<div class="row" id="finalTicket">
							
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


