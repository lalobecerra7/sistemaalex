<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Menú</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript: void(0)">Inicio</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <!-- [ sample-page ] start -->
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-6">
            <h5>Inicio</h5>
          </div>
          <div class="col-6 text-end">
            <button type="button" class="btn btn-outline-secondary btn-sm cargarVista" carga="v_inicio" titulo="Inicio"><i class="fas fa-rotate-right"></i></button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 text-center">
            #empleadoDatos#  
          </div>
        </div>
        <br><br>
        <div class="row">
          <div class="col-md-4">
            <div class="form-floating mb-3">
              <input type="month" class="form-control" id="fechaI" name="fechaI" placeholder="Fecha Inicio" />
              <label for="fechaI">Fecha Inicio</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating mb-3">
              <input type="month" class="form-control" id="fechaF" name="fechaF" placeholder="Fecha Fin" />
              <label for="fechaF">Fecha Fin</label>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">#tipoUsuario#</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" style="position: relative;">Asignados</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <br>
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table table-hover table-striped table-bordered text-center myDataTable" id="tablaCare" style="font-size: 12px;" width="100%">
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Recha Recorrido</th>
                          #thTema#
                          <th>Área</th>
                          <th>Acompañante</th>
                          <th>Estatus</th>
                          <th>Condiciones Abiertas</th>
                          <th>Condiciones Cerradas</th>
                          <th>Última modificación</th>
                          <th orden="No">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                      <tfoot>
                        
                      </tfoot>
                    </table>
                  </div>
                </div>     
              </div>
              <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <br>
                <div class="row">
                  <div class="col-12 text-center">
                    <h5>Care Tour</h5>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table table-hover table-striped table-bordered text-center myDataTable" id="tablaCondicionesTour" style="font-size: 12px;" width="100%">
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Recha Recorrido</th>
                          <th>Realizado Por</th>
                          <th>Tema</th>
                          <th>Área</th>
                          <th>Recomendaciones</th>
                          <th>Comentarios</th>
                          <th>Fecha Compromiso</th>
                          <th>Estatus</th>
                          <th>Foto Antes</th>
                          <th>Foto Despues</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>
                <br>
                <hr>
                <br>
                <div class="row">
                  <div class="col-12 text-center">
                    <h5>Care Visit</h5>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table table-hover table-striped table-bordered text-center myDataTable" id="tablaCondicionesVisit" style="font-size: 12px;" width="100%">
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Recha Recorrido</th>
                          <th>Realizado Por</th>
                          <th>Área</th>
                          <th>Categoría</th>
                          <th>Tipo</th>
                          <th>Acción</th>
                          <th>Comentarios</th>
                          <th>Fecha Compromiso</th>
                          <th>Estatus</th>
                          <th>Foto Antes</th>
                          <th>Foto Despues</th>
                          <th>Acciones</th>
                        </tr>
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
      </div>
    </div>
  </div>
  <!-- [ sample-page ] end -->
</div>
<!-- [ Main Content ] end -->


<!-- Modal -->
<div class="modal fade" id="modalCondicionTour" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Care Tour</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formCondicionTour">
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <h5>Recomendaciones: </h5>
            </div>
            <div class="col-12 text-center" id="verComentariosTour">
                  
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-12">
              <div class="form-floating mb-3">
                <textarea class="form-control" name="comentariosTour" id="comentariosTour" style="height: 100px; resize: none;"></textarea>
                <label for="comentariosTour">Comentarios</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating mb-3">
                <input type="date" class="form-control" id="fechaCompromisoTour" name="fechaCompromisoTour" placeholder="Fecha" />
                <label for="fechaCompromisoTour">Fecha Compromiso</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating">
                <select class="form-control" id="estatusCondicionTour" name="estatusCondicionTour">
                  <option value="">-Selecciona una opción-</option>
                  <option value="Abierto">Abierto</option>
                  <option value="Cerrado">Cerrrado</option>
                </select>
                <label for="estatusCondicionTour">Estatus</label>    
              </div>
            </div>    
          </div>
          <hr>
          <div class="row">
            <div class="col-12">
              <h5>Foto Despues</h5>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-12">
              <div class="row mb-3">
                <div class="col-12">
                  <a href="vistas/assets/images/fondo.jpg" data-fancybox="images" class="imagenCondicion" id="imgDespuesTour">
                    <div style="background-image: url('vistas/assets/images/fondo.jpg'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;"></div>
                  </a>
                </div>
              </div>
              <div class="row justify-content-md-center">
                <div class="col-md-6">
                  <input type="file" class="form-control form-control-sm fotoCondicion" name="fotoCondicion1" accept="image/*">
                </div>
                <div class="col-2 mb-3">
                  <button type="button" class="btn btn-sm btn-danger bQuitarFotoCondicion"><i class="fas fa-times"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>  
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
          <button type="submit" class="btn btn-primary" id="bGuardarCondicionTour">Guardar <i class="fas fa-save"></i></button>
        </div>  
      </form>  
    </div>
  </div>
</div>  

<!-- Modal -->
<div class="modal fade" id="modalCondicionVisit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Care Visit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formCondicionVisit">
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <h5>Acción: </h5>
            </div>
            <div class="col-12 text-center" id="verAccionVisit">
                  
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-12">
              <div class="form-floating mb-3">
                <textarea class="form-control" name="comentariosVisit" id="comentariosVisit" style="height: 100px; resize: none;"></textarea>
                <label for="comentariosVisit">Comentarios</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating mb-3">
                <input type="date" class="form-control" id="fechaCompromisoVisit" name="fechaCompromisoVisit" placeholder="Fecha" />
                <label for="fechaCompromisoVisit">Fecha Compromiso</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating">
                <select class="form-control" id="estatusCondicionVisit" name="estatusCondicionVisit">
                  <option value="">-Selecciona una opción-</option>
                  <option value="Abierto">Abierto</option>
                  <option value="Cerrado">Cerrrado</option>
                </select>
                <label for="estatusCondicionVisit">Estatus</label>    
              </div>
            </div>    
          </div>
          <hr>
          <div class="row">
            <div class="col-12">
              <h5>Foto Despues</h5>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-12">
              <div class="row mb-3">
                <div class="col-12">
                  <a href="vistas/assets/images/fondo.jpg" data-fancybox="images" class="imagenCondicion" id="imgDespuesVisit">
                    <div style="background-image: url('vistas/assets/images/fondo.jpg'); width: 50px; height: 50px; background-size: cover; background-position: center; margin: 0 auto; cursor: pointer; border-radius: 100%;"></div>
                  </a>
                </div>
              </div>
              <div class="row justify-content-md-center">
                <div class="col-md-6">
                  <input type="file" class="form-control form-control-sm fotoCondicionVisit" name="fotoCondicionVisit" accept="image/*">
                </div>
                <div class="col-2 mb-3">
                  <button type="button" class="btn btn-sm btn-danger bQuitarFotoCondicionVisit"><i class="fas fa-times"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>  
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cerrar <i class="fas fa-times"></i></button>
          <button type="submit" class="btn btn-primary" id="bGuardarCondicionVisit">Guardar <i class="fas fa-save"></i></button>
        </div>  
      </form>  
    </div>
  </div>
</div>  