function v_cajas() {
    TablaCajas(); 
    $('#FormCajas').validate({
        rules: {
            NombreCaja: {
                required: true
            },
            SucursalesCaja: {
                required: true
            },
        },
        messages: {
            NombreCaja: {
                required: "El nombre de la caja es obligatorio"
            },
            SucursalesCaja: {
                required: "Seleccione una sucursal para esta caja"
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#GuardarCaja").attr("tipo")+"&accion=cajas&IDCaja="+$("#GuardarCaja").attr("attrid")+"&Nombre="+$("#NombreCaja").val()+"&Detalles="+$("#DetallesCaja").val()+"&Sucursal="+$("#SucursalesCaja").val();
            var btn = $('#GuardarCaja');
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    if ($("#GuardarCaja").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificada";
                    }else{
                        var tipoAlerta = "guardada";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Caja '+tipoAlerta+' correctamente'
                    });
                    TablaCajas(); 
                    $("#ModalCajas").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#GuardarCaja").attr("tipo")+' Caja.'
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
  
}

function TablaCajas(){
    ajaxMyDatatable({
        "table": $("#TablaCajas"), 
        "colums": [
            "Caja",
            "Sucursal",
            "Detalles",
            "Estatus",
            "Usuario",
            "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "cajas"
        }
    });
}

function ConsultarSucursales(){
    var data = "metodo=detalles&accion=cajas&tipo=ConsultarSucursales";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        $("#SucursalesCaja").html(res);
    })
    .fail(function() {
        console.log("Error ajax");
    });  
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevaCaja', function() {
        $("#GuardarCaja").attr('tipo', "insertar");
        $("#GuardarCaja").attr('attrid', "");
        $("#FormCajas").trigger('reset');
        $("#TituloModalCajas").text("Agregar nueva");
        ConsultarSucursales();
    });

    $(document).on('click', '#EliminarCaja', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        console.log(id);
        Swal.fire({
          title: '¿Estás a punto de eliminar la caja '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=cajas&IDCaja="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                console.log(res);
                if ($.trim(res) == "Correcto") {
                    TablaCajas();
                    Swal.fire({
                        icon: 'success',
                        title: 'Caja eliminada correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar Caja.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarCaja', function() {
        var id = $(this).attr('attrid');
        ConsultarSucursales();
        var data = "metodo=detalles&accion=cajas&IDCaja="+id+"&tipo=ConsultarCaja";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log(res);
            $("#GuardarCaja").attr('tipo', 'modificar');
            $("#GuardarCaja").attr('attrid', id);
            $("#TituloModalCajas").text("Modificar");
            var datos = JSON.parse($.trim(res));
            $("#NombreCaja").val(datos.Nombre);
            $("#SucursalesCaja").val(datos.FK_Sucursal);
            $("#DetallesCaja").val(datos.Detalles);
            $('#ModalCajas').modal('show');
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

});

