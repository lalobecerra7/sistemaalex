<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
              	<div class="row row-cols-auto justify-content-end">
                    <div class="col">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalNuevoNegocio" id="NuevoNegocio">Nuevo negocio <i class="fas fa-edit"></i></button>
                    </div>
                  	<div class="col">
                        <button type="button" class="btn btn-light"  id="recargarUsuarios" onclick="$('#cargaClientes').trigger('click');">Recargar <i class="fas fa-sync-alt"></i></button>
                  	</div>
              	</div>
              	<br>
              	<div class="row">
                  	<div class="col-12 table-responsive" style="font-size: 13px; overflow: scroll;">
                    	<table class="table table-hover table-bordered table-striped text-center Datatable tablaDatatable" id="tablaNegocios" width="100%">
                          	<thead>
                              	<tr>
                                	<th style="width: 20%">Negocio</th>
                                    <th style="width: 25%">Contacto</th>
                  					<th style="width: 25%">Dirección</th>
                  					<th>Estatus del negocio</th>
                                    <th>Acciones</th>
                				</tr>
                          	</thead>
                      	</table>
                  </div>
              	</div>
            </div>
        </div>
    </div>
</div>
<br>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalDetallesNegocio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Negocio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <input type="hidden" name="hiddenID" id="hiddenID">
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <a id="hrefFotoNegocio" data-fancybox="images">
                            <div id="DivFotoNegocio" style="width: 150px; height: 150px; border-radius: 50%; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer;">
                            </div>
                        </a>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="color: #000; font-size: 35px; font-weight: bold;" id="NombreNegocioSpan"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="color: #000;">
                                Clasificación: <span id="spanClasificacion" style="font-weight: bold; font-size: 18px;"></span>
                            </div>       
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="color: #000;">
                                Tiempo de preparación promedio: <span id="spanTiempoPrepa" style="font-weight: bold; font-size: 18px;"></span>
                            </div>       
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="color: #000;">
                                Costo de envío: <span id="spanCostoEnvio" class="dinero" style="font-weight: bold; font-size: 18px;"></span>
                            </div>       
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="color: #000;">
                                Calificación: <span id="spanCalificacion" style="font-weight: bold; font-size: 18px;"></span>
                            </div>       
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="mostrarEstatus">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs">
                          <li class="nav-item">
                            <a class="nav-link BotonPestanaNegocio BPestanaDatos" tipo="DatosNegocio" href="javascript:void(0);">Datos del negocio</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link BotonPestanaNegocio" tipo="Ubicacion" href="javascript:void(0);">Ubicación</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link BotonPestanaNegocio" tipo="Horarios" href="javascript:void(0);">Horarios</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link BotonPestanaNegocio" tipo="Categorias" href="javascript:void(0);">Categorias</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link BotonPestanaNegocio" tipo="Productos" href="javascript:void(0);">Productos</a>
                          </li>
                        </ul>
                    </div>
                </div>
                <div class="row PestanaDatos" style="display: none;">
                    <div class="col-md-12">
                        <br>
                        <form id="FormDatosNegocio">
                            <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> INFORMACIÓN BÁSICA</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;">Nombre</td>
                                                    <td><input type="text" class="form-control" name="NombreNegocio" id="NombreNegocio" placeholder="Nombre" required> </td>
                                                </tr>
                                                <!-- <tr>
                                                    <td style="width: 30%;">Tiempo de preparación</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                 <input type="number" min="0" max="60" step="any" class="form-control" name="TiempoPreparacion" id="TiempoPreparacion" placeholder="Tiempo de preparación" required> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr> -->
                                                <tr>
                                                    <td style="width: 30%;">Rango de espera</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-1 mt-2">
                                                                <label>De: </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input class="form-control" name="RangoInicio" type="number" id="RangoInicio" min="0" placeholder="10">
                                                            </div>
                                                            <div class="col-md-1 mt-2">
                                                                <label>a: </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input class="form-control" name="RangoFin" min="0" type="number" id="RangoFin" placeholder="30">
                                                            </div>
                                                            <div class="col-md-2 mt-2">
                                                                <label>minutos</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Costo de envio</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                 <input type="number" min="0" max="60" step="any" class="form-control" name="CostoEnvio" id="CostoEnvio" placeholder="Costo de envio" required> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Descripción</td>
                                                    <td><input type="text" class="form-control" name="DescripcionNegocio" id="DescripcionNegocio" placeholder="Descripción del negocio" required> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Imagen del negocio</td>
                                                    <td><input type="file" class="form-control" name="ImagenNegocio" id="ImagenNegocio" placeholder="Imagen del negocio"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Prioridad del negocio</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                 <input type="number" min="0" class="form-control" name="PrioridadNegocio" id="PrioridadNegocio" placeholder="Prioridad del negocio" required> 
                                                                 <span class="text-muted">Orden en el que se mostrarán los negocios </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> INFORMACIÓN DEL NEGOCIO</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;">Clasificación</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                 <select id="ClasificacionNegocioModificar" name="ClasificacionNegocioModificar" class="form-control" required>
                                                                    #listaClasificacionesModificar#
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Usuario</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                 <input type="text" class="form-control" name="UsuarioNegocioModificar" id="UsuarioNegocioModificar" placeholder="Usuario de inicio de sesión" required> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Nueva contraseña</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                 <input type="password" class="form-control" name="NuevaContraNegocio" id="NuevaContraNegocio" placeholder="Nueva contraseña para iniciar sesión"> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> INFORMACIÓN PÚBLICA</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;">Correo electrónico público</td>
                                                    <td><input type="text" class="form-control" name="Correoelectronico" id="Correoelectronico" placeholder="Correo electrónico (Los clientes pueden ver este correo)"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Telefono</td>
                                                    <td><input type="phone" class="form-control" name="TelefonoNegocio" id="TelefonoNegocio" placeholder="Telefono del negocio"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">WhatsApp</td>
                                                    <td><input type="phone" class="form-control" name="WhatsNegocio" id="WhatsNegocio" placeholder="WhatsApp del negocio"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Página web</td>
                                                    <td><input type="text" class="form-control" name="PaginaWeb" id="PaginaWeb" placeholder="Página web del negocio"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Instagram</td>
                                                    <td><input type="text" class="form-control" name="InstaNegocio" id="InstaNegocio" placeholder="Instagram del negocio"> </td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 30%;">Facebook</td>
                                                    <td><input type="text" class="form-control" name="FacebookNegocio" id="FacebookNegocio" placeholder="Facebook del negocio"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Twitter</td>
                                                    <td><input type="text" class="form-control" name="TwitterNegocio" id="TwitterNegocio" placeholder="Twitter del negocio"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Youtube</td>
                                                    <td><input type="text" class="form-control" name="YoutubeNegocio" id="YoutubeNegocio" placeholder="Youtube del negocio"> </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">TikTok</td>
                                                    <td><input type="text" class="form-control" name="TikTokNegocio" id="TikTokNegocio" placeholder="TikTok del negocio"> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> INFORMACIÓN DE CONTACTO</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;">Nombre del contacto</td>
                                                    <td><input type="text" class="form-control" name="NombreContactoNegocio" id="NombreContactoNegocio" placeholder="Nombre del contacto del negocio"></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Teléfono</td>
                                                    <td><input type="phone" class="form-control" name="TelefonoContactoNegocio" id="TelefonoContactoNegocio" placeholder="Teléfono de contacto"></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Correo electrónico</td>
                                                    <td><input type="text" class="form-control" name="CorreoContactoNegocio" id="CorreoContactoNegocio" placeholder="Correo electrónico de contacto"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> ESTATUS DEL NEGOCIO </span>
                                       <span class="text-muted">(Inicio de sesión)</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><span id="SpanEstatus">Activo</span></td>
                                                    <td><div class="form-check form-switch">
                                                            <input class="form-check-input checkbox-lg text-center" type="checkbox" id="EstatusNegocio">
                                                        </div>   
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> ESTATUS DE LA CUENTA</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><span id="SpanEstatusCuenta">Pendiente</span></td>
                                                    <td>
                                                        <select class="form-control" id="EstatusCuenta">
                                                            <option value="" selected>Selecciona una opción</option>
                                                            <option value="Pendiente">Pendiente</option>
                                                            <option value="Aceptada">Aceptada</option>
                                                            <option value="Rechazada">Rechazada</option>
                                                        </select> 
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> VISIBILIDAD DEL NEGOCIO</span>
                                       <span class="text-muted">(Se utiliza para mostrar el negocio a los clientes)</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><span id="SpanEstatus">Activo</span></td>
                                                    <td><div class="form-check form-switch">
                                                            <input class="form-check-input checkbox-lg text-center" type="checkbox" id="ActivoNegocio">
                                                        </div>   
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>    
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> NEGOCIO ABIERTO</span>
                                       <span class="text-muted">(Se utiliza para empezar a recibir pedidos)</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><span id="SpanNegocioAbierto">Negocio abierto</span></td>
                                                    <td><div class="form-check form-switch">
                                                            <input class="form-check-input checkbox-lg text-center" type="checkbox" id="NegocioAbierto" checked>
                                                        </div>   
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>  
                            </div>
                        </form>
                        <br>
                    </div>
                </div>  
                <div class="row PestanaUbicacion" style="display: none;">
                    <div class="col-md-12">
                        <br>
                        <form id="FormUbicacionNegocio">
                            <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                                <div class="row">
                                   <div class="col-md-12">
                                       <span style="color: #000; font-weight: bold; font-size: 17px;"> UBICACIÓN DEL NEGOCIO</span>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless" style="color: #000; font-weight: bold; font-size: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%;">Calle</td>
                                                    <td><input type="text" class="form-control" name="CalleNegocio" id="CalleNegocio" placeholder="Calle del negocio" required> </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td style="width: 30%;">Número del negocio</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input class="form-control" name="NumeroInteriorNegocio" type="text" id="NumeroInteriorNegocio" placeholder="No. Interior">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input class="form-control" name="NumeroExteriorNegocio" type="text" id="NumeroExteriorNegocio" required placeholder="No. Exterior">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Localización</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input class="form-control" type="text" name="CodigoPostal" id="CodigoPostal" required placeholder="Código postal">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input class="form-control" type="text" name="ColoniaNegocio" id="ColoniaNegocio" placeholder="Colonia del negocio">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Detalles adicionales</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <textarea class="form-control" type="text" name="DetallesAdicionales" id="DetallesAdicionales" placeholder="Detalles para encontrar tu negocio"></textarea>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Ubicación</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input class="form-control" type="text" name="CiudadNegocio" id="CiudadNegocio" placeholder="Código postal">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input class="form-control" type="text" name="EstadoNegocio" id="EstadoNegocio" value="Jalisco" disabled placeholder="Estado del negocio">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input class="form-control" type="text" name="PaisNegocio" id="PaisNegocio" value="México" disabled placeholder="País del negocio">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%;">Coordenadas</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input class="form-control" type="number" name="LatitudNegocio" id="LatitudNegocio" placeholder="Latitud del negocio">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input class="form-control" type="number" name="LongitudNegocio" id="LongitudNegocio" placeholder="Longitud del negocio">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>  
                <div class="row PestanaHorarios" style="display: none;">
                    <div class="col-md-12">
                        <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                            <div class="row">
                                <div class="col-md-12">
                                    <span style="color: #000; font-weight: bold; font-size: 17px;"> HORARIOS</span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-outline-primary NuevoHorarioBoton">Nuevo horario <i class="fas fa-plus"></i></button>
                                </div>    
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-bordered table-striped text-center Datatable" id="TablaHorarios" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Día de la semana</th>
                                                <th>Horario</th>
                                                <th>Horario Especial</th>
                                                <th>Rango de fechas</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center" style="align-items: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="row PestanaCategorias" style="display: none;">
                    <div class="col-md-12">
                        <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                            <div class="row">
                                <div class="col-md-12">
                                    <span style="color: #000; font-weight: bold; font-size: 17px;"> CATEGORIAS</span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-outline-primary NuevaCategoriaBoton">Nueva categoria <i class="fas fa-plus"></i></button>
                                </div>    
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-bordered table-striped text-center Datatable" id="TablaCategorias" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center" style="align-items: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="row PestanaProductos" style="display: none;">
                    <div class="col-md-12">
                        <div class="card" style="box-shadow: 0px 0px 5px #ccc;">
                            <div class="row">
                                <div class="col-md-12">
                                    <span style="color: #000; font-weight: bold; font-size: 17px;"> PRODUCTOS</span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-outline-primary NuevoProducto" >Nuevo producto <i class="fas fa-plus"></i></button>
                                </div>    
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-bordered table-striped text-center Datatable" id="TablaProductos" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Detalles</th>
                                                <th>Estatus</th>
                                                <th>Extras y Opciones</th>
                                                <th>Categorias</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center" style="align-items: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>               
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"  data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                <button type="button" class="btn btn-primary" tipo="DatosNegocio" id="ModificarNegocio">Guardar <i class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalNuevoNegocio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo negocio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormNuevosNegocios">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nombre</label>
                            <input type="text" name="NombreNegocioNuevo" id="NombreNegocioNuevo" class="form-control" required>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Descripción</label>
                            <textarea class="form-control" rows="1" id="DescripcionNegocioNuevo" name="DescripcionNegocioNuevo" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label>Clasificacion</label>
                            <select id="ClasificacionNegocio" name="ClasificacionNegocio" class="form-control" required>
                                #listaClasificaciones#
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Usuario</label>
                            <input type="email" name="UsuarioNegocio" id="UsuarioNegocio" class="form-control" required>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Contraseña</label>
                            <input type="password" class="form-control" id="ContrasenaNegocio" name="ContrasenaNegocio" required>
                        </div>
                        <div class="col-md-4">
                            <label>Imagen</label>
                            <input type="file" name="ImagenNegocioNuevo" id="ImagenNegocioNuevo" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row"> 
                        <div class="col-md-3">
                            <label>Costo de envio</label>
                            <input type="number" step="any" min="0" name="CostoEnvioNegocio" id="CostoEnvioNegocio" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Duración</label>
                            <input type="number" step="any" min="0" name="DuracionNegocio" id="DuracionNegocio" class="form-control" required>
                            <span class="text-muted" style="font-size: 11px;">Tiempo de preparación promedio</span>
                        </div>
                        <div class="col-md-3">
                            <label>Telefono</label>
                            <input type="phone" name="TelefonoNuevoNegocio" id="TelefonoNuevoNegocio" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>WhatsApp</label>
                            <input type="phone" name="WhatsappNuevoNegocio" id="WhatsappNuevoNegocio" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                    <button type="submit" class="btn btn-primary" id="GuardarNegocio">Guardar <i class="fas fa-save"></i></button>
                </div>
            </form> 
        </div>
    </div>    
