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

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalPagarImportes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Pagar importes de la venta <span id="folioVentaImportes"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="FormPagarImportes">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        Producto: <span id="NombreProductoImporte"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        Importes: <span id="spanImportes"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        Precio: <span id="spanPrecioImporte" class="dinero"></span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        Pagados: <span id="spanPagados"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        Total Pagados: <span id="spanTotalPagados" class="dinero"></span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        Restantes: <span id="spanRestantes"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        Total Restantes: <span id="spanTotalRestantes" class="dinero"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="CampoImportesPagados" name="CampoImportesPagados" min="1" max="" placeholder="Ingresa cuantos importes vas a pagar">
                            <label for="CampoImportesPagados">Pagar importes</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="submit" class="btn btn-primary" id="GuardarImportesPagados" attrid=""><i class="fa fa-check-circle"></i> <strong>Aceptar</strong></button>
            </div>
        </form>
    </div>
  </div>
</div> 
