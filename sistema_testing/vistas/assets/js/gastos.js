var sucursalesSeleccionadasGastos = [];
function v_gastos() {
    var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaFinalGastos").val(today);
    
    now.setDate(now.getDate() - 10);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#FechaInicioGastos").val(today);

    TablaGastos();
    // CalcularTotalesVentasxProducto();
    sucursalesSeleccionadasGastos = [];
    SucursalesReporteGastos();

    $('#FormNuevoGasto').validate({
        rules: {
            FechaGasto: {
                required: true
            },
            FormaPagoGasto: {
                required: true
            },
            MontoGasto: {
                required: true
            },
            DescripcionGasto: {
                required: true
            },
        },
        messages: {
            FechaGasto: {
                required: "La fecha del gasto es requerida."
            },
            FormaPagoGasto: {
                required: "La forma de pago es requerida."
            },
            MontoGasto: {
                required: "El monto del gasto es requerido."
            },
            DescripcionGasto: {
                required: "La descrición es requerida."
            },
        },
        submitHandler: function(form) { 
            var data = new FormData(document.getElementById("FormNuevoGasto"));
            data.append("metodo", $("#bGuardarGasto").attr("tipo"));
            data.append("accion", "gastos");
            data.append("IDGasto", $("#bGuardarGasto").attr("attrid"));

            var btn = $('#bGuardarGasto');
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
                    $("#ModalGastos").modal("hide");
                    if ($("#bGuardarGasto").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Gasto '+tipoAlerta+' correctamente'
                    });
                    TablaGastos();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarGasto").attr("tipo")+' sucursal.'
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
    
    $(document).on('change', '#FechaInicioGastos', function() {
        TablaGastos();
        // CalcularTotalesVentasxProducto();
    });
    
    $(document).on('change', '#FechaFinalGastos', function() {
        TablaGastos();
        // CalcularTotalesVentasxProducto();
    });


    $(document).on('click', '#bontonNuevoGasto', function() {
        $("#TituloModalGastos").text("Agregar nuevo");
        $("#bGuardarGasto").attr('tipo', 'insertar');
        $("#bGuardarGasto").attr('attrid', '');
        $("#FormNuevoGasto").trigger("reset");
        var now = new Date();
        var day = now.getDate().toString().padStart(2, '0');
        var month = (now.getMonth() + 1).toString().padStart(2, '0');
        var today = now.getFullYear()+"-"+(month)+"-"+(day);

        console.log(today)
        $("#FechaGasto").val(today);
    });

    $(document).on('click', '#EliminarGasto', function() {
        var boton = $(this);
        Swal.fire({
          title: '¿Deseas eliminar el gasto con el concepto de '+boton.attr('descripcion')+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=gastos&id="+boton.attr('attrid');

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    TablaGastos();
                    Swal.fire({
                        icon: 'success',
                        title: 'Gasto eliminado correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar el gasto.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarGasto', function() {
        $("#TituloModalGastos").text("Modificar");
        var id = $(this).attr('attrid');

        var data = "metodo=detalles&accion=gastos&IDGasto="+id+"&tipo=ConsultarGasto";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log($.trim(res));
            var res = JSON.parse(res);
            $("#bGuardarGasto").attr("attrid", id);
            $("#bGuardarGasto").attr("tipo", "modificar");
            $("#FechaGasto").val(res.Fecha_Gasto);
            $("#FormaPagoGasto").val(res.Forma_Pago);
            $("#MontoGasto").val(res.Monto);
            $("#DescripcionGasto").val(res.Descripcion);
            $("#ModalGastos").modal("show");
        })
        .fail(function() {
            console.log("Error ajax");
        });  
    });

    $(document).on('click', '#CargarSucursalesModalGastos', function() {
        $("#ModalSucursalesGastos").modal("show");
    });

    $(document).on('click', '.CheckInputSucursalGastos', function() {
        var boton = $(this);
        if (boton.prop("checked") == true) {
            sucursalesSeleccionadasGastos.push({
                "ID" : boton.attr("attrid"),
                "Nombre" : boton.attr("nombre"),
            })
        }else{
            const nuevoarreglo = sucursalesSeleccionadasGastos.filter(item => item.ID !== boton.attr("attrid")); //Si la desseleccionan se elimina del arreglo
            sucursalesSeleccionadasGastos = nuevoarreglo;
        }
    });

    $(document).on('click', '#SeleccionarSucursalesMarcadasGastos', function() {
        TablaGastos();
        var textoSucursales = "";
        for (var i = 0; i < sucursalesSeleccionadasGastos.length; i++) {
            textoSucursales += sucursalesSeleccionadasGastos[i]["Nombre"]+", ";
        }
        var str = textoSucursales.replace(/,\s*$/, "");
        if (sucursalesSeleccionadasGastos.length <= 0) {
            str = "No has seleccionado sucursales";
        }
        $("#MostrarSucursalesSeleccionadasGastos").text(str);
        $("#ModalSucursalesGastos").modal("hide")
    });

});

function SucursalesReporteGastos(){
    ajaxMyDatatable({
        "table": $("#TablaSucursalesGastos"), 
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
            "accion": "gastos"
        }
    });
}

function TablaGastos(){
    ajaxMyDatatable({
        "table": $("#TablaGastos"), 
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
            "accion": "gastos",
            "fechaInicio": $("#FechaInicioGastos").val(),
            "fechaFin": $("#FechaFinalGastos").val(),
            "Sucursales": JSON.stringify(sucursalesSeleccionadasGastos) 
        }
    });
}