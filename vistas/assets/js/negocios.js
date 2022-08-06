$(document).ready(function() {

    $(document).on('click', '.DetallesNegocio', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=consultar&accion=negocios&idNegocio="+id+"&tipo=ConsultarDatosNegocio";
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res) {
          $("#ModalDetallesNegocio").modal("show");
          var datos = JSON.parse(res);
          console.log(datos.Imagen);
          $("#mostrarEstatus").html("");
          $(".BPestanaDatos").trigger("click");
          if (datos.Imagen == "") {
            $("#hrefFotoNegocio").attr("href", "../../../server_SPIDI_APP/images/logos/logo rojo.png");
            $("#DivFotoNegocio").css("background-image", "url('../../../server_SPIDI_APP/images/logos/logo rojo.png')");
          }else{
            $("#hrefFotoNegocio").attr("href", '../../../server_SPIDI_APP/images/negocios/'+datos.Imagen);
            $("#DivFotoNegocio").css("background-image", "url('../../../server_SPIDI_APP/images/negocios/"+datos.Imagen);
          }
          $("#hiddenID").val(datos.ID_Negocio);
          $("#ModificarNegocio").attr("attrid", datos.ID_Negocio);
          $("#NombreNegocioSpan").text(datos.NombreNegocio);
          $("#spanClasificacion").text(datos.NombreClasificacion);
          $("#spanTiempoPrepa").text(datos.Tiempo_Prepa_Prom);
          $("#spanCostoEnvio").text(datos.Costo_Envio);
          $("#spanCalificacion").text(datos.Calificacion);
          if(datos.Estatus_Cuenta == 'Pendiente'){
              $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-warning">Pendiente de verificar</span>');
          }else if(datos.Estatus_Cuenta == 'Aceptada'){
              $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-success">Cuenta aceptada</span>');
          }else if(datos.Estatus_Cuenta == 'Rechazada'){
              $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-danger">Cuenta rechazada</span>');
          }else{
              $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-warning">Pendiente de verificar</span>');
          }
          $("#ClasificacionNegocioModificar").val(datos.FK_Clasificacion);
          $("#UsuarioNegocioModificar").val(datos.Usuario);
          $("#PrioridadNegocio").val(datos.Prioridad);
          $("#NombreNegocio").val(datos.NombreNegocio);
          //$("#TiempoPreparacion").val(datos.Tiempo_Prepa_Prom);
          $("#CostoEnvio").val(datos.Costo_Envio);
          var separar = datos.Tiempo_Prepa_Prom.split(" ");
          $("#RangoInicio").val(separar[1]);
          $("#RangoFin").val(separar[3]);
          $("#DescripcionNegocio").val(datos.Descripcion);
          $("#Correoelectronico").val(datos.Correo);
          $("#TelefonoNegocio").val(datos.Telefono);
          $("#WhatsNegocio").val(datos.Whatsapp);
          $("#PaginaWeb").val(datos.Pagina_web);
          $("#InstaNegocio").val(datos.Instagram);
          $("#FacebookNegocio").val(datos.Facebook);
          $("#TwitterNegocio").val(datos.Twitter);
          $("#YoutubeNegocio").val(datos.Youtube);
          $("#TikTokNegocio").val(datos.Tiktok);
          $("#NombreContactoNegocio").val(datos.Nombre_Contacto);
          $("#TelefonoContactoNegocio").val(datos.Telefono_Contacto);
          $("#CorreoContactoNegocio").val(datos.Correo_Contacto);

          if (datos.Estatus == "Desbloqueado") {
            $("#EstatusNegocio").prop("checked", true);
          }else{
            $("#EstatusNegocio").prop("checked", false);
          }

          if (datos.Activo == "1") {
            $("#ActivoNegocio").prop("checked", true);
          }else{
            $("#ActivoNegocio").prop("checked", false);
          }

          $("#EstatusCuenta").val(datos.Estatus_Cuenta);
          
          if (datos.Abierto == "1") {
            $("#NegocioAbierto").prop("checked", true);
            $("#NegocioAbierto").trigger("change");
          }else{
            $("#NegocioAbierto").prop("checked", false);
            $("#NegocioAbierto").trigger("change");
          }
          $("#CalleNegocio").val(datos.Calle);
          $("#NumeroInteriorNegocio").val(datos.No_Interior);
          $("#NumeroExteriorNegocio").val(datos.No_Exterior);
          $("#CodigoPostal").val(datos.Codigo_Postal);
          $("#ColoniaNegocio").val(datos.Colonia);
          $("#DetallesAdicionales").val(datos.Detalles);
          $("#CiudadNegocio").val(datos.Ciudad);
          $("#LatitudNegocio").val(datos.Latitud);
          $("#LongitudNegocio").val(datos.Longitud);

          moneda();
        })
        .fail(function(){
            console.log("error ajax");
        });
    });

    $(document).on('change', '#NegocioAbierto', function() {
      if ($(this).prop("checked") == true) {
        $("#SpanNegocioAbierto").text("Negocio abierto");
      }else{
        $("#SpanNegocioAbierto").text("Negocio cerrado");
      }
    });    

   $(document).on('click', '.BotonPestanaNegocio', function() {
        $('.BotonPestanaNegocio').removeClass("PestanaActiva");
        if ($(this).attr("tipo") == "DatosNegocio") {
          $("#ModificarNegocio").css("display", "inline");
          $("#ModificarNegocio").attr("tipo", "DatosNegocio");
          $(this).addClass("PestanaActiva");
          $(".PestanaDatos").css("display", "inline");
          $(".PestanaUbicacion").css("display", "none");
          $(".PestanaHorarios").css("display", "none");
          $(".PestanaCategorias").css("display", "none");
          $(".PestanaProductos").css("display", "none");
        }else if($(this).attr("tipo") == "Ubicacion"){
          $("#ModificarNegocio").css("display", "inline");
          $("#ModificarNegocio").attr("tipo", "Ubicacion");
          $(this).addClass("PestanaActiva");
          $(".PestanaUbicacion").css("display", "inline");
          $(".PestanaDatos").css("display", "none");
          $(".PestanaHorarios").css("display", "none");
          $(".PestanaCategorias").css("display", "none");
          $(".PestanaProductos").css("display", "none");
        }else if($(this).attr("tipo") == "Horarios"){
          $("#ModificarNegocio").css("display", "none");
          $("#ModificarNegocio").css("tipo", "Horarios");
          $(this).addClass("PestanaActiva");
          $(".PestanaHorarios").css("display", "inline");
          $(".PestanaDatos").css("display", "none");
          $(".PestanaUbicacion").css("display", "none");
          $(".PestanaCategorias").css("display", "none");
          $(".PestanaProductos").css("display", "none");
          TablaHorariosNegocio($("#ModificarNegocio").attr("attrid"));
          //$( "#calendariodias" ).datepicker();
        }else if($(this).attr("tipo") == "Categorias"){
          $("#ModificarNegocio").css("display", "none");
          $("#ModificarNegocio").attr("tipo", "Categorias");
          $(this).addClass("PestanaActiva");
          $(".PestanaHorarios").css("display", "none");
          $(".PestanaDatos").css("display", "none");
          $(".PestanaUbicacion").css("display", "none");
          $(".PestanaCategorias").css("display", "inline");
          $(".PestanaProductos").css("display", "none");
          TablaCategoriasNegocio($("#ModificarNegocio").attr("attrid"));
          //TablaEventosEmpleado($("#ModificarNegocio").attr("attrid"));
          //$( "#calendariodias" ).datepicker();
        }else if($(this).attr("tipo") == "Productos"){
          $("#ModificarNegocio").css("display", "none");
          $("#ModificarNegocio").attr("tipo", "Productos");
          $(this).addClass("PestanaActiva");
          $(".PestanaHorarios").css("display", "none");
          $(".PestanaDatos").css("display", "none");
          $(".PestanaUbicacion").css("display", "none");
          $(".PestanaCategorias").css("display", "none");
          $(".PestanaProductos").css("display", "inline");
          TablaProductosNegocio($("#ModificarNegocio").attr("attrid"));
          moneda();
          //TablaEventosEmpleado($("#ModificarNegocio").attr("attrid"));
          //$( "#calendariodias" ).datepicker();
        }
    });

   /*$(document).on('click', '#GuardarNuevoNegocio', function(event) {
    event.preventDefault();
    var form = $('#FormNegocio');
      form.validate({
        rules: {
          NombreNegocioNuevo: {
            required: true,
          },
          DescripcionNegocioNuevo: {
            required: true,
          },
          ClasificacionNegocio: {
            required: true,
          },
          UsuarioNegocio: {
            required: true,
          },
          ContrasenaNegocio: {
            required: true,
          },
          ImagenNegocioNuevo: {
            required: true,
          },
          CostoEnvioNegocio: {
            required: true,
            min: 0
          },
          TelefonoNuevoNegocio: {
            required: true,
          }
        }
      });

      if (!form.valid()) {
        return;
      }else{

        var formulario = new FormData(document.getElementById("FormNuevosNegocios"));
        formulario.append("metodo", "insertar");
        formulario.append("accion", "negocios");
        formulario.append("tipo", "NuevoNegocio");
        console.log(formulario);
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: formulario,
          processData: false,
          contentType: false,
          beforeSend: function() {
            $("#carga").show();
          }
        })
        .done(function(res) {
          var dato = res.split("~");
          //console.log(dato);
          if ($.trim(dato[0]) == "Correcto") {
            Swal.fire({
              icon: 'success',
              title: 'Negocio guardado correctamente'
            });
            $('#ModalNuevoNegocio').modal("show");
            $('#FormNegocio').trigger("reset");
            var table = $('#tablaNegocios').DataTable();
            var data = "metodo=consultar&accion=negocios&idNegocio="+dato[1]+"&tipo=tablaNuevo";
            setTimeout(function() {
              $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
              })
              .done(function(res) {
                //console.log(res);
                var arreglo = JSON.parse(res);
                console.log(arreglo);
                table.row.add( {
                  "Negocio": arreglo.data[0]["Negocio"],
                  "Contacto": arreglo.data[0]["Contacto"],
                  "Direccion": arreglo.data[0]["Direccion"],
                  "Estatus": arreglo.data[0].Estatus,
                  "Acciones": arreglo.data[0]["Acciones"],
                }).draw(false); 
                moneda();
              })
              .fail(function() {
                console.log("Error ajax");
              });
            }, 0);
          }else{
            Swal.fire({
              icon: 'warning',
              title: '¡Error al agregar negocio!',
            });
          }
        })
        .fail(function() {
          console.log("Error ajax");
        })
        .always(function() {
          $("#carga").hide();
        });
      }
  });*/

  $(document).on('click', '#ModificarNegocio', function(event) {
    event.preventDefault();
    var boton = $(".botonVerDetalles"+$("#ModificarNegocio").attr("attrid"));
    if ($(this).attr("tipo") == "DatosNegocio") {

      var form = $('#FormDatosNegocio');

      form.validate({
        rules: {
          NombreNegocio: {
            required: true,
          },
          CostoEnvio: {
            required: true,
            min: 0
          },
          RangoInicio: {
            required: true,
            min: 0
          },
          RangoFin: {
            required: true,
            min: 0
          },
          DescripcionNegocio: {
            required: true,
          },
        }
      });

      if (!form.valid()) {
        return;
      }else{
        if(parseFloat($("#RangoInicio").val()) > parseFloat($("#RangoFin").val())){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El tiempo final tiene que ser mayor al tiempo inicial.'
            });
            return;
        }else{
          Swal.fire({
            title: '¿Estas a punto de modificar los datos del negocio?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, guardar',
            cancelButtonText: 'Cancelar',
          }).then((result) => {
            if (result.value) {
                if ($("#EstatusNegocio").prop('checked') == true) {
                  var estatus = "Desbloqueado";
                }else{
                  var estatus = "Bloqueado";
                }

                if ($("#ActivoNegocio").prop('checked') == true) {
                  var activo = 1;
                }else{
                  var activo = 0;
                }

                if ($("#NegocioAbierto").prop('checked') == true) {
                  var abierto = 1;
                }else{
                  var abierto = 0;
                }
                
                var data = new FormData(document.getElementById("FormDatosNegocio"));
                data.append("metodo", "modificar");
                data.append("accion", "negocios");
                data.append("tipo", "DatosNegocio");
                data.append("id", $("#ModificarNegocio").attr("attrid"));
                data.append("estatus", estatus);
                data.append("activo", activo);
                data.append("abierto", abierto);
                data.append("EstatusCuenta", $("#EstatusCuenta").val());
                

                $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
                })
                .done(function(res) {
                  var dato = res.split("~");
                  //console.log(dato);
                  if ($.trim(dato[0]) == "Correcto") {
                    Swal.fire({
                      icon: 'success',
                      title: 'Negocio modificado correctamente'
                    });

                    $("#NombreNegocioSpan").text($("#NombreNegocio").val());
                    $("#spanClasificacion").text($("#ClasificacionNegocioModificar option:selected").text());
                    $("#spanTiempoPrepa").text("De "+$("#RangoInicio").val()+" a "+$("#RangoFin").val()+" minutos.");
                    $("#spanCostoEnvio").text($("#CostoEnvio").val());
                     $("#mostrarEstatus").html("");
                    if($("#EstatusCuenta").val() == 'Pendiente'){
                      $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-warning">Pendiente de verificar</span>');
                    }else if($("#EstatusCuenta").val() == 'Aceptada'){
                      $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-success">Cuenta aceptada</span>');
                    }else if($("#EstatusCuenta").val() == 'Rechazada'){
                      $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-danger">Cuenta rechazada</span>');
                    }else{
                      $("#mostrarEstatus").append('Estatus de la cuenta: <span class="badge rounded-pill bg-warning">Pendiente de verificar</span>');
                    }


                    $('#NuevaContraNegocio').val("");
                    var table = $('#tablaNegocios').DataTable();
                    table.row( boton.parent().parent() ).remove().draw(false);
                    var data = "metodo=consultar&accion=negocios&idNegocio="+$("#hiddenID").val()+"&tipo=tablaNuevo";
                    setTimeout(function() {
                      $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: data
                      })
                      .done(function(res) {
                        //console.log(res);
                        var arreglo = JSON.parse(res);
                        console.log(arreglo);
                        table.row.add( {
                          "Negocio": arreglo.data[0]["Negocio"],
                          "Contacto": arreglo.data[0]["Contacto"],
                          "Direccion": arreglo.data[0]["Direccion"],
                          "Estatus": arreglo.data[0].Estatus,
                          "Acciones": arreglo.data[0]["Acciones"],
                        }).draw(false); 
                        moneda();
                      })
                      .fail(function() {
                        console.log("Error ajax");
                      });
                    }, 0);
                  }else{
                    Swal.fire({
                      icon: 'warning',
                      title: '¡Error al modificar negocio!',
                    });
                  }
                })
                .fail(function() {
                  console.log("Error ajax");
                })
                .always(function() {
                  $("#carga").hide();
                });

            }
          }); 
        }
      }

    }else if($(this).attr("tipo") == "Ubicacion") {

      var form = $('#FormUbicacionNegocio');

      form.validate({
        rules: {
          CalleNegocio: {
            required: true,
          },
          NumeroExteriorNegocio: {
            required: true,
            min: 0
          },
          CodigoPostal: {
            required: true,
          },
        }
      });

      if (!form.valid()) {
        return;
      }else{
        Swal.fire({
          title: '¿Estas a punto de modificar la ubicacion del negocio?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, guardar',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {
              var data = new FormData(document.getElementById("FormUbicacionNegocio"));
              data.append("metodo", "modificar");
              data.append("accion", "negocios");
              data.append("tipo", "Ubicacion");
              data.append("id", $("#ModificarNegocio").attr("attrid"));
              $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                  $("#carga").show();
                }
              })
              .done(function(res) {
                var dato = res.split("~");
                //console.log(dato);
                if ($.trim(dato[0]) == "Correcto") {
                  Swal.fire({
                    icon: 'success',
                    title: 'Negocio modificado correctamente'
                  });
                  var table = $('#tablaNegocios').DataTable();
                  table.row( boton.parent().parent() ).remove().draw(false);
                  var data = "metodo=consultar&accion=negocios&idNegocio="+$("#hiddenID").val()+"&tipo=tablaNuevo";
                  setTimeout(function() {
                    $.ajax({
                      url: 'index.php',
                      type: 'POST',
                      data: data
                    })
                    .done(function(res) {
                      //console.log(res);
                      var arreglo = JSON.parse(res);
                      console.log(arreglo);
                      table.row.add( {
                        "Negocio": arreglo.data[0]["Negocio"],
                        "Contacto": arreglo.data[0]["Contacto"],
                        "Direccion": arreglo.data[0]["Direccion"],
                        "Estatus": arreglo.data[0].Estatus,
                        "Acciones": arreglo.data[0]["Acciones"],
                      }).draw(false);
                      moneda();
                    })
                    .fail(function() {
                      console.log("Error ajax");
                    });
                  }, 0);
                }else{
                  Swal.fire({
                    icon: 'warning',
                    title: '¡Error al modificar negocio!',
                  });
                }
              })
              .fail(function() {
                console.log("Error ajax");
              })
              .always(function() {
                $("#carga").hide();
              });

          }
        });
      }

    }

  });

  $(document).on('click', '.VerAsistenciasEmpleado', function() {
    $("#ImprimirReporteVacaciones").attr("attrid", $(this).attr("attrid"));
  });

  $(document).on('click', '#ImprimirReporteVacaciones', function() {
    if ($("#SemanaInicio").val() == "") {
      Swal.fire({
        icon: 'warning',
        title: '¡Seleccione una fecha de inicio!',
      });
      $("#SemanaInicio").focus();

      return false;
    }
    if ($("#SemanaFinal").val() == "") {
      Swal.fire({
        icon: 'warning',
        title: '¡Seleccione una fecha final!',
      });
      $("#SemanaFinal").focus();
      return false;
    }
    window.open("controladores/pdf/ReporteAsistencias.php?id="+$(this).attr("attrid")+"&fechaini="+$("#SemanaInicio").val()+"&fechafin="+$("#SemanaFinal").val());
  });

  $(document).on('click', '.NuevoHorarioBoton', function() {
    $("#GuardarNuevoHorario").attr("attrid", "");
    $("#GuardarNuevoHorario").attr("tipo", "InsertarHorario");
    $("#FormNuevoHorario").trigger("reset");
    $("#ModalNuevoHorario").modal("show");
  });

  $(document).on('click', '.NuevaCategoriaBoton', function() {
    $("#GuardarNuevaCategoria").attr("attrid", "");
    $("#GuardarNuevaCategoria").attr("tipo", "InsertarCategoria");
    $("#FormNuevaCategoria").trigger("reset");
    $("#ModalNuevaCategoria").modal("show");
  });

  $(document).on('click', '.NuevoProducto', function() {
    $("#GuardarNuevoProducto").attr("attrid", "");
    $("#GuardarNuevoProducto").attr("tipo", "InsertarProducto");
    $("#FormNuevoProducto").trigger("reset");
    $("#ModalNuevoProducto").modal("show");
  });

  
  $(document).on('click', '.ModificarHorario', function() {
        var id = $(this).attr('attrid');
        $("#GuardarNuevoHorario").attr("attrid", id);
        $("#GuardarNuevoHorario").attr("tipo", "ModificarHorario");
        var data = "metodo=consultar&accion=negocios&idHorario="+id+"&tipo=ConsultarDatosHorarios";
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res) {
          $("#ModalNuevoHorario").modal("show");
          var datos = JSON.parse(res);
          $("#DiaSemana").val(datos.Dia_Semana);
          $("#HoraInicio").val(datos.Hora_Apertura);
          $("#HoraFinal").val(datos.Hora_Cierre);
          $("#HorarioEspecial").val(datos.Especial);
          $("#FechaInicio").val(datos.Fecha_Inicio);
          $("#FechaFinal").val(datos.Fecha_Final);
        })
        .fail(function(){
            console.log("error ajax");
        });
    });

    $(document).on('click', '.ModificarCategoria', function() {
        var id = $(this).attr('attrid');
        $("#GuardarNuevaCategoria").attr("attrid", id);
        $("#GuardarNuevaCategoria").attr("tipo", "ModificarCategoria");
        var data = "metodo=consultar&accion=negocios&idCategoria="+id+"&tipo=ConsultarDatosCategoria";
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res) {
          $("#ModalNuevaCategoria").modal("show");
          var datos = JSON.parse(res);
          $("#NombreCategoria").val(datos.Nombre);
          $("#DescripcionCategoria").val(datos.Descripcion);
        })
        .fail(function(){
            console.log("error ajax");
        });
    });

    $(document).on('click', '.ModificarProducto', function() {
        var id = $(this).attr('attrid');
        $("#GuardarNuevoProducto").attr("attrid", id);
        $("#GuardarNuevoProducto").attr("tipo", "ModificarProducto");
        var data = "metodo=consultar&accion=negocios&idProducto="+id+"&tipo=ConsultarDatosProducto";
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res) {
          $("#ModalNuevoProducto").modal("show");
          var datos = JSON.parse(res);

          $("#NombreProducto").val(datos.Nombre);
          $("#PreparacionProducto").val(datos.Duracion);
          $("#DescripcionProducto").val(datos.Descripcion);
          $("#PresentacionProducto").val(datos.Presentacion);
          $("#PrecioProducto").val(datos.Precio);
          $("#DescuentoProducto").val(datos.Descuento);
          $("#TipoVentaProducto").val(datos.Tipo_Venta);
          $("#UnidadGranelProducto").val(datos.Unidad_Granel);
          $("#MadurezProducto").val(datos.Madurez);
          $("#AgotadoProducto").val(datos.Agotado);
          $("#DadoBajaProducto").val(datos.Dado_Baja);

        })
        .fail(function(){
            console.log("error ajax");
        });
    });

    $(document).on('click', '.EliminarOpcion', function() {
        var boton = $(this);
        Swal.fire({
          title: '¿Estas a punto de eliminar esta opción?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {
            var id = $(this).attr('attrid');
            var data = "metodo=eliminar&accion=negocios&idOpcion="+id+"&tipo=EliminarOpcion";
            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: data
            })
            .done(function(res) {
              if (res == "Correcto") {
                Swal.fire({
                  icon: 'success',
                  title: 'Opcion eliminada correctamente'
                });
                var table = $('#TablaOpcionesProducto').DataTable();
                table.row( boton.parent().parent() ).remove().draw(false);
              }else{
                Swal.fire({
                  icon: 'warning',
                  title: 'Error al eliminar opcion'
                });
                console.log(res);
              }
              
            })
            .fail(function(){
                console.log("error ajax");
            });
          }
        })
    });

    $(document).on('click', '.EliminarHorario', function() {
        var boton = $(this);
        Swal.fire({
          title: '¿Estas a punto de eliminar este horario?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {
            var id = $(this).attr('attrid');
            var data = "metodo=eliminar&accion=negocios&idHorario="+id+"&tipo=EliminarHorario";
            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: data
            })
            .done(function(res) {
              if (res == "Correcto") {
                Swal.fire({
                  icon: 'success',
                  title: 'Horario eliminado correctamente'
                });
                var table = $('#TablaHorarios').DataTable();
                table.row( boton.parent().parent() ).remove().draw(false);
              }else{
                Swal.fire({
                  icon: 'warning',
                  title: 'Error al eliminar horario'
                });
                console.log(res);
              }
              
            })
            .fail(function(){
                console.log("error ajax");
            });
          }
        })
    });

    $(document).on('click', '.EliminarCategoria', function() {
        var boton = $(this);
        Swal.fire({
          title: '¿Estas a punto de eliminar esta categoria?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {
            var id = $(this).attr('attrid');
            var data = "metodo=eliminar&accion=negocios&idCategoria="+id+"&tipo=EliminarCategoria";
            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: data
            })
            .done(function(res) {
              if (res == "Correcto") {
                Swal.fire({
                  icon: 'success',
                  title: 'Categoria eliminada correctamente'
                });
                var table = $('#TablaCategorias').DataTable();
                table.row( boton.parent().parent() ).remove().draw(false);
              }else{
                Swal.fire({
                  icon: 'warning',
                  title: 'Error al eliminar categoria'
                });
                console.log(res);
              }
              
            })
            .fail(function(){
                console.log("error ajax");
            });
          }
        })
    });

    $(document).on('click', '.EliminarProducto', function() {
        var boton = $(this);
        Swal.fire({
          title: '¿Estas a punto de eliminar este producto?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {
            var id = $(this).attr('attrid');
            var data = "metodo=eliminar&accion=negocios&idProducto="+id+"&tipo=EliminarProducto";
            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: data
            })
            .done(function(res) {
              if (res == "Correcto") {
                Swal.fire({
                  icon: 'success',
                  title: 'Producto eliminado correctamente'
                });
                var table = $('#TablaProductos').DataTable();
                table.row( boton.parent().parent() ).remove().draw(false);
              }else{
                Swal.fire({
                  icon: 'warning',
                  title: 'Error al eliminar producto'
                });
                console.log(res);
              }
              
            })
            .fail(function(){
                console.log("error ajax");
            });
          }
        })
    });

    $(document).on('click', '#GuardarNuevoHorario', function(event) {
        event.preventDefault();
        var btn = $(this);
        var idNegocio = $("#hiddenID").val();
        var form = $('#FormNuevoHorario');
        if ($(this).attr("tipo") == "InsertarHorario") {
          form.validate({
              rules: {
                  DiaSemana: {
                      required: true
                  },
                  HoraInicio: {
                      required: true
                  },
                  HoraFinal: {
                      required: true
                  }
              }
          });

          if (!form.valid()) {
            return;
          }else{

              var data = new FormData(document.getElementById("FormNuevoHorario"));
              data.append("metodo", "insertar");
              data.append("accion", "negocios");
              data.append("tipo", "NuevoHorario");
              data.append("idNegocio", idNegocio);

              $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
              })
              .done(function(res) {
                  var datos = res.split("~");
                  if ($.trim(datos[0]) == "Correcto") {
                      Swal.fire({
                          icon: 'success',
                          title: 'El horario ha sido guardado correctamente'
                      });
                      $.ajax({
                          url: 'index.php',
                          type: 'POST',
                          data: "metodo=consultar&accion=negocios&tipo=ConsultarHorariosNegocioNuevo&idHorario="+datos[1]
                      })
                      .done(function(res) {
                          var resA = JSON.parse(res);     
                          var table = $('#TablaHorarios').DataTable();
                          table.row.add({ 
                              "DiaRegistrado": resA.data[0].DiaRegistrado,
                              "Horario": resA.data[0].Horario,
                              "HorarioEspecial": resA.data[0].HorarioEspecial,
                              "Rango": resA.data[0].Rango,
                              "Accion": resA.data[0].Accion,
                          }).draw(false);
                      })
                      .fail(function() {
                          console.log("Error ajax");
                      });
                      document.getElementById("FormNuevoHorario").reset();
                      $("#GuardarNuevoHorario").attr("attrid", "");
                      $("#GuardarNuevoHorario").attr("tipo", "InsertarHorario");
                      $("#ModalNuevoHorario").modal('hide');
                  }else{
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error inesperado al guardar horario.'
                      });

                      console.log($.trim(res));
                  }
              })
              .fail(function() {
                  console.log("Error ajax");
              })
              .always(function() {
                  $("#carga").hide();
              });
          }  
        }else if ($(this).attr("tipo") == "ModificarHorario") {
          form.validate({
              rules: {
                  DiaSemana: {
                      required: true
                  },
                  HoraInicio: {
                      required: true
                  },
                  HoraFinal: {
                      required: true
                  }
              }
          });

          if (!form.valid()) {
            return;
          }else{

              var data = new FormData(document.getElementById("FormNuevoHorario"));
              data.append("metodo", "modificar");
              data.append("accion", "negocios");
              data.append("tipo", "ModificarHorario");
              data.append("idNegocio", idNegocio);
              data.append("idHorario", $("#GuardarNuevoHorario").attr("attrid"));

              $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
              })
              .done(function(res) {
                  if ($.trim(res) == "Correcto") {
                      Swal.fire({
                          icon: 'success',
                          title: 'El horario ha sido modificado correctamente'
                      });

                      document.getElementById("FormNuevoHorario").reset();
                      $("#ModalNuevoHorario").modal('hide');
                      $("#GuardarNuevoHorario").attr("attrid", "");
                      $("#GuardarNuevoHorario").attr("tipo", "InsertarHorario");

                      $.ajax({
                          url: 'index.php',
                          type: 'POST',
                          data: "metodo=consultar&accion=negocios&tipo=ConsultarHorariosNegocioNuevo&idHorario="+$("#GuardarNuevoHorario").attr("attrid")
                      })
                      .done(function(res) {
                          var resA = JSON.parse(res);     
                          var boton = $(".btnModificarHorario"+$("#GuardarNuevoHorario").attr("attrid"));
                          var table = $('#TablaHorarios').DataTable();
                          table.row( boton.parent().parent() ).remove().draw(false);
                          table.row.add({ 
                              "DiaRegistrado": resA.data[0].DiaRegistrado,
                              "Horario": resA.data[0].Horario,
                              "HorarioEspecial": resA.data[0].HorarioEspecial,
                              "Rango": resA.data[0].Rango,
                              "Accion": resA.data[0].Accion,
                          }).draw(false);
                      })
                      .fail(function() {
                          console.log("Error ajax");
                      });
                  }else{
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error inesperado al guardar horario.'
                      });

                      console.log($.trim(res));
                  }
              })
              .fail(function() {
                  console.log("Error ajax");
              })
              .always(function() {
                  $("#carga").hide();
              });
          }  
        }
    });

    $(document).on('click', '#GuardarNegocio', function(event) {
        event.preventDefault();
        var form = $('#FormNuevosNegocios');

        form.validate({
          rules: {
            NombreNegocioNuevo: {
              required: true
            },
            UsuarioNegocio: {
              required: true
            },
            ContrasenaNegocio: {
              required: true
            },
            CostoEnvioNegocio: {
              required: true
            },
            TelefonoNuevoNegocio: {
              required: true
            },
          }
        });

        if (!form.valid()) {
          return;
        }else{

              var data = new FormData(document.getElementById("FormNuevosNegocios"));
              data.append("metodo", "insertar");
              data.append("accion", "negocios");
              data.append("tipo", "NuevoNegocio");

              $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
              })
              .done(function(res) {
                 var datos = res.split("~");
                 if ($.trim(datos[0]) == "Correcto") {
                      Swal.fire({
                          icon: 'success',
                          title: 'El negocio ha sido guardado correctamente'
                      });

                      document.getElementById("FormNuevosNegocios").reset();
                      $("#ModalNuevoNegocio").modal('hide');

                      $.ajax({
                          url: 'index.php',
                          type: 'POST',
                          data: "metodo=consultar&accion=negocios&tipo=tablaNuevo&idNegocio="+datos[1]
                      })
                      .done(function(res) {
                          var resA = JSON.parse(res);     
                          var table = $('#tablaNegocios').DataTable();
                          table.row.add( {
                            "Negocio": resA.data[0]["Negocio"],
                            "Contacto": resA.data[0]["Contacto"],
                            "Direccion": resA.data[0]["Direccion"],
                            "Estatus": resA.data[0].Estatus,
                            "Acciones": resA.data[0]["Acciones"],
                          }).draw(false); 
                      })
                      .fail(function() {
                          console.log("Error ajax");
                      });
                  }else{
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error inesperado al guardar negocio.'
                      });

                      console.log($.trim(res));
                  }
              })
              .fail(function() {
                  console.log("Error ajax");
              })
              .always(function() {
                  $("#carga").hide();
              });
        }  
        
    });

    $(document).on('click', '#GuardarNuevaCategoria', function(event) {
        event.preventDefault();
        var btn = $(this);
        var idNegocio = $("#hiddenID").val();
        var form = $('#FormNuevaCategoria');
        if ($(this).attr("tipo") == "InsertarCategoria") {
          form.validate({
              rules: {
                  NombreCategoria: {
                      required: true
                  },
              }
          });

          if (!form.valid()) {
            return;
          }else{

              var data = new FormData(document.getElementById("FormNuevaCategoria"));
              data.append("metodo", "insertar");
              data.append("accion", "negocios");
              data.append("tipo", "NuevaCategoria");
              data.append("idNegocio", idNegocio);

              $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
              })
              .done(function(res) {
                  var datos = res.split("~");
                  if ($.trim(datos[0]) == "Correcto") {
                      Swal.fire({
                          icon: 'success',
                          title: 'La categoria ha sido guardada correctamente'
                      });

                      document.getElementById("FormNuevaCategoria").reset();
                      $("#ModalNuevaCategoria").modal('hide');

                      $.ajax({
                          url: 'index.php',
                          type: 'POST',
                          data: "metodo=consultar&accion=negocios&tipo=ConsultarCategoriasNegocioNuevo&idCategoria="+datos[1]
                      })
                      .done(function(res) {
                          var resA = JSON.parse(res);     
                          var table = $('#TablaCategorias').DataTable();
                          table.row.add({ 
                              "Nombre": resA.data[0].Nombre,
                              "Descripcion": resA.data[0].Descripcion,
                              "Accion": resA.data[0].Accion,
                          }).draw(false);
                      })
                      .fail(function() {
                          console.log("Error ajax");
                      });
                  }else{
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error inesperado al guardar categoria.'
                      });

                      console.log($.trim(res));
                  }
              })
              .fail(function() {
                  console.log("Error ajax");
              })
              .always(function() {
                  $("#carga").hide();
              });
          }  
        }else if ($(this).attr("tipo") == "ModificarCategoria") {
          form.validate({
              rules: {
                  Nombre: {
                      required: true
                  },
              }
          });

          if (!form.valid()) {
            return;
          }else{

              var data = new FormData(document.getElementById("FormNuevaCategoria"));
              data.append("metodo", "modificar");
              data.append("accion", "negocios");
              data.append("tipo", "ModificarCategoria");
              data.append("idNegocio", idNegocio);
              data.append("idCategoria", $("#GuardarNuevaCategoria").attr("attrid"));

              $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
              })
              .done(function(res) {
                  if ($.trim(res) == "Correcto") {
                      Swal.fire({
                          icon: 'success',
                          title: 'La categoria ha sido modificada correctamente'
                      });

                      document.getElementById("FormNuevaCategoria").reset();
                      $("#ModalNuevaCategoria").modal('hide');

                      $.ajax({
                          url: 'index.php',
                          type: 'POST',
                          data: "metodo=consultar&accion=negocios&tipo=ConsultarCategoriasNegocioNuevo&idCategoria="+$("#GuardarNuevaCategoria").attr("attrid")
                      })
                      .done(function(res) {
                          var resA = JSON.parse(res);     
                          var boton = $(".btnModificarCategoria"+$("#GuardarNuevaCategoria").attr("attrid"));
                          var table = $('#TablaCategorias').DataTable();
                          table.row( boton.parent().parent() ).remove().draw(false);
                          table.row.add({ 
                              "Nombre": resA.data[0].Nombre,
                              "Descripcion": resA.data[0].Descripcion,
                              "Accion": resA.data[0].Accion,
                          }).draw(false);
                      })
                      .fail(function() {
                          console.log("Error ajax");
                      });
                  }else{
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error inesperado al guardar categoria.'
                      });

                      console.log($.trim(res));
                  }
              })
              .fail(function() {
                  console.log("Error ajax");
              })
              .always(function() {
                  $("#carga").hide();
              });
          }  
        }
    });

    $(document).on('change', '#TipoVentaProducto', function() {
      if ($(this).val() == "Granel") {
        $(".MostrarSelectUnidad").css("display", "inline");
      }else{
        $(".MostrarSelectUnidad").css("display", "none");
      }
    });

    $(document).on('click', '#SeleccionarCategoria', function() {
      var idCategoria = $(this).attr("attrid");
      var idProducto = $(this).attr("producto");
      var idNegocio = $(this).attr("negocio");
      if ($(this).prop("checked") == true) {
        var seleccionar = "si";
      }else{
        var seleccionar = "no";
      }
      var data = "metodo=consultar&accion=negocios&idProducto="+idProducto+"&tipo=InsertarCategoriaProducto&idNegocio="+idNegocio+"&idCategoria="+idCategoria+"&seleccionar="+seleccionar;
      $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
      })
      .done(function(res) {
        if (res == "Correcto") {
          Swal.fire({
            icon: 'success',
            title: 'Categoria agregada correctamente'
          });
        }else{
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error inesperado al guardar la categoria.'
          });
        }
      })
      .fail(function(){
        console.log("error ajax");
      });
    });

    $(document).on('click', '.ConsultarCategoriasProd', function(event) {
        var id = $(this).attr('attrid');
        var nombredelProducto = $(this).attr('nombreProducto');
        var idNegocio = $("#hiddenID").val();
        var data = "metodo=consultar&accion=negocios&idProducto="+id+"&tipo=ConsultarCategoriasProducto&idNegocio="+idNegocio;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
          $("#ModalVerCategorias").modal("show");
          $("#NombreProductoCategorias").text(nombredelProducto);
          $("#DivMostrarCategorias").html(res);
        })
        .fail(function(){
              console.log("error ajax");
        });
    });

    $(document).on('click', '.ConsultarExtrasProducto', function(event) {
        var id = $(this).attr('attrid');
        var nombredelProducto = $(this).attr('nombreProducto');
        var idNegocio = $("#hiddenID").val();
        $("#ModalOpcionesProducto").modal("show");
        $("#NombreProductoOpciones").text(nombredelProducto);  
        TablaOpcionesProductos(id);
    });

    $(document).on('click', '.NuevaOpcionProducto', function(event) {
        var id = $(this).attr('attrid');
        var nombredelProducto = $(this).attr('nombreProducto');
        var idNegocio = $("#hiddenID").val();
        $("#ModalNuevaOpcion").modal("show");
        $("#GuardarNuevaOpcion").attr("idProducto", id);
        $("#NombreProductoNuevaOpcion").text(nombredelProducto);  
        $("#NombreExtra").val("");
        $("#PrecioExtra").val("");
        $("#CantidadOpcion").val("");
    });
    var arregloProductosExtras = '';
    $(document).on('click', '.BotonAgregarProductos', function() {
        var nombre = $("#NombreExtra").val();
        var precio = $("#PrecioExtra").val();
        var estatus = $("#EstatusProductoExtra option:selected").text();
        var agotado = $("#AgotadoExtra option:selected").text(); 
        var valorestatus = $("#EstatusProductoExtra option:selected").val();
        var valoragotado = $("#AgotadoExtra option:selected").val(); 
        if (nombre == "") {
          $("#NombreExtra").focus();
          return false;
        }
        if (precio == "" || precio < 0) {
          $("#PrecioExtra").focus();
          return false;
        }

        arregloProductosExtras += nombre+","+precio+","+valorestatus+","+valoragotado+"~";

        var fila = '\
          <tr>\
            <th>'+nombre+'</th>\
            <th class="dinero">'+precio+'</th>\
            <th>'+agotado+'</th>\
            <th>'+estatus+'</th>\
            <th><button class="btn btn-outline-danger btn-sm EliminarFila" cadena="'+nombre+","+precio+","+valorestatus+","+valoragotado+"~"+'"><i class="fas fa-trash"></i></button></th>\
          </tr>';
         $("#TablaProductosAgregados").append(fila);
         $("#NombreExtra").val("");
         $("#PrecioExtra").val("");
         $("#EstatusProductoExtra").val("1");
         $("#AgotadoExtra").val("1");
         moneda();
    });

    $(document).on('click', '.EliminarFila', function() {
      var cadena = $(this).attr("cadena");
      var nuevacadena = arregloProductosExtras.replace(cadena,'');
      var nuevacadena2 = arregloProductosExtrasM.replace(cadena,'');
      arregloProductosExtras = nuevacadena;
      arregloProductosExtrasM = nuevacadena2;
      $(this).parent().parent().remove();

    });

    $(document).on('click', '#GuardarNuevaOpcion', function() {
        if ($("#TituloOpcion").val() == "") {
            Swal.fire({
              icon: 'info',
              title: 'Ingresa un titulo para la opción.'
            });
            return false;
        }

        if ($("#CantidadOpcion").val() == "" || $("#CantidadOpcion").val() < 0) {
            Swal.fire({
              icon: 'info',
              title: 'Ingresa una cantidad mayor o igual a 1.'
            });
            return false;
        }
        if (arregloProductosExtras == "") {
            Swal.fire({
              icon: 'info',
              title: 'Ingresa al menos un producto para esta opción.'
            });
            return false;
        }

        var Titulo = $("#TituloOpcion").val();
        var TipoOpcion = $("#TipoOpcion").val();
        var CantidadOpcion = $("#CantidadOpcion").val(); 
        var TipoSeleccion = $("#TipoSeleccion").val();
        var EstatusOpcion = $("#EstatusOpcion").val();
        var idProducto = $(this).attr("idproducto");
        var data = "metodo=insertar&accion=negocios&tipo=NuevoExtraProducto&Titulo="+Titulo+"&TipoOpcion="+TipoOpcion+"&CantidadOpcion="+CantidadOpcion+"&TipoSeleccion="+TipoSeleccion+"&EstatusOpcion="+EstatusOpcion+"&arregloProductos="+arregloProductosExtras+"&idProducto="+idProducto;
        //console.log(data);
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res) {
          var datos = res.split("~");
          if (datos[0] == "Correcto") {
            Swal.fire({
              icon: 'success',
              title: 'Opcion agregada correctamente'
            });
            arregloProductosExtras = "";
            $("#ModalNuevaOpcion").modal("hide");
            $("#TituloOpcion").val("");
            $("#TipoOpcion").val("1");
            $("#CantidadOpcion").val("");
            $("#TipoSeleccion").val("1");
            $("#EstatusOpcion").val("1");
            $("#TablaProductosAgregados").html("");
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error inesperado al guardar opción.'
            });
          }
        })
        .fail(function(){
              console.log("error ajax");
        });
    });

    var arregloProductosExtrasM = '';
    $(document).on('click', '.BotonAgregarProductosM', function() {
        var nombre = $("#NombreExtraM").val();
        var precio = $("#PrecioExtraM").val();
        var estatus = $("#EstatusProductoExtraM option:selected").text();
        var agotado = $("#AgotadoExtraM option:selected").text(); 
        var valorestatus = $("#EstatusProductoExtraM option:selected").val();
        var valoragotado = $("#AgotadoExtraM option:selected").val(); 
        if (nombre == "") {
          $("#NombreExtraM").focus();
          return false;
        }
        if (precio == "" || precio < 0) {
          $("#PrecioExtraM").focus();
          return false;
        }

        arregloProductosExtrasM += nombre+","+precio+","+valorestatus+","+valoragotado+"~";

        var fila = '\
          <tr>\
            <th>'+nombre+'</th>\
            <th class="dinero">'+precio+'</th>\
            <th>'+agotado+'</th>\
            <th>'+estatus+'</th>\
            <th><button class="btn btn-outline-danger btn-sm EliminarFila" cadena="'+nombre+","+precio+","+valorestatus+","+valoragotado+"~"+'"><i class="fas fa-trash"></i></button></th>\
          </tr>';
         $("#TablaProductosAgregadosM").append(fila);
         $("#NombreExtraM").val("");
         $("#PrecioExtraM").val("");
         $("#EstatusProductoExtraM").val("1");
         $("#AgotadoExtraM").val("1");
         moneda();
    });

    $(document).on('click', '.ModificarOpcion', function() {
        $("#ModalModificarOpcion").modal("show");
        var id = $(this).attr('attrid');
        var data = "metodo=consultar&accion=negocios&idOpcion="+id+"&tipo=ConsultarDatosOpcion";
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res) {
          var datos = JSON.parse(res);
          //console.log(datos);
          $("#ModificarNuevaOpcion").attr("attrid", datos.data[0].ID_Opcion);
          $("#TituloOpcionM").val(datos.data[0].Titulo);
          $("#TipoOpcionM").val(datos.data[0].Es_Cantidad);
          $("#CantidadOpcionM").val(datos.data[0].Maximo);
          $("#TipoSeleccionM").val(datos.data[0].Obligatorio);
          $("#EstatusOpcionM").val(datos.data[0].Activa);
          for (var i = 0; i < datos.data[0].ArregloProductos.length; i++) {
            var estatus = "Inactivo";
            if (datos.data[0].ArregloProductos[i].Activa == "1") {
              estatus = "Activo";
            }

            var agotado = "No";
            if (datos.data[0].ArregloProductos[i].Agotada == "1") {
              agotado = "Si";
            }

            var id = datos.data[0].ArregloProductos[i].ID_Detalle;
            var nombre = datos.data[0].ArregloProductos[i].Nombre;
            var precio = datos.data[0].ArregloProductos[i].Precio;
            var valorestatus = datos.data[0].ArregloProductos[i].Activa;
            var valoragotado = datos.data[0].ArregloProductos[i].Agotada;
            arregloProductosExtrasM += nombre+","+precio+","+valorestatus+","+valoragotado+"~";

            var fila = '\
              <tr>\
                <th>'+nombre+'</th>\
                <th class="dinero">'+precio+'</th>\
                <th>'+agotado+'</th>\
                <th>'+estatus+'</th>\
                <th><button class="btn btn-outline-danger btn-sm EliminarFila" cadena="'+nombre+","+precio+","+valorestatus+","+valoragotado+"~"+'"><i class="fas fa-trash"></i></button></th>\
              </tr>';

            $("#TablaProductosAgregadosM").append(fila);
          }
        })
        .fail(function(){
              console.log("error ajax");
        });
    });

    $(document).on('click', '#ModificarNuevaOpcion', function() {
        var boton = $(".btnModificarOpcion"+$(this).attr("attrid"));
        if ($("#TituloOpcionM").val() == "") {
            Swal.fire({
              icon: 'info',
              title: 'Ingresa un titulo para la opción.'
            });
            return false;
        }

        if ($("#CantidadOpcionM").val() == "" || $("#CantidadOpcionM").val() < 0) {
            Swal.fire({
              icon: 'info',
              title: 'Ingresa una cantidad mayor o igual a 1.'
            });
            return false;
        }
        if (arregloProductosExtrasM == "") {
            Swal.fire({
              icon: 'info',
              title: 'Ingresa al menos un producto para esta opción.'
            });
            return false;
        }

        var Titulo = $("#TituloOpcionM").val();
        var TipoOpcion = $("#TipoOpcionM").val();
        var CantidadOpcion = $("#CantidadOpcionM").val(); 
        var TipoSeleccion = $("#TipoSeleccionM").val();
        var EstatusOpcion = $("#EstatusOpcionM").val();
        var idOpcion = $(this).attr("attrid");
        var data = "metodo=modificar&accion=negocios&tipo=ModificarExtraProducto&Titulo="+Titulo+"&TipoOpcion="+TipoOpcion+"&CantidadOpcion="+CantidadOpcion+"&TipoSeleccion="+TipoSeleccion+"&EstatusOpcion="+EstatusOpcion+"&arregloProductos="+arregloProductosExtrasM+"&idOpcion="+idOpcion;
        console.log(data);
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: data
        })
        .done(function(res) {
          var datos = res.split("~");
          if (datos[0] == "Correcto") {
            Swal.fire({
              icon: 'success',
              title: 'Opcion modificada correctamente'
            });

            $.ajax({
              url: 'index.php',
              type: 'POST',
              data: "metodo=consultar&accion=negocios&tipo=ConsultarOpcionesProductoNuevo&idOpcion="+datos[1]
            })
            .done(function(res) {
              var resA = JSON.parse(res);     
              var table = $('#TablaOpcionesProducto').DataTable();
              table.row( boton.parent().parent() ).remove().draw(false);
              table.row.add({ 
                "Titulo" : resA.data[0].Titulo,
                "Tipo" : resA.data[0].Tipo,
                "Cantidad" : resA.data[0].Cantidad,
                "Detalles" : resA.data[0].Detalles,
                "Productos" : resA.data[0].Productos,
                "Accion" : resA.data[0].Accion,
              }).draw(false);
              moneda();
            })
            .fail(function() {
              console.log("Error ajax");
            });

            arregloProductosExtrasM = "";
            $("#ModalModificarOpcion").modal("hide");
            $("#TituloOpcionM").val("");
            $("#TipoOpcionM").val("1");
            $("#CantidadOpcionM").val("");
            $("#TipoSeleccionM").val("1");
            $("#EstatusOpcionM").val("1");
            $("#TablaProductosAgregadosM").html("");
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error inesperado al modificar opción.'
            });
          }
        })
        .fail(function(){
              console.log("error ajax");
        });
    });

    

    $(document).on('click', '#GuardarNuevoProducto', function(event) {
        event.preventDefault();
        var btn = $(this);
        var idNegocio = $("#hiddenID").val();
        var form = $('#FormNuevoProducto');
        if ($(this).attr("tipo") == "InsertarProducto") {
          form.validate({
              rules: {
                  NombreProducto: {
                      required: true
                  },
                  PreparacionProducto: {
                      required: true,
                      min: 0,
                      max: 100
                  },
                  PrecioProducto: {
                      required: true,
                      min: 0,
                  },
              }
          });

          if (!form.valid()) {
            return;
          }else{

              var data = new FormData(document.getElementById("FormNuevoProducto"));
              data.append("metodo", "insertar");
              data.append("accion", "negocios");
              data.append("tipo", "NuevoProducto");
              data.append("idNegocio", idNegocio);

              $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
              })
              .done(function(res) {
                  var datos = res.split("~");
                  if ($.trim(datos[0]) == "Correcto") {
                      Swal.fire({
                          icon: 'success',
                          title: 'El producto ha sido guardado correctamente'
                      });

                      document.getElementById("FormNuevoProducto").reset();
                      $("#ModalNuevoProducto").modal('hide');

                      $.ajax({
                          url: 'index.php',
                          type: 'POST',
                          data: "metodo=consultar&accion=negocios&tipo=ConsultarProductosNegocioNuevo&idProducto="+datos[1]
                      })
                      .done(function(res) {
                          var resA = JSON.parse(res);     
                          var table = $('#TablaProductos').DataTable();
                          table.row.add({ 
                            "Producto" : resA.data[0].Producto,
                            "Precio" : resA.data[0].Precio,
                            "Detalles" : resA.data[0].Detalles,
                            "Estatus" : resA.data[0].Estatus,
                            "Extras" : resA.data[0].Extras,
                            "Categorias" : resA.data[0].Categorias,
                            "Accion" : resA.data[0].Accion
                          }).draw(false);
                          moneda();
                      })
                      .fail(function() {
                          console.log("Error ajax");
                      });
                  }else{
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error inesperado al guardar producto.'
                      });

                      console.log($.trim(res));
                  }
              })
              .fail(function() {
                  console.log("Error ajax");
              })
              .always(function() {
                  $("#carga").hide();
              });
          }  
        }else if ($(this).attr("tipo") == "ModificarProducto") {
          form.validate({
              rules: {
                  NombreProducto: {
                      required: true
                  },
                  PreparacionProducto: {
                      required: true,
                      min: 0,
                      max: 100
                  },
                  PrecioProducto: {
                      required: true,
                      min: 0,
                  },
              }
          });

          if (!form.valid()) {
            return;
          }else{
              var idProducto = $("#GuardarNuevoProducto").attr("attrid");
              var data = new FormData(document.getElementById("FormNuevoProducto"));
              data.append("metodo", "modificar");
              data.append("accion", "negocios");
              data.append("tipo", "ModificarProducto");
              data.append("idProducto", idProducto);

              $.ajax({
                  url: 'index.php',
                  type: 'POST',
                  data: data,
                  processData: false,
                  contentType: false,
                  beforeSend: function() {
                    $("#carga").show();
                  }
              })
              .done(function(res) {
                  if ($.trim(res) == "Correcto") {
                      Swal.fire({
                          icon: 'success',
                          title: 'El producto ha sido modificado correctamente'
                      });

                      document.getElementById("FormNuevoProducto").reset();
                      $("#ModalNuevoProducto").modal('hide');

                      $.ajax({
                          url: 'index.php',
                          type: 'POST',
                          data: "metodo=consultar&accion=negocios&tipo=ConsultarProductosNegocioNuevo&idProducto="+$("#GuardarNuevoProducto").attr("attrid")
                      })
                      .done(function(res) {
                          var resA = JSON.parse(res);     
                          var boton = $(".btnModificarProducto"+$("#GuardarNuevoProducto").attr("attrid"));
                          var table = $('#TablaProductos').DataTable();
                          table.row( boton.parent().parent() ).remove().draw(false);
                          table.row.add({ 
                            "Producto" : resA.data[0].Producto,
                            "Precio" : resA.data[0].Precio,
                            "Detalles" : resA.data[0].Detalles,
                            "Estatus" : resA.data[0].Estatus,
                            "Extras" : resA.data[0].Extras,
                            "Categorias" : resA.data[0].Categorias,
                            "Accion" : resA.data[0].Accion
                          }).draw(false);
                          moneda();
                      })
                      .fail(function() {
                          console.log("Error ajax");
                      });
                  }else{
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error inesperado al guardar producto.'
                      });

                      console.log($.trim(res));
                  }
              })
              .fail(function() {
                  console.log("Error ajax");
              })
              .always(function() {
                  $("#carga").hide();
              });
          }  
        }
    });

    function TablaHorariosNegocio(id) {
      $("#TablaHorarios").dataTable({
              "destroy": true,
              "order": [0, 'desc'],
              "pageLength": 10,
              "ajax":{
                  "url": 'index.php',
                  "method": 'POST',
                  "data": {
                      "metodo": "consultar",
                      "accion": "negocios",
                      "tipo": "ConsultarHorariosNegocio",
                      "idNegocio": id
                  }
              },
              "autoWidth": false,
              "columns": [
                  { "data": "DiaRegistrado"},
                  { "data": "Horario"},
                  { "data": "HorarioEspecial"},
                  { "data": "Rango"},
                  { "data": "Accion"}
              ],
              "initComplete": function(settings, json) {
                  //console.log(json);
                  moneda();
              },
              "stateSave": true,
              "stateSaveParams": function (settings, data) {
                  data.search.search = "";
              },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
          }); 
    }

    function TablaCategoriasNegocio(id) {
      $("#TablaCategorias").dataTable({
              "destroy": true,
              "order": [0, 'desc'],
              "pageLength": 10,
              "ajax":{
                  "url": 'index.php',
                  "method": 'POST',
                  "data": {
                      "metodo": "consultar",
                      "accion": "negocios",
                      "tipo": "ConsultarCategoriasNegocio",
                      "idNegocio": id
                  }
              },
              "autoWidth": false,
              "columns": [
                  { "data": "Nombre"},
                  { "data": "Descripcion"},
                  { "data": "Accion"}
              ],
              "initComplete": function(settings, json) {
                  //console.log(json);
                  moneda();
              },
              "stateSave": true,
              "stateSaveParams": function (settings, data) {
                  data.search.search = "";
              },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
          }); 
    }

    function TablaProductosNegocio(id) {
      $("#TablaProductos").dataTable({
              "destroy": true,
              "order": [0, 'desc'],
              "pageLength": 10,
              "ajax":{
                  "url": 'index.php',
                  "method": 'POST',
                  "data": {
                      "metodo": "consultar",
                      "accion": "negocios",
                      "tipo": "ConsultarProductosNegocio",
                      "idNegocio": id
                  }
              },
              "autoWidth": false,
              "columns": [
                  { "data": "Producto"},
                  { "data": "Precio"},
                  { "data": "Detalles"},
                  { "data": "Estatus"},
                  { "data": "Extras"},
                  { "data": "Categorias"},
                  { "data": "Accion"},
              ],
              "initComplete": function(settings, json) {
                  //console.log(json);
                  moneda();
              },
              "stateSave": true,
              "stateSaveParams": function (settings, data) {
                  data.search.search = "";
              },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
          }); 
    }

    function TablaOpcionesProductos(id) {
      /*var data = "metodo=consultar&accion=negocios&idProducto="+id+"&tipo=ConsultarOpcionesProducto";
      $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
      })
      .done(function(res) {
        console.log(res);
      })
      .fail(function(){
        console.log("error ajax");
      });*/
      $("#TablaOpcionesProducto").dataTable({
        "destroy": true,
        "order": [0, 'desc'],
        "pageLength": 10,
        "ajax":{
            "url": 'index.php',
            "method": 'POST',
            "data": {
                "metodo": "consultar",
                "accion": "negocios",
                "tipo": "ConsultarOpcionesProducto",
                "idProducto": id
            }
        },
        "autoWidth": false,
        "columns": [
          { "data": "Titulo"},
          { "data": "Tipo"},
          { "data": "Cantidad"},
          { "data": "Detalles"},
          { "data": "Productos"},
          { "data": "Accion"}
        ],
        "initComplete": function(settings, json) {
          //console.log(json);
          moneda();
        },
        "stateSave": true,
        "stateSaveParams": function (settings, data) {
          data.search.search = "";
         },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
        }); 
    }

});

   