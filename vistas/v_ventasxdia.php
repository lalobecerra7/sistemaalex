<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Ventas por día</li>
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
                    <a href="javascript:void(0)" class="btn btn-success" id="bExcelVentasxDia">Exportar excel <i class="fas fa-file-excel"></i></a>
				</div>
				<div class="col-md-1 text-end">
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_ventasxdia" titulo="Ventas por día"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row">
					<div class="col-md-4">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="FechaInicioReporteDia" name="FechaInicioReporteDia" placeholder="Fecha Inicio">
	                        <label>Fecha</label>
	                    </div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="FechaFinalReporteDia" name="FechaFinalReporteDia" placeholder="Fecha Inicio">
	                        <label>Fecha</label>
	                    </div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
	                        <select class="form-select" name="SucursalVentasXDia" id="SucursalVentasXDia" required>
		                      	#SucursalesVentasXDia# 
		                    </select>	
		                    <label for="SucursalVentasXDia">Sucursal</label>
	                    </div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-2 text-center">
						Total de importes
						<br>
						<span class="dinero" id="SpanTotalImportes">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de descuentos
						<br>
						<span class="dinero" id="SpanTotalDescuentos">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de Subtotal
						<br>
						<span class="dinero" id="SpanTotalSubtotal">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de impuestos
						<br>
						<span class="dinero" id="SpanTotalImpuestos">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de ventas
						<br>
						<span class="dinero" id="SpanTotalVentas">0</span>
					</div>
					<div class="col-md-2 text-center">
						Total de devoluciones
						<br>
						<span class="dinero" id="SpanTotalDevoluciones">0</span>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
				    <div class="col-12">
				        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteVentasxDia" width="100%" style="font-size: 12px;">
		                    <thead>
		                        <th>Fecha</th>
		                        <th>Sucursal</th>
		                        <th>Importes</th>
		                        <th>Descuento</th>
		                        <th>Subtotal</th>
		                        <th>Impuestos</th>
		                        <th>Ventas</th>
		                        <th>Devoluciones</th>
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

