<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Facturas emitidas</li>
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
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelReporteFacturasEmitidas">Exportar excel <i class="fas fa-file-excel"></i></a>
				</div>
				<div class="col-md-1 text-end">
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_reporteFacturasEmitidas" titulo="Facturas emitidas"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row">
					<div class="col-md-4">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="FechaInicioReporteFacturasEmitidas" name="FechaInicioReporteFacturasEmitidas" placeholder="Fecha Inicio">
	                        <label>Fecha</label>
	                    </div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="FechaFinalReporteFacturasEmitidas" name="FechaFinalReporteFacturasEmitidas" placeholder="Fecha Final">
	                        <label>Fecha</label>
	                    </div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
	                        <select class="form-select" name="SucursalFacturasEmitidas" id="SucursalFacturasEmitidas" required>
		                      	#SucursalesFacturasEmitidas# 
		                    </select>	
		                    <label for="SucursalFacturasEmitidas">Sucursal</label>
	                    </div>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
				    <div class="col-12">
				        <table class="table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteFacturasEmitidas" width="100%" style="font-size: 12px;">
		                    <thead>
		                        <th>Folio</th>
		                        <th>UUID</th>
		                        <th>Fecha</th>
		                        <th>RFC</th>
		                        <th>Cliente</th>
		                        <th>Importe</th>
		                        <th>Divisa</th>
		                        <th>Estatus</th>
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