</div>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalNuevoHorario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Horario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormNuevoHorario">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Dia de la semana</label>
                            <select class="form-control" id="DiaSemana" name="DiaSemana" required>
                                <option value="" selected>Seleccione una opción</option>
                                <option value="0">Domingo</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miercoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sabado</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Hora de inicio</label>
                            <input type="time" name="HoraInicio" id="HoraInicio" required class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Hora de termino</label>
                            <input type="time" name="HoraFinal" id="HoraFinal" required class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>¿Es un horario especial?</label>
                            <select class="form-control" id="HorarioEspecial" name="HorarioEspecial">
                                <option value="" selected>Seleccione una opción</option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Fecha de inicio</label>
                            <input type="date" name="FechaInicio" id="FechaInicio" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha de termino</label>
                            <input type="date" name="FechaFinal" id="FechaFinal" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                    <button type="submit" class="btn btn-primary" attrid="" tipo="InsertarHorario" id="GuardarNuevoHorario">Guardar <i class="fas fa-save"></i></button>
                </div>
            </form> 
        </div>
    </div>    
</div>



<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalNuevaOpcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva opción del producto: <span id="NombreProductoNuevaOpcion"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Titulo</label> <br>
                            <input type="text" name="TituloOpcion" id="TituloOpcion" required class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tipo de opción</label> <br>
                            <select class="form-control" id="TipoOpcion" name="TipoOpcion">
                                <option value="1">Por cantidad</option>
                                <option value="0">Por selección</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Cantidad máxima de productos</label> <br>
                            <input type="number" name="CantidadOpcion" id="CantidadOpcion" min="0" required class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Tipo de selección de productos</label> <br>
                            <select class="form-control" id="TipoSeleccion" name="TipoSeleccion">
                                <option value="1">Obligatorio</option>
                                <option value="0">Opcional</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Estatus de la opción</label> <br>
                            <select class="form-control" id="EstatusOpcion" name="EstatusOpcion">
                                <option value="1">Activa</option>
                                <option value="0">Desactivada</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Nombre</label> <br>
                            <input type="text" name="NombreExtra" id="NombreExtra" required class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Precio</label> <br>
                            <input type="number" step="any" min="0" name="PrecioExtra" id="PrecioExtra" required class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Estatus del producto extra</label> <br>
                            <select class="form-control" id="EstatusProductoExtra" name="EstatusProductoExtra">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Producto agotado</label> <br>
                            <select class="form-control" id="AgotadoExtra" name="AgotadoExtra">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button  type="button" class="btn btn-sm BotonAgregarProductos btn-primary">Agregar producto <i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12" style="max-height: 300px; overflow-y: scroll;">
                           <table class="table table-hover text-center" id="TablaProductosAgregados">
                               <thead>
                                   <tr>
                                       <th style="width: 20%;">Nombre</th>
                                       <th style="width: 20%;">Precio</th>
                                       <th style="width: 20%;">Producto agotado</th>
                                       <th style="width: 20%;">Estatus</th>
                                       <th style="width: 20%;">Accion</th>
                                   </tr>
                               </thead>
                           </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                    <button type="button" class="btn btn-primary" attrid="" tipo="InsertarOpcion" id="GuardarNuevaOpcion">Guardar <i class="fas fa-save"></i></button>
                </div>
        </div>
    </div>    
