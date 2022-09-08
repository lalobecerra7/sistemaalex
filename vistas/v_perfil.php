<br>
<div class="mb-3 mt-2">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Perfil</li>
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
			<div class="Principal">
		    <div class="row mb-5">
		      <form id="FormDatosPerfil">
            <div class="row">
            	<div class="col-md-4 offset-md-4 mb-3">
              	<div class='rounded mx-auto d-block'>
                	<div class='rounded mx-auto d-block' id="verFotoPerfil" style="width: 250px; height: 170px; cursor:pointer; border-radius:4px; overflow:hidden;"><img src="#RutaImagenPerfil#" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;"></div> 
                </div>
                <br>
                <input class="form-control" type="file" id="FotoPerfil" name="FotoPerfil" accept="image/png, image/jpeg, image/gif">
                <br>
              </div>
            </div>
            <div class="row">
            	<div class="col-md-4 offset-md-4">
              	<div class="form-floating">
                	<input type="text" class="form-control" id="CorreoActual" name="CorreoActual" placeholder="Ingresa tu correo electrónico" value="#CorreoActual#">
                  <label for="CorreoActual">Correo electrónico</label>
                </div>
              </div>
            </div>
            <div class="row text-end">
            	<div class="col-md-12">
              	<button type="submit" class="btn btn-primary" id="GuardarDatosPerfil">
                	Guardar
                </button>
              </div>
            </div>
          </form>
		  	</div>
		  </div>
		</div>
	</div>
</div>

