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
					<button type="button" class="btn btn-success" id="bNuevaPromocion"><i class="fa fa-file"></i> Nuevo</button>
					<a href="javascript:void(0)" class="btn btn-light btn-reload" onclick="$('#cargarPromociones').trigger('click')"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<div class="Principal">
				<div class="row mb-5">
					<div class="col-12">
						<table class="table table table-hover table-bordered text-center myDataTable" id="tablaPromociones" width="100%" style="font-size: 12px;">
							<thead>
								<th>Fecha</th>
								<th>Nombre</th>
								<th>Tipo. Promoción</th>
								<th orden="No">Promoción</th>
								<th orden="No">Sucursales</th>
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
<div class="modal fade" id="modalPromociones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-inverse bd-inverse-darken">
				<h5 class="modal-title" style="font-weight: bold;">Nueva Promoción</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="formPromociones">
				<div class="modal-body">
					<div class="row">
						<div class="col-4 mb-3">
							<div class="form-floating">
								<input type="date" class="form-control" id="FechaPromocion" name="FechaPromocion" placeholder="Fecha de la promoción">
								<label>Fecha de la promoción</label>
							</div>
						</div>
						<div class="col-8 mb-3">
							<div class="form-floating">
								<select class="form-select" id="TipoPromocion" name="TipoPromocion">
									<option value="" selected> - Seleccione una opción - </option>
									<option value="CantidadRegalo">Por X piezas recibe X piezas de regalo</option>
									<option value="ProductoRegalo">Por x piezas recibe X producto de regalo</option>
									<option value="ComboProductoRegalo">Producto 1 + Producto 2 recibe X producto de regalo</option>
									<!-- <option value="ComboPrecioEspecial">Producto 1 + Producto 2 recibe precio especial por los 2 productos</option> -->
								</select>
								<label for="TipoPromocion">Tipo de promoción</label>
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="NombrePromocion" name="NombrePromocion" placeholder="Nombre de la promoción">
								<label>Nombre de la promoción</label>
							</div>
						</div>
						<div class="col-6 mb-3">
							<div class="form-floating">
								<input type="text" class="form-control" id="DescripcionPromocion" name="DescripcionPromocion" placeholder="Descripción de la promoción">
								<label>Descripción de la promoción</label>
							</div>
						</div>
						<div class="col-md-6 d-grid gap-2">
							<button type="button" style="height: 54px;" class="btn btn-outline-secondary" id="CargarSucursalesModalPromociones">
								<i class="fas fa-search"></i> Sucursales donde aplica
							</button>
						</div>
						<div class="col-md-6">
							<span>
								Sucursales seleccionadas:
							</span>
							<span id="MostrarSucursalesPromociones">No has seleccionado sucursales</span>
						</div>
						<h3 class="mt-3">Condiciones de la Promoción</h3>
						<div class="row" id="Padre">
							<div class="col-12">
								<table class="table table-hover table-bordered text-center" id="tablaGenerarPromocion" width="100%" style="font-size: 12px;">
									<thead>
										<th style="width: 25%;" class="columnas oculto CantidadRegalo ProductoRegalo ComboProductoRegalo ComboPrecioEspecial" style="width: 150px;">Producto en promoción</th><!-- Producto y presentacion para aplicar promocion  -->
										<th style="width: 25%;" class="columnas oculto ComboProductoRegalo ComboPrecioEspecial" style="width: 150px;">Producto en conjunto</th>
										<th class="columnas oculto CantidadRegalo ProductoRegalo">Cantidad</th>
										<th class="columnas oculto CantidadRegalo">Cantidad Regalo</th>
										<th style="width: 25%;" class="columnas oculto ProductoRegalo ComboProductoRegalo">Producto Regalo</th>
										<th class="columnas oculto ComboPrecioEspecial">Precio Combo</th>
									</thead>
									<tbody class="TbodyPromociones"> 
										<tr>
											<td class="columnas CantidadRegalo ProductoRegalo ComboProductoRegalo ComboPrecioEspecial oculto">
												<div class="form-floating mb-3">
													<select class="form-select" name="ProductosPromocion" id="ProductosPromocion" style="width: 100%">
													</select>
													<label>Producto con promoción</label>
												</div>
												<div class="form-floating mb-3">
													<select class="form-select" name="PresentacionesProductoPromocion" id="PresentacionesProductoPromocion" style="width: 100%">
													</select>
													<label>Presentación</label>
												</div>
											</td>
											<td class="columnas ComboProductoRegalo ComboPrecioEspecial oculto">
												<div class="form-floating mb-3">
													<select class="form-select" name="ProductosPromocion2" id="ProductosPromocion2" style="width: 100%">
													</select>
													<label>Producto con promoción</label>
												</div>
												<div class="form-floating mb-3">
													<select class="form-select" name="PresentacionesProductoPromocion2" id="PresentacionesProductoPromocion2" style="width: 100%">
													</select>
													<label>Presentación</label>
												</div>
											</td>
											<td class="columnas CantidadRegalo ProductoRegalo oculto">
												<div class="form-floating mb-3">
													<input type="number" step="any" min="0" class="form-control" id="CantidadCondicionPromocion" name="CantidadCondicionPromocion" placeholder="Cantidad de piezas">
													<label>Cantidad para que aplique promoción</label>
												</div>
											</td>
											<td class="columnas CantidadRegalo oculto">
												<div class="form-floating mb-3">
													<select class="form-select" name="PresentacionProductoCantidadRegalar" id="PresentacionProductoCantidadRegalar" style="width: 100%">
													</select>
													<label>Presentación a regalar</label>
												</div>
												<div class="form-floating mb-3">
													<input type="number" step="any" min="0" class="form-control" id="CantidadRegalarPromocion" name="CantidadRegalarPromocion" placeholder="Cantidad de piezas">
													<label>Cantidad para regalar</label>
												</div>
											</td>
											<td class="columnas ProductoRegalo ComboProductoRegalo oculto">
												<div class="form-floating mb-3">
													<select class="form-select" name="ProductosPromocionRegalo" id="ProductosPromocionRegalo" style="width: 100%">
													</select>
													<label>Producto de regalo</label>
												</div>
												<div class="form-floating mb-3">
													<select class="form-select" name="PresentacionesProductoPromocionRegalo" id="PresentacionesProductoPromocionRegalo" style="width: 100%">
													</select>
													<label>Presentación</label>
												</div>
											</td>
											<td class="columnas ComboPrecioEspecial oculto">
												<div class="form-floating mb-3">
													<input type="number" step="any" min="0" class="form-control" id="PrecioPromocionCombo" name="PrecioPromocionCombo" placeholder="Cantidad de piezas">
													<label>Precio Especial</label>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="bGuardarPromocion"><i class="fa fa-check-circle"></i> <strong>Guardar</strong></button>
					<button type="button" class="btn" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <strong>Cancelar</strong></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!--/////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalSucursalesPromociones" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Sucursales</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row table-responsive">
					<div class="col-12" id="divTablaProductos">
						<table class="table text-center myDataTable" id="TablaSucursalesPromociones" width="100%">
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
				<button type="button" class="btn btn-primary" id="SeleccionarSucursalesMarcadasPromociones">
					<i class="fa fa-check-circle"></i> <strong>Seleccionar Sucursales</strong>
				</button>
			</div>
		</div>    
	</div>
</div>