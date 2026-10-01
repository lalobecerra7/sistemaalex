<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item" aria-current="page">Productos</li>
			    <li class="breadcrumb-item active" aria-current="page">Lotes</li>
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
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_lotes" titulo="Lotes"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-striped table-bordered text-center myDataTable" id="tablaLotes" width="100%" style="font-size: 12px;">
		          <thead>
		          	<th>Fecha Registro</th>
		            <th>Código</th>
		            <th>Nombre</th>
		            <th>Caducidad</th>
		            <th>Producto</th>
		            <th>Cantidad</th>
		            <th>Sucursal</th>
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
<div class="modal fade" id="modalLote" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 9999 !important;">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
	      <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Conversiones</h5>
	      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     	</div>
      <form id="formLotes">
		    <div class="modal-body">
		      <div class="row">
		       	<div class="col-12 mb-3">
              <div class="form-floating">
                <input type="date"  class="form-control" id="fechaCadLote" name="fechaCadLote" placeholder="Fecha de Caducidad">
                <label for="fechaCadLote">Fecha de Caducidad</label>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-floating">
                <input type="text" class="form-control" id="nombreLote" name="nombreLote" placeholder="Nombre Lote">
                <label for="nombreLote">Nombre</label>
              </div>
            </div>	
            <div class="col-12 mb-3">
              <div class="form-floating">
                <input type="number" min='0' step="any" class="form-control" id="cantidadLote" name="cantidadLote" placeholder="Cantidad de producto">
                <label for="cantidadLote">Cantidad</label>
              </div>
            </div>	
					</div>
			  </div>
			  <div class="modal-footer">
			    <button type="submit" class="btn btn-primary" id="bGuardarLote"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			  </div>
  		</form>
    </div>
  </div>
</div> 