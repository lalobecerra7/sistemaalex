<div>
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
					<a href="./controladores/excel/catalogo.php" target="_blank" class="btn btn-success"><i class="fa fa-file-excel"></i></a>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarInventario').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row mb-5">
					<div class="col-12">
						<table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaInventario" width="100%" style="font-size: 12px;">
							<thead>
								<th style="width: 20%;">Descripción</th>
								<th style="width: 10%;">Existencia</th>
								<th style="width: 10%;" orden="No">Precios</th>
								<th style="width: 15%;">Merma</th>
								<th style="width: 15%;">Lotes</th>
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

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalDetalles" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalles de merma de <span id="NombreProductoM">Producto</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="">
					<table class="table table-hover table-striped table-bordered text-center myDataTable" id="TablaMerma" width="100%" style="font-size: 12px;">
						<thead>
							<th>Fecha Registro</th>
							<th>Fecha Merma</th>
							<th>Costo</th>
							<th>Cantidad</th>
							<th>Total</th>
							<th>Motivo</th>
							<th orden="No">Imagen</th>
							<th orden="No">Acciones</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id='CerrarDetalle' attrid= ''>Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalMerma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Merma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormMerma">
                <div class="modal-body">
                    <div class="row mt-3">
                    	<div class="col-md-12 col-sm-12 text-center mb-3">
				      		<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" id="verFotoMerma" style="width: 250px; height: 170px;cursor:pointer;border-radius:4px;border:2px solid grey;"><img src="vistas/assets/archivos/defaultImagen.jpg"></div>	
							</div>
							<br>
							<input class="form-control" type="file" id="FotoMerma" name="FotoMerma">
				      	</div>
                        <div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="date"  class="form-control" id="fechaMerma" name="fechaMerma" placeholder="Selecciona la fecha de la merma">
                                <label for="FechaMerma">Fecha de Merma</label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="number" min='0.01' step="any" class="form-control" id="cantidadMerma" name="cantidadMerma" placeholder="Ingresa la cantidad de producto">
                                <label>Cantidad</label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
                        	<h6 id="costoMerma" class="dinero">$0.00</h6>
                        </div>	
                        <div class="col-md-12 col-sm-6 mb-3">
                        	<h6 id="totalMerma" class="dinero">$0.00</h6>
                        </div>	
						<div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="motivoMerma" name="motivoMerma" placeholder="Ingresa el motivo de la merma">
                                <label for="MotivoMerma">Motivo de la merma</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="submit" class="btn btn-primary" id="GuardarMerma"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalConversionProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered" style="z-index: 9999 !important;">
    	<div class="modal-content">
      		<div class="modal-header bg-inverse bd-inverse-darken">
	        	<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Conversiones</h5>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<form id="FormConversionProducto">
		      	<div class="modal-body">
		       		<div class="row">
		       			<div class="col-md-6 text-center">
					   		<div class="row">
					   			<div class="col-12 mb-3" id="conversionProducto">
					   				
					   			</div>
					   			<div class="col-12 mb-3" id="conversionSucursal">
					   				
					   			</div>
					   		</div>
					   	</div>
					   	<div class="col-md-6">
					   		<div class="row mb-3">
					   			<div class="col-12">
                            		<div class="form-floating">
                                		<input type="number" min='0.01' step="any" class="form-control" id="cantidadConversion" name="cantidadConversion" placeholder="Ingresa la cantidad de producto" required>
                                		<label>Cantidad</label>
                            		</div>
					   			</div>
					   		</div>
					   		<div class="row mb-3">
					   			<div class="col-12 table-responsive">
							   		<table class="table table-hover table-striped text-center" width="100%" style="font-size: 12px;">
							   			<thead>
							   				<tr>
							   					<th>Nombre</th>
							   					<th>Cantidad</th>
							   				</tr>
							   			</thead>
							   			<tbody id="verPresentaciones">
							   				
							   			</tbody>
							   		</table>
								</div>
					   		</div>
					   	</div>
					</div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="submit" class="btn btn-primary" id="GuardarConversionProducto"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
		      	</div>
  			</form>
    	</div>
  	</div>
</div> 

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalConversiones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalles Conversiones</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body row">
				<div class="col-12 text-center mb-3" id="verDetalleProducto">
					
				</div>
				<div class="col-12 table-responsive">
					<table class="table table-hover table-striped table-bordered text-center myDataTable" id="TablaConversiones" width="100%" style="font-size: 12px;">
						<thead>
							<th>Fecha Registro</th>
							<th>Cantidad</th>
							<th orden="No">Conversión</th>
							<th orden="No">Acciones</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id='CerrarDetalleConversiones' attrid= ''>Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVerPresentaciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center" width="100%" style="font-size: 12px;">
                        <thead>
                            <th>Nombre</th>
                            <th>Abreviatura</th>
                            <th>Existencia</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody id="verTablaPrese">
                               
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalLoteInve" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    	<div class="modal-content">
      		<div class="modal-header bg-inverse bd-inverse-darken">
	        	<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Conversiones</h5>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<form id="formLoteInve">
		    <div class="modal-body">
		       	<div class="row">
		       		<div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="date"  class="form-control" id="fechaCadLoteInve" name="fechaCadLoteInve" placeholder="Fecha de Caducidad">
                            <label for="fechaCadLoteInve">Fecha de Caducidad</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nombreLoteInve" name="nombreLoteInve" placeholder="Nombre Lote">
                            <label for="nombreLoteInve">Nombre</label>
                        </div>
                    </div>	
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="number" min='0' step="any" class="form-control" id="cantidadLoteInve" name="cantidadLoteInve" placeholder="Cantidad de producto">
                            <label for="cantidadLoteInve">Cantidad</label>
                        </div>
                    </div>	
				</div>
		    </div>
		    <div class="modal-footer">
		    	<button type="button" class="btn btn-outline-danger" id="bEliminarLoteInve"><i class="fas fa-trash"></i> <strong>Eliminar</strong></button>
		    	<button type="button" class="btn btn-outline-info" id="bImprimirLoteInve"><i class="fas fa-barcode"></i> <strong>Imprimir</strong></button>
		        <button type="submit" class="btn btn-primary" id="bGuardarLoteInve"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
		    </div>
  			</form>
    	</div>
  	</div>
</div> 