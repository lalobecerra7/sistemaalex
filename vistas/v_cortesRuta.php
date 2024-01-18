<div>
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
					<button type="button" class="btn btn-success" id="bNuevoCorteRuta"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarCortesRuta').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
		    <div class="row mb-5">
		      <div class="col-12">
		        <table class="table table table-hover table-bordered text-center myDataTable" id="tablaCortesRuta" width="100%" style="font-size: 12px;">
		          <thead>
		          	<th>Fecha</th>
		          	<th>Ruta</th>
		            <th>Fecha Inicio</th>
		            <th>Fecha Fin</th>
		            <th>Total</th>
		            <th>Verificado</th>
		            <th>Detalles</th>
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

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalCorteRuta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" style="font-weight: bold;">Corte De Ruta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formCorteDeRuta">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="rutasCorte" id="rutasCorte">
									<option value="">- Seleccione una opción -</option>
										#RutasCorte#
								</select>
								<label>Ruta corte</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<input type="date" class="form-control " id="FechaInicioCorte" name="FechaInicioCorte" placeholder="Fecha Inicio">
								<label>Fecha inicio</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<input type="date" class="form-control " id="FechaFinCorte" name="FechaFinCorte" placeholder="Fecha Fin">
								<label>Fecha Fin</label>
							</div>
						</div>
	       	</div>
	       	<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="selectChofer" id="selectChofer">
									<option value="">- Seleccione una opción -</option>
										#SelectChofer#
								</select>
								<label>Chofer</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="selectVehiculo" id="selectVehiculo">
									<option value="">- Seleccione una opción -</option>
										#SelectVehiculo#
								</select>
								<label>Vehiculo</label>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 mb-3">
							<button class="btn btn-lg btn-primary" type="button" id="bGenerarClientes" name="bGenerarClientes"><i class="fa-solid fa-search"></i> Generar</button>
						</div>
	       	</div>
	       	<br>
	       	<hr>
	       	<br>
	       	<div class="row mb-5 d-none" id="tablaClientesruta">
			      <div class="col-12">
			        <table class="table table table-hover table-bordered text-center myDataTable" id="tablaClientesRuta" width="100%" style="font-size: 12px;">
			          <thead>
			          	<th>Orden Ruta</th>
			          	<th>Nombre</th>
			            <th>Domicilio</th>
			            <th>Total</th>
			            <th orden="No">Acciones</th>
			          </thead>
			          <tbody> 

			          </tbody>
			        </table>
		      	</div>
		    	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="bGuardarCorte"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
			<button type="button" class="btn" id="cerrarModalCorteRuta"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalCorteClientes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Clientes Detalles De Ruta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="formCorteClientes">
				<div class="modal-body">
					<div class="row">
						<div class="col-9" id="lista-ventas-cliente">
							
						</div>
						<div class="col-3 sticky-top mx-auto" id="agregarVentasContainer">
							<button class="btn btn-lg btn-outline-primary m-0 w-100" id="agregarVentaACorte" type="button"><i class="fa-solid fa-plus"></i> Añadir venta</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
				</div>
			</form>
		</div>
	</div>
</div>


<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalAgregarVenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Clientes Detalles De Ruta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="formCorteClientes">
				<div class="modal-body">
					<div class="row px-3">
						<div class="input-group mb-3">
							<input class="form-control" name="searched" id="searchedForAdd" type="search" placeholder="Buscar por ID de la venta">
							<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
						</div>
					</div>
					<div class="row">
						<div class="col-12 " id="lista-ventas-cliente-excluidas">
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
				</div>
			</form>
		</div>
	</div>
</div>