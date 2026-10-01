<div style="margin-top: -22px;">
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
					<button type="button" class="btn btn-success" id="bontonNuevoGasto" data-bs-toggle="modal" data-bs-target="#ModalGastos"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarGastos').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-3">
					<div class="form-floating">
            <input type="date" class="form-control" id="FechaInicioGastos" name="FechaInicioGastos" placeholder="Fecha Inicio">
          	<label>Fecha</label>
          </div>
				</div>
				<div class="col-md-3">
					<div class="form-floating">
            <input type="date" class="form-control" id="FechaFinalGastos" name="FechaFinalGastos" placeholder="Fecha Final">
            <label>Fecha</label>
          </div>
				</div>
				<div class="col-md-3 d-grid gap-2">
          <button type="button" style="height: 54px;" class="btn btn-outline-secondary" id="CargarSucursalesModalGastos">
            <i class="fas fa-search"></i> Sucursales
          </button>
				</div>
				<div class="col-md-3">
					<span>
						Sucursales seleccionadas:
					</span>
					<span id="MostrarSucursalesSeleccionadasGastos">No has seleccionado sucursales</span>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row mb-5">
					<div class="col-12">
						<table class="table table table-hover table-bordered text-center myDataTable" id="TablaGastos" width="100%" style="font-size: 12px;">
		        	<thead>
		          	<th style="width: 20%;">Fecha</th>
		            <th style="width: 20%;">Sucursal</th>
								<th style="width: 15%;">Descripción</th>
		            <th style="width: 15%;">Monto</th>
		            <th style="width: 20%;" orden="No">Evidencias</th>
		            <th style="width: 10%;" orden="No">Acciones</th>
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


<!--////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalGastos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-inverse bd-inverse-darken">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;"><span id="TituloModalGastos"></span> gasto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="FormNuevoGasto">
	      <div class="modal-body">
	       	<div class="row">
	       		<div class="col-md-4 col-sm-12 mb-3">
		          <div class="form-floating">
		          	<input type="date" class="form-control" id="FechaGasto" name="FechaGasto" placeholder="Ingresa el nombre de la sucursal">
		            <label for="FechaGasto">Fecha del gasto</label>
		          </div>
		        </div>
						<div class="col-md-4 col-sm-12 mb-3">
							<div class="form-floating mb-3">
								<select class="form-select" name="FormaPagoGasto" id="FormaPagoGasto" >
										<option value="Efectivo">Efectivo</option>
										<option value="Deposito">Deposito</option>
										<option value="Cheque">Cheque</option>
										<option value="Transferencia">Transferencia</option>
										<option value="TDebitoCredito">Pago con tarjeta de crédito o debito</option>
								</select>
								<label for="FormaPagoGasto">Forma de pago</label>
							</div>
		       </div> 
		        <div class="col-md-4 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="number" step="any" class="form-control" id="MontoGasto" name="MontoGasto" placeholder="Ingresa el monto del gasto">
		            <label for="MontoGasto">Monto</label>
		          </div>
		        </div>
		        <div class="col-md-6 col-sm-12 mb-3">
		          <div class="form-floating">
		            <input type="text" class="form-control" id="DescripcionGasto" name="DescripcionGasto" placeholder="Ingresa el concepto del gasto">
		           	<label for="DescripcionGasto">Descripción</label>
		          </div>
		        </div>
						<div class="col-md-6 col-sm-12 mb-3">
		        	<div class="form-floating">
		          	<input type="file" class="form-control" id="ArchivoGasto" name="ArchivoGasto" placeholder="Ingresa la evidencia del gasto">
		            <label for="ArchivoGasto">Evidencia</label>
		          </div>
		        </div>
	       	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-primary" id="bGuardarGasto" attrid="" tipo="insertar"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
	      </div>
  		</form>
    </div>
  </div>
</div>


<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalSucursalesGastos" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Sucursales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div class="col-12" id="divTablaProductos">
                        <table class="table text-center myDataTable" id="TablaSucursalesGastos" width="100%">
                            <thead>
                                <tr>
                                    <th orden="No">Seleccionar</th>
                                    <th>Sucursal</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>
                </div>    
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn BotonDatosPrecio" producto="" presentacion=""  data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
                <button type="button" class="btn btn-primary" id="SeleccionarSucursalesMarcadasGastos">
                	<i class="fa fa-check-circle"></i> <strong>Seleccionar Sucursales</strong>
                </button>
            </div>
        </div>    
    </div>
</div>