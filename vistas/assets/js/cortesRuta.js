const audio1 = new Audio('vistas/assets/sounds/addBip.mp3');
const audio2 = new Audio('vistas/assets/sounds/notBip.mp3');
var tipoClienteRuta = 0;
var lotesRuta = [];

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
                required: "La ruta es requerida."
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
            var data = "metodo="+$("#bGuardarCorte").attr('tipo')+"&accion=cortesRuta&ruta="+$("#rutasCorte").val()+"&fechaInicio="+$("#FechaInicioCorte").val()+"&fechaFin="+$("#FechaFinCorte").val()+"&chofer="+$("#selectChofer").val()+"&vehiculo="+$("#selectVehiculo").val()+"&sucursal="+$("#selectSucursal").val()+"&monto="+$("#montoCorte").val()+"&id="+$("#bGuardarCorte").attr('attrID');
            
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
                        title: 'Ruta '+tipoAlerta+' correctamente'
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

    $('#formVerificarCubeta').validate({
        rules: {
            codigoProductoCubeta: {
                required: true
            }
        },
        messages: {
            codigoProductoCubeta: {
                required: ""
            }
        },
        submitHandler: function(form) {
            var separa = $.trim($("#codigoProductoCubeta").val()).split('~');
            var codigo = separa[0];
            var lote = separa[1];
            var elementos = $("tr.comproCubetas:contains('"+codigo+"')");
            var encontro = false;
            var fila = null;

            elementos.each(function(index, el) {
                if(codigo == $(this).children('th:eq(0)').text()){
                    var cantidad = parseInt($(this).children('th:eq(2)').text());
                    var valor = parseInt($(this).children('th:eq(3)').text());

                    if(cantidad > valor){
                        valor++;
                        $(this).children('th:eq(3)').text(valor);
                        encontro = true;
                        fila = $(this);
                        
                        if(cantidad == valor){
                            $(this).addClass('table-success');
                            $(this).children('th:eq(4)').html("<span class='badge rounded-pill bg-success'>Listo</span>");
                        }else{
                            $(this).children('th:eq(4)').html("<span class='badge rounded-pill bg-warning'>Pendiente</span>");
                        }

                        // 👉 Aquí sumamos por lote si existe
                        if(lote && lote.trim() !== ""){
                            // Buscar si ya existe ese lote en el arreglo
                            var existe = lotesRuta.find(obj => obj.codigo === codigo && obj.lote === lote);
                            if(existe){
                                existe.cantidad++;
                            }else{
                                lotesRuta.push({
                                    codigo: codigo,
                                    lote: lote,
                                    cantidad: 1,
                                    producto: $(this).attr('producto'),
                                    presentacion: $(this).attr('presentacion')
                                });
                            }
                        }

                        return false;
                    }
                }
            });

            if(encontro == false){
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Producto no encontrado",
                    showConfirmButton: false,
                    timer: 500
                }); 
                audio2.play();
            }else{
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: fila.children('th:eq(1)').text()+': '+fila.children('th:eq(3)').text(),
                    showConfirmButton: false,
                    timer: 500
                }); 
                audio1.play();
            }    

            $("#codigoProductoCubeta").val('');
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
                required: ""
            }
        },
        submitHandler: function(form) { 
            var separa = $.trim($("#codigoProducto").val()).split('~');
            var codigo = separa[0];
            var lote = separa[1];
            var elementos = $("tr.comproProd:contains('"+codigo+"')");
            var encontro = false;
            var fila = null;

            elementos.each(function(index, el) {
                if(codigo == $(this).children('th:eq(0)').text()){
                    var cantidad = parseInt($(this).children('th:eq(2)').text());
                    var valor = parseInt($(this).children('th:eq(3)').text());

                    if(cantidad > valor){
                        valor++;
                        $(this).children('th:eq(3)').text(valor);
                        encontro = true;
                        fila = $(this);
                        
                        if(cantidad == valor){
                            $(this).addClass('table-success');
                            $(this).children('th:eq(4)').html("<span class='badge rounded-pill bg-success'>Listo</span>");
                        }else{
                            $(this).children('th:eq(4)').html("<span class='badge rounded-pill bg-warning'>Pendiente</span>");
                        }

                        // 👉 Aquí sumamos por lote si existe
                        if(lote && lote.trim() !== ""){
                            // Buscar si ya existe ese lote en el arreglo
                            var existe = lotesRuta.find(obj => obj.codigo === codigo && obj.lote === lote);
                            if(existe){
                                existe.cantidad++;
                            }else{
                                lotesRuta.push({
                                    codigo: codigo,
                                    lote: lote,
                                    cantidad: 1,
                                    producto: $(this).attr('producto'),
                                    presentacion: $(this).attr('presentacion')
                                });
                            }
                        }

                        return false;
                    }
                }
            });

            if(encontro == false){
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Producto no encontrado",
                    showConfirmButton: false,
                    timer: 500
                }); 

                audio2.play();
            }else{
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: fila.children('th:eq(1)').text()+': '+fila.children('th:eq(3)').text(),
                    showConfirmButton: false,
                    timer: 500
                }); 

                audio1.play();
            }    

            $("#codigoProducto").val('');               
        }
    }); 

    $('#formExtra').validate({
        rules: {
            clienteExtra: {
                required: true
            },
            ordenExtra: {
                required: true
            }
        },
        messages: {
            codigoProducto: {
                required: "El clinete es requerido."
            },
            ordenExtra: {
                required: "El orden es requerido."
            }
        },
        submitHandler: function(form) { 
            if($("#verClientesExtras").children('tr[attrID="'+$("#clienteExtra").attr('attrID')+'"]').length == 0){
                var data = "metodo=detalles&accion=cortesRuta&tipo=agregarExtra&ruta="+$("#rutasCorte").val()+"&cliente="+$("#clienteExtra").attr('attrID')+"&orden="+$("#ordenExtra").val()+"&corte="+$("#bGuardarCorte").attr('attrID');
                
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        $("#carga").show();
                    }
                })
                .done(function(res) {
                    var separa = $.trim(res).split('~');
                    if (separa[0] == "Correcto") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cliente agregado correctamente'
                        });

                        $("#verClientesExtras").append(`<tr attrID="`+$("#clienteExtra").attr('attrID')+`">
                            <td>`+$("#clienteExtra").val()+`</td>
                            <td>`+$("#ordenExtra").val()+`</td>
                            <td><button type="button" class="btn btn-danger btn-sm bBorrarExtra" attrID="`+separa[1]+`"><i class="fas fa-trash"></i></button></td>
                        </tr>`);

                        verVentasRuta(); 
                        $("#modalExtra").modal("hide");
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al agregar el cliente.'
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
            }else{
                $("#modalExtra").modal("hide");
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
            "Estatus",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "cortesRuta",
            "tipo": "cortesRutas"
        }
    });
}

