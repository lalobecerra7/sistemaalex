<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Reporte Compras</li>
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
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_reporteCompras" titulo="Reporte compras"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row">
					<div class="col-md-6">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="fechaInicioCompra" name="fechaInicioCompra" placeholder="Fecha Inicio">
	                        <label>Fecha inicio</label>
	                    </div>
					</div>
					<div class="col-md-6">
						<div class="form-floating">
	                        <input type="date" class="form-control" id="fechaFinCompra" name="fechaFinCompra" placeholder="Fecha Inicio">
	                        <label>Fecha Fin</label>
	                    </div>
					</div>
				</div>
				<br>
			    <div class="row mb-5">
			     	<div class="col-12" id="chartdivCompras" style="height: 600px;">
			        
			      	</div>
			    </div>
		  	</div>
		</div>
	</div>
</div>

