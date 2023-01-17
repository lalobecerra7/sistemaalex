<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Importes</li>
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
                    <a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_importes" titulo="Importes"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaReporteImportes" width="100%" style="font-size: 12px;">
                    <thead>
                        <th style="width: 25%;">Cliente</th>
                        <th style="width: 25%;">Importes</th>
                        <th style="width: 15%;">Estatus</th>
                        <th style="width: 15%;" orden="No">Acciones</th>
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

<div class="modal fade" id="ModalVerProductosImporte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Importes del cliente <span id="FolioImporteVenta"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="TablaCargarProductosImporte" width="100%" style="font-size: 12px;">
                        <thead>
                            <tr>
                            	<th>Venta</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Importe</th>
                                <th>Total</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                               
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>