function tablaClientesExtra() {
    ajaxMyDatatable({
        "table": $("#tablaClientesExtra"), 
        "colums": [
            "Nombre",
            "Ruta",
            "Orden"
        ], 
        "sort": [0, "asc"],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "cortesRuta",
            "tipo": "clientesExtra",
            "ruta": $("#rutasCorte").val(),
            "tipoCliente": tipoClienteRuta
        }
    });
}

function verVentasRuta() {
   ajaxMyDatatable({
        "table": $("#tablaVentasRuta"), 
        "colums": [
            "Orden",
            "Folio",
            "Cliente",
            "Domicilio",         
            "Total",
            "Fecha",
            "Estatus",
            "Acciones"
        ], 
        "sort": [0, "desc"],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "cortesRuta",
            "tipo": "ventasRutas",
            "ruta": $("#rutasCorte").val(),
            "fechaIn": $("#FechaInicioCorte").val(),
            "fechaFin": $("#FechaFinCorte").val(),
            "sucursal": $("#selectSucursal").val(),
            "corte": $("#bGuardarCorte").attr('attrID')
        },
        "totals":{
            4: "Total"
        }
    });

    caluloTotalBalance($("#bGuardarCorte").attr('attrID'));
}

function verificarProd(arreglo) {
    var data = "metodo=detalles&accion=cortesRuta&tipo=verificarProd&verificados="+JSON.stringify(arreglo)+"&lotesRuta="+JSON.stringify(lotesRuta);
    //console.log(data);

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
            $("#carga").show();
        }
    }).done(function(res){
        if($.trim(res) != 'Correcto'){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error inespeardo al guardar los productos verificados.'
            });

            console.log($.trim(res));
        }else{
            lotesRuta = [];
        }
    }).fail(function(){
        console.log('Error ajax');
    }).always(function(){
        $("#carga").hide();
    });
}

