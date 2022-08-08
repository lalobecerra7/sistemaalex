function v_proveedores() {
    TablaProveedores();

    $('#FormProveedor').validate({
        rules: {
            NombreEmpresaProveedor: {
                required: true
            },
            DireccionProveedor:{
                required: true
            },
            ContactoProveedor:{
                required: true
            },
            CelularContactoProveedor:{
                required: true
            },
        },
        messages: {
            NombreEmpresaProveedor: {
                required: "El nombre de la empresa es obligatorio"
            },
            DireccionProveedor:{
                required: "La dirección del proveedor es obligatoria"
            },
            ContactoProveedor:{
                required: "El nombre del contacto es obligatorio"
            },
            CelularContactoProveedor:{
                required: "El telefono del contacto es obligatorio"
            },
        },
        submitHandler: function(form) { 
            var descuentoProveedor = 0;
            if ($("#TipoDescuentoProveedor").val() != "" && ($("#DescuentoProveedor").val() == "" || $("#DescuentoProveedor").val() < 0)) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Ingresa la cantidad del descuento'
                });
                $("#DescuentoProveedor").focus();
            }else{
                if ($("#DescuentoProveedor").val() == "" || $("#DescuentoProveedor").val() < 0) {
                    descuentoProveedor = 0;
                }else{
                    descuentoProveedor = $("#DescuentoProveedor").val();
                }
                var data = "metodo="+$("#GuardarProveedor").attr("tipo")+"&accion=proveedores&IDProveedor="+$("#GuardarProveedor").attr("attrid")+"&NombreEmpresaProveedor="+$("#NombreEmpresaProveedor").val()+"&RazonSocialProveedor="+$("#RazonSocialProveedor").val()+"&TelefonoProveedor="+$("#TelefonoProveedor").val()+"&DireccionProveedor="+$("#DireccionProveedor").val()+"&ColoniaProveedor="+$("#ColoniaProveedor").val()+"&CiudadProveedor="+$("#CiudadProveedor").val()+"&EstadoProveedor="+$("#EstadoProveedor").val()+"&PaisProveedor="+$("#PaisProveedor").val()+"&CPProveedor="+$("#CPProveedor").val()+"&ContactoProveedor="+$("#ContactoProveedor").val()+"&PuestoContactoProveedor="+$("#PuestoContactoProveedor").val()+"&CorreoContactoProveedor="+$("#CorreoContactoProveedor").val()+"&CelularContactoProveedor="+$("#CelularContactoProveedor").val()+"&RFCProveedor="+$("#RFCProveedor").val()+"&BancoProveedor="+$("#BancoProveedor").val()+"&NoCuentaProveedor="+$("#NoCuentaProveedor").val()+"&TipoDescuento="+$("#TipoDescuentoProveedor").val()+"&DescuentoProveedor="+descuentoProveedor+"&Credito="+$("#CreditoProveedor").val();
                var btn = $('#GuardarProveedor');
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
                        $("#ModalProveedor").modal("hide");
                        if ($("#GuardarProveedor").attr("tipo") == "modificar") {
                            var tipoAlerta = "modificado";
                        }else{
                            var tipoAlerta = "guardado";
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Proveedor '+tipoAlerta+' correctamente'
                        });
                        TablaProveedores();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al '+$("#GuardarProveedor").attr("tipo")+' proveedor.'
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
        }
    });    
}

jQuery(document).ready(function($) {
    
    $(document).on('click', '#bontonNuevoProve', function() {
        $("#TituloModalProveedor").text("Agregar nuevo");
        $("#GuardarProveedor").attr('tipo', 'insertar');
        $("#GuardarProveedor").attr('attrid', '');
        $("#FormProveedor").trigger("reset");
        $("#TipoDescuentoProveedor").trigger("change");
        $("#DescuentoProveedor").val("");
    });

    $(document).on('click', '#EliminarProveedor', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        Swal.fire({
          title: '¿Deseas eliminar el proveedor '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=proveedores&id="+id;

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaProveedores();
                    Swal.fire({
                        icon: 'success',
                        title: 'Proveedor eliminado correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar proveedor.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarProveedor', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=proveedores&IDProveedor="+id;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            var datos = JSON.parse(res);
            $("#GuardarProveedor").attr("attrid", id);
            $("#GuardarProveedor").attr("tipo", "modificar");
            $("#TituloModalProveedor").text("Modificar");
            $("#NombreEmpresaProveedor").val(datos.Empresa);
            $("#RazonSocialProveedor").val(datos.Razon_Social);
            $("#TelefonoProveedor").val(datos.Telefono);
            $("#DireccionProveedor").val(datos.Direccion);
            $("#ColoniaProveedor").val(datos.Colonia);
            $("#CiudadProveedor").val(datos.Ciudad);
            $("#EstadoProveedor").val(datos.Estado);
            $("#PaisProveedor").val(datos.Pais);
            $("#CPProveedor").val(datos.Codigo_Postal);
            $("#ContactoProveedor").val(datos.Nombre);
            $("#PuestoContactoProveedor").val(datos.Puesto);
            $("#CorreoContactoProveedor").val(datos.Correo);
            $("#CelularContactoProveedor").val(datos.Celular);
            $("#RFCProveedor").val(datos.RFC);
            $("#BancoProveedor").val(datos.Banco);
            $("#NoCuentaProveedor").val(datos.No_Cuenta);
            $("#TipoDescuentoProveedor").val(datos.Tipo_Descuento);
            $("#TipoDescuentoProveedor").trigger("change");
            $("#DescuentoProveedor").val(datos.Descuento);
            $("#DescuentoProveedor").trigger("keyup");
            $("#ModalProveedor").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('change', '#TipoDescuentoProveedor', function() {
        var tipo = $(this).val();
        if (tipo != "") {
            $("#TituloTipoDescuentoProveedor").text(tipo);
            $("#DescuentoProveedor").attr("disabled", false);
            $("#DescuentoProveedor").val("");
        }else{
            $("#TituloTipoDescuentoProveedor").text("No aplica descuento");
            $("#DescuentoProveedor").attr("disabled", true);
            $("#DescuentoProveedor").val("");
        }
        $("#DescuentoProveedor").trigger("keyup");
    });

    $(document).on('keyup', '#DescuentoProveedor', function() {
        var tipo = $('#TipoDescuentoProveedor').val();
        if (tipo == "Porcentaje") {
            $("#LabelDescuentoProveedor").text($(this).val()+" %");
        }else if (tipo == "Cantidad") {
            $("#LabelDescuentoProveedor").text("$"+$(this).val());
        }else{
            $("#LabelDescuentoProveedor").text("No aplica");
        }
    });
});

function TablaProveedores(){
    ajaxMyDatatable({
        "table": $("#TablaProveedores"), 
        "colums": [
            "Fecha",
            "Empresa",
            "Contacto",
            "Direccion",
            "Acciones"
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "proveedores"
        }
    });
}