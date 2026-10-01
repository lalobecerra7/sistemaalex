var sucursalesSeleccionadasDepositos = [];
function v_depositos() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalDepositos").val(today);
    
    now.setDate(now.getDate() - 10);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioDepositos").val(today);

    TablaDepositos();
    // CalcularTotalesVentasxProducto();
    sucursalesSeleccionadasDepositos = [];
    SucursalesReporteDepositos();

    $('#FormNuevoDepositos').validate({
        rules: {
            FechaDepositos: {
                required: true
            },
            MontoDepositos: {
                required: true
            },
            DescripcionDepositos: {
                required: true
            },
        },
        messages: {
            FechaDepositos: {
                required: "La fecha del deposito es requerida."
            },
            MontoDepositos: {
                required: "El monto del deposito es requerido."
            },
            DescripcionDepositos: {
                required: "La descripción es requerida."
            },
        },
        submitHandler: function(form) { 
            var data = new FormData(document.getElementById("FormNuevoDepositos"));
            data.append("metodo", $("#bGuardarDepositos").attr("tipo"));
            data.append("accion", "depositos");
            data.append("IDDepositos", $("#bGuardarDepositos").attr("attrid"));

            var btn = $('#bGuardarDepositos');
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
                    $("#ModalDepositos").modal("hide");
                    if ($("#bGuardarDepositos").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Deposito '+tipoAlerta+' correctamente'
                    });
                    TablaDepositos();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarDepositos").attr("tipo")+' sucursal.'
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


jQuery(document).ready(function($) {
    
    $(document).on('change', '#FechaInicioDepositos', function() {
        TablaDepositos();
        // CalcularTotalesVentasxProducto();
    });
    
    $(document).on('change', '#FechaFinalDepositos', function() {
        TablaDepositos();
        // CalcularTotalesVentasxProducto();
    });


    $(document).on('click', '#bontonNuevoDeposito', function() {
        $("#TituloModalDepositos").text("Agregar nuevo");
        $("#bGuardarDepositos").attr('tipo', 'insertar');
        $("#bGuardarDepositos").attr('attrid', '');
        $("#FormNuevoDepositos").trigger("reset");
        var now = new Date();
        var day = now.getDate().toString().padStart(2, '0');
        var month = (now.getMonth() + 1).toString().padStart(2, '0');
        var today = now.getFullYear()+"-"+(month)+"-"+(day);

        console.log(today)
        $("#FechaDepositos").val(today);
    });

    $(document).on('click', '#EliminarDeposito', function() {
        var boton = $(this);
        Swal.fire({
          title: '¿Deseas eliminar el deposito con el concepto de '+boton.attr('descripcion')+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=depositos&id="+boton.attr('attrid');

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaDepositos();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deposito eliminado correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar el deposito.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarDeposito', function() {
        $("#TituloModalDepositos").text("Modificar");
        var id = $(this).attr('attrid');

        var data = "metodo=detalles&accion=depositos&IDDepositos="+id+"&tipo=ConsultarDepositos";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log($.trim(res));
            var res = JSON.parse(res);
            $("#bGuardarDepositos").attr("attrid", id);
            $("#bGuardarDepositos").attr("tipo", "modificar");
            $("#FechaDepositos").val(res.Fecha_Deposito);
            $("#MontoDepositos").val(res.Monto);
            $("#DescripcionDepositos").val(res.Descripcion);
            $("#ModalDepositos").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });  
    });

    $(document).on('click', '#CargarSucursalesModalDepositos', function() {
        $("#ModalSucursalesDepositos").modal("show");
    });

    $(document).on('click', '.CheckInputSucursalDepositos', function() {
        var boton = $(this);
        if (boton.prop("checked") == true) {
            sucursalesSeleccionadasDepositos.push({
                "ID" : boton.attr("attrid"),
                "Nombre" : boton.attr("nombre"),
            })
        }else{
            const nuevoarreglo = sucursalesSeleccionadasDepositos.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
            sucursalesSeleccionadasDepositos = nuevoarreglo;
        }
    });

    $(document).on('click', '#SeleccionarSucursalesMarcadasDepositos', function() {
        TablaDepositos();
        var textoSucursales = "";
        for (var i = 0; i < sucursalesSeleccionadasDepositos.length; i++) {
            textoSucursales += sucursalesSeleccionadasDepositos[i]["Nombre"]+", ";
        }
        var str = textoSucursales.replace(/,\s*$/, "");
        if (sucursalesSeleccionadasDepositos.length <= 0) {
            str = "No has seleccionado sucursales";
        }
        $("#MostrarSucursalesSeleccionadasDepositos").text(str);
        $("#ModalSucursalesDepositos").modal("hide")
    });

});

function SucursalesReporteDepositos(){
    ajaxMyDatatable({
        "table": $("#TablaSucursalesDepositos"), 
        "colums": [
            "Seleccionar",
            "Sucursal",
            "Direccion",
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "tipo": "ConsultarSucursales",
            "accion": "depositos"
        }
    });
}

function TablaDepositos(){
    ajaxMyDatatable({
        "table": $("#TablaDepositos"), 
        "colums": [
            "Fecha",
            "Sucursal",
            "Descripcion",
            "Monto",
            "Evidencias",
            "Acciones"
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "depositos",
            "fechaInicio": $("#FechaInicioDepositos").val(),
            "fechaFin": $("#FechaFinalDepositos").val(),
            "Sucursales": JSON.stringify(sucursalesSeleccionadasDepositos) 
        }
    });
}