function caluloTotalBalance(corte) {
    var data = "metodo=detalles&accion=cortesRuta&tipo=totales&corte="+corte;

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
            $("#carga").show();
        }
    }).done(function(res){
        //console.log($.trim(res));
        var datos = JSON.parse($.trim(res));
        //console.log(datos);

        $("#total_corte_bruto").html(datos.Total);
        $("#total_gastos_corte").html(datos.Gastos);
        $("#total_neto_corte").html(parseFloat(datos.Total) - parseFloat(datos.Gastos));
        $("#balance_final").html(parseFloat($("#montoCorte").val() == '' ? 0 : $("#montoCorte").val()) - (parseFloat(datos.Total) - parseFloat(datos.Gastos)));
    
        moneda();
    }).fail(function(){
        console.log('Error ajax');
    }).always(function(){
        $("#carga").hide();
    });
}

function verCubetasCorte(idCorte, orden){
    var data = "metodo=detalles&accion=cortesRuta&tipo=verCubetas&id="+idCorte+"&orden="+orden;

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
            $("#carga").show();
        }
    }).done(function(res){
        $('#tbodyCubetas').html($.trim(res));

        $("#modalCubeta").modal('show');
    }).fail(function(){
        console.log('Error ajax');
    }).always(function(){
        $("#carga").hide();
    });
}

