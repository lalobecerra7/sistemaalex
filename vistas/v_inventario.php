<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
					<li class="breadcrumb-item"><a href="index.php">Productos</a></li>
					<li class="breadcrumb-item active" aria-current="page">Inventario</li>
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
					<!-- <button type="button" class="btn btn-primary" id="botonVerTraslados" data-bs-toggle="modal" data-bs-target="#ModalVerTraslados"><i class="fa fa-print"></i> Traslados</button> -->
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
<div class="modal fade" id="ModalVerTraslados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Traslados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            	<div class="row mb-3">
            		<div class="col-md-6">
            			<label for="FechaInicioTraslado">Desde</label>
            			<input type="date" id="FechaInicioTraslado" name="FechaInicioTraslado" class="form-control">
            		</div>
            		<div class="col-md-6">
            			<label for="FechaFinalTraslado">Hasta</label>
            			<input type="date" id="FechaFinalTraslado" name="FechaFinalTraslado" class="form-control">
            		</div>
            	</div>
            	<div class="row mb-5">
					<div class="col-12">
						<table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaImprimirTraslados" width="100%" style="font-size: 12px;">
							<thead>
								<th style="width: 33%;">Fecha</th>
								<th style="width: 33%;">Detalles</th>
								<th style="width: 33%;">Acciones</th>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerConversiones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Conversiones del producto <b class="TituloConversionesModal"></b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            	<div class="row">
					<div class="col-12">
						<table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaConversiones" width="100%" style="font-size: 12px;">
							<thead>
								<th style="width: 15%;">Origen</th>
								<th style="width: 15%;">Destino</th>
								<th style="width: 10%;">Usuario</th>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
            </div>
        </div>
    </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerDistribucionPresentacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Distribución de <b id="NombreProductoPresentaciones"></b> en la sucursal: <b id="NombreSucursalModalPresentacion"></b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            	<div class="row">
					<div class="col-12">
						<table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaPresentacionesSucursal" width="100%" style="font-size: 12px;">
							<thead>
								<th style="width: 33%;">Presentación</th>
								<th style="width: 33%;">Abreviatura</th>
								<th style="width: 33%;">Cantidad</th>
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

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalConversionProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md modal-dialog-centered" style="z-index: 9999 !important;">
    	<div class="modal-content">
      		<div class="modal-header bg-inverse bd-inverse-darken">
	        	<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Conversiones</h5>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<form id="FormConversionProducto">
		      	<div class="modal-body">
		       		<div class="row">
					   	<div class="col-md-12">
					   		<div class="form-floating mb-3">
								<select class="form-select" name="SucursalOrigenPresentacion" id="SucursalOrigenPresentacion" >
								</select>
								<label for="SucursalOrigenPresentacion">Sucursal de origen</label>
							</div>
					   		<div class="form-floating mb-3">
								<select class="form-select" name="PresentacionesProductoOrigen" id="PresentacionesProductoOrigen" >
									<option value=""> Seleccione una opción </option>
								</select>
								<label for="PresentacionesProductoOrigen">Presentación de origen</label>
							</div>
							<div class="form-floating mb-3">
								<input type="number" class="form-control" min='0' id="CantidadPresentacionOrigen" name="CantidadPresentacionOrigen" placeholder="Ingresa la cantidad">
								<label for="CantidadPresentacionOrigen">Cantidad</label>
							</div>
							<div class="form-floating mb-3">
								<select class="form-select" name="SucursalDestinoPresentacion" id="SucursalDestinoPresentacion" >
								</select>
								<label for="SucursalDestinoPresentacion">Sucursal de destino</label>
							</div>
							<div class="form-floating mb-3">
								<select class="form-select" name="PresentacionesProductoDestino" id="PresentacionesProductoDestino" >
									<option value=""> Seleccione una opción </option>
								</select>
								<label for="PresentacionesProductoDestino">Presentación de destino</label>
							</div>
							<div class="form-floating mb-3">
								<input type="number" class="form-control" min='0' id="CantidadPresentacionDestino" name="CantidadPresentacionDestino" placeholder="Ingresa la cantidad">
								<label for="CantidadPresentacionDestino">Cantidad</label>
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
<div class="modal fade" id="ModalTraslados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Traslado del producto <span id="NombreProductoT">Producto</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormTraslados">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="date"  class="form-control" id="FechaTraslado" name="FechaTraslado" placeholder="Selecciona la fecha del traslado">
                                <label for="FechaTraslado">Fecha de traslado</label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="SucursalOrigen" id="SucursalOrigen" >
									<option value="">- Seleccione una opción -</option>
										#sucursales#
								</select>
								<label for="SucursalOrigen">Sucursal de origen</label>
							</div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="PresentacionProductoTraslado" id="PresentacionProductoTraslado" >
									<option value="">- Seleccione una opción -</option>
								</select>
								<label for="PresentacionProductoTraslado">Presentación</label>
							</div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="SucursalDestino" id="SucursalDestino" >
									<option value="">- Seleccione una opción -</option>
								</select>
								<label for="SucursalDestino">Sucursal de destino</label>
							</div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="number" min='1' max='' class="form-control" id="Cantidad" name="Cantidad" placeholder="Ingresa la cantidad de producto a trasladar">
                                <label for="Cantidad">Cantidad</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-md-12 col-sm-12">
                    		<div class="">
								<table class="table table-hover table-striped table-bordered text-center myDataTable" id="TablaTraslados" width="100%" style="font-size: 12px;">
									<thead>
										<th style="width: 20%;" >Fecha</th>
							     		<th style="width: 20%;" >Origen</th>
										<th style="width: 15%;" >Destino</th>
										<th style="width: 15%;" >Cantidad</th>
										<th style="width: 15%;" >Usuario</th>
									</thead>
									<tbody>
									
									</tbody>
								</table>
							</div>
                    	</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" attrid='' id="GuardarTraslado"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalDetalles" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalles de merma de <span id="NombreProductoM">Producto</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="">
					<table class="table table-hover table-striped table-bordered text-center myDataTable" id="TablaMerma" width="100%" style="font-size: 12px;">
						<thead>
							<th style="width: 20%;" >Fecha de merma</th>
							<th style="width: 20%;" >Motivo</th>
							<th style="width: 15%;" >Sucursal</th>
							<th style="width: 15%;" >Presentación</th>
							<th style="width: 15%;" >Cantidad</th>
							<th style="width: 15%;" orden="No">Imagen</th>
							<th style="width: 15%;" orden="No">Acciones</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Agregar merma de <span id="NombreProductoAM">Producto</span></h5>
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
                                <input type="date"  class="form-control" id="FechaMerma" name="FechaMerma" placeholder="Selecciona la fecha de la merma">
                                <label for="FechaMerma">Fecha de merma</label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
							<div class="form-floating">
								<select class="form-select" name="SucursalMerma" id="SucursalMerma" >
									<option value="">- Seleccione una opción -</option>
										#sucursales#
								</select>
								<label for="SucursalMerma">Sucursal</label>
							</div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
							<div class="form-floating">
								<select class="form-select" name="PresentacionProductoMerma" id="PresentacionProductoMerma">
									<option value="">- Seleccione una opción -</option>
								</select>
								<label for="PresentacionProductoMerma">Presentación</label>
							</div>
                        </div>
                        <div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="number" min='1' class="form-control" id="CantidadMerma" name="CantidadMerma" placeholder="Ingresa la cantidad de producto a trasladar">
                                <label for="CantidadMerma">Cantidad</label>
                            </div>
                        </div>
						<div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="MotivoMerma" name="MotivoMerma" placeholder="Ingresa el motivo de la merma">
                                <label for="MotivoMerma">Motivo de la merma</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="submit" class="btn btn-primary" attrid='' id="GuardarMerma"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalEditarMerma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar merma</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormEditarMerma">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="date"  class="form-control" id="FechaMermaE" name="FechaMermaE" placeholder="Selecciona la fecha de la merma">
                                <label for="FechaMermaE">Fecha de merma</label>
                            </div>
                        </div>
						<div class="col-md-12 col-sm-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="MotivoMermaE" name="MotivoMermaE" placeholder="Ingresa el motivo de la merma">
                                <label for="MotivoMermaE">Motivo de la merma</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" attrid='' id="GuardarMermaE"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>