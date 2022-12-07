<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item" aria-current="page">Productos</li>
			    <li class="breadcrumb-item active" aria-current="page">Productos</li>
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
					<button type="button" class="btn btn-success" id="botonNuevoProductos" data-bs-toggle="modal" data-bs-target="#ModalProductos"><i class="fa fa-file"></i> Nueva</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_productos" titulo="Productos"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaProductos" width="100%" style="font-size: 12px;">
		          <thead>
		            <th style="width: 15%;">Código</th>
		            <th style="width: 15%;">Descripción</th>
		            <th style="width: 10%;">Costo</th>
		            <th style="width: 25%;">Precio</th>
		            <th style="width: 30%;">Detalles</th>
		            <th style="width: 5%;" orden="No">Acciones</th>
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

<!--/////////////////////////Modal///////////////////////////////////-->
<div class="modal fade" id="ModalProductos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalProductos"></span> producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formPresentaciones"><button type="submit" id="bGuardarPres" hidden></button></form>
      <form id="formPreciosProd"><button type="submit" id="bGuardarPrecio" hidden></button></form>
      <form id="FormProductos">
	      <div class="modal-body">
	       	<div class="row">
		        <div class="col-md-6 col-sm-12 mb-3">
							<div class="form-group">
								<center>
									<svg id="CodigoB"></svg>
									<script>
										JsBarcode("#CodigoB", "CODIGO");
									</script>
								</center>
							</div>
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="CodigoBarras" name="CodigoBarras" value="" placeholder="Ingresa el código de barras del producto">
		            <label for="CodigoBarras">Código de barras</label>
		          </div>
		        </div>
						<div class="col-md-6 col-sm-12 mb-3">
					   	<div class='rounded mx-auto d-block'>
								<div class='rounded mx-auto d-block' id="verImagenProducto" style="width: 250px; height: 170px; cursor:pointer; border-radius:4px; overflow:hidden;"><img src="vistas/assets/img/default.jpg" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;">
								</div>	
	            </div>
	            <br>
	            <input class="form-control" type="file" id="ImagenProducto" name="ImagenProducto" accept="image/png, image/jpeg, image/gif">
	            <br>
			      </div>
		      </div>
					<div class="row">
			      <div class="col-md-4 col-sm-12 mb-3">
			      	<div class="form-floating">
			         	<input type="text" class="form-control" id="Descripcion" name="Descripcion" placeholder="Ingresa una descripción del producto">
			          <label for="Descripcion">Descripción</label>
			        </div>
			      </div>
			      <div class="col-md-4 col-sm-12 mb-3">
					   	<div class="form-floating mb-3">
								<select class="form-select" name="Area" id="Area" >
									<option value="">- Seleccione una opción -</option>
										#areas#
								</select>
								<label for="Area">Area</label>
							</div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
						  	<div class="form-floating mb-3">
									<select class="form-select" name="Categoria" id="Categoria" >
										<option value="">- Seleccione una opción -</option>
											#categorias#
									</select>
									<label for="Categoria">Familias</label>
								</div>
		        </div>
		      </div>

					<div class="row">
						<div class="col-md-4 col-sm-12 mb-3">
					  	<div class="form-floating mb-3">
								<select class="form-select" name="ClaseProducto" id="ClaseProducto" >
									<option value="">- Seleccione una opción -</option>
									<option value="Pieza">Pieza</option>
									<option value="Granel">Granel</option>
								</select>
								<label for="ClaseProducto">Clase de producto</label>
							</div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		           	<input type="number" class="form-control" step="any" min='0' max='10000' id="PrecioProducto" name="PrecioProducto" placeholder="Ingresa el precio del producto">
		            <label for="PrecioProducto">Precio</label>
		          </div>
		        </div>
	       		<div class="col-md-4 col-sm-12 mb-3">
				   		<div class="form-floating">
		           	<input type="number" class="form-control" step="any" min='0' max='10000' id="CostoProducto" name="CostoProducto" placeholder="Ingresa el costo del producto">
		            <label for="CostoProducto">Costo</label>
		          </div>
		        </div>
	       	</div>
					<div class="row">
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="number" class="form-control" step="any" min='0' max='10000' id="PrecioMayoreo" name="PrecioMayoreo" placeholder="Ingresa el precio de mayoreo del producto">
		            <label for="PrecioMayoreo">Precio de mayoreo</label>
		          </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="number" class="form-control" id="Minimo" step="any" min='0' max='1000' name="Minimo" placeholder="Ingresa el mínimo de stock del producto">
		            <label for="Minimo">Stock Mínimo</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" id="Maximo" step="any" min='0' max='1000' name="Maximo" placeholder="Ingresa el máximo de stock del producto">
		                <label for="Maximo">Stock Máximo</label>
		            </div>
		        </div>
	       	</div>
					<div class="row">
		        <div class="col-md-8 col-sm-12 mb-3">
		        	<div class="form-floating">
		            <input type="text" class="form-control" id="DetallesProducto" name="DetallesProducto" placeholder="Ingresa los detalles adicionales del producto">
		            <label for="DetallesProducto">Detalles adicionales</label>
		          </div>
		        </div>
	    		</div>
	    		<hr>
		      <div class="row mb-3">
		       	<div class="col-12 text-start">
		       		<b class="mb-3">Datos de Facturación</b>
		       	</div>
		      </div>
		      <div class="row mb-3">
		      	<div class="col-md-4 mb-3">
              <div class="input-group">
                <div class="form-floating flex-grow-1">
                  <input type="text" class="form-control" id="claveProdServ" name="claveProdServ" placeholder="Clave Prod./Serv." readonly>
                  <label>Clave Prod./Serv.</label>
              	</div>
                <button type="button" class="btn btn-outline-secondary" id="bBuscarClaveProd"><i class="fas fa-search"></i></button>
              </div>
          	</div>
          	<div class="col-md-4 mb-3">
              <div class="input-group">
                <div class="form-floating flex-grow-1">
                  <input type="text" class="form-control" id="claveUnidadProd" name="claveUnidadProd" placeholder="lave de Unidad" readonly>
                  <label>Clave de Unidad</label>
              	</div>
                <button type="button" class="btn btn-outline-secondary" id="bBuscarUnidadProd"><i class="fas fa-search"></i></button>
              </div>
          	</div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="unidadProd" name="unidadProd" placeholder="Nombre de Unidad">
		                <label for="Maximo">Nombre de Unidad</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="abreUnudadProd" name="abreUnudadProd" placeholder="Nombre de Unidad">
		                <label for="Maximo">Abreviatura de Unidad</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
					  	<div class="form-floating mb-3">
								<select class="form-select" name="objImProducto" id="objImProducto">
									<option value="">- Seleccione una opción -</option>
									<option value="01">01 - No objeto de impuesto.</option>
									<option value="02">02 - Sí objeto de impuesto.</option>
									<option value="03">03 - Sí objeto del impuesto y no obligado al desglose.</option>
									<option value="04">04 - Sí objeto del impuesto y no causa impuesto.</option>
								</select>
								<label>Objeto de Impuesto</label>
							</div>
		        </div>
		      </div>
		      <hr>
	    		<div class="row mb-3">
		       	<div class="col-md-6 col-sm-12 text-start">
		       		<b class="mb-3">Presentaciones del productos</b>
		       	</div>
		      </div>
		      <div class="col-md-12 col-sm-12">
		      	<div class="table-responsive">
		       		<table class="table table table-hover table-striped table-bordered text-center" id="tablaPresentacionProducto" width="100%" style="font-size: 12px;">
				        <thead>
				        	<th style="width: 33%;">Clave Unidad</th>
				          <th style="width: 33%;">Nombre</th>
				        	<th style="width: 33%;">Abreviatura</th>
				        	<th style="width: 33%;">Acciones</th>
				        </thead>
				        <tbody id="verPresentaciones">

				        </tbody>
				        <tfoot>
				        	<tr>
				        		<td>
				        			<div class="input-group mb-3">
											  <input type="text" form="formPresentaciones" class="form-control" id="unidadPresentacion" name="unidadPresentacion" placeholder="Ingresa la clave de la unidad" readonly>
											  <button type="button" class="btn btn-outline-secondary" id="bBuscarUnidadPres"><i class="fas fa-search"></i></button>
											</div>
		                </td>
				        		<td>
		                  <input type="text" form="formPresentaciones" class="form-control" id="nombrePresentacion" name="nombrePresentacion" placeholder="Ingresa el nombre de la presentación/unidad" required>
		                </td>
		                <td>
		                  <input type="text" form="formPresentaciones" class="form-control" id="abreviaturaPresentacion" name="abreviaturaPresentacion" placeholder="Ingresa la abreviatura de la presentación/unidad" required>
		                </td>
		                <td>
		                	<button  type="button" class="btn btn-sm btn-success" id="bAgergarPresentacion"><i class="fas fa-plus"></i></button>
		                </td>
				        	</tr>
				        </tfoot>
				    	</table>
		      	</div>
		      </div>
		      <hr>
		      <div class="row mb-3">
		       	<div class="col-md-6 col-sm-12 text-start">
		       		<b class="mb-3">Precios del producto</b>
		       	</div>
		      </div>
		      <div class="col-md-12 col-sm-12">
		      	<div class="table-responsive">
		       		<table class="table table table-hover table-striped table-bordered text-center" id="tablaPreciosProductos" width="100%" style="font-size: 12px;">
				        <thead>
				          <th style="width: 20%;">Zona</th>
				          <th style="width: 30%;">Presentación</th>
				          <th style="width: 30%;">Nombre</th>
				        	<th style="width: 20%;">Precio</th>
				        	<th style="width: 20%;">Mayoreo</th>
				        	<th style="width: 10%;">Acciones</th>
				        </thead>
				        <tbody id="verPreciosProd">

				        </tbody>
				        <tfoot>
				        	<tr>
				        		<td>
                    	<select form="formPreciosProd" class="form-select" name="zonaPrecioProducto" id="zonaPrecioProducto" required>
                      	<option value="">--Seleccione una opción--</option>  
                      	#zonas# 
                    	</select>
                		</td>
                		<td>
	                    <select form="formPreciosProd" class="form-select" name="presentacionProdSelect" id="presentacionProdSelect">
	                      <option value="">--Seleccione una opción--</option>  
	                    </select>
	                	</td>
		                <td>
		                  <input type="text" form="formPreciosProd" class="form-control" id="nombrePrecio" name="nombrePrecio" placeholder="Ingresa el nombre del precio del producto" required>
		                </td>
		                <td>
		                  <input type="number" form="formPreciosProd" class="form-control" id="precioProductoPres" name="precioProductoPres" step="any" min="0" placeholder="$0.00" required>
		                </td>
		                <td>
		                  <input type="number" form="formPreciosProd" class="form-control" id="precioProductoMayoreoPres" name="precioProductoMayoreoPres" step="any" min="0" placeholder="$0.00">
		                </td>
		                <td>
		                	<button type="button" class="btn btn-sm btn-success" id="bAgergarPrecio" attrid nombre><i class="fas fa-plus"></i></button>
		                </td>
				        	</tr>
				        </tfoot>
				    	</table>
		      	</div>
		      </div>
		      <hr>
		      <div class="row mb-3">
		       	<div class="col-md-6 col-sm-12 text-start">
		       		<b class="mb-3">Impuestos</b>
		       	</div>
		       	<div class="col-md-6 col-sm-12 text-end">
		       		<button type="button" class="btn btn-success" id="bAgregarImpuestoProd">Agregar Impuesto <i class="fas fa-plus"></i></button>
		      	</div>
		      </div>
		      <div class="col-md-12 col-sm-12">
		      	<div class="table-responsive">
		       		<table class="table table table-hover table-striped table-bordered text-center" width="100%" style="font-size: 12px;">
				        <thead>
				          <th>Nombre</th>
				          <th>Porcentaje</th>
				          <th>Clave CFDI</th>
				        	<th>Tipo Factor</th>
				        	<th>Clase</th>
				        	<th>Acciones</th>
				        </thead>
				        <tbody id="verImpuetsosProd">
				        
				        </tbody>
				    	</table>
		      	</div>
		      </div>
				</div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarProducto" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 