</div>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalOpcionesProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Opciones del producto <span id="NombreProductoOpciones"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered table-striped text-center Datatable" id="TablaOpcionesProducto" width="100%">
                            <thead>
                                <tr>
                                    <th>Titulo</th>
                                    <th>Tipo</th>
                                    <th>Máximo</th>
                                    <th>Detalles</th>
                                    <th style="width: 30%">Productos</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>    
                            <tbody class="text-center" style="align-items: center;">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>    
</div>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalModificarOpcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modificar opción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Titulo</label> <br>
                            <input type="text" name="TituloOpcionM" id="TituloOpcionM" required class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tipo de opción</label> <br>
                            <select class="form-control" id="TipoOpcionM" name="TipoOpcionM">
                                <option value="1">Por cantidad</option>
                                <option value="0">Por selección</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Cantidad máxima de productos</label> <br>
                            <input type="number" name="CantidadOpcionM" id="CantidadOpcionM" min="0" value="1" required class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Tipo de selección de productos</label> <br>
                            <select class="form-control" id="TipoSeleccionM" name="TipoSeleccionM">
                                <option value="1">Obligatorio</option>
                                <option value="0">Opcional</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Estatus de la opción</label> <br>
                            <select class="form-control" id="EstatusOpcionM" name="EstatusOpcionM">
                                <option value="1">Activa</option>
                                <option value="0">Desactivada</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Nombre</label> <br>
                            <input type="text" name="NombreExtraM" id="NombreExtraM" required class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Precio</label> <br>
                            <input type="number" step="any" min="0" name="PrecioExtraM" id="PrecioExtraM" required class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Estatus del producto extra</label> <br>
                            <select class="form-control" id="EstatusProductoExtraM" name="EstatusProductoExtraM">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Producto agotado</label> <br>
                            <select class="form-control" id="AgotadoExtraM" name="AgotadoExtraM">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <button  type="button" class="btn btn-sm BotonAgregarProductosM btn-primary">Agregar producto <i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12" style="max-height: 300px; overflow-y: scroll;">
                           <table class="table table-hover text-center" id="TablaProductosAgregadosM">
                               <thead>
                                   <tr>
                                       <th style="width: 20%;">Nombre</th>
                                       <th style="width: 20%;">Precio</th>
                                       <th style="width: 20%;">Producto agotado</th>
                                       <th style="width: 20%;">Estatus</th>
                                       <th style="width: 20%;">Accion</th>
                                   </tr>
                               </thead>
                           </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                    <button type="button" class="btn btn-primary" attrid="" id="ModificarNuevaOpcion">Modificar <i class="fas fa-save"></i></button>
                </div>
        </div>
    </div>    
