<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Ventas por producto</li>
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
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelVentasxProducto">Exportar excel <i class="fas fa-file-excel"></i></a>
				</div>
				<div class="col-md-1 text-end">
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_ventasxproducto" titulo="Ventas por producto"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row">
					<div class="col-md-4 mb-3">
						<div class="form-floating">
	                        <input type="datetime-local" class="form-control" id="FechaInicioReporteProducto" name="FechaInicioReporteProducto" placeholder="Fecha Inicio">
	                        <label>Fecha Inicio</label>
	                    </div>
					</div>
					<div class="col-md-4 mb-3">
						<div class="form-floating">
	                        <input type="datetime-local" class="form-control" id="FechaFinalReporteProducto" name="FechaFinalReporteProducto" placeholder="Fecha Fin">
	                        <label>Fecha Fin</label>
	                    </div>
					</div>
					<div class="col-md-4 mb-3">
						<div class="form-floating">
	                        <select class="form-select" name="proveedorReporteProducto" id="proveedorReporteProducto" required>
		                      	#proveedoresVentasXProducto# 
		                    </select>	
		                    <label for="proveedorReporteProducto">Proveedor</label>
	                    </div>
					</div> 
					<div class="col-md-2 d-grid gap-2 mb-3">
		                <button type="button" style="height: 54px;" class="btn btn-outline-secondary" id="CargarSucursalesModalProductos">
	                        <i class="fas fa-search"></i> Sucursales
	                    </button>
					</div>
					<div class="col-md-2">
						<span>
							Sucursales seleccionadas:
						</span>
						<span id="MostrarSucursalesSeleccionadasProductos">No has seleccionado sucursales</span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-2 text-center">
						Total de ventas
						<br>
						<span class="dinero" id="SpanTotalVentas">0</span>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
				    <div class="col-12">
				        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteVentasxProducto" width="100%" style="font-size: 12px;">
		                    <thead>
		                        <th>Fecha</th>
		                        <th>Codigo</th>
		                        <th>Descripcion</th>
		                        <th>Cantidad</th>
		                        <th>Total</th>
		                        <th>Devuelto</th>
		                        <th>Sucursal</th>
		                        <th Orden="No">Proveedores</th>
		                    </thead>
		                    <tbody>
		                           
		                    </tbody>
		                    <tfoot>
		                        <tr>
		                            <td>Totales</td>
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <td></td>
		                            <th></th>
		                        </tr>
		                    </tfoot>
				        </table>
				    </div>
			    </div>
		  	</div>
		</div>
	</div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalSucursalesProductos" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Sucursales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div class="col-12" id="divTablaProductos">
                        <table class="table text-center myDataTable" id="TablaSucursalesProductos" width="100%">
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
                <button type="button" class="btn btn-primary" id="SeleccionarSucursalesMarcadasProductos">
                	<i class="fa fa-check-circle"></i> <strong>Seleccionar Sucursales</strong>
                </button>
            </div>
        </div>    
    </div>
</div>