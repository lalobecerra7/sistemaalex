var ventas_cliente = [];
var recaud = 0;

function check_and_push(idCliente, data){
    const existingObject = ventas_cliente.find((indice) => indice.cliente === idCliente);
    if(!existingObject){
        ventas_cliente.push(data);
    }
}

function remove_venta(idCliente, idVenta){
    ventas_cliente.map(item => {
        if(item.cliente === idCliente){
            item.ventas = item.ventas.filter(venta => !idVenta.includes(venta));
        }
        return item;
    })
}

function add_venta(idCliente, idVenta){
    const clientRow = ventas_cliente.find(obj => obj.cliente === idCliente);

    if(clientRow){
        if(!clientRow.ventas.includes(idVenta)){
            clientRow.ventas.push(idVenta);
        }
    }
}

function v_cortesRuta() {
	tablaCortesRuta();

	$('#formCorteDeRuta').validate({
        rules: {
            rutasCorte: {
                required: true
            },
            FechaInicioCorte: {
                required: true
            },
            FechaFinCorte: {
                required: true
            }
        },
        messages: {
            rutasCorte: {
                required: "La marca es requerida."
            },
            FechaInicioCorte: {
                required: "La fecha de inicio es requerida."
            },
            FechaFinCorte: {
                required: "La fecha de fin es requerida."
            }
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarCorte").attr('tipo')+"&accion=cortesRuta&tipo=insertar&rutasCorte="+$.trim($("#rutasCorte").val())+"&FechaInicioCorte="+$.trim($("#FechaInicioCorte").val())+"&FechaFinCorte="+$.trim($("#FechaFinCorte").val())+"&selectChofer="+$.trim($("#selectChofer").val())+"&selectVehiculo="+$.trim($("#selectVehiculo").val())+"&id="+$("#bGuardarCorte").attr('attrID')+"&detalleVentas="+JSON.stringify(ventas_cliente);
            
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
                    if ($("#bGuardarCorte").attr("tipo") == "modificar") {
                        var tipoAlerta = "modificado";
                    }else{
                        var tipoAlerta = "guardado";
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Corte '+tipoAlerta+' correctamente'
                    });

                    tablaCortesRuta(); 
                    $("#modalCorteRuta").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al '+$("#bGuardarCorte").attr("tipo")+' la ruta.'
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

function tablaCortesRuta() {
	ajaxMyDatatable({
        "table": $("#tablaCortesRuta"), 
        "colums": [
            "Fecha",
            "Ruta",
            "Fecha_Inicio",
            "Fecha_Fin",
            "Total",
            "Verificado",
            "Detalles",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "tipo": "cortesRutas",
            "accion": "cortesRuta",
        }
    });
}

function tablaDetallesClientes(idCliente, ventas_obj){
    if(ventas_obj.length > 0){
        var data = `metodo=consultar&accion=cortesRuta&tipo=clientesDetalles&idCliente=${idCliente}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}&ventas=${JSON.stringify(ventas_obj)}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            $('#lista-ventas-cliente').html($.trim(res));
            $("#agregarVentaACorte").attr('clienteId', idCliente);

            if($("#cerrarModalCorteRuta").attr('corteFinalizado') == 'si'){
                $(".borrarDeCorteDeRuta").addClass('d-none');
                $("#agregarVentaACorte").addClass('d-none');
            }else{
                $(".borrarDeCorteDeRuta").removeClass('d-none');
                $("#agregarVentaACorte").removeClass('d-none');
            }
        }).fail(function(){
            console.log('Error ajax');
        })
    }else{
        $('#lista-ventas-cliente').html('<h1>No hay nada we</h1>');
        $("#agregarVentaACorte").attr('clienteId', idCliente);
    }
}

function tablaClientesRuta() {
    ajaxMyDatatable({
        "table": $("#tablaClientesRuta"), 
        "colums": [
            "Orden_Ruta",
            "Nombre",
            "Domicilio",
            "Total",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "tipo": "clientesRuta",
            "Ruta": $("#rutasCorte").val(),
            "FechaInicioCorte": $("#FechaInicioCorte").val(),
            "FechaFinCorte": $("#FechaFinCorte").val(),
            "accion": "cortesRuta",
            "ventas": ventas_cliente.length > 0 ? JSON.stringify(ventas_cliente) : []
        }
    });
}

function getBalanceData(idCorteRuta, total, recaudado){
    var data = `metodo=consultar&accion=cortesRuta&tipo=obtener_balance_datos&ID_Ruta=${idCorteRuta}`;
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function(){
            $("#carga").show();
        }
    }).done(function(res){
        var jsonData = JSON.parse($.trim(res));
        $("#tablaCostesRuta tbody").html(jsonData.Costes);
        $("#total_gastos_corte").text(jsonData.Total_Costes);
        $("#total_neto_corte").text(total - jsonData.Total_Costes);

        if($("#cerrarModalCorteRuta").attr('corteFinalizado') == 'si'){
            $(".eliminateCoste").attr('disabled', true);
        }

        if(recaudado){
            calcBalance(parseFloat(total - jsonData.Total_Costes), recaudado);
        }
    }).fail(function(){
        console.log('Error ajax');
    }).always(function(){
        $("#carga").hide();
    })
}

function calcBalance(neto, obtenido){
    var balance = obtenido - neto;

    $("#balance_final").text(balance);
    moneda();
    if(balance > 0){
        $("#balance_final").addClass('text-success');
    }else{
        $("#balance_final").addClass('text-danger');
    }
}

function recalcTotalLocal(){
    var data = `metodo=consultar&accion=cortesRuta&tipo=obtener_total_recalc&ventas=${JSON.stringify(ventas_cliente)}`;
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    }).done(function(res){
        $("#total_corte_bruto").text($.trim(res));
        getBalanceData($("#bGuardarCorte").attr('attrID'), $.trim(res), recaud);
    }).fail(function(){
        console.log('Error ajax');
    })
}

jQuery(document).ready(function($) {

    $(document).on('click', "#bGuardarCorte", function(){
        $("#formCorteDeRuta").submit();
    })

    $(document).on('click', '#bNuevoCorteRuta', function() {
        var today = new Date();
        var threeMoreDays = new Date(today);
        threeMoreDays.setDate(today.getDate() + 3);


        $("#modalCorteRuta").modal('show');
        $("#formCorteDeRuta")[0].reset();
        $("#bGuardarCorte").attr('tipo', 'insertar');
        $("#tablaClientesruta").addClass('d-none');
        ventas_cliente = [];
        $("#FechaInicioCorte").val(today.toISOString().split('T')[0])
        $("#FechaFinCorte").val(threeMoreDays.toISOString().split('T')[0])
        $("#rutasCorte").attr('disabled', false);
        $("#FechaInicioCorte").attr('readonly', false);
        $("#FechaFinCorte").attr('readonly', false);
        $("#bGenerarClientes").removeClass('d-none');
        $("#contenedorBalance").addClass('d-none');
        $("#accionesCorteGeneral").addClass('d-none');
    });

	$(document).on('click', '#bGenerarClientes', function() {

        var data = `metodo=consultar&accion=cortesRuta&tipo=all_ventas_clientes&Ruta=${$("#rutasCorte").val()}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            var jsonData = JSON.parse(res);
            ventas_cliente = jsonData;
            $("#tablaClientesruta").removeClass('d-none');
            tablaClientesRuta();
        })

	});

    $(document).on('click', '.bDetallesCorteClientes', function() {
        var clientID = $(this).attr('attrID');
        const ventas_val = ventas_cliente.find(obj => obj.cliente === clientID);
        $("#modalCorteClientes").modal('show');
        tablaDetallesClientes(clientID, ventas_val ? ventas_val.ventas : jsonData.ventas);
    });

    $(document).on('click', '.borrarDeCorteDeRuta', function() {
        var btn = $(this);
        Swal.fire({
            title: 'Quitar venta del corte de ruta',
            text: '¿Estas seguro de quitar esta venta del corte de ruta actual?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                remove_venta(btn.attr('ID_Cliente'), btn.attr('ID_Venta'))

                const ventas_val = ventas_cliente.find(obj => obj.cliente == btn.attr('ID_Cliente'));
                tablaDetallesClientes(btn.attr('ID_Cliente'), ventas_val.ventas);
                tablaClientesRuta();
                recalcTotalLocal();

                if(ventas_val.ventas.length > 0){
                }else{
                    //TODO: mostrar que no hay nada
                }
            }
        });
    });

    $(document).on('click', '#agregarVentaACorte', function(){
        $("#modalAgregarVenta").modal('show');
        var clientId = $(this).attr('clienteId');
        var clientVentas = ventas_cliente.find(obj => obj.cliente === clientId).ventas;
        
        var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${$(this).attr('clienteId')}&ventas=${JSON.stringify(clientVentas)}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            tablaCortesRuta();
            if($.trim(res).length > 0){
                $("#lista-ventas-cliente-excluidas").html($.trim(res));
                $("#searchedForAdd").attr('ID_Cliente', clientId);
                //Recalc
            }else{
                $("#lista-ventas-cliente-excluidas").html('<h1>No hay nada we</h1>');
            }
        }).fail(function(){
            console.log('Error ajax')
        });
        
    });

    $(document).on('click', '.agregarDeCorteDeRuta', function(){
        var btn = $(this); 
        Swal.fire({
            title: 'Agregar venta a corte',
            text: '¿Estas seguro de agregar esta venta al corte de ruta actual?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, agregar'
        }).then((result) => {
            if (result.isConfirmed) {
                add_venta(btn.attr('ID_Cliente'), btn.attr('ID_Venta'))

                const ventas_val = ventas_cliente.find(obj => obj.cliente === btn.attr('ID_Cliente'));
                var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${btn.attr('ID_Cliente')}&ventas=${JSON.stringify(ventas_val.ventas)}`;
                tablaDetallesClientes(btn.attr('ID_Cliente'), ventas_val.ventas);
                tablaClientesRuta();
                recalcTotalLocal();

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data
                }).done(function(res){
                    if($.trim(res).length > 0){
                        $("#lista-ventas-cliente-excluidas").html($.trim(res));
                        $("#searchedForAdd").attr('ID_Cliente', btn.attr('ID_Cliente'));
                    }else{
                        $("#lista-ventas-cliente-excluidas").html('<h3>Sin registros</h3>');
                    }
                }).fail(function(){
                    console.log('Error ajax')
                })
            }
        });
    })

    $(document).on('dblclick', '#tablaClientesruta tbody td', function() {
        if($(this).children('span.orden').text() != ""){
            const searchRegExp = new RegExp(',', 'g'); 

            var idClienteVar = $(this).children('span.orden').attr('attrID');
            $(this).html('<input type="number" style="width: 100px;" class="inputOrdenRuta" attrID="'+idClienteVar+'" value="'+$(this).text().replace('$', '').replace(searchRegExp, '')+'">');
            $(this).children('input.inputOrdenRuta').focus();
        }

    });

    $(document).on('focusout', '.inputOrdenRuta', function() {
        var input = $(this);
        var padre = $(this).parent();
        var data = "metodo=modificar&accion=cortesRuta&tipo=ordenRuta&valor="+$(this).val()+"&id="+$(this).attr('attrID');
    
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
                padre.html('<span class="orden" attrID = "'+input.attr('attrID')+'">'+input.val()+'</span>');
                moneda();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inesperado al cambiar el orden.'
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
    });

    $(document).on('click', "#cerrarModalCorteRuta", function(){
        if($(this).attr('corteFinalizado') == 'si'){
            $("#modalCorteRuta").modal('hide');
        }else{
            Swal.fire({
                title: '¿Estas seguro de descartar cambios?',
                text: 'Al cerrar esta modal se descartaran todas aquellas acciones realizadas sin guardar',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No, continuar',
                confirmButtonText: 'Si, descartar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#modalCorteRuta").modal('hide');
                }
            });
        }
    })

    $(document).on('keyup', '#searchedForAdd', function(){
        var clientId = $(this).attr('ID_Cliente');
        var buscado = $(this).val();
        var clientVentas = ventas_cliente.find(obj => obj.cliente === clientId).ventas;
        
        var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${clientId}&ventas=${JSON.stringify(clientVentas)}&buscado=${buscado}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            tablaCortesRuta();
            if($.trim(res).length > 0){
                $("#lista-ventas-cliente-excluidas").html($.trim(res));
                $("#searchedForAdd").attr('ID_Cliente', clientId);
            }else{
                $("#lista-ventas-cliente-excluidas").html('<h3>Sin registros</h3>');
            }
        }).fail(function(){
            console.log('Error ajax')
        });
    })

    $(document).on('click', '.bEliminarCorteRuta', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro de eliminar el corte venta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = "metodo=eliminar&accion=cortesRuta&id="+btn.attr('attrID');

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
                        Swal.fire({
                            icon: 'success',
                            title: 'El corte de ruta ha sido eliminado correctamente'
                        });

                        tablaCortesRuta(); 
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el corte de ruta.'
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
    });

    $(document).on('click', '.bVerificarCorteRuta', function() {
        $("#modalVerificar").modal('show');
    });

    $(document).on('click', '.bModificarCorteRuta', function(){
        var idCorte = $(this).attr('attrID');
        var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerCorteGuardado&idCorte=${$(this).attr('attrID')}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function(){
                $("#carga").show();
            }
        }).done(function(res){
            const resData = JSON.parse(res);

            $("#rutasCorte").val(resData.Ruta);
            $("#FechaInicioCorte").val(resData.Fecha_Inicio);
            $("#FechaFinCorte").val(resData.Fecha_Fin);
            $("#selectChofer").val(resData.FK_Chofer);
            $("#selectVehiculo").val(resData.FK_Vehiculo);
            ventas_cliente = JSON.parse(resData.Detalles);
            recaud = resData.Recaudado_numero;

            $("#tablaClientesruta").removeClass('d-none');
            tablaClientesRuta();

            $("#rutasCorte").attr('disabled', true);
            $("#FechaInicioCorte").attr('readonly', true);
            $("#FechaFinCorte").attr('readonly', true);
            $("#bGenerarClientes").addClass('d-none');

            $("#contenedor_recaudado").html(resData.Recaudado);
            $("#total_corte_bruto").text(resData.Total);
            getBalanceData(idCorte, resData.Total, resData.Recaudado_numero);

            $("#contenedorBalance").removeClass('d-none');
            $("#accionesCorteGeneral").removeClass('d-none');
            $("#añadirGastoACorte").attr('ID_Ruta', idCorte);

            $("#bGuardarCorte").attr('attrID', idCorte);
            $("#bGuardarCorte").attr('tipo', 'modificar');
            

            if(resData.Estado === 'Finalizado'){
                $("#gastosFormulario").addClass('d-none');
                $("#selectChofer").attr('disabled', true);
                $("#selectVehiculo").attr('disabled', true);
                $("#bGuardarCorte").addClass('d-none');
                $("#reabrirCorteRuta").removeClass('d-none');
                $("#cerrarCorteRuta").addClass('d-none');
                $("#cerrarModalCorteRuta").attr('corteFinalizado', 'si');
            }else{
                $("#gastosFormulario").removeClass('d-none');
                $("#selectChofer").attr('disabled', false);
                $("#selectVehiculo").attr('disabled', false);
                $("#bGuardarCorte").removeClass('d-none');
                $("#reabrirCorteRuta").addClass('d-none');
                $("#cerrarCorteRuta").removeClass('d-none');
                $("#cerrarModalCorteRuta").attr('corteFinalizado', 'no');
            }

            if(resData.Imagen && resData.Imagen != ''){
                $("#downloadTheFile").removeClass('d-none');
                $("#uploadImgBtn strong").text('Resubir archivo');
                $("#uploadImgBtn").attr('nombre_photo', resData.Imagen);
            }else{
                $("#downloadTheFile").addClass('d-none');
                $("#uploadImgBtn strong").text('Subir archivo');
                $("#uploadImgBtn").attr('nombre_photo', '');
            }

            $("#modalCorteRuta").modal('show');
        }).always(function(){
            $("#carga").hide();
        })
    });

    $.validator.addMethod("soloNumerosDecimales", function(value, element) {
        return this.optional(element) || /^[0-9]+(\.[0-9]+)?$/.test(value);
    }, "Por favor, ingresa un número válido.");

    $(document).on('click', '#añadirGastoACorte', function(){
        var corteId = $("#añadirGastoACorte").attr('ID_Ruta');
        $("#gastosFormulario").validate({
            rules: {
                gastoDescripcion: {
                    required: true
                },
                gastoCoste: {
                    required: true,
                    soloNumerosDecimales: true
                }
            },
            messages: {
                gastoDescripcion: {
                    required: "La descripcion es requerida"
                },
                gastoCoste: {
                    required: "El coste es requerido",
                    soloNumerosDecimales: "Por favor, ingresa un número válido."
                }
            },
            submitHandler: function(form){
                var total = parseFloat($("#total_corte_bruto").text().replace(/[$,]/g, ''));
                var data = `metodo=insertar&accion=cortesRuta&tipo=insertar_gastos&ID_Ruta=${corteId}&Descripcion=`+$.trim($("#gastoDescripcion").val())+"&Coste="+$.trim($("#gastoCoste").val());
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        $("#carga").show();
                    }
                }).done(function(res){
                    if($.trim(res) == "Correcto"){
                        Swal.fire({
                            icon: 'success',
                            title: 'Gasto añadido correctamente'
                        });
                        getBalanceData(corteId, total, recaud);
                        $("#gastosFormulario")[0].reset();
                    }
                }).fail(function(){
                    console.log("Error ajax");
                }).always(function(){
                    $("#carga").hide();
                })
            }
        });
    });

    $(document).on('click', '.eliminateCoste', function(){
        var deleteId = $(this).attr('ID_Coste');
        var corteId = $(this).attr('ID_Corte');
        var total = parseFloat($("#total_corte_bruto").text().replace(/[$,]/g, ''));
        Swal.fire({
            title: '¿Estás seguro de eliminar el gasto del corte?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = "metodo=eliminar&accion=cortesRuta&tipo=eliminar_gasto&ID_Gasto="+deleteId;

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
                        Swal.fire({
                            icon: 'success',
                            title: 'El gasto se elimino del corte correctamente.'
                        });

                        getBalanceData(corteId, total, recaud);
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el gasto del corte.'
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
    });

    $(document).on('dblclick', '#contenedor_recaudado', function(){
        var ID_Ruta = $(this).children('span.recaudado').attr('ID_Ruta');
        var Ctty = $(this).children('span.recaudado').text();
        $(this).html('<input type="text" class="form-control inputRecaudado dinero" ID_Ruta = "'+ID_Ruta+'" value="'+Ctty+'">');
        $(this).children('input.inputRecaudado').focus();
    });

    $(document).on('focusout', '.inputRecaudado', function(){
        var workingFocus = $(this);
        if (/^[0-9]+(\.[0-9]+)?$/.test(workingFocus.val())) {
            var data = `metodo=modificar&accion=cortesRuta&tipo=actualizar_dinero_obtenido&Monto=${parseFloat(workingFocus.val())}&ID_Ruta=${workingFocus.attr('ID_Ruta')}`;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function(){
                    $("#carga").show()
                }
            }).done(function(res){
                if($.trim(res) == "Correcto"){
                    workingFocus.parent().html('<span class="fs-5 recaudado dinero" ID_Ruta="'+workingFocus.attr('ID_Ruta')+'">'+workingFocus.val()+'</span>');
                    recaud = parseFloat(workingFocus.val());
                    calcBalance(parseFloat($("#total_corte_bruto").text()), parseFloat(workingFocus.val()));
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al ingresar el monto recaudado.'
                    });

                    console.log($.trim(res));
                }
            }).fail(function(){
                console.log("Error ajax");
            }).always(function(){
                $("#carga").hide();
            })
        }else{
            Swal.fire({
                icon: "error",
                title: "Porfavor introduce solo una cantidad numerica",
                didClose: () => {
                    workingFocus.focus();
                }
            });
        }
    });

    $(document).on('click', '#uploadImgBtn', function(){
        $("#imageInput").click();
    });

    $(document).on('change', '#imageInput', function(){
        if($(this).val()){
            Swal.fire({
                title: '¿Estás seguro de querer subir el archivo seleccionado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No, cancelar',
                confirmButtonText: 'Si, subir'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(document.querySelector("#imageUploadForm"));
                    formData.append("metodo", "modificar");
                    formData.append("accion", "cortesRuta");
                    formData.append("tipo", "subirArchivo");
                    formData.append('ID_Corte_Ruta', $("#bGuardarCorte").attr('attrID'));
                    formData.append('ImagenAnterior', $("#uploadImgBtn").attr('nombre_photo'));

                    $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: formData,
                        cache: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#carga').show();
                        }
                    }).done(function(res){
                        var jsonData = JSON.parse($.trim(res));
                        if(jsonData.status == "Correcto"){
                            Swal.fire({
                                icon: 'success',
                                title: 'El archivo se ha subido y añadido al corte de ruta correctamente',
                            });
                            $("#uploadImgBtn").attr('nombre_photo', jsonData.newImage);
                        }else if($.trim(res) === 'Error 1 formato'){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'El formato del archivo no está permitido, los formatos permitidos son .png, .jpg, .svg o .pdf'
                            })
                        }else if($.trim(res) === 'Error 2 peso'){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'El tamaño del archivo excedió el peso máximo permitido, el peso máximo es de 10MB.'
                            })
                        }else if($.trim(res) === 'Error 4 Borrar'){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'Ha ocurrido un error al eliminar el archivo anterior'
                            })
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado para modificar el producto'
                            });
                            console.log($.trim(res));
                        }
                    }).fail(function(){
                        console.log('Error ajax');
                    }).always(function(){
                        $('#carga').hide();
                    })
                }
            });
        }
    });

    $(document).on('click', '#cerrarCorteRuta', function(){
        Swal.fire({
            title: '¿Estas seguro de marcar como finalizado el corte de ruta?',
            text: 'Una vez terminado el corte de ruta no se podra modificar las ventas del corte o sus gastos',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, finalizar'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = `metodo=modificar&accion=cortesRuta&tipo=terminarCorte&ID_Corte_Ruta=${$("#bGuardarCorte").attr('attrID')}`;

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function(){
                        $("#carga").show();
                    }
                }).done(function(res){
                    if($.trim(res) == "Correcto"){
                        Swal.fire({
                            icon: 'success',
                            title: 'El corte de ruta se finalizo correctamente.'
                        });
                        
                        tablaCortesRuta(); 
                        $("#modalCorteRuta").modal("hide");
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado para finalizar el corte de ruta'
                        });
                        console.log($.trim(res));
                    }
                }).fail(function(){
                    console.log('Error ajax');
                }).always(function(){
                    $("#carga").hide();
                })
            }
        });
    });

    $(document).on('click', "#reabrirCorteRuta", function(){
        Swal.fire({
            title: '¿Estas seguro de reabir el corte de ruta?',
            text: 'Podras modificar nuevamente el corte de ruta, haciendo que aquellos balances y verificaciones puedan ser realizadas nuevamente',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, reabrir'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = `metodo=modificar&accion=cortesRuta&tipo=reabrirCorteRuta&ID_Corte_Ruta=${$("#bGuardarCorte").attr('attrID')}`;

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function(){
                        $("#carga").show();
                    }
                }).done(function(res){
                    if($.trim(res) == "Correcto"){
                        Swal.fire({
                            icon: 'success',
                            title: 'El corte de ruta se reabrio correctamente.'
                        });
                        tablaCortesRuta(); 
                        $("#modalCorteRuta").modal("hide");
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado para finalizar el corte de ruta'
                        });
                        console.log($.trim(res));
                    }
                }).fail(function(){
                    console.log('Error ajax');
                }).always(function(){
                    $("#carga").hide();
                })
            }
        });
    });

    $(document).on('click', "#downloadTheFile", function(){
        var data = `metodo=consultar&accion=cortesRuta&tipo=descargarArchivo&archivo=${$("#uploadImgBtn").attr('nombre_photo')}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function(){
                $("#carga").show();
            }
        }).done(function(res){
            if($.trim(res) == 'Error'){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El archivo a descargar no existe'
                });
            }else{
                var blob = new Blob([$.trim(res)]);

                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = $("#uploadImgBtn").attr('nombre_photo');
                link.click();
            }
        }).fail(function(){
            console.log('Error ajax');
        }).always(function(){
            $("#carga").hide();
        })
    });

    $(document).on('click', '.bGenerarPDFCorteRuta', function(){
        var routeId = $(this).attr('attrID');
        window.open('/sistemaalex/controladores/pdf/mpdf/corteRuta.php'+'?id='+routeId, '_blank');
    });

	/*$(document).on('click', '.bModificarVehiculo', function() {
		var padre = $(this).parent().parent();
		$("#formVehiculos")[0].reset();
		$("#marcaVehiculo").val(padre.children('td:eq(1)').text());
        $("#modeloVehiculo").val(padre.children('td:eq(2)').text());
        $("#matriculaVehiculo").val(padre.children('td:eq(3)').text());
		$("#descripcionVehiculo").val(padre.children('td:eq(4)').text());

		$("#bGuardarVehiculo").attr('attrID', $(this).attr('attrID'));
		$("#bGuardarVehiculo").attr('tipo', 'modificar');
		$("#modalVehiculo").modal('show');
	});

	*/
});