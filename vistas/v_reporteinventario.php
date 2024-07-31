<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Reporte de inventario</li>
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
				<div class="col-md-11 text-end">
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelReporteInventario">Exportar excel <i class="fas fa-file-excel"></i></a>
				</div>
				<div class="col-md-1 text-end">
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_reporteinventario" titulo="Reporte de inventario"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row" id="Padre">
					<div class="col-md-3">
						<div class="form-floating">
	                        <select class="form-select" name="ProveedoresReporteInventario" id="ProveedoresReporteInventario" required>
	                        	<option value="">Seleccione una opción</option>
		                      	#ProveedoresReporteInventario# 
		                    </select>	
		                    <label for="ProveedoresReporteInventario">Proveedor</label>
	                    </div>
					</div>
					<div class="col-md-3 d-grid gap-2">
		                <button type="button" style="height: 54px;" class="btn btn-outline-secondary" id="CargarSucursalesModal">
	                        <i class="fas fa-search"></i> Sucursales
	                    </button>
					</div>
					<div class="col-md-3">
						<span>
							Sucursales seleccionadas:
						</span>
						<span id="MostrarSucursalesSeleccionadas">No has seleccionado sucursales</span>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
				    <div class="col-12">
				        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteInventario" width="100%" style="font-size: 12px;">
		                    <thead>
		                        <th>Codigo</th>
		                        <th>Descripción</th>
		                        <th>Existencia</th>
		                        <th>Proveedor</th>
		                        <th>Sucursal</th>
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

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalSucursalesInventario" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Sucursales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div class="col-12" id="divTablaProductos">
                        <table class="table text-center myDataTable" id="TablaSucursalesInventario" width="100%">
                            <thead>
                                <tr>
                                    <th orden="No">Seleccionar</th>
                                    <th>Sucursal</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>
                </div>    
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn BotonDatosPrecio" producto="" presentacion=""  data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="button" class="btn btn-primary" id="SeleccionarSucursalesMarcadas">
                	<i class="fa fa-check-circle"></i> <strong>Seleccionar Sucursales</strong>
                </button>
            </div>
        </div>    
    </div>
</div>

