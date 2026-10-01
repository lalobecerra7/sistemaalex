<div>
	<div id="content" class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-8">
					<h1 style="font-weight: bold;" id="vistaTitulo"></h1>
				</div>
				<div class="col-2">
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_precios" titulo="Precios"><i class="fa fa-retweet"></i></a>
				</div>
				<div class="col-2">
					<button class="btn btn-sm btn-primary" id="CargarPreciosDeiman">
						Cargar precios Deiman
					</button>
				</div>
			</div>
			<br>
			<div class="row">
			    <div class="col-md-4 mb-3">
					<div class="form-floating mb-3">
						<select class="form-select" name="zonaPrecio" id="zonaPrecio">
							<option value="Todas">Todas</option>
							#zonas#
						</select>
						<label>Zonas</label>
					</div>
		        </div>
		        <div class="col-md-4 mb-3">
					<div class="form-floating mb-3">
						<select class="form-select" name="tipoProdPrecio" id="tipoProdPrecio">
							<option value="Todos">Todos</option>
							<option value="Uno">Un producto</option>
						</select>
						<label>Productos</label>
					</div>
		        </div>
		        <div id="buscarProductoPrecio" class="col-md-4 mb-3 oculto">
	              	<div class="input-group">
	                	<div class="form-floating flex-grow-1">
	                  		<input type="text" class="form-control" id="productoPrecio" name="productoPrecio" placeholder="Producto" readonly="">
	                  		<label>Producto</label>
	              		</div>
	                	<button type="button" class="btn btn-outline-secondary" id="bBuscarProductoPrecio"><i class="fas fa-search"></i></button>
	              </div>
	          	</div>
			</div>
			<br>
			<form id="FormAgregarPrecioNuevo" class="row">
				<div class="col-md-2">
					<div class="form-floating flex-grow-1">
	              		<input type="text" class="form-control" id="ReferenciaPrecioNuevo" name="ReferenciaPrecioNuevo" placeholder="Referencia" required>
	              		<label>Referencia</label>
	          		</div>
	          	</div>
				<div class="col-md-2">
					<div class="form-floating flex-grow-1">
	              		<input type="number" class="form-control" id="Precio3PrecioNuevo" name="Precio3PrecioNuevo" placeholder="Precio 3" required>
	              		<label>Precio 3 Neto</label>
	          		</div> 
				</div>
				<div class="col-md-2">
					<div class="form-floating flex-grow-1">
	              		<select class="form-select" name="ImpuestosPrecioNuevo" id="ImpuestosPrecioNuevo"> 
	                      	<option value="">-- Sin impuesto --</option>
	                      	<option value="IVA0">IVA 0%</option>
	                      	<option value="IVA">IVA 16%</option>
	                      	<option value="IEPS3">IEPS 3%</option>
	                      	<option value="IEPS">IEPS 8%</option>
                    	</select>
                    	<label>Impuesto</label>
	          		</div>
				</div>
				<div class="col-md-2">
					<div class="form-floating flex-grow-1">
	              		<input type="number" class="form-control" id="AumentoPrecioNuevo" name="AumentoPrecioNuevo" placeholder="% de aumento" required>
	              		<label>% de aumento</label>
	          		</div>
				</div>
				<div class="col-md-2">
					<div class="form-floating flex-grow-1">
	              		<select class="form-select" name="ZonaPrecioNuevo" id="ZonaPrecioNuevo"> 
	                      	#CargarZonasPrecioNuevo# 
                    	</select>
                    	<label>Zonas</label>
	          		</div>
				</div>
				<div class="col-md-2">
					<button class="btn btn-primary btn-sm" type="submit">Agregar precio <i class="fas fa-plus"></i></button>
				</div>
			</form>
			<br>
			<div class="row">
				<div class="col-12 table-responsive">
					<table class="table table-hover table-bordered table-striped text-center myDataTable" id="tablaPrecios" style="font-size: 12px;" width="100%">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Presentación</th>
								<th>Proveedores</th>
								<th>Nombre</th>
								<th style="width: 15%;">Precio</th>
								<th style="width: 15%;">Margen</th>
								<th style="width: 15%;">Costo Neto</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!--/////////////////////////Modal///////////////////////////////////-->
<div class="modal fade" id="modalProductosPrecios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    	<div class="modal-content">
      		<div class="modal-header bg-inverse bd-inverse-darken">
        		<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Productos</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<div class="modal-body">
	      		<div class="row">
			   		<div class="table-responsive">
						<table class="table table-bordered table-striped text-center myDataTable" id="tablaProductosPrecios" width="100%">
							<thead>
								<tr>
									<th orden="No">Imagen</th>
									<th>Código</th>
				          			<th>Descripcion</th>
				        			<th orden="No">Acciones</th>
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
<div class="modal fade" id="ModalCargarPreciosDeiman" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    	<div class="modal-content">
      		<div class="modal-header bg-inverse bd-inverse-darken">
        		<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Cargar precios de Deiman</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<form id="FormPreciosExcel">
      		<div class="modal-body">
      			<div class="row">
      				<div class="col-md-4">
	      				<select form="formPreciosProd" class="form-select" name="ZonaCargarPrecioProducto" id="ZonaCargarPrecioProducto">
	                      	<option value="">--Seleccione una opción--</option>  
	                      	#CargarZonasPrecio# 
                    	</select>
      				</div>
      				<div class="col-md-8">
                       	<input type="file" class="form-control" name="ExcelPrecios" id="ExcelPrecios">
      				</div>
      			</div>
	    	</div>
	    	<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
				<button type="submit" class="btn btn-primary" tipo="Insertar" id="bGuardarExcelPrecios">Guardar <i class="fas fa-save"></i></button>
	    	</div>
	    	</form>
    	</div>
  	</div>
</div> 
