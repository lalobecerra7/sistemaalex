<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Reporte de inventario general</li>
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
				<!-- <div class="col-md-11 text-end">
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelReporteInventarioGeneral">Exportar excel <i class="fas fa-file-excel"></i></a>
				</div> -->
				<div class="col-md-1 text-end">
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_reporteInventarioGeneral" titulo="Reporte de inventario general"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row">
                    <div class="col-md-3 d-grid gap-2">
		                <button type="button" style="height: 54px;" class="btn btn-outline-secondary" id="CargarProveedoresModalRInventarioGral">
	                        <i class="fas fa-search"></i> Proveedores
	                    </button>
					</div>
					<div class="col-md-3 d-grid gap-2">
		                <button type="button" style="height: 54px;" class="btn btn-outline-secondary" id="CargarSucursalesModalRInventarioGral">
	                        <i class="fas fa-search"></i> Sucursales
	                    </button>
					</div>
					<div class="col-md-3">
						<span>
							Sucursales seleccionadas:
						</span>
						<span id="MostrarSucursalesSeleccionadasRInventarioGral">No has seleccionado sucursales</span>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
				    <div class="col-12 table-responsive">
				        <table class="table table table-hover table-striped table-bordered text-center" id="TablaReporteInventarioGeneral" width="100%" style="font-size: 12px;">
		                    <thead>
		                        
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
<div class="modal fade" id="ModalSucursalesInventarioGeneral" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Sucursales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div class="col-12" id="divTablaProductosInventarioGral">
                        <table class="table text-center myDataTable" id="TablaSucursalesInventarioGeneral" width="100%">
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
                <button type="button" class="btn BotonDatosPrecioInvenGeneral" productoInvenGral="" presentacionInvenGral=""  data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="button" class="btn btn-primary" id="SeleccionarSucursalesMarcadasRInventarioGral">
                	<i class="fa fa-check-circle"></i> <strong>Seleccionar Sucursales</strong>
                </button>
            </div>
        </div>    
    </div>
</div>

<!-- Modal para seleccionar proveedores como filtro de busqueda -->
<div class="modal fade" id="ModalProveedoresInventarioGeneral" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Proveedores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div class="col-12" id="divTablaProveedoresInventarioGral">
                        <table class="table text-center myDataTable" id="TablaProveedoresInventarioGeneral" width="100%">
                            <thead>
                                <tr>
                                    <th orden="No">Seleccionar</th>
                                    <th>Proveedor</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>
                </div>    
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="button" class="btn btn-primary" id="SeleccionarProveedoresMarcadosRInventarioGral">
                	<i class="fa fa-check-circle"></i> <strong>Seleccionar Proveedores</strong>
                </button>
            </div>
        </div>    
    </div>
</div>