<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Proveedores</li>
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
					<button type="button" class="btn btn-success" id="bontonNuevoProve" data-bs-toggle="modal" data-bs-target="#ModalProveedor"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarProveedores').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-bordered text-center myDataTable" id="TablaProveedores" width="100%" style="font-size: 12px;">
		          <thead>
		          	<th style="width: 20%;">Fecha</th>
		            <th style="width: 20%;">Empresa</th>
		            <th style="width: 20%;" orden="No">Contacto</th>
		            <th style="width: 20%;" orden="No">Dirección</th>
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


<div class="modal fade" id="ModalProveedor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalProveedor"></span> proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormProveedor">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NombreEmpresaProveedor" name="NombreEmpresaProveedor" placeholder="Ingresa el nombre de la empresa">
		                <label for="NombreEmpresaProveedor">Nombre de la empresa</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="RazonSocialProveedor" name="RazonSocialProveedor" placeholder="Ingresala razón social del proveedor">
		                <label for="RazonSocialProveedor">Razón social</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="TelefonoProveedor" name="TelefonoProveedor" placeholder="Ingresa el telefono del proveedor">
		                <label for="TelefonoProveedor">Telefono del proveedor</label>
		            </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos de ubicación</b>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CalleProveedor" name="CalleProveedor" placeholder="Ingresa la dirección del proveedor">
		                <label for="CalleProveedor">Calle del proveedor</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NoExteriorProveedor" name="NoExteriorProveedor" placeholder="Ingresa la dirección del proveedor">
		                <label for="NoExteriorProveedor">No. Exterior</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NoInteriorProveedor" name="NoInteriorProveedor" placeholder="Ingresa la dirección del proveedor">
		                <label for="NoInteriorProveedor">No. Interior</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="ColoniaProveedor" name="ColoniaProveedor" placeholder="Ingresa la colonia donde se ubica el proveedor">
		                <label for="ColoniaProveedor">Colonia</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CiudadProveedor" name="CiudadProveedor" placeholder="Ingresa la ciudad donde se ubica el proveedor">
		                <label for="CiudadProveedor">Ciudad</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="EstadoProveedor" name="EstadoProveedor" placeholder="Ingresa el estado donde se ubica el proveedor">
		                <label for="EstadoProveedor">Estado</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="PaisProveedor" name="PaisProveedor" placeholder="Ingresa el pais donde se ubica el proveedor">
		                <label for="PaisProveedor">País</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CPProveedor" name="CPProveedor" placeholder="Ingresa el codigo postal del proveedor">
		                <label for="CPProveedor">Codigo postal</label>
		            </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos de contacto</b>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="ContactoProveedor" name="ContactoProveedor" placeholder="Ingresa el nombre del contacto con el proveedor">
		                <label for="ContactoProveedor">Nombre del contacto</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="PuestoContactoProveedor" name="PuestoContactoProveedor" placeholder="Ingresa el puesto del contacto con el proveedor">
		                <label for="PuestoContactoProveedor">Puesto del contacto</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CorreoContactoProveedor" name="CorreoContactoProveedor" placeholder="Ingresa el correo electrónico del contacto">
		                <label for="CorreoContactoProveedor">Correo electrónico del contacto</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="CelularContactoProveedor" name="CelularContactoProveedor" placeholder="Ingresa el telefono del contacto con el proveedor">
		                <label for="CelularContactoProveedor">Teléfono del contacto</label>
		            </div>
		        </div>
		        <hr>
		        <b class="mb-3">Datos adicionales</b>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="RFCProveedor" name="RFCProveedor" placeholder="Ingresa el RFC del proveedor">
		                <label for="RFCProveedor">RFC del proveedor</label>
		            </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="BancoProveedor" name="BancoProveedor" placeholder="Ingresa el nombre del banco del proveedor">
		                <label for="BancoProveedor">Banco</label>
		            </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="NoCuentaProveedor" name="NoCuentaProveedor" placeholder="Ingresa el número de cuenta o clabe del proveedor">
		                <label for="NoCuentaProveedor">CLABE o No. de cuenta</label>
		            </div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" min="0" step="any" class="form-control" id="CreditoProveedor" name="CreditoProveedor" placeholder="Ingresa el credito que ofrece el proveedor">
		                <label for="CreditoProveedor">Monto de crédito que ofrecen</label>
		            </div>
		        </div>
		        <!--<hr>
		        <b class="mb-3">Descuento que ofrece el proveedor</b>
		        <br>
		        <div class="col-md-4 col-sm-12 mb-3">
              <div class="form-floating">
              	<select class="form-select" id="TipoDescuentoProveedor" name="TipoDescuentoProveedor">
                	<option value="" selected> - Seleccione una opción - </option>
                  <option value="Porcentaje">Descuento por porcentaje</option>
                  <option value="Cantidad">Descuento por cantidad</option>
                </select>
                <label for="TipoDescuentoProveedor">Tipo de descuento</label>
              </div>
            </div>
            <div class="col-md-4 col-sm-12 mb-3">
            	<div class="form-floating">
              	<input type="number" disabled="true" min="0" step="any" class="form-control" id="DescuentoProveedor" name="DescuentoProveedor" placeholder="Ingresa el valor del descuento">
                <label for="DescuentoProveedor"><span id="TituloTipoDescuentoProveedor"></span></label>
              </div>
            </div>
            <div class="col-md-4 text-center col-sm-12 mb-3">
            	<h6>Descuento</h6>
              <h4 id="LabelDescuentoProveedor"><b class="cantidad">0</b></h4>
            </div>-->
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarProveedor" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
			<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>

