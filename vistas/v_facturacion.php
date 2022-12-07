<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Facturación</li>
			  </ol>
			</nav>
		</div>
	</div>
	<br>
	<div id="content" class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-10">
					<h1 style="font-weight: bold;" id="vistaTitulo"></h1>
				</div>
				<div class="col-2">
					<a href="javascript:void(0)" class="btn btn-light btn-reload cargarVista" carga="v_facturacion" titulo="Facturación 4.0"><i class="fa fa-retweet"></i></a>
				</div>
			</div>
			<br>
			<form id="formFacturacion" class="row">
				<div class="col-md-6 mb-3">
				    <div class="form-floating">
				        <input type="text" class="form-control" id="rfcFacturacion" name="rfcFacturacion" placeholder="RFC" value="#rfc#">
				        <label>RFC</label>
				    </div>
			    </div>
			    <div class="col-md-6 mb-3">
				    <div class="form-floating">
				        <input type="text" class="form-control" id="nombreFacturacion" name="nombreFacturacion" placeholder="Nombre/Razón social" value="#nombre#">
				        <label>Nombre / Razón social</label>
				    </div>
			    </div>
			    <div class="col-md-12 mb-3">
					<div class="form-floating mb-3">
						<select class="form-select" name="regimenFacturacion" id="regimenFacturacion">
							<option value="">- Seleccione una opción -</option>
							<option value="601">601 - General de Ley Personas Morales</option>
							<option value="603">603 - Personas Morales con Fines no Lucrativos</option>
							<option value="605">605 - Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
							<option value="606">606 - Arrendamiento</option>
							<option value="607">607 - Régimen de Enajenación o Adquisición de Bienes</option>
							<option value="608">608 - Demás ingresos</option>
							<option value="610">610 - Residentes en el Extranjero sin Establecimiento Permanente en México</option>
							<option value="611">611 - Ingresos por Dividendos (socios y accionistas)</option>
							<option value="612">612 - Personas Físicas con Actividades Empresariales y Profesionales</option>
							<option value="614">614 - Ingresos por intereses</option>
							<option value="615">615 - Régimen de los ingresos por obtención de premios</option>
							<option value="616">616 - Sin obligaciones fiscales</option>
							<option value="620">620 - Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
							<option value="621">621 - Incorporación Fiscal</option>
							<option value="622">622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
							<option value="623">623 - Opcional para Grupos de Sociedades</option>
							<option value="624">624 - Coordinados</option>
							<option value="625">625 - Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas</option>
							<option value="626">626 - Régimen Simplificado de Confianza</option>
						</select>
						<label>Régimen Fiscal</label>
					</div>
		        </div>
		        <div class="col-md-6 mb-3">
					<label class="form-label">Certificado (.cer)</label>
					<input type="file" class="form-control" id="certificadoFacturacion" name="certificadoFacturacion">
				</div>
				<div class="col-md-6 mb-3">
					<label class="form-label">Key (.key)</label>
					<input type="file" class="form-control" id="keyFacturacion" name="keyFacturacion">
				</div>
				<div class="col-md-6 mb-3">
				    <div class="form-floating">
				        <input type="password" class="form-control" id="contraFacturacion" name="contraFacturacion" placeholder="Contraseñal">
				        <label>Contraseña de los certificados</label>
				    </div>
			    </div>
			    <div class="col-12 d-grid mb-3">
			    	<button type="submit" class="btn btn-success btn-lg">Guardar <i class="fas fa-save"></i></button>
			    </div>
			</form>
		</div>
	</div>
</div>

