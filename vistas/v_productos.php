<div class="modal fade" id="ModalProductos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalProductos"></span> producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
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
						<div class='rounded mx-auto d-block' id="verImagenProducto" style="width: 250px; height: 170px; cursor:pointer; border-radius:4px; overflow:hidden;"><img src="vistas/assets/img/default.jpg" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;"></div>	
                    </div>
                    <br>
                    <input class="form-control" type="file" id="ImagenProducto" name="ImagenProducto" accept="image/png, image/jpeg, image/gif">
                    <br>
		        </div>
	       	</div>
			<div class="row">
		        <div class="col-md-12 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="Descripcion" name="Descripcion" placeholder="Ingresa una descripción del producto">
		                <label for="Descripcion">Descripción</label>
		            </div>
		        </div>
	       	</div>
			<div class="row">
	       		<div class="col-md-3 col-sm-12 mb-3">
				  	<div class="form-floating mb-3">
						<select class="form-select" name="ClaseProducto" id="ClaseProducto" >
							<option value="">- Seleccione una opción -</option>
							<option value="Pieza">Pieza</option>
							<option value="Granel">Granel</option>
						</select>
						<label for="ClaseProducto">Clase de producto</label>
					</div>
		        </div>
		        <div class="col-md-3 col-sm-12 mb-3">
				  	<div class="form-floating mb-3">
						<select class="form-select" name="TipoUnidad" id="TipoUnidad" >
							<option value="">- Seleccione una opción -</option>
							<option value="1">Producto</option>
							<option value="2">Materia</option>
						</select>
						<label for="TipoUnidad">Tipo de unidad</label>
					</div>
		        </div>
				<div class="col-md-3 col-sm-12 mb-3">
				  	<div class="form-floating mb-3">
						<select class="form-select" name="Unidad" id="Unidad" >
							<option value="">- Seleccione una opción -</option>
								#unidades#
						</select>
						<label for="Unidad">Unidad</label>
					</div>
		        </div>
				<div class="col-md-3 col-sm-12 mb-3">
				  	<div class="form-floating mb-3">
						<select class="form-select" name="Categoria" id="Categoria" >
							<option value="">- Seleccione una opción -</option>
								#categorias#
						</select>
						<label for="Categoria">Categoria</label>
					</div>
		        </div>
	       	</div>
			<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
				   <div class="form-floating">
		               	<input type="number" class="form-control" min='0'  id="CostoProducto" name="CostoProducto" placeholder="Ingresa el costo del producto">
		                <label for="CostoProducto">Costo</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" min='0' id="PrecioProducto" name="PrecioProducto" placeholder="Ingresa el precio del producto">
		                <label for="PrecioProducto">Precio</label>
		            </div>
		        </div>
				<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" min='0' id="PrecioMayoreo" name="PrecioMayoreo" placeholder="Ingresa el precio de mayoreo del producto">
		                <label for="PrecioMayoreo">Precio de mayoreo</label>
		            </div>
		        </div>
	       	</div>
			<div class="row">
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
		        	<div class="form-floating">
		               	<input type="number" class="form-control" id="Minimo" name="Minimo" placeholder="Ingresa el mínimo de stock del producto">
		                <label for="Minimo">Stock Mínimo</label>
		            </div>
		        </div>
				<div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" id="Maximo" name="Maximo" placeholder="Ingresa el máximo de stock del producto">
		                <label for="Maximo">Stock Máximo</label>
		            </div>
		        </div>
	       	</div>
			<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
				   	<div class="form-floating mb-3">
						<select class="form-select" name="PonerUnidad" id="PonerUnidad" >
							<option value="">- Seleccione una opción -</option>
							<option value="1">Sí</option>
							<option value="0">No</option>
						</select>
						<label for="PonerUnidad">Mostrar unidad en ticket</label>
					</div>
		        </div>
		        <div class="col-md-8 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="text" class="form-control" id="DetallesProducto" name="DetallesProducto" placeholder="Ingresa los detalles adicionales del producto">
		                <label for="DetallesProducto">Detalles adicionales</label>
		            </div>
		        </div>
	       	</div>
			<div id = 'PreciosSucursal'>
				<hr>
				<b class="mb-3">Precios por sucursal</b>
				<div class="row mt-3">
					<div class="col-md-4 col-sm-12 mb-3">
						<div class="form-floating mb-3">
							<select class="form-select" name="Sucursales" id="Sucursales" >
								<option value="">- Seleccione una opción -</option>
									#sucursales#
							</select>
							<label for="Sucursales">Sucursal</label>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-3">
					<div class="form-floating">
							<input type="number" class="form-control" min='0' id="CostoProductoD" name="CostoProductoD" placeholder="Ingresa el costo del producto">
							<label for="CostoProductoD">Costo</label>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-3">
						<div class="form-floating">
							<input type="number" class="form-control" ,in='0' id="PrecioProductoD" name="PrecioProductoD" placeholder="Ingresa el precio del producto">
							<label for="PrecioProductoD">Precio</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 col-sm-12 mb-3">
						<div class="form-floating">
							<input type="number" class="form-control" min='0' id="PrecioMayoreoD" name="PrecioMayoreoD" placeholder="Ingresa el precio de mayoreo del producto">
							<label for="PrecioMayoreoD">Precio de mayoreo</label>
						</div>
					</div>
					<div class="col-md-3 col-sm-12 mb-3">
						<div class="form-floating">
							<input type="number" class="form-control" id="MinimoD" name="MinimoD" placeholder="Ingresa el mínimo de stock del producto">
							<label for="MinimoD">Stock Mínimo</label>
						</div>
					</div>
					<div class="col-md-3 col-sm-12 mb-3">
						<div class="form-floating">
							<input type="number" class="form-control" id="MaximoD" name="MaximoD" placeholder="Ingresa el máximo de stock del producto">
							<label for="MaximoD">Stock Máximo</label>
						</div>
					</div>
					<div class="col-md-3 col-sm-12 mb-3">
						<button type="button" class="btn btn-success" id="DetalleProductoSucursal"><i class="fas fa-plus-circle"></i> <strong>Agregar</strong></button>
					</div>
				</div>
				<div class="table-responsive" >
						<table class="table table-bordered table-striped text-center">
							<thead>
								<tr>
									<th>Sucursal</th>
									<th>Costo</th>
									<th>Precio</th>
									<th>Precio Mayoreo</th>
									<th>Stock mínimo</th>
									<th>Stock máximo</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody id="tbodyDetallesProducto">
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



