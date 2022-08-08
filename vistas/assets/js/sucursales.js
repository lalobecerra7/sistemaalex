function v_sucursales() {

    $('#FormSucursalNueva').validate({
        rules: {
            NombreSucursal: {
                required: true
            },
            DireccionSucursal:{
                required: true
            },
            TelefonoSucursal:{
                required: true
            },
        },
        messages: {
            NombreSucursal: {
                required: "El nombre de la sucursal es requerido"
            },
            DireccionSucursal:{
                required: "La dirección de la sucursal es requerida"
            },
            TelefonoSucursal:{
                required: "El telefono de la sucursal es requerido"
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarSucu").attr("tipo")+"&accion=sucursales&IDSucursal="+$("#bGuardarSucu").attr("attrid")+"&NombreSucursal="+$("#NombreSucursal").val()+"&DireccionSucursal="+$("#DireccionSucursal").val()+"&TelefonoSucursal="+$("#TelefonoSucursal").val()+"&RFCSucursal="+$("#RFCSucursal").val()+"&NombreSucursalGerente="+$("#NombreSucursalGerente").val();
            var btn = $('#bGuardarSucu');
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    progressBoton(btn);
                }
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    $("#ModalSucursal").modal("hide");
                    if ($("#bGuardarSucu").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificada";
                    }else{
                        var tipoAlerta = "guardada";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucursal '+tipoAlerta+' correctamente'
                    });
                    TablaSucursales();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarSucu").attr("tipo")+' sucursal.'
                    });
                    console.log($.trim(res));
                }
            })
            .fail(function() {
                console.log("Error ajax");
            })
            .always(function() {
                unprogressBoton(btn);
            });          
        }
    });  

    TablaSucursales();  
}


jQuery(document).ready(function($) {
    

    $(document).on('click', '#bontonNuevoSu', function() {
        $("#TituloModalSucursal").text("Agregar nueva");
        $("#bGuardarSucu").attr('tipo', 'insertar');
        $("#bGuardarSucu").attr('attrid', '');
        $("#FormSucursalNueva").trigger("reset");
    });

    $(document).on('click', '#EliminarSucursal', function() {
        var boton = $(this);
        Swal.fire({
          title: '¿Deseas eliminar la sucursal con el nombre de '+boton.attr('nombre')+'?',
          text: "Una vez eliminada ya no podrá ser recuperada",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=sucursales&id="+boton.attr('attrid');

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaSucursales();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucursal eliminada correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar la sucursal.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarSucursal', function() {
        $("#TituloModalSucursal").text("Modificar");
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=sucursales&id="+id+"&tipoDetalle=1";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            var res = JSON.parse(res);
            $("#bGuardarSucu").attr("attrid", id);
            $("#bGuardarSucu").attr("tipo", "modificar");
            $("#NombreSucursal").val(res.Nombre);
            $("#DireccionSucursal").val(res.Direccion);
            $("#TelefonoSucursal").val(res.Telefono);
            $("#RFCSucursal").val(res.RFC);
            $("#NombreSucursalGerente").val(res['Nombre del Gerente']);
            $("#ModalSucursal").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });  
    });
});

function TablaSucursales(){
    ajaxMyDatatable({
        "table": $("#TablaSucursales"), 
        "colums": [
            "Nombre",
            "Direccion",
            "Telefono",
            "RFC",
            "NombreGerente",
            "Acciones"
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "sucursales"
        }
    });
}