function tablaVentasExtra() {
  ajaxMyDatatable({
    table: $('#tablaVentasExtra'),
    colums: [
      'Datos',
      'Estatus',
      'Cliente',
      'Total'
    ],
    sort: [0, 'desc'],
    url: 'index.php',
    params: {
      metodo: 'detalles',
      accion: 'cortesRuta',
      tipo: "ventas"
    }
  });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#bNuevoCorteRuta', function() {
        $(".verExtras").addClass('oculto');
        $(".verBalancesRuta").addClass('oculto');
        $("#formCorteDeRuta")[0].reset();

        var today = new Date();
        var threeMoreDays = new Date(today);
        threeMoreDays.setDate(today.getDate() - 3);

        $("#FechaInicioCorte").val(threeMoreDays.toISOString().split('T')[0]);
        $("#FechaFinCorte").val(today.toISOString().split('T')[0]);
        verVentasRuta();

        $("#bGuardarCorte").attr('tipo', 'insertar');
        $("#bGuardarCorte").attr('attrID', '');
        $("#modalCorteRuta").modal('show');
    });

    $(document).on('change', '#FechaInicioCorte', function() {
        verVentasRuta();
    });

    $(document).on('change', '#FechaFinCorte', function() {
        verVentasRuta();
    });

    $(document).on('change', '#rutasCorte', function() {
        verVentasRuta();
    });

    $(document).on('change', '#rutasCorte', function() {
        verVentasRuta();
    });

    $(document).on('change', '#selectSucursal', function() {
        verVentasRuta();
    });

    $(document).on('click', '.bVerificarRuta', function() {
        if($("#bGuardarCorte").attr('tipo') == 'insertar'){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Para empezar a verificar, por favor guarda la ruta.'
            });
        }else{
            $("#verDatosVentaCorte").html(`
                <h6 style="margin: 0;">Folio: `+$(this).parent().parent().children('td:eq(1)').text()+`</h6>
                <h6 style="margin: 0;">Cliente: `+$(this).parent().parent().children('td:eq(2)').text()+`</h6> 
            `);
            
            var id = $(this).attr('attrID');
            var data = `metodo=detalles&accion=cortesRuta&tipo=verProductos&id=${id}&corte=${$("#bGuardarCorte").attr('attrID')}`;

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            }).done(function(res){
                $('#tbodyVerificar').html($.trim(res));

                $("#modalVerificar").modal('show');
            }).fail(function(){
                console.log('Error ajax');
            }).always(function(){
                $("#carga").hide();
            });
        }
    });

    $(document).on('change keyup', '#montoCorte', function() {
        caluloTotalBalance($("#bGuardarCorte").attr('attrID'));
    });

    $(document).on('click', "#bGuardarCorte", function(event){
        event.preventDefault();
        $("#formCorteDeRuta").submit();
    });
    
    $(document).on('click', '.bEliminarCorteRuta', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro de eliminar el corte de ruta?',
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

    $(document).on('click', '.bModificarCorteRuta', function(){
        $(".verExtras").removeClass('oculto');
        $(".verBalancesRuta").removeClass('oculto');
        var idCorte = $(this).attr('attrID');
        $("#bGuardarCorte").attr('attrID', idCorte);
        $("#bGuardarCorte").attr('tipo', 'modificar');
        var data = `metodo=detalles&accion=cortesRuta&tipo=obtenerCorteGuardado&id=${$(this).attr('attrID')}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function(){
                $("#carga").show();
            }
        }).done(function(res){
            //console.log($.trim(res));
            const resData = JSON.parse(res);
            //console.log(resData);

            $("#rutasCorte").val(resData.FK_Ruta);
            $("#FechaInicioCorte").val(resData.Fecha_Inicio);
            $("#FechaFinCorte").val(resData.Fecha_Fin);
            $("#selectChofer").val(resData.FK_Chofer);
            $("#selectVehiculo").val(resData.FK_Vehiculo);
            $("#selectSucursal").val(resData.FK_Sucursal);
            $("#montoCorte").val(resData.Monto);

            $("#tablaCostesRuta tbody").html(resData.Gastos);
            $("#verClientesExtras").html(resData.Extras);
            $("#verClientesQuitarRuta").html(resData.Quitados);

            if(resData.Estatus == 'Finalizado'){
                $("#cerrarCorteRuta").addClass('oculto');
                $(".bEliminarGastoRuta").remove();
                $("#gastosFormulario").addClass('oculto');
                $("#bGuardarCorte").addClass('oculto');
                $(".bBorrarExtra").remove();
            }else{
                $("#cerrarCorteRuta").removeClass('oculto');
                $("#bGuardarCorte").removeClass('oculto');
                $("#gastosFormulario").removeClass('oculto');
            }

            verVentasRuta();

            /*if(resData.Imagen && resData.Imagen != ''){
                $("#downloadTheFile").removeClass('d-none');
                $("#downloadTheFile").attr('href', `vistas/assets/archivos/cortesRuta/${resData.Imagen}`);
                $("#uploadImgBtn strong").text('Resubir archivo');
                $("#uploadImgBtn").attr('nombre_photo', resData.Imagen);
            }else{
                $("#downloadTheFile").addClass('d-none');
                $("#downloadTheFile").attr('href', ``);
                $("#uploadImgBtn strong").text('Subir archivo');
                $("#uploadImgBtn").attr('nombre_photo', '');
            }*/

            moneda();
            $("#modalCorteRuta").modal('show');
        }).always(function(){
            $("#carga").hide();
        })
    });

    $(document).on('click', '.bConcentradoCorteRuta', function() {
        var idCorte = $(this).attr('attrID');
        $('#bImprimirVerificacion').attr('href', "controladores/pdf/ticketCorteRuta.php?id="+idCorte);
        var data = `metodo=detalles&accion=cortesRuta&tipo=concentradoCorte&id=${idCorte}`;

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        }).done(function(res){
            //console.log($.trim(res));
            $('#tbodyConcentradoRuta').html($.trim(res));

            console.log($("#modalConcentradoRuta"));
            $("#modalConcentradoRuta").modal('show');
        }).fail(function(){
            console.log('Error ajax');
        })
        .always(function() {
            $("#carga").hide();
        });  
    });

    $(document).on('click', '.bVerificarCubetas', function() {
        var idCorte = $(this).attr('attrID');
        $("#bOrdenCubetas").attr('attrID', idCorte);

        var orden = "DESC";
        if($("#bOrdenCubetas").children('i').hasClass('fa-down-long')){
            orden = "ASC";
        }

        verCubetasCorte(idCorte, orden);
    });

    $(document).on('shown.bs.modal', "#modalCubeta", function(){
        $("#codigoProductoCubeta").focus();
    });

    $(document).on('focusout', '#codigoProductoCubeta', function() {
        $("#codigoProductoCubeta").focus();
    });

    $(document).on('hide.bs.modal', "#modalCubeta", function(){
        var verificados = [];
        $("tr.comproCubetas").each(function(index, el) {
            if($(this).children('th:eq(3)').text() != '0'){ 
                verificados.push(
                    {
                        corte: $(this).attr('corte'), 
                        venta: $(this).attr('venta'), 
                        producto: $(this).attr('producto'), 
                        presentacion: $(this).attr('presentacion'),
                        cantidad: $(this).children('th:eq(3)').text()
                    }
                );
            }
        });

        verificarProd(verificados);
    });

    $(document).on('shown.bs.modal', "#modalVerificar", function(){
        $("#codigoProducto").focus();
    });

    $(document).on('focusout', '#codigoProducto', function() {
        $("#codigoProducto").focus();
    });

    $(document).on('hide.bs.modal', "#modalVerificar", function(){
        var verificados = [];
        $("tr.comproProd").each(function(index, el) {
            if($(this).children('th:eq(3)').text() != '0'){ 
                verificados.push(
                    {
                        corte: $(this).attr('corte'), 
                        venta: $(this).attr('venta'), 
                        producto: $(this).attr('producto'), 
                        presentacion: $(this).attr('presentacion'),
                        cantidad: $(this).children('th:eq(3)').text()
                    }
                );
            }
        });

        verificarProd(verificados);
        verVentasRuta();
    });

    $(document).on('hide.bs.modal', "#modalCorteRuta", function(){
        tablaCortesRuta();
    });

    $(document).on('click', '.bEliminarGastoRuta', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro de eliminar el gasto de la ruta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = "metodo=detalles&accion=cortesRuta&tipo=eliminarGasto&id="+btn.attr('attrID');

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
                            title: 'El gasto ha sido eliminado correctamente'
                        });

                        btn.parent().parent().remove();
                        caluloTotalBalance($("#bGuardarCorte").attr('attrID')); 
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el gasto.'
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

    $(document).on('click', '#cerrarCorteRuta', function() {
        Swal.fire({
            title: '¿Estás seguro que quieres cerrar el corte de ruta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, finalizar'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = "metodo=detalles&accion=cortesRuta&tipo=finalizar&id="+$("#bGuardarCorte").attr('attrID');

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
                            title: 'El corte ha sido finalizado correctamente'
                        });

                        $("#modalCorteRuta").modal('hide');
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al finalizar el corte.'
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

    $(document).on('submit', '#gastosFormulario', function(event) {
        event.preventDefault();
        var data = "metodo=detalles&accion=cortesRuta&tipo=insertar_gastos&corte="+$("#bGuardarCorte").attr('attrID')+"=&descripcion="+$.trim($("#gastoDescripcion").val())+"&monto="+$.trim($("#gastoCoste").val());
                
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        }).done(function(res){
            var separa = $.trim(res).split('~');

            if(separa[0] == "Correcto"){
                $("#tablaCostesRuta tbody").append(`
                    <tr>
                        <td>`+$.trim($("#gastoDescripcion").val())+`</td>
                        <td><span class="dinero">`+$.trim($("#gastoCoste").val())+`</span></td>
                        <td><button type="button" class="btn btn-sm btn-danger bEliminarGastoRuta" attrID="`+separa[1]+`"><i class="fas fa-trash"></i></button></td>
                    </tr>
                `);

                $("#gastosFormulario")[0].reset();
                caluloTotalBalance($("#bGuardarCorte").attr('attrID'));
            }
        }).fail(function(){
            console.log("Error ajax");
        }).always(function(){
            $("#carga").hide();
        });
    });

    $(document).on('click', '#bAgregarClienteExtra', function() {
        $("#formExtra")[0].reset();
        $("#modalExtra").modal('show');
    });

    $(document).on('click', '#bBuscarClienteExtra', function() {
        tipoClienteRuta = 0;
        tablaClientesExtra();
        $("#modalClientesExtra").modal('show');
    });

    $(document).on('click', '#tablaClientesExtra tbody tr', function() {
        var fila = $(this);

        if(tipoClienteRuta == 0){
            $("#clienteExtra").attr('attrID', fila.attr('ID'));
            $("#clienteExtra").val(fila.children('td:eq(0)').text());
            $("#ordenExtra").val(fila.children('td:eq(2)').text());

            $("#modalClientesExtra").modal('hide');
        }else{
            if($("#verClientesQuitarRuta").children('tr[attrID="'+fila.attr('ID')+'"]').length == 0){
                var data = "metodo=detalles&accion=cortesRuta&tipo=agregarQuitado&cliente="+fila.attr('ID')+"&corte="+$("#bGuardarCorte").attr('attrID');

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        $("#carga").show();
                    }
                })
                .done(function(res) {
                    var separa = $.trim(res).split('~');

                    if (separa[0] == "Correcto") {
                        $("#verClientesQuitarRuta").append(`<tr attrID="`+fila.attr('ID')+`">
                            <td>`+fila.children('td:eq(0)').text()+`</td>
                            <td><button type="button" class="btn btn-danger btn-sm bEliminarClienteQuitado" attrID="`+separa[1]+`""><i class="fas fa-trash"></i></button></td>
                        </tr>`);

                        verVentasRuta();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al quitar el cliente.'
                        });

                        console.log($.trim(res));
                    }

                    $("#modalClientesExtra").modal('hide');
                })
                .fail(function() {
                    console.log("Error ajax");
                })
                .always(function() {
                    $("#carga").hide();
                });
            }else{
                $("#modalClientesExtra").modal('hide');
            }
        }
    });

    $(document).on('click', '.bBorrarExtra', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro de eliminar el cliente de la ruta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = "metodo=detalles&accion=cortesRuta&tipo=eliminarExtra&id="+btn.attr('attrID');

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
                            title: 'El cliente ha sido eliminado correctamente'
                        });

                        btn.parent().parent().remove();
                        verVentasRuta();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el cliente.'
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

    $(document).on('click', '.bGenerarPDFCorteRuta', function(){
        var routeId = $(this).attr('attrID');
        window.open('./controladores/pdf/corteRuta.php'+'?id='+routeId, '_blank');
    });

    $(document).on('click', '#bQuitarClienteRuta', function() {
        tipoClienteRuta = 1;
        tablaClientesExtra();
        $("#modalClientesExtra").modal('show');
    });

    $(document).on('click', '.bEliminarClienteQuitado', function() {
        var btn = $(this);
        Swal.fire({
            title: '¿Estás seguro de remover el cliente?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar',
            confirmButtonText: 'Si, quitar'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = "metodo=detalles&accion=cortesRuta&tipo=eliminarQuitado&id="+btn.attr('attrID');

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
                            title: 'El cliente ha sido removido correctamente'
                        });

                        btn.parent().parent().remove();
                        verVentasRuta();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al eliminar el cliente.'
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
	
    $(document).on('click', '#bOrdenCubetas', function(){
        var orden = "";

        if($(this).children('i').hasClass('fa-up-long')){
            $(this).children('i').removeClass('fa-up-long');
            $(this).children('i').addClass('fa-down-long');

            orden = "ASC";
        }else{
            $(this).children('i').removeClass('fa-down-long');
            $(this).children('i').addClass('fa-up-long');

            orden = "DESC";
        }  

        verCubetasCorte($(this).attr('attrID'), orden); 
    });

    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    //>>>>>>>>>>>>>>>>>>Ventas Extras>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


    $(document).on('click', '#bAgregarVentaRuta', function() {
        tablaVentasExtra();
        $("#modalVentasExtra").modal('show');
    });

    $(document).on('click', '#tablaVentasExtra tbody tr', function() {
        var data = "metodo=detalles&accion=cortesRuta&tipo=agregarVentaExtra&venta="+$(this).attr('id')+'&corte='+$("#bGuardarCorte").attr('attrID');

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
                verVentasRuta();
                caluloTotalBalance($("#bGuardarCorte").attr('attrID'));
                $("#modalVentasExtra").modal('hide');
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inesperado al agregar la venta.'
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

    $(document).on('click', '.bQuitarVentaRuta', function() {
        var data = "metodo=detalles&accion=cortesRuta&tipo=quitarVentaExtra&venta="+$(this).attr('idVenta')+'&corte='+$("#bGuardarCorte").attr('attrID');

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
                verVentasRuta();
                caluloTotalBalance($("#bGuardarCorte").attr('attrID'));
                $("#modalVentasExtra").modal('hide');
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inesperado al quitar la venta.'
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

    /*$(document).on('change', '#imageInput', function(){
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
    */
});