<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item" aria-current="page">Productos a la venta</li>
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
					<!-- <button type="button" class="btn btn-success" id="botonNuevaArea" onclick="$('#ModalAreas').appendTo('body').modal('show')"><i class="fa fa-file"></i> Nueva</button> -->
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
		            <th style="width: 10%;">Tipo</th>
		            <th style="width: 10%;">Costo</th>
		            <th style="width: 10%;">Precio</th>
		            <th style="width: 15%;">Precio de mayoreo</th>
		            <th style="width: 15%;">Detalles</th>
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



<div class="modal fade" id="ModalPreciosSucursal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Modificar precios por sucursal de <span id="TituloModalPrecios">producto</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormPrecios">
	      <div class="modal-body">
	       	<div class="row">
			<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
				   	<div class="form-floating mb-3">
						<select class="form-select" name="Sucursal" id="Sucursal" >
							<option value="">- Seleccione una opción -</option>
								#sucursales#
						</select>
						<label for="Sucursal">Sucursal</label>
					</div>
		        </div>
				<div class="col-md-4 col-sm-12 mb-3">
				   <div class="form-floating">
		               	<input type="number" class="form-control"  min='0' id="CostoProductoE" name="CostoProductoE" placeholder="Ingresa el costo del producto">
		                <label for="CostoProductoE">Costo</label>
		            </div>
		        </div>
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" min='0' id="PrecioProductoE" name="PrecioProductoE" placeholder="Ingresa el precio del producto">
		                <label for="PrecioProductoE">Precio</label>
		            </div>
		        </div>
	       	</div>
			<div class="row">
				<div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control"min='0' id="PrecioMayoreoE" name="PrecioMayoreoE" placeholder="Ingresa el precio de mayoreo del producto">
		                <label for="PrecioMayoreoE">Precio de mayoreo</label>
		            </div>
		        </div>
				<div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" id="MinimoE" name="MinimoE" placeholder="Ingresa el mínimo de stock del producto">
		                <label for="MinimoE">Stock Mínimo</label>
		            </div>
		        </div>
				<div class="col-md-3 col-sm-12 mb-3">
		        	<div class="form-floating">
		               	<input type="number" class="form-control" id="MaximoE" name="MaximoE" placeholder="Ingresa el máximo de stock del producto">
		                <label for="MaximoE">Stock Máximo</label>
		            </div>
		        </div>
				<div class="col-md-3 col-sm-12 mb-3">
					<button type='submit' class="btn btn-success" id="PSucursal" attrid="" tipo="agregar"><i class="fas fa-plus-circle"></i> <strong id='NombreBoton'>Agregar</strong></button>
		        </div>
	       	</div>
			   <div class="table-responsive">
						<table class="table table-bordered table-striped text-center">
							<thead>
								<tr>
									<th>Sucursal</th>
									<th>Costo</th>
									<th>Precio</th>
									<th>Precio Mayoreo</th>
									<th>Stock mínimo</th>
									<th>Stock máximo</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody id="tbodyPreciosSucursal">
							</tbody>
						</table>
					</div>
				</div>
	      </div>
	      <div class="modal-footer">
	        <!-- <button type="submit" class="btn btn-primary" id="GuardarDetalle" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button> -->
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div> 

