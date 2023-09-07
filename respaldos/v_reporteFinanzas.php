<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Reporte Finanzas</li>
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
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_reporteFinanzas" titulo="Reporte finanzas"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row">
					<div class="col-md-6">
						<div class="form-floating">
	                        <input type="month" class="form-control" id="fechaInicioFinanzas" name="fechaInicioFinanzas" placeholder="Fecha Inicio">
	                        <label>Fecha inicio</label>
	                    </div>
					</div>
					<div class="col-md-6">
						<div class="form-floating">
	                        <input type="month" class="form-control" id="fechaFinFinanzas" name="fechaFinFinanzas" placeholder="Fecha Inicio">
	                        <label>Fecha Fin</label>
	                    </div>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
			     	<div class="col-12 table-responsive">
			        	<table class="table table-hover table-striped table-bordered text-center" width="100%">
			        		<thead>
			        			<th>Mes</th>
			        			<th>Compras</th>
			        			<th>Ventas</th>
			        			<th>Diferencia</th>
			        		</thead>
			        		<tbody id="chartdivFinanzas">
			        			
			        		</tbody>
			        		<tfoot>
			        			<th>Totales:</th>
			        			<th><h5 class="dinero" id="totalCompras"></h5></th>
			        			<th><h5 class="dinero" id="totalVentas"></h5></th>
			        			<th><h5 class="dinero" id="totalDiferencia"></h5></th>
			        		</tfoot>
			        	</table>
			      	</div>
			    </div>
		  	</div>
		</div>
	</div>
</div>

