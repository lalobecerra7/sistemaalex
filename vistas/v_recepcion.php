<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item" aria-current="page">Productos</li>
			    <li class="breadcrumb-item active" aria-current="page">Recepción</li>
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
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_recepcion" titulo="Recepción"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12 table-responsive">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="tablaRecepcion" width="100%" style="font-size: 12px;">
		          <thead>
		          	<th>Fecha Registro</th>
		            <th>Orden</th>
		            <th>Proveedor</th>
		            <th>Fecha Programada</th>
		            <th>Fecha Recepción</th>
		            <th>Concentrado</th>
		            <th>Estatus Orden</th>
		            <th orden="No">Acciones</th>
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

<!--////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="modalRecepcion" tabindex="-1" data-bs-focus="false">
	<div class="modal-dialog modal-xxl modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
	      <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Recepción</h5>
	      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     	</div>
      	<div class="modal-body">
		      <form class="row" id="formRecepcion">
		       	<div class="col-md-6 mb-3">
              <div class="form-floating">
                <input type="datetime-local"  class="form-control" id="fechaProgRece" name="fechaProgRece" placeholder="Fecha Programada">
                <label for="fechaProgRece">Fecha Programada</label>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="form-floating">
                <input type="datetime-local" class="form-control" id="fechaRecepRece" name="fechaRecepRece" placeholder="Fecha Recepción">
                <label for="fechaRecepRece">Fecha Recepción</label>
              </div>
            </div>		
					</form>
					<br>
					<hr>
					<form id="formAgreProdRece" class="row">
						<div class="col-md-6 col-sm-12 mb-3">
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-barcode"></i></span>
                <input type="text" class="form-control" id="codProdRece" name="codProdRece" placeholder="Código del producto" required>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 d-grid mb-3">
              <button type="submit" class="btn btn-outline-danger" id="bAgregarProdRece">Agregar producto <i class="fas fa-check"></i></button>
            </div>    
					</form>
					<br>
					<div class="row">
						<div class="col-12 table-responsive">
							<table class="table table-striped table-bordered text-center" id="tablaConcentradoProdRece" width="100%" style="font-size: 12px;">
								<thead>
									<tr>
										<th>Código</th>
										<th>Producto</th>
										<th>Lote</th>
										<th>Caducidad</th>
										<th>Cantidad Orden</th>
										<th>Cantidad Recibida</th>
										<th>Estatus</th>
										<th>Observaciones</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
			  </div>
			  <div class="modal-footer">
			    <button type="submit" class="btn btn-primary" id="bGuardarRecepcion"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			  </div>
    </div>
  </div>
</div> 