</div>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalNuevaCategoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormNuevaCategoria">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nombre</label>
                            <input type="text" name="NombreCategoria" id="NombreCategoria" class="form-control" required>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label>Descripción</label>
                            <textarea class="form-control" rows="5" id="DescripcionCategoria" name="DescripcionCategoria"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                    <button type="submit" class="btn btn-primary" tipo="InsertarCategoria" attrid="" id="GuardarNuevaCategoria">Guardar <i class="fas fa-save"></i></button>
                </div>
            </form> 
        </div>
    </div>    
</div>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalVerCategorias" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Categorias de <span id="NombreProductoCategorias"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="DivMostrarCategorias"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>    
</div>

<!--//////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade" id="ModalNuevoProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormNuevoProducto">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nombre</label>
                            <input type="text" name="NombreProducto" id="NombreProducto" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Preparación</label>
                            <input type="number" min="0" max="100" step="any" name="PreparacionProducto" id="PreparacionProducto" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Descripción</label>
                            <textarea class="form-control" rows="1" placeholder="Descripción del producto" name="DescripcionProducto" id="DescripcionProducto"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Imagen</label>
                            <input type="file" name="ImagenProducto" id="ImagenProducto" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Presentación del producto</label>
                            <input type="text" name="PresentacionProducto" id="PresentacionProducto" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Precio</label>
                            <input type="number" min="0" step="any" name="PrecioProducto" id="PrecioProducto" class="form-control" required>
                            <span class="text-muted" style="font-size: 11px;">Precio del producto</span>
                        </div>
                        <div class="col-md-3">
                            <label>Descuento</label>
                            <input type="number" min="0" step="any" name="DescuentoProducto" id="DescuentoProducto" class="form-control">
                            <span class="text-muted" style="font-size: 11px;">Porcentaje de descuento</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Tipo de venta</label>
                            <select name="TipoVentaProducto" id="TipoVentaProducto" class="form-control">
                                <option value="Pieza" selected>Pieza</option>
                                <option value="Granel">Granel</option>
                            </select>
                        </div>
                        <div class="col-md-4 MostrarSelectUnidad" style="display: none;">
                            <label>Unidad del producto</label>
                            <select name="UnidadGranelProducto" id="UnidadGranelProducto" class="form-control"> 
                                <option value="" selected></option>
                                <option value="L">Litro</option>
                                <option value="M">Metro</option>
                                <option value="Kg">Kilogramo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Madurez del producto</label>
                            <select name="MadurezProducto" id="MadurezProducto" class="form-control">
                                <option value="" selected> - Selecciona una opción - </option>
                                <option value="Maduro">Maduro (Para hoy)</option>
                                <option value="Normal">Normal (3 a 5 días)</option>
                                <option value="Verde">Verde (7 días)</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Producto agotado</label>
                            <select name="AgotadoProducto" id="AgotadoProducto" class="form-control">
                                <option value="1">Si</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Estatus del producto</label>
                            <select name="DadoBajaProducto" id="DadoBajaProducto" class="form-control">
                                <option value="1">Producto dado de baja</option>
                                <option value="0" selected>Producto activo</option>
                            </select>
                            <span class="text-muted" style="font-size: 11px;">¿El producto se muestra a los clientes?</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
                    <button type="submit" class="btn btn-primary" tipo="InsertarProducto" attrid="" id="GuardarNuevoProducto">Guardar <i class="fas fa-save"></i></button>
                </div>
            </form> 
        </div>
    </div>    
</div>