<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Reporte de Importes</li>
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
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelReporteImportes">Exportar excel <i class="fas fa-file-excel"></i></a>
				</div>
				<div class="col-md-1 text-end">
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_reporteImportes" titulo="Reporte de Importes"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row">
					<div class="col-md-3">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="FechaInicioReporteImportes" name="FechaInicioReporteImportes" placeholder="Fecha Inicio">
	                        <label>Fecha</label>
	                    </div>
					</div>
					<div class="col-md-3">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="FechaFinalReporteImportes" name="FechaFinalReporteImportes" placeholder="Fecha Inicio">
	                        <label>Fecha</label>
	                    </div>
					</div>
					<div class="col-md-3 d-grid gap-2">
		                <button type="button" style="height: 54px;" class="btn btn-outline-secondary" id="CargarSucursalesModalImportes">
	                        <i class="fas fa-search"></i> Sucursales
	                    </button>
					</div>
					<div class="col-md-3">
						<span>
							Sucursales seleccionadas:
						</span>
						<span id="MostrarSucursalesSeleccionadasImportes">No has seleccionado sucursales</span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-2 text-center">
						Total de Importes
						<br>
						<br>
						<span style="font-weight: bold;" id="SpanTotalImportes">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de Pagados
						<br><br>
						<span style="font-weight: bold;" id="SpanTotalImpoPagados">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de Pendientes
						<br><br>
						<span style="font-weight: bold;" id="SpanTotalImpoPendientes">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de Importes PVC
						<br>
						<span style="font-weight: bold;" id="SpanTotalImportesPVC">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de Pagados PVC
						<br><br>
						<span style="font-weight: bold;" id="SpanTotalImpoPagadosPVC">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de Pendientes PVC
						<br>
						<span style="font-weight: bold;" id="SpanTotalImpoPendientesPVC">0</span>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
				    <div class="col-12">
				        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteDeImportes" width="100%" style="font-size: 12px;">
		                    <thead>
		                        <th>Cliente</th>
		                        <th orden="No">Importes</th>
		                        <th orden="No">Pagados</th>
		                        <th orden="No">Pendientes</th>
		                        <th orden="No">Cantidad Botes PVC 5L</th>
		                        <th orden="No">Botes PVC 5L pagados</th>
		                        <th orden="No">Botes PVC 5L pendientes</th>
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
		                            <td></td>
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
<div class="modal fade" id="ModalSucursalesImportes" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Sucursales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div class="col-12" id="divTablaProductos">
                        <table class="table text-center myDataTable" id="TablaSucursalesImportes" width="100%">
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
                <button type="button" class="btn btn-primary" id="SeleccionarSucursalesMarcadasImportes">
                	<i class="fa fa-check-circle"></i> <strong>Seleccionar Sucursales</strong>
                </button>
            </div>
        </div>    
    </div>
</div>