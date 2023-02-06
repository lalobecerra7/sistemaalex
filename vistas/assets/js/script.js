//formato de modeda a la clase .dinero
function moneda() {
    $(".dinero").each(function(index, el) {
        if(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) < 0){
            $(this).html(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * -1);
            $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
            $(this).html('-'+$(this).html());
        }else{
            $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
        }
    });

    $(".porcentaje").each(function(index, el) {
        if(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) < 0){
            $(this).html(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * -1);
            $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * 100) / 100)+'%');
            $(this).html('-'+$(this).html());
        }else{
            $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * 100) / 100)+'%');
        }
    });

    $(".cantidad").each(function(index, el) {
        $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
    });
}

jQuery(document).ready(function($) {
    $("#carga").hide();
    EstatusCaja();

    setInterval(function() {
        var data = "metodo=renovar";

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log("Sesion renovada");    
        })
        .fail(function(){
            console.log("error ajax");
        });
    }, 60000*10);
    
    $(document).on('click', '.cargarVista', function() {
        var nombre = $(this).attr('carga'), titulo = $(this).attr('titulo'), id = $(this).attr('id'), atri = $(this).attr('atri'), pesta = $(this).attr('pesta'); 
        var data = "metodo=cambiar&accion="+nombre+"&atri="+atri+"&pesta="+pesta;
        idVista = $(this).attr('id');
        var itemVista = $(this);
        
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            $("#verVista").html(res);
            $("#vistaTitulo").html(titulo);
            $(".cargarVista").removeClass("active");
            itemVista.addClass("active");
            if(nombre == "v_inicio"){
             
            }
            EstatusCaja();
            crearDataTable();
            
            if(typeof window[nombre] === 'function') {
              window[nombre]();
            }
        })
        .fail(function() {
            console.log("Error ajax");
        }).always(function() {
            $("#carga").hide();
        }); 
    });

    $(document).on('click', '#CerrarSesion', function() {
        cerrarSesion();
    });

    //CERRAR CAJA
    $('#FormCerrarCaja').validate({
        rules: {
            MontoCierreCaja: {
                required: true,
                min: 1,
            },
        },
        messages: {
            MontoCierreCaja: {
                required: "Ingresa el monto de cierre de la caja"
            },
        },
        submitHandler: function(form) { 
            $("#spanMontoApertura").text("");
            $("#spanMontoCierre").text("");
            $("#spanFechaAbrir").text("");
            $("#spanFechaCerrar").text("");
            $("#totalIngresosSpan").text("");
            $("#totalEgresosSpan").text("");
            $("#totalUtilidadSpan").text("");
            $("#spanTotalVentas").text("");
            $("#spanTotalImportes").text("");
            $("#spanTotalCompras").text("");
            $("#spanTotalComprasCredito").text("");
            $("#spanTotalPagos").text("");
            $("#spanTotalDevoluciones").text("");
            $("#DivMostrarVentasDesplegada").html("");
            $("#DivMostrarComprasDesplegado").html("");
            $("#DivMostrarPagosDesplegado").html("");

            var data = "metodo=detalles&accion=hacerventa&tipo=CerrarCaja&MontoCierre="+$("#MontoCierreCaja").val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                var datos = res.split("~");
                if ($.trim(datos[0]) == "Correcto") {
                    $("#ModalCerrarCaja").modal("hide");
                    $("#ModalBalanceCaja").modal("show");
                    var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarBalanceCerrar&IDDetalleCaja="+datos[1];
                    $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: data
                    })
                    .done(function(res) {
                        var datos = JSON.parse($.trim(res));
                        console.log(datos);
                        $("#spanMontoApertura").text(datos[0].Monto_Abrir);
                        $("#spanMontoCierre").text(datos[0].Monto_Cierre);
                        $("#spanUsuarioAbrir").text(datos[0].Usuario_Abrir);
                        $("#spanUsuarioCerrar").text(datos[0].Usuario_Cerrar);
                        $("#spanFechaAbrir").text(datos[0].Fecha_Abrir);
                        $("#spanFechaCerrar").text(datos[0].Fecha_Cerrar);
                        var totalIngresos = parseFloat(datos[0].Monto_Abrir) + parseFloat(datos[0].Total_Ingresos);
                        $("#totalIngresosSpan").text(totalIngresos);
                        $("#totalEgresosSpan").text(datos[0].Total_Egresos);
                        var utilidad = parseFloat(datos[0].Total_Ingresos) - parseFloat(datos[0].Total_Egresos);
                        $("#totalUtilidadSpan").text(utilidad);
                        //INGRESOS
                        $("#spanTotalVentas").text(datos[0].Total_Ventas);
                        $("#spanTotalImportes").text(datos[0].Total_Importes);
                        //EGRESOS
                        $("#spanTotalCompras").text(datos[0].Total_Compras);
                        $("#spanTotalComprasCredito").text(datos[0].Total_Pagos);
                        $("#spanTotalPagos").text(datos[0].Total_Pagos);
                        $("#spanTotalDevoluciones").text(datos[0].Total_Devoluciones);
                        var totalIngresosEfectivo = parseFloat(datos[0].Monto_Abrir) + parseFloat(datos[0].Total_Ingresos_Efectivo);
                        var totalEgresosEfectivo = parseFloat(datos[0].Total_Egresos_Efectivo)
                        var totalActualEfectivo = totalIngresosEfectivo - totalEgresosEfectivo;
                        $("#totalActualEfectivo").text(totalActualEfectivo);
                        var diferencia = parseFloat(datos[0].Monto_Cierre) - totalActualEfectivo;
                        $("#totalDiferenciaSpan").text(diferencia);
                        $("#totalIngresosEfectivo").text(totalIngresosEfectivo);
                        $("#totalEgresosEfectivo").text(totalEgresosEfectivo);

                        if (datos[0].Total_Ventas_Efectivo > 0) {
                            $("#DivMostrarVentasDesplegada").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Ventas en efectivo <span class="dinero">`+datos[0].Total_Ventas_Efectivo+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Ventas_Deposito > 0) {
                            $("#DivMostrarVentasDesplegada").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Ventas en depósito <span class="dinero">`+datos[0].Total_Ventas_Deposito+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Ventas_Cheque > 0) {
                            $("#DivMostrarVentasDesplegada").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Ventas en cheque <span class="dinero">`+datos[0].Total_Ventas_Cheque+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Ventas_TransferenciaBancaria > 0) {
                            $("#DivMostrarVentasDesplegada").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Ventas en transferencia bancaria <span class="dinero">`+datos[0].Total_Ventas_TransferenciaBancaria+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Ventas_TarjetaCreditoDebito > 0) {
                            $("#DivMostrarVentasDesplegada").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Ventas en tarjeta de crédito / debito <span class="dinero">`+datos[0].Total_Ventas_TarjetaCreditoDebito+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Ventas_PagoOnline > 0) {
                            $("#DivMostrarVentasDesplegada").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Ventas en pago online <span class="dinero">`+datos[0].Total_Ventas_PagoOnline+`</span>
                                    </div>
                                </div>`);
                        }

                        //COMPRAS DESGLOZADAS
                        if (datos[0].Total_Compras_Efectivo > 0) {
                            $("#DivMostrarComprasDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Compras en efectivo <span class="dinero">`+datos[0].Total_Compras_Efectivo+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Compras_Cheque > 0) {
                            $("#DivMostrarComprasDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Compras en cheque <span class="dinero">`+datos[0].Total_Compras_Cheque+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Compras_Deposito > 0) {
                            $("#DivMostrarComprasDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Compras en deposito <span class="dinero">`+datos[0].Total_Compras_Deposito+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Compras_Tarjeta > 0) {
                            $("#DivMostrarComprasDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Compras en transferencia bancaria <span class="dinero">`+datos[0].Total_Compras_Tarjeta+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Compras_Transferencia > 0) {
                            $("#DivMostrarComprasDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Compras en tarjeta de crédito / debito <span class="dinero">`+datos[0].Total_Compras_Transferencia+`</span>
                                    </div>
                                </div>`);
                        }

                        ///PAGOS
                        if (datos[0].Total_Pagos_Efectivo > 0) {
                            $("#DivMostrarPagosDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Pagos en efectivo <span class="dinero">`+datos[0].Total_Pagos_Efectivo+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Pagos_Deposito > 0) {
                            $("#DivMostrarPagosDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Pagos en deposito <span class="dinero">`+datos[0].Total_Pagos_Deposito+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Pagos_Cheque > 0) {
                            $("#DivMostrarPagosDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Pagos en cheques <span class="dinero">`+datos[0].Total_Pagos_Cheque+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Pagos_TransferenciaBancaria > 0) {
                            $("#DivMostrarPagosDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Pagos en transferencia bancaria <span class="dinero">`+datos[0].Total_Pagos_TransferenciaBancaria+`</span>
                                    </div>
                                </div>`);
                        }

                        if (datos[0].Total_Pagos_TarjetaCreditoDebito > 0) {
                            $("#DivMostrarPagosDesplegado").append(`
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        Pagos en tarjeta de crédito / debito <span class="dinero">`+datos[0].Total_Pagos_TarjetaCreditoDebito+`</span>
                                    </div>
                                </div>`);
                        }

                        $("#ImprimirBalance").attr("attrid", datos[0].ID_Detalle_Caja);
                        moneda();
                    })
                    .fail(function() {
                        console.log("Error ajax");
                    })
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al cerrar caja.'
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

    $(document).on('hidden.bs.modal', '#ModalBalanceCaja',function(){
        $("#cargarVentas").trigger("click");
    });

    $(document).on('click', '#ImprimirBalance', function() {
        var iddetalle = $(this).attr("attrid");
        var idsucursal = $("#SucursalVenta").attr("attrid");
        var altura=50;
        var anchura=310;
        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
        window.open("controladores/ticketCaja.php?id="+iddetalle+"&idsucursal="+idsucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
        $("#ModalBalanceCaja").modal("hide");
    });

    $(document).on('click', '#VerContrasenas', function() {
        console.log( $(this).parent().parent().html());
        if($(this).children('i').hasClass('fa-eye')){
            $(this).children('i').removeClass('fa-eye');
            $(this).children('i').addClass('fa-eye-slash');
            $('.contraCampo').attr('type', 'text');
        }else{
            $(this).children('i').removeClass('fa-eye-slash');
            $(this).children('i').addClass('fa-eye');
            $('.contraCampo').attr('type', 'password');
        }
    });

    $(document).on('click', '#GuardarNuevaContrasena', function(event) {
      event.preventDefault();
      var boton = $(this);
      var form = $('#FormNuevaContrasena');

      form.validate({
        rules: {
          ContrasenaActual: {
            required: true,
          },
          ContrasenaRepetir: {
            required: true,
            equalTo: "#ContrasenaNueva"
          },

        },
        messages: {
            ContrasenaActual: {
                required: "La contraseña actual es obligatoria"
            },
            ContrasenaRepetir:{
                required: "La nueva contraseña es obligatoria"
            },
        },
      });

      if (!form.valid()) {
        return;
      }else{
        Swal.fire({
          title: '¿Estas a punto de cambiar tu contraseña actual?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, guardar',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {
            var data = "metodo=detalles&accion=usuarios&tipo=CambiarContrasena&ContraActual="+$("#ContrasenaActual").val()+"&ContraNueva="+$("#ContrasenaNueva").val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
              if ($.trim(res) == "Correcto") {
                Swal.fire({
                  icon: 'success',
                  title: 'Contraseña cambiada correctamente'
                });
                $("#FormNuevaContrasena").trigger("reset");
                $("#ModalCambiarContrasena").modal("hide");
              }else{
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error inesperado al cambiar la contraseña.'
                });
                console.log(res);
              }
            })
            .fail(function(){
              console.log("error ajax");
            });
          }
        });
      }
    });  

});

function permisos() {
    var data = "metodo=detalles&accion=usuarios&tipo=ConsultarPermisosUsuario";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        console.log($.trim(res));
        var resA = JSON.parse(res);
        if(resA.Tipo != "Administrador"){
          //$(".cargarVista").hide();
          //$(".cargarVista[carga='v_usuarios']").remove();
          var cadena = resA.Cadena.split('~');
          var permisos = "";
          //console.log(cadena);

          for (var i = cadena.length - 1; i >= 0; i--) {
            permisos = cadena[i].split(',');
            if(permisos[0] == "v_ventas"){
              if(permisos[2] == '0'){
                $("#cargarHacerVenta").remove();
              }
            }

            if(permisos[0] == "v_inicio"){
              if(permisos[1] == '1'){
                setTimeout(function(){
                    $("#cargarInicio").trigger("click");
                }, 10);
              }else{
                $(".cargarVista[carga='"+permisos[0]+"']").remove();
              }
            }else{
              if(permisos[1] == '0'){
                $(".cargarVista[carga='"+permisos[0]+"']").remove();
              }       
            }                
          }
        }else{
          setTimeout(function(){
            $("#cargarInicio").trigger("click");
          }, 10);
        }
    })
    .fail(function() {
        console.log("Error ajax");
    })
    .always(function() {
        //console.log("complete");
    });
}

function cerrarSesion(){
    var data="metodo=eliminar&accion=login";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
    })
    .done(function(res) {
        console.log(res);
        window.location.reload();
    })
    .fail(function() {
        console.log("Error ajax");
    });
}

function readURL(input,ima) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $(ima).html("<img src='"+e.target.result+"' style='width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;' class='img-thumbnail'><br>");
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function ConsultarImagen(){
  var data="metodo=detalles&accion=perfil";
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: data,
  })
  .done(function(res) {
    if ($.trim(res) != "") {
      $(".imagenPerfilChica").attr("src", "vistas/assets/archivos/fotosUsuarios/"+$.trim(res));
    }else{
      $(".imagenPerfilChica").attr("src", "vistas/assets/archivos/default.jpg");
    }
    
  })
  .fail(function() {
    console.log("Error ajax");
  });
}