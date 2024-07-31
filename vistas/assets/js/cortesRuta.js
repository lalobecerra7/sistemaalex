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
            },
            selectSucursal: {
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
            },
            selectSucursal: {
                required: "La sucursal es requerida."
            }
        },
        submitHandler: function(form) { 
            var data = "metodo="+$("#bGuardarCorte").attr('tipo')+"&accion=cortesRuta&tipo=insertar&rutasCorte="+$.trim($("#rutasCorte").val())+"&FechaInicioCorte="+$.trim($("#FechaInicioCorte").val())+"&FechaFinCorte="+$.trim($("#FechaFinCorte").val())+"&selectChofer="+$.trim($("#selectChofer").val())+"&selectVehiculo="+$.trim($("#selectVehiculo").val())+"&selectSucursal="+$.trim($("#selectSucursal").val())+"&envasesPrestados="+$.trim($("#envasesPrestados").val())+"&envasesRegresados="+$.trim($("#envasesRegresados").val())+"&id="+$("#bGuardarCorte").attr('attrID')+"&detalleVentas="+JSON.stringify(ventas_cliente);
            
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
                    console.log(res);
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


    $('#formVerificar').validate({
        rules: {
            codigoProducto: {
                required: true
            }
        },
        messages: {
            codigoProducto: {
                required: "El codigo es requerido"
            }
        },
        submitHandler: function(form) { 
            $("#codigoProducto").prop('disabled', true);
            $("#carga").show();

            var object = $(".codigoProducto").filter(':contains("'+$.trim($("#codigoProducto").val())+'")').parent();
            //console.log(object);
            var id = $('#bGuardarCorte').attr('attrID');
            

            if(object.length > 0){
                var data = "metodo=modificar&accion=cortesRuta&tipo=verificar&Presentacion="+$.trim(object[0].getAttribute('presentacion'))+"&Producto="+$.trim(object[0].getAttribute('producto'))+"&IDCorte="+id+"&idCliente="+$.trim($('#codigoProducto').attr('attrID'));
                //console.log(data);
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        $("#codigoProducto").val('');
                    }
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {

                        var IDCor = id;
                        var IDCli = $('#codigoProducto').attr('attrID');

                        tablaVerificarCorte(IDCor,IDCli); 

                        Swal.fire({
                          position: "top-end",
                          icon: "success",
                          title: "Producto encontrado",
                          showConfirmButton: false,
                          timer: 500
                        });
                    }else if($.trim(res) == "Suficiente"){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ya se acompleto la cantidad',
                            showConfirmButton: false,
                            timer: 500
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al verificar'
                        });

                        console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                })
                .always(function() {
                    $("#codigoProducto").prop('disabled', false);
                    $("#carga").hide();
                    $('#codigoProducto').focus();
                });  
            }else{
                $('#codigoProducto').val('');
                $("#codigoProducto").prop('disabled', false);
                $('#codigoProducto').focus();
                $("#carga").hide();
                
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Producto no encontrado",
                    showConfirmButton: false,
                    timer: 500
                });
            }             
        }
    }); 

    $('#formVerificarCubeta').validate({
        rules: {
            codigoProductoCubeta: {
                required: true
            }
        },
        messages: {
            codigoProductoCubeta: {
                required: "El codigo es requerido"
            }
        },
        submitHandler: function(form) { 
            $("#codigoProductoCubeta").prop('disabled', true);
            $("#carga").show();

            var object = $(".codigoProductoCubeta").filter(function() {
                return $(this).text().trim() === $("#codigoProductoCubeta").val().trim() && $(this).parent().attr('propEstado') === 'true';
            }).parent();
            //console.log(object);
            var id = $("#codigoProductoCubeta").attr('attrCorteRuta');
            

            if(object.length > 0 && $.trim(object[0].getAttribute('propEstado')) == 'true'){
                var data = "metodo=modificar&accion=cortesRuta&tipo=verificar&Presentacion="+$.trim(object[0].getAttribute('presentacion'))+"&Producto="+$.trim(object[0].getAttribute('producto'))+"&IDCorte="+id+"&idCliente="+$.trim(object[0].getAttribute('cliente'));
                //console.log(data);
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        $("#codigoProductoCubeta").val('');
                    }
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {

                        Swal.fire({
                          position: "top-end",
                          icon: "success",
                          title: "Producto encontrado",
                          showConfirmButton: false,
                          timer: 500
                        });
                        tablaVerificarCubetas(id);
                    }else if($.trim(res) == "Suficiente"){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ya se acompleto la cantidad',
                            showConfirmButton: false,
                            timer: 500
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al verificar'
                        });

                        console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                })
                .always(function() {
                    $("#codigoProductoCubeta").prop('disabled', false);
                    $("#carga").hide();
                    $('#codigoProductoCubeta').focus();
                });  
            }else{
                $('#codigoProductoCubeta').val('');
                $("#codigoProductoCubeta").prop('disabled', false);
                $('#codigoProductoCubeta').focus();
                $("#carga").hide();
                
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Producto no encontrado",
                    showConfirmButton: false,
                    timer: 500
                });
            }             
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
            "Concentrado",
            "Cubetas",
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
    if(ventas_obj.length > 0 ){
        var data = `metodo=consultar&accion=cortesRuta&tipo=clientesDetalles&idCliente=${idCliente}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}&selectSucursal=${$("#selectSucursal").val()}&ventas=${JSON.stringify(ventas_obj)}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        }).done(function(res){
            $('#lista-ventas-cliente').html(res);
            $("#agregarVentaACorte").attr('clienteId', idCliente);

            if($("#cerrarModalCorteRuta").attr('corteFinalizado') == 'si'){
                $(".borrarDeCorteDeRuta").addClass('d-none');
                $("#agregarVentaACorte").addClass('d-none');
            }else{
                $(".borrarDeCorteDeRuta").removeClass('d-none');
                $("#agregarVentaACorte").removeClass('d-none');
            }
            moneda();
            tablaClientesRuta();
        }).fail(function(){
            console.log('Error ajax');
        })
    }else{
        $('#lista-ventas-cliente').html('<h1>No existen registros</h1>');
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
            "Estado",
            "Acciones"
        ], 
        "sort": [0, "asc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "tipo": "clientesRuta",
            "Ruta": $("#rutasCorte").val(),
            "FechaInicioCorte": $("#FechaInicioCorte").val(),
            "FechaFinCorte": $("#FechaFinCorte").val(),
            "selectSucursal": $("#selectSucursal").val(),
            "accion": "cortesRuta",
            "IDCorteRuta": $("#bGuardarCorte").attr('attrID'),
            "ventas": JSON.stringify(ventas_cliente)
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
    if(balance >= 0){
        $("#balance_final").addClass('text-success');
        $("#balance_final").removeClass('text-danger');
    }else{
        $("#balance_final").addClass('text-danger');
        $("#balance_final").removeClass('text-success');
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

        if ($("#bGuardarCorte").attr('attrID') != "n/a") {
            getBalanceData($("#bGuardarCorte").attr('attrID'), $.trim(res), recaud); 
        }

    }).fail(function(){
        console.log('Error ajax');
    })
}

function tablaConcentrarCorte(idCorte){
        var data = `metodo=consultar&accion=cortesRuta&tipo=Concentrado&idCorte=${idCorte}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        }).done(function(res){

            $('#tbodyConcentrado').html($.trim(res));
            tablaCortesRuta();
            
        }).fail(function(){
            console.log('Error ajax');
        }).always(function(){
            $("#carga").hide();
        })
}


