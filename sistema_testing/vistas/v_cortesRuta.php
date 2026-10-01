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
				<!-- <div class="col-4 text-start">
					<div class="form-floating">
                        <select class="form-select" name="SucursalCorteRuta" id="SucursalCorteRuta" required="">
							#OpcionesFiltroSucursales#
                        </select>   
                        <label for="SucursalCorteRuta">Sucursal</label>
                    </div>
				</div>
				<div class="col-4 text-start">
					<div class="form-floating">
                        <select class="form-select" name="RutaCortesRuta" id="RutaCortesRuta" required="">
							#OpcionesFiltroRutas#
						</select>   
                        <label for="RutaCortesRuta">Rutas</label>
                    </div>
				</div> -->
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
		            <th orden="No">Concentrado</th>
		            <th orden="No">Cubetas</th>
		            <th>Estatus</th>
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
	    <div class="modal-body row">
	    	<form id="formCorteDeRuta" class="col-12">
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
							<div class="form-floating mb-3">
								<select class="form-select" name="selectSucursal" id="selectSucursal" aria-describedby="selectSucursal-error" aria-invalid="false">
									<option value="">- Seleccione una opción -</option>
									#selectSucursal#	
								</select>
								<label>Sucursal</label>
							</div>
						</div>
		      </div>
				</form>
	      <br>
	      <hr>
	      <br>
	      <div class="col-12 mb-3">
	      	<div class="row">
			      <div class="col-12 table-responsive">
			        <table class="table table table-hover table-bordered text-center myDataTable" id="tablaVentasRuta" width="100%" style="font-size: 12px;">
			          <thead>
			          	<th>Orden Ruta</th>
			          	<th>Folio</th>
			          	<th>Cliente</th>
			            <th>Domicilio</th>
			            <th>Total</th>
			            <th>Fecha Registro</th>
			            <th>Estatus</th>
			            <th orden="No">Acciones</th>
			          </thead>
			          <tbody> 

			          </tbody>
			          <tfoot>
			          	
			          </tfoot>
			        </table>
		      	</div>
		    	</div>
		    </div>
		    <div class="col-12">
		    	<hr>
		    	<div class="row mx-2 justify-content-between verBalancesRuta">
						<div class="col-5">
							<div class="row text-start">
								<h5><b>Balance de corte</b></h5>
							</div>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Total</p>
									<p class="p-0 m-0 fs-5 dinero" id="total_corte_bruto">0</p>
								</div>
							</div>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Gastos</p>
									<p class="p-0 m-0 fs-5 dinero" id="total_gastos_corte">0</p>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Total neto</p>
									<p class="p-0 m-0 fs-5 dinero" id="total_neto_corte">0</p>
								</div>
							</div>
							<div class="row">
								<div class="d-flex justify-content-between">
									<p class="p-0 m-0 fs-5">Dinero recaudado</p>
									<div id="contenedor_recaudado">
										<input type="number" class="form-control" step="any" value="0" id="montoCorte" name="montoCorte">
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="d-flex gap-3 justify-content-center">
									<p class="p-0 m-0 fs-5">Balance:</p>
									<p class="p-0 m-0 fs-5 col-2 dinero" id="balance_final">0</p>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="row text-end">
								<h5><b>Gastos del corte</b></h5>
							</div>
							<div class="row align-items-end">
								<form id="gastosFormulario" class="row align-items-end">
									<div class="col-md-6 col-sm-12 mb-3">
										<label>Descripcion</label>
										<input type="text" class="form-control" id="gastoDescripcion" placeholder="Descripcion" required>
									</div>
									<div class="col-md-3 col-sm-12 mb-3">
										<label>Monto</label>
										<input type="number" step="any" class="form-control" id="gastoCoste" required>
									</div>
									<div class="col-3 mb-3 justify-content-center align-items-center">
										<button type="submit" class="btn btn-sm btn-primary py-2" id="añadirGastoACorte">
											<i class="fa-solid fa-plus"></i> Añadir Gasto
										</button>
									</div>
								</form>
							</div>
							<div class="row mb-3">
								<div class="col-12 table-responsive">
									<table class="table table table-hover table-bordered text-center" id="tablaCostesRuta" width="100%" style="font-size: 12px;">
										<thead>
											<th>Descripcion</th>
											<th>Monto</th>
											<th orden="No">Acciones</th>
										</thead>
										<tbody>
										
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row verBalancesRuta">
						<hr>
						<div class="col-12 d-flex justify-content-center gap-2">
							<button class="btn btn-info" type="button" id="cerrarCorteRuta"><i class="fa-solid fa-file-export" style="margin-right: 10px;"></i> <strong>Cerrar Corte</strong></button>
							<!-- <button class="btn btn-success d-none" type="button" id="reabrirCorteRuta"><i class="fa-solid fa-file-import" style="margin-right: 10px;"></i> <strong>Reabrir Corte</strong></button>--> 
							<!--#botonSubirArchivo#
							<a class="d-none" data-fancybox id="downloadTheFile">
								<button class="btn btn-danger" type="button"><i class="fa-solid fa-file-contract" style="margin-right: 10px;"></i> <strong>Ver archivo</strong></button>
							</a>
							<form style="display: none;" id="imageUploadForm">
								<input type="file" name="imageInput" id="imageInput" accept="image/*,application/pdf">
							</form>-->
						</div>
					</div>
		    </div>
		    <div class="col-12 oculto verExtras">
		    	<div class="row">
		    		<div class="col-12 text-end">
		    			<button type="button" class="btn btn-primary btn-sm" id="bAgregarClienteExtra">Agregar Cliente <i class="fas fa-plus"></i></button>
		    		</div>
		    	</div>
		    	<br>
		    	<div class="row">
		    		<div class="col-12 table-responsive">
		    			<table class="table table-bordered table-hover table-striped text-center" width="100%" style="font-size: 12px">
				    		<thead>
				    			<tr>
				    				<th>Cliente Extra</th>
				    				<th>Orden</th>
				    				<th>Acciones</th>
				    			</tr>
				    		</thead>
				    		<tbody id="verClientesExtras">
				    			
				    		</tbody>
				    	</table>
		    		</div>
		    	</div>
		    </div>
		    <div class="col-12 oculto verExtras">
		    	<div class="row">
		    		<div class="col-12 text-end">
		    			<button type="button" class="btn btn-primary btn-sm" id="bQuitarClienteRuta">Quitar Cliente <i class="fas fa-minus"></i></button>
		    		</div>
		    	</div>
		    	<br>
		    	<div class="row">
		    		<div class="col-12 table-responsive">
		    			<table class="table table-bordered table-hover table-striped text-center" width="100%" style="font-size: 12px">
				    		<thead>
				    			<tr>
				    				<th>Cliente</th>
				    				<th>Acciones</th>
				    			</tr>
				    		</thead>
				    		<tbody id="verClientesQuitarRuta">
				    			
				    		</tbody>
				    	</table>
		    		</div>
		    	</div>
		    </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" id="cerrarModalCorteRuta"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
				<button type="button" class="btn btn-primary" id="bGuardarCorte"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
			</div>
    </div>
  </div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalVerificar" tabindex="-1">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Verificar corte de ruta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<form id="formVerificar">
							<div class="row mb-3 sm-3">
								<div class="col-9">
								  <input type="text" class="form-control" name="codigoProducto" id="codigoProducto" type="text" placeholder="Codigo...">
								</div>
								<div class="col-3">
								  <button type="submit" class="btn btn-outline-dark"><i class="fa-solid fa-magnifying-glass"></i></button>
								</div>	
							</div>
						</form>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12" id="verDatosVentaCorte">
							
					</div>
				</div>
				<br>
				<div class="row">
					<div class="mb-3 col-12 table-responsive">
						<table class="table text-center" width="100%" style="font-size: 17px">
							<thead>
							  <tr>
							    <th>Codigo</th>
							    <th>Producto</th>
							    <th>Cantidad</th>
							    <th>Verificados</th>
							    <th>Estatus</th>
							  </tr>
							</thead>
							<tbody id="tbodyVerificar">
							    
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			</div>	
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalCubeta" tabindex="-1">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Verificar cubetas del corte</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<br>
				<div class="row">
					<div class="col-12">
						<form id="formVerificarCubeta">
							<div class="row mb-3 sm-3">
							  <div class="col-7">
							  	<input type="text" class="form-control" name="codigoProductoCubeta" id="codigoProductoCubeta" type="text" placeholder="Codigo...">
							  </div>
							  <div class="col-3">
							  	<button type="submit" class="btn btn-outline-dark"><i class="fa-solid fa-magnifying-glass"></i></button>
							  </div>	
							  <div class="col-2">
							  	<button type="button" class="btn btn-outline-dark" id="bOrdenCubetas"><i class="fas fa-up-long"></i></button>
							  </div>	
							</div>
						</form>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="mb-3 col-12" id="tbodyCubetas">

					</div>
			 	</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			</div>
		</div>
	</div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalExtra" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cliente extra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formExtra">
      <div class="modal-body">
      	<div class="input-group mb-3">
				  <input type="text" class="form-control" name="clienteExtra" id="clienteExtra" placeholder="Cliente" readonly>
				  <button type="button" class="btn btn-outline-secondary" id="bBuscarClienteExtra"><i class="fas fa-search"></i></button>
				</div>
      	<div class="form-floating mb-3">
				  <input type="number" class="form-control" name="ordenExtra" id="ordenExtra" placeholder="name@example.com">
				  <label for="ordenExtra">Orden</label>
				</div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Agregar <i class="fas fa-plus"></i></button>
      </div>
      </form>
    </div>
  </div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalClientesExtra" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Clientes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-12 table-responsive">
      			<table class="table table table-hover table-bordered text-center myDataTable" id="tablaClientesExtra" width="100%" style="font-size: 12px;">
			        <thead>
			          <th>Nombre</th>
			          <th>Ruta</th>
			          <th>Orden</th>
			        </thead>
			        <tbody> 

			        </tbody>
			      </table>
      		</div> 
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--///////////////////////////////////////////////////////-->
<div class="modal fade" id="modalConcentradoRuta" tabindex="-1">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Concentrado</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 text-end">
						<a class="btn btn-sm btn-info" id="bImprimirVerificacion" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="mb-3 col-12 table-responsive">
						<table class="table text-center" width="100%" style="font-size: 17px">
							<thead>
							  <tr>
							    <th>Codigo</th>
							    <th>Producto</th>
							    <th>Cantidad</th>
							    <th>Verificados</th>
							    <th>Estatus</th>
							  </tr>
							</thead>
							<tbody id="tbodyConcentradoRuta">
							    
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cerrar</strong></button>
			</div>	
		</div>
	</div>
</div>