<!--/////////////////////////Modal///////////////////////////////////-->
<div class="modal fade" id="ModalExistenciasProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Aumentar existencias</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormExistenciaProducto">
	      <div class="modal-body">
	       	<div class="row">
				   	<div class="col-md-12">
				   		<div class="form-floating mb-3">
								<select class="form-select" name="PresentacionesProducto" id="PresentacionesProducto" >
								</select>
								<label for="PresentacionesProducto">Presentación</label>
							</div>
							<div class="form-floating mb-3">
								<select class="form-select" name="SucursalExistencia" id="SucursalExistencia" >
									<option value="">- Seleccione una opción -</option>
									#sucursales#
								</select>
								<label for="SucursalExistencia">Sucursal</label>
							</div>
							<div class="form-floating mb-3">
								<input type="number" class="form-control" step="any" min='0' id="CantidadExistencia" name="CantidadExistencia" placeholder="Ingresa la cantidad">
								<label for="CantidadExistencia">Cantidad</label>
							</div>
				   	</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="GuardarExistenciaProducto"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 

<!--/////////////////////////Modal///////////////////////////////////-->
<div class="modal fade" id="modalImpuestosProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Impuestos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	      <div class="row">
			   	<div class="table-responsive">
						<table class="table table-bordered table-striped text-center myDataTable" id="tablaImpuestosProd" width="100%">
							<thead>
								<tr>
									<th>Nombre</th>
				          <th>Porcentaje</th>
				          <th>Clave CFDI</th>
				        	<th>Tipo Factor</th>
				        	<th>Clase</th>
				        	<th>Acciones</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
	    </div>
	    <div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
	    </div>
    </div>
  </div>
</div> 

<!--/////////////////////////Modal///////////////////////////////////-->
<div class="modal fade" id="modalClavesProdServ" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Impuestos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	      <div class="row">
			   	<div class="table-responsive">
						<table class="table table-bordered table-striped text-center myDataTable" id="tablaClavesProdServ" width="100%">
							<thead>
								<tr>
									<th>Clave</th>
				          <th>Descripción</th>
				          <th>Palabras</th>
				        	<th>Acciones</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
	    </div>
	    <div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
	    </div>
    </div>
  </div>
</div> 

<!--/////////////////////////Modal///////////////////////////////////-->
<div class="modal fade" id="modalClavesUnidades" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Impuestos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	      <div class="row">
			   	<div class="table-responsive">
						<table class="table table-bordered table-striped text-center myDataTable" id="tablaClavesUnidades" width="100%">
							<thead>
								<tr>
									<th>Clave</th>
				          <th>Nombre</th>
				          <th>Símbolo</th>
				        	<th>Acciones</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
	    </div>
	    <div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
	    </div>
    </div>
  </div>
</div> 