function tablaVerificarCorte(idCorte, idCliente){
    var data = `metodo=consultar&accion=cortesRuta&tipo=verificarCorte&idCorte=${idCorte}&idCliente=${idCliente}`;

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
            //$("#carga").show();
        }
    }).done(function(res){
        $('#tbodyVerificar').html($.trim(res));
    }).fail(function(){
        console.log('Error ajax');
    }).always(function(){
        //$("#carga").hide();
    });
}

function tablaVerificarCubetas(idCorte){
    var data = `metodo=consultar&accion=cortesRuta&tipo=verificarCubetas&idCorte=${idCorte}`;

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
            //$("#carga").show();
        }
    }).done(function(res){
        $('#tbodyCubetas').html($.trim(res));
    }).fail(function(){
        console.log('Error ajax');
    }).always(function(){
        //$("#carga").hide();
    });
}

jQuery(document).ready(function($) {
    
    $(document).on('hide.bs.modal', "#modalVerificar", function(){
        tablaClientesRuta();
    });

    $(document).on('hide.bs.modal', "#modalCorteRuta", function(){
        tablaCortesRuta();
    });

    $(document).on('click', "#bGuardarCorte", function(){

        if ($("#tablaClientesrutaROW").hasClass("d-none") || $("#tablaClientesRuta tr:eq(1) td:eq(0)").text().trim() === "No existen registros.") {
             Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Tiene que existir ventas generadas para el corte de ruta.'
            });
        }else{
            $("#formCorteDeRuta").submit();
        }
    })

    $(document).on('click', '#bNuevoCorteRuta', function() {
        var today = new Date();
        var threeMoreDays = new Date(today);
        threeMoreDays.setDate(today.getDate() + 3);

        $("#modalCorteRuta").modal('show');
        $("#formCorteDeRuta")[0].reset();
        $("#bGuardarCorte").attr('tipo', 'insertar');
        $("#envasesPrestados").val('0');
        $("#envasesRegresados").val('0');
        $("#tablaClientesrutaROW").addClass('d-none');
        ventas_cliente = [];
        $("#FechaInicioCorte").val(today.toISOString().split('T')[0])
        $("#FechaFinCorte").val(threeMoreDays.toISOString().split('T')[0])
        $("#rutasCorte").attr('disabled', false);
        $("#FechaInicioCorte").attr('readonly', false);
        $("#FechaFinCorte").attr('readonly', false);
        $("#selectSucursal").attr('disabled', false);
        $("#bGenerarClientes").removeClass('d-none');
        $("#contenedorBalance").addClass('d-none');
        $("#accionesCorteGeneral").addClass('d-none');
        $("#botonGenerarVista").removeClass('d-none');
        $("#bGuardarCorte").attr('attrID', 'n/a');
    });

	$(document).on('click', '#bGenerarClientes', function() {
        if ($("#rutasCorte").val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ingrese una ruta para generar corte'
            });
        }else if ($("#FechaInicioCorte").val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ingrese una fecha de inicio para generar corte'
            });
        }else if ($("#FechaFinCorte").val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ingrese una fecha final para generar corte'
            });
        }else if ($("#selectSucursal").val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ingrese una sucursal para generar corte'
            });
        }else{
            var data = `metodo=consultar&accion=cortesRuta&tipo=all_ventas_clientes&Ruta=${$("#rutasCorte").val()}&FechaInicioCorte=${$("#FechaInicioCorte").val()}&FechaFinCorte=${$("#FechaFinCorte").val()}&selectSucursal=${$("#selectSucursal").val()}`;

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            }).done(function(res){
                //console.log(res);
                var jsonData = JSON.parse(res);
                ventas_cliente = jsonData;
                $("#tablaClientesrutaROW").removeClass('d-none');
                $("#bGuardarCorte").attr('attrID', 'n/a');
                tablaClientesRuta();
            }).fail(function(){
                console.log('Error ajax');
            })
        }
	});

    $(document).on('click', '.bDetallesCorteClientes', function() {
        var clientID = $(this).attr('attrID');
        const ventas_val = ventas_cliente.find(obj => obj.cliente === clientID);
        $("#modalCorteClientes").modal('show');
         $('#lista-ventas-cliente').html('');
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
        var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${$(this).attr('clienteId')}&selectSucursal=${$('#selectSucursal').val()}&ventas=${JSON.stringify(clientVentas)}`;

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
                $("#lista-ventas-cliente-excluidas").html('<h1>No existen registros</h1>');
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
                var data = `metodo=consultar&accion=cortesRuta&tipo=obtenerExcluidos&id=${btn.attr('ID_Cliente')}&selectSucursal=${$("#selectSucursal").val()}&ventas=${JSON.stringify(ventas_val.ventas)}`;
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
            $(this).html('<input type="number" style="width: 100px;" class="inputOrdenRuta" attrID="'+idClienteVar+'" attrValor="'+$(this).text()+'" value="'+$(this).text().replace('$', '').replace(searchRegExp, '')+'">');
            $(this).children('input.inputOrdenRuta').focus();
        }

    });

    $(document).on('focusout', '.inputOrdenRuta', function() {
        var input = $(this);
        var padre = $(this).parent();
        var data = "metodo=modificar&accion=cortesRuta&tipo=ordenRuta&valor="+$(this).val()+"&id="+$(this).attr('attrID')+"&valorAntes="+$(this).attr('attrValor');
    
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
                var data = "metodo=eliminar&accion=cortesRuta&tipo=cortesRuta&id="+btn.attr('attrID');

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
        $("#modalConcentrado").modal('show');
        var corteID = $(this).attr('attrID');
        
        tablaConcentrarCorte(corteID);
        $('#bImprimirVerificacion').attr('href', "controladores/pdf/ticketCorteRuta.php?id="+corteID);
    });

    $(document).on('focusout', '#codigoProducto', function() {
        $("#codigoProducto").focus();
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
            $("#selectSucursal").val(resData.FK_Sucursal);
            $("#envasesPrestados").val(resData.Envases_Prestados);
            $("#envasesRegresados").val(resData.Envases_Recaudados);
            ventas_cliente = JSON.parse(resData.Detalles);
            recaud = resData.Recaudado_numero;
            $("#bVerificarCliente").attr('attrID', idCorte);

            $("#tablaClientesrutaROW").removeClass('d-none');
           

            $("#rutasCorte").attr('disabled', true);
            $("#FechaInicioCorte").attr('readonly', true);
            $("#FechaFinCorte").attr('readonly', true);
            $("#selectSucursal").attr('disabled', true);
            $("#bGenerarClientes").addClass('d-none');

            $("#contenedor_recaudado").html(resData.Recaudado);
            $("#total_corte_bruto").text(resData.Total);
            getBalanceData(idCorte, resData.Total, resData.Recaudado_numero != 0 ? resData.Recaudado_numero : resData.Total);

            $("#contenedorBalance").removeClass('d-none');
            $("#accionesCorteGeneral").removeClass('d-none');
            $("#botonGenerarVista").addClass('d-none');
            $("#añadirGastoACorte").attr('ID_Ruta', idCorte);

            $("#bGuardarCorte").attr('attrID', idCorte);
            $("#bGuardarCorte").attr('tipo', 'modificar');
            $("#bGuardarCorte").attr('attrVerificar', idCorte);
            tablaClientesRuta();

            if(resData.Estado === 'Finalizado'){
                $("#gastosFormulario").addClass('d-none');
                $("#selectChofer").attr('disabled', true);
                $("#selectVehiculo").attr('disabled', true);
                $("#envasesPrestados").attr('disabled', true);
                $("#envasesRegresados").attr('disabled', true);
                $("#bGuardarCorte").addClass('d-none');
                $("#reabrirCorteRuta").removeClass('d-none');
                $("#cerrarCorteRuta").addClass('d-none');
                $("#cerrarModalCorteRuta").attr('corteFinalizado', 'si');
            }else{
                $("#gastosFormulario").removeClass('d-none');
                $("#selectChofer").attr('disabled', false);
                $("#selectVehiculo").attr('disabled', false);
                $("#envasesPrestados").attr('disabled', false);
                $("#envasesRegresados").attr('disabled', false);
                $("#bGuardarCorte").removeClass('d-none');
                $("#reabrirCorteRuta").addClass('d-none');
                $("#cerrarCorteRuta").removeClass('d-none');
                $("#cerrarModalCorteRuta").attr('corteFinalizado', 'no');
            }

            if(resData.Imagen && resData.Imagen != ''){
                $("#downloadTheFile").removeClass('d-none');
                $("#downloadTheFile").attr('href', `vistas/assets/archivos/cortesRuta/${resData.Imagen}`);
                $("#uploadImgBtn strong").text('Resubir archivo');
                $("#uploadImgBtn").attr('nombre_photo', resData.Imagen);
            }else{
                $("#downloadTheFile").addClass('d-none');
                $("#downloadTheFile").attr('href', ``);
                $("#uploadImgBtn strong").text('Subir archivo');
                $("#uploadImgBtn").attr('nombre_photo', '');
            }

            moneda();
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
                        getBalanceData(corteId, total, recaud != 0 ? recaud : total);
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

                        getBalanceData(corteId, total, recaud != 0 ? recaud : total);
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
            var data = `metodo=modificar&accion=cortesRuta&tipo=actualizar_dinero_obtenido&Monto=${parseFloat(workingFocus.val())}&ID_Ruta=${$("#bGuardarCorte").attr('attrID')}`;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function(){
                    $("#carga").show()
                }
            }).done(function(res){
                if($.trim(res) == "Correcto"){
                    workingFocus.parent().html('<span class="fs-5 recaudado dinero" ID_Ruta="'+$("#bGuardarCorte").attr('attrID')+'">'+workingFocus.val()+'</span>');
                    recaud = parseFloat(workingFocus.val());
                    calcBalance(monedaToNumber($("#total_neto_corte").text()), parseFloat(workingFocus.val()));
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
                        var jsonData = JSON.parse(res);
                        if(jsonData.status == "Correcto"){
                            Swal.fire({
                                icon: 'success',
                                title: 'El archivo se ha subido y añadido al corte de ruta correctamente',
                            });
                            $("#downloadTheFile").attr('href', `vistas/assets/archivos/cortesRuta/${jsonData.newImage}`);
                            $("#uploadImgBtn").attr('nombre_photo', jsonData.newImage);
                            tablaCortesRuta();
                        }else if($.trim(res).includes('Error 1 formato')){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'El formato del archivo no está permitido, los formatos permitidos son .png, .jpg, .svg o .pdf'
                            })
                        }else if($.trim(res).includes('Error 2 peso')){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'El tamaño del archivo excedió el peso máximo permitido, el peso máximo es de 10MB.'
                            })
                        }else if($.trim(res).includes('Error 4 Borrar')){
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
                        }
                        console.log($.trim(res));
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

    $(document).on('click', '.bGenerarPDFCorteRuta', function(){
        var routeId = $(this).attr('attrID');
        window.open('/rios/controladores/pdf/mpdf/corteRuta.php'+'?id='+routeId, '_blank');
    });


    $(document).on('click', '.bVerificarCorteRutaCliente', function() {
        var padre = $(this).parent().parent();

        $("#modalVerificar").modal('show');
        $("#nombreCliente").text('Cliente: '+padre.children('td:eq(1)').text());
        $("#modalVerificar").on('shown.bs.modal', function(){
            $("#codigoProducto").focus();
        });

        $("#codigoProducto").attr('attrID', $(this).attr('attrID'));
        var corteID = $('#bGuardarCorte').attr('attrID');
        var clienteID = $(this).attr('attrID');
        $('#tablaParaVerificar').attr('attrCliente',clienteID);
        tablaVerificarCorte(corteID, clienteID);
        $('#bImprimirVerificacion').attr('href', "controladores/pdf/ticketCorteRuta.php?id="+corteID+"&cliente="+clienteID);
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

    $(document).on('click', '.bVerificarCubetas', function() {
        tablaVerificarCubetas($(this).attr('attrID'));
        $("#modalCubeta").modal('show');
        $("#codigoProductoCubeta").attr('attrCorteRuta',$(this).attr('attrID'));
        $("#modalCubeta").on('shown.bs.modal', function(){
            $("#codigoProductoCubeta").focus();
        });
    });

    $(document).on('focusout', '#codigoProductoCubeta', function() {
        $("#codigoProductoCubeta").focus();
    });
});