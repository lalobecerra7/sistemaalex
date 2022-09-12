function v_sucursales() {

    $('#FormSucursalNueva').validate({
        rules: {
            NombreSucursal: {
                required: true
            },
            EncargadoSucursal:{
                required: true
            },
            CalleSucursal:{
                required: true
            },
            NoExteriorSucursal:{
                required: true
            },
            ColoniaSucursal:{
                required: true
            },
            CPSucursal:{
                required: true
            },
            CiudadSucursal:{
                required: true
            },
            EstadoSucursal:{
                required: true
            },
            PaisSucursal:{
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
            EncargadoSucursal:{
                required: "El gerente de la sucursal es requerido"
            },
            CalleSucursal:{
                required: "La calle de la sucursal es requerido"
            },
            NoExteriorSucursal:{
                required: "El número esterior de la sucursal es requerido"
            },
            ColoniaSucursal:{
                required: "La colonia es requerida"
            },
            CPSucursal:{
                required: "El código postal es requerido"
            },
            CuidadSucursal:{
                required: "La ciudad es requerida"
            },
            EstadoSucursal:{
                required: "El estado es requerido"
            },
            PaisSucursal:{
                required: "El pais es requerido"
            },
            TelefonoSucursal:{
                required: "El telefono de la sucursal es requerido"
            },
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarSucu").attr("tipo")+"&accion=sucursales&IDSucursal="+$("#bGuardarSucu").attr("attrid")+"&NombreSucursal="+$("#NombreSucursal").val()+"&EncargadoSucursal="+$("#EncargadoSucursal").val()+"&CalleSucursal="+$("#CalleSucursal").val()+"&NoExteriorSucursal="+$("#NoExteriorSucursal").val()+"&NoInteriorSucursal="+$("#NoInteriorSucursal").val()+"&ColoniaSucursal="+$("#ColoniaSucursal").val()+"&CPSucursal="+$("#CPSucursal").val()+"&CiudadSucursal="+$("#CiudadSucursal").val()+"&EstadoSucursal="+$("#EstadoSucursal").val()+"&PaisSucursal="+$("#PaisSucursal").val()+"&EmailSucursal="+$("#EmailSucursal").val()+"&TelefonoSucursal="+$("#TelefonoSucursal").val()+"&Telefono2Sucursal="+$("#telefono2Sucursal").val();
           console.log(data);
            var btn = $('#bGuardarSucu');
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
                $("#carga").hide();
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
            $("#EncargadoSucursal").val(res.FK_Encargado);
            $("#CalleSucursal").val(res.Calle);
            $("#NoExteriorSucursal").val(res.No_Exterior);
            $("#NoInteriorSucursal").val(res.No_Interior);
            $("#ColoniaSucursal").val(res.Colonia);
            $("#CPSucursal").val(res.CP);
            $("#CiudadSucursal").val(res.Ciudad);
            $("#EstadoSucursal").val(res.Estado);
            $("#PaisSucursal").val(res.Pais);
            $("#EmailSucursal").val(res.Email);
            $("#telefono2Sucursal").val(res.Segundo_Telefono);
            $("#TelefonoSucursal").val(res.Telefono);
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
            "NombreGerente",
            "Correo",
            "Telefonos",
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