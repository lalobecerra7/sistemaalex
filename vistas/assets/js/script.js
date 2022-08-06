    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    function agregarArreglo(arr, idRepa, latitud, longitud, foto) {
        var index = arr.map(x => {
          return x.ID;
        }).indexOf(idRepa);

        arr.splice(index, 1); 

        /*if (marcadores.length > 0){
            //for (var x = 0; x < marcadores.length; x++) {
            marcadores[0].setMap(null); 
            //}
        }*/

        arr.push(
            {
                ID: idRepa,
                Latitud: latitud,
                Longitud: longitud,
                Foto: foto
            }
        );
        return arr;
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

    function ConsultarPedidosPendientes(){
        var data="metodo=consultar&accion=principal";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            $("#DivPedidosPendientes").html("");
            $("#DivPedidosPendientes").append(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    }

    //formato de modeda a la clase .dinero
    function moneda() {
        $(".dinero").each(function(index, el) {
            if(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) < 0){
                $(this).html(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * -1);
                $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
                $(this).html('-'+$(this).html());
                $(this).css('color', 'red');
            }else{
                $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
            }
        });

        $(".cantidad").each(function(index, el) {
            $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));

            /*if ($(this).hasClass('canPlantas')) {
                $(this).html($(this).html().substring(0, $(this).text().length - 3));
            }*/
        });
    }

    function generarLetra(){
        var letras = ["a","b","c","d","e","f","0","1","2","3","4","5","6","7","8","9"];
        var numero = (Math.random()*15).toFixed(0);
        return letras[numero];
    }

    function colorHEX(){
        var coolor = "";
        for(var i=0;i<6;i++){
            coolor = coolor + generarLetra() ;
        }
        return "#" + coolor;
    }

    $(document).on('click', '#CerrarSesion', function(){
        cerrarSesion();
    });


    $(document).on('click', '.DetallesPedido', function(){
        var id = $(this).attr("attrid");
        $('#tbodyDetallesProducto').html("");
        $("#folioOrden").html("#"+$(this).attr("folio"));
        $("#SubtotalPedido").html($(this).attr('totalpedido'));
        $("#CostoEnvioPedido").html($(this).attr('costoenvio'));
        $("#TotalPedido").html(parseFloat($(this).attr('totalpedido'))+parseFloat($(this).attr('costoenvio')));
        $("#MetodoPago").html($(this).attr('metodopago'));
        $("#FechaPedido").html($(this).attr('fechapedido'));
        $("#HoraPedido").html($(this).attr('horapedido'));
        $("#RechazarPedidoModal").attr("attrid", id);
        $("#RechazarPedidoModal").attr("idNegocio", $(this).attr('idNegocio'));
        $("#RechazarPedidoModal").attr("idCliente", $(this).attr('idCliente'));
        $("#RechazarPedidoModal").attr("folio", $(this).attr('folio'));
        $("#AceptarPedidoModal").attr("attrid", id);
        $("#AceptarPedidoModal").attr("idNegocio", $(this).attr('idNegocio'));
        $("#AceptarPedidoModal").attr("idCliente", $(this).attr('idCliente'));
        $("#AceptarPedidoModal").attr("folio", $(this).attr('folio'));
        
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidos",
                "tipo": "ConsultarOrden",
                "idPedido": id
            }
        })
        .done(function(res) {
            $('#tbodyDetallesProducto').html(res);
            moneda();
        })
        .fail(function() {
            console.log("error");
        });

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidos",
                "tipo": "ConsultarInformacionPedido",
                "idPedido": id
            }
        })
        .done(function(res) {
            $('#InformacionPedido').html(res);
            moneda();
        })
        .fail(function() {
            console.log("error");
        });
    });

    $(document).on('click', '.AceptarPedido', function(){
        $("#ModalVerDetalles").modal("hide");

        var table = $('#TablaPedidosPendientes').DataTable();
        var fila = null;

        var id = $(this).attr("attrid");
        var folio = $(this).attr("folio");
        var boton = $(".btnDetallesPendiente"+id);
        var idPedido = $(this).attr("attrid");
        var idNegocio = $(this).attr("idNegocio");
        var idCliente = $(this).attr("idCliente");
        Swal.fire({
          title: '¿Seguro que quieres aceptar la orden #'+folio+'?',
          icon: 'warning',
          showCancelButton: true,
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
           confirmButtonText: 'Aceptar',
          confirmButtonColor: '#3085d6',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: {
                        "metodo": "modificar",
                        "accion": "pedidos",
                        "tipo": "AceptarPedido",
                        "idNegocio": idNegocio,
                        "idPedido": idPedido,
                        "idCliente": idCliente
                    }
                })
                .done(function(res) {
                    if (res == "Correcto") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido aceptado correctamente'
                        });
                        /*var table = $('#TablaPedidosPendientes').DataTable();
                        table.row( boton.parent().parent() ).remove().draw(false);*/
                        fila = table.row('#PEDIDO-'+id);
                        table.row(fila).remove().draw(false);
                        ConsultarPedidosPendientes();
                        //row.remove().draw(false);
                        //socket.emit('pedidoNegocio', {ID_Pedido: idPedido, Cliente: idCliente, Negocio: idNegocio});
                        socket.emit('pedidoCliente', {idCliente: idCliente, Negocio: idNegocio});
                        //ObtenerPendientes();
                        //ObtenerAceptados();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al aceptar el pedido.'
                        });
                        console.log(res);
                    }
                    
                })
                .fail(function() {
                    console.log("error");
                });
            }
        });
    });

    $(document).on('click', '.RechazarPedido', function(){
        $("#ModalVerDetalles").modal("hide");
        var id = $(this).attr("attrid");

        var table = $('#TablaPedidosPendientes').DataTable();
        var fila = null;
        
        Swal.fire({
          title: '¿Seguro que quieres rechazar la orden #'+$(this).attr("folio")+'?',
          input: 'text',
          inputPlaceholder: 'Motivo del rechazo',
          icon: 'warning',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
           confirmButtonText: 'Aceptar',
          confirmButtonColor: '#3085d6',
        }).then((result) => {
            if (result.value) {
                var idPedido = $(this).attr("attrid");
                var idNegocio = $(this).attr("idNegocio");
                var idCliente = $(this).attr("idCliente");
                var motivo = result.value;
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: {
                        "metodo": "modificar",
                        "accion": "pedidos",
                        "tipo": "RechazarPedido",
                        "idNegocio": idNegocio,
                        "idPedido": idPedido,
                        "idCliente": idCliente,
                        "problema": motivo,
                    }
                })
                .done(function(res) {
                    if (res == "Correcto") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido rechazado correctamente'
                        });
                        fila = table.row('#PEDIDO-'+id);
                        table.row(fila).remove().draw(false);
                        socket.emit('pedidoNegocio', {ID_Pedido: idPedido, Cliente: idCliente, Negocio: idNegocio});
                        ConsultarPedidosPendientes();
                        //ObtenerPendientes();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al rechazar el pedido.'
                        });
                        console.log(res);
                    }
                    
                })
                .fail(function() {
                    console.log("error");
                });
            }
        });
    }); 

    $(document).on('click', '.IrPedidosPendientes', function(){
        $("#cargarInicio").trigger('click');
    });

    function crearDatatable() {
        $(".crearDataTable").dataTable({
            "destroy": true,
            //"order": [[0, 'desc'], [1, 'asc']],
            "initComplete": function(settings, json) {
                moneda();
            },
            "stateSave": true,
            "stateSaveParams": function (settings, data) {
                data.search.search = "";
            },
            "deferRender": true,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "",
                "searchPlaceholder": "Buscar . . .",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                 buttons: {
                    copy: 'Copiar',
                    copySuccess: {
                        1: "Se ha copiado una fila",
                        _: "Se han copiado %d filas"
                    },
                    copyTitle: 'Elementos copiados'
                }
            },
            dom:"<'row mb-3'<'col-sm-12 text-end'B>>"+
                "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                "<'row mb-3'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-secondary btn-sm',
                    text: "<i class='fas fa-copy'></i>",
                    titleAttr: 'Copiar',
                    footer: true
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-success btn-sm',
                    text: "<i class='fas fa-file-excel'></i>",
                    titleAttr: 'Excel',
                    filename: 'SPIDI',
                    title: 'SPIDI',
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-danger btn-sm',
                    text: "<i class='fas fa-file-pdf'></i>",
                    titleAttr: 'PDF',
                    filename: 'SPIDI',
                    title: 'SPIDI',
                    orientation: 'portrait',
                    pageSize: 'LETTER',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 11;
                        doc.styles.tableHeader.fontSize = 14;
                        doc.defaultStyle.alignment = 'center';
                    },
                    footer: true
                }
            ]
        });
    }

    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function hacerDataTable(vista) {
        var table = $('.tablaDatatable');
        if (table.length == 0) {
            return;
        }

        var orden = [0, 'asc'];
        var clase = vista.split("_");
        var numRegistros = 10;
        var autoWidth = true;
        var defs = [];

        var columnas = null;
        if(clase[1] == "negocios"){
            orden = [0, 'desc'];
            columnas = [
                { "data": "Negocio"},
                { "data": "Contacto"},
                { "data": "Direccion"},
                { "data": "Estatus"},
                { "data": "Acciones"},
            ];
        }

        /*$.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": clase[1],
                "tipo": "tabla"
            }
        })
        .done(function(res) {
            console.log($.trim(res));
            //var resRes = JSON.parse(res);
            //console.log(resRes);
        })
        .fail(function() {
            console.log("error");
        });*/

        //$.fn.dataTable.ext.errMode = 'none';
        table.dataTable({
            "destroy": true,
            "order": orden,
            "pageLength": numRegistros,
            "ajax":{
                "url": 'index.php',
                "method": 'POST',
                "data": {
                    "metodo": "consultar",
                    "accion": clase[1],
                    "tipo": "tabla"
                }
            },
            "autoWidth": autoWidth,
            "columnDefs": defs,
            "columns": columnas,
            "initComplete": function(settings, json) {
                //console.log(json);
                moneda();
            },
            "stateSave": true,
            "stateSaveParams": function (settings, data) {
                data.search.search = "";
            },
            "deferRender": true,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Cargando...",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "",
                "searchPlaceholder": "Buscar . . .",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                buttons: {
                    copy: 'Copiar',
                    copySuccess: {
                        1: "Se ha copiado una fila",
                        _: "Se han copiado %d filas"
                    },
                    copyTitle: 'Elementos copiados'
                }
            },
            dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+
                "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                "<'row mb-3'<'col-sm-12'tr>>" +
                "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-secondary btn-sm',
                    text: "<i class='fas fa-copy'></i>",
                    titleAttr: 'Copiar',
                    footer: true
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-success btn-sm',
                    text: "<i class='fas fa-file-excel'></i>",
                    titleAttr: 'Excel',
                    filename: 'SPIDI',
                    title: 'SPIDI',
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-danger btn-sm',
                    text: "<i class='fas fa-file-pdf'></i>",
                    titleAttr: 'PDF',
                    filename: 'SPIDI',
                    title: 'SPIDI',
                    orientation: 'portrait',
                    pageSize: 'LETTER',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 11;
                        doc.styles.tableHeader.fontSize = 14;
                        doc.defaultStyle.alignment = 'center';
                    },
                    footer: true
                }
            ]
        });
    }

    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    function permisos() {
        var data = "metodo=detalles&accion=principal";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log($.trim(res));
            var resA = JSON.parse(res);

            if(resA.Tipo != "Administrador"){
                $(".nvpagina").hide();
                $(".nvpagina[carga='v_usuarios']").remove();
                var cadena = resA.Cadena.split('~');
                var permisos = "";
                //console.log(cadena);

                for (var i = cadena.length - 1; i >= 0; i--) {
                    permisos = cadena[i].split(',');
                    if(permisos[0] == "v_inicio"){
                        if(permisos[1] == '0'){
                            $("#perInicio1").remove();
                        }if(permisos[2] == '0'){
                            $("#perInicio2").remove();
                        }if(permisos[1] == '0'){
                            $("#perInicio3").remove();
                        }if(permisos[4] == '0'){
                            $("#perInicio4").remove();
                        }if(permisos[5] == '0'){
                            $("#perInicio5").remove();
                        }
                    }else if(permisos[0] == "v_reportes"){
                        if(permisos[1] == '0'){
                            $(".linksaldos").remove();
                        }if(permisos[2] == '0'){
                            $(".linkcompras").remove();
                        }if(permisos[3] == '0'){
                            $(".linkventas").remove();
                        }if(permisos[4] == '0'){
                            $(".linkpagos").remove();
                        }if(permisos[5] == '0'){
                            $(".linkgastos").remove();
                        }if(permisos[6] == '0'){
                            $(".linkmovimientos").remove();
                        }if(permisos[7] == '0'){
                            $(".linkegresos").remove();
                        }if(permisos[8] == '0'){
                            $(".linkingresos").remove();
                        }if(permisos[9] == '0'){
                            $(".linkfinanzas").remove();
                        }if(permisos[10] == '0'){
                            $(".linkestadisticas").remove();
                        }
                    }else{
                        if(permisos[1] == '0'){
                            //console.log(permisos[0]);
                            $(".nvpagina[carga='"+permisos[0]+"']").remove();
                        }       
                    }
                }

                $(".dropdown-menu").each(function(index, el) {
                    var aparece = false;
                    $(this).children("li").each(function(index, el) {
                        if($(this).html() != ""){
                            aparece = true;
                        }
                    });

                    if(aparece == false){
                        $(this).parent().remove();
                    }
                });

                $(".nvpagina").show();
            }
            $(".perIn").show();
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            //console.log("complete");
        });
    }

    //Cada 1000 plantas que compren baja 1 peso hasta llegar a las 5 mil, de ahi en adelante siguen siendo los 5 pesos
    /*
    
    if($row[0]['Tipo_Venta'] == 'Administrador'){
        if($plantas > 1000 && $plantas <= 2000){
            $precio -= 1;
        }else if($plantas > 2000 && $plantas <= 3000){
            $precio -= 2;
        }else if($plantas > 3000 && $plantas <= 4000){
            $precio -= 3;
        }else if($plantas > 4000 && $plantas <= 5000){
            $precio -= 4;
        }else if($plantas > 5000){
            $precio -= 5;
        }
    }
    */
// INICIALIZAR FUNCIONES AL CARGAR LA PAGINA

    var marcadores = [];
    var arregloRepartidores = [];
    var arregloImagenRepartidores = [];
    var lat = 20.70876190388258;
    var lon = -102.3520653367936;
jQuery(document).ready(function() {
    //permisos();
    setTimeout(function() {
        $("#cargarInicio").trigger('click');
    }, 800);
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    var idVista = "cargarInicio";
    
    $(document).on('click', '.nvpagina', function(){
        idVista = $(this).attr('id');
        var itemVista = $(this);
        var nombre = $(this).attr('carga'), titulo = $(this).attr('titulo');
        var data = "metodo=cambiar&accion="+nombre;
        ConsultarPedidosPendientes();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            // beforeSend: function() {
            //     $("#carga").show();
            // }
        })
        .done(function(res) {
            $("#main").html(res);
            $("#titleVista").html(titulo);
            $(".nvpagina").removeClass("active");
            $(".menu-item").removeClass("active");
           
            if(nombre != "v_perfil"){
                itemVista.addClass("active");
            }

            if (nombre == "v_inicio") {
                TablaPedidosPendientes();
            }else if (nombre == "v_pedidos") {
                TablaPedidosEnCurso();
            }else if(nombre == "v_pedidosAceptados"){
                TablaPedidosAceptados();

                socket.on("pedidoNegocio", (data) => {
                    console.log(data);
                    var table = $('#TablaPedidosAceptados').DataTable();
                    var fila = null;
                    fila = table.row('#PEDIDOACEPTADO-'+data.ID_Pedido);
                    table.row(fila).remove().draw(false);

                });

                socket.on("pedidoCliente", (data) => {
                    console.log(data);
                    TablaPedidosAceptados();
                });
                
            }else if(nombre == "v_historialPedidos"){
                TablaHistorialPedidos();
            }else if(nombre == "v_mapa"){
                cargarMapa();

                var data="metodo=consultar&accion=negocios&tipo=ConsultarFotosRepartidores";
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                })
                .done(function(res) {
                    var datos = JSON.parse(res);
                    arregloImagenRepartidores = datos;
                })
                .fail(function() {
                    console.log("Error ajax");
                });


                socket.on("ubicacionRepartidor", (data) => {
                    var idRepartidor = data.ID_Repartidor;
                    var lati = data.Latitud;
                    var long = data.Longitud;
                    //console.log(data);
                    agregarMarcadores(agregarArreglo(arregloRepartidores, idRepartidor, lati, long, arregloImagenRepartidores[idRepartidor].Foto)); 
                });
            }

            crearDatatable();
            hacerDataTable(nombre);

            setTimeout(function() {
                var table = $('.dataTable').DataTable();
                table.page( 'first' ).draw( 'page' );
            }, 1000);

            moneda();
        })
        .fail(function() {
            console.log("Error ajax");
        }).always(function() {
            setTimeout(function() {
                $("#carga").hide();
            }, 1500);
        });
    });

    var idleTime = 0, numero = 100;

    function barra() {
        numero -= 10;
        $("#progressbarSe").css('width', numero+'%');
        $("#cuentaSe").html(numero/10+'s');

        if(numero > 100 && idleTime > 14){
            setTimeout(barra(), 1000);
        }
    }

    setInterval(function() {
        idleTime ++;
        if (idleTime > 14) { // 15 minutos
            if(!$("#session-timeout-dialog").hasClass('show')){
                $("#session-timeout-dialog").modal('show');
            }
            barra();
            setTimeout(function() {
                if (idleTime > 14){
                    cerrarSesion();
                }
            }, 11000);
        }
    }, 60000);//1 minuto

    $(document).on('click mousemove keypress', function() {
        idleTime = 0;
        numero = 100;
        $("#session-timeout-dialog").modal('hide');
        $("#progressbarSe").css('width', '100%');
        $("#cuentaSe").html('10s');
    });

    setInterval(function(){
        var data = "metodo=renovar";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
        })
        .done(function(res) {
            //console.log("Sesion renovada: "+$.trim(res));
        })
        .fail(function(){
            console.log("error ajax");
        });
    }, 60000*10);//10 min

});

    /*function ObtenerPendientes(){
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidos",
                "tipo": "ConsultarPendientes"
            }
        })
        .done(function(res) {
            $("#PedidosPendientes").html(res);
            //var resRes = JSON.parse(res);
            //console.log(resRes);
        })
        .fail(function() {
            console.log("error");
        });
    }*/

    /*function ObtenerAceptados(){
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidos",
                "tipo": "ConsultarAceptados"
            }
        })
        .done(function(res) {
            $("#PedidosAceptados").html(res);
            //var resRes = JSON.parse(res);
            //console.log(resRes);
        })
        .fail(function() {
            console.log("error");
        });
    }*/

    function TablaPedidosPendientes() {
        ConsultarPedidosPendientes();
        /*$.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidosAceptados",
                "tipo": "ConsultarAceptados"
            }
        })
        .done(function(res) {
            console.log(res);
        })
        .fail(function() {
            console.log("error");
        });*/


        $("#TablaPedidosPendientes").dataTable({
              "destroy": true,
              "order": [0, 'desc'],
              "pageLength": 10,
              "ajax":{
                  "url": 'index.php',
                  "method": 'POST',
                  "data": {
                      "metodo": "consultar",
                      "accion": "pedidos",
                      "tipo": "ConsultarPendientes"
                  }
              },
              "autoWidth": false,
              "columns": [
                  { "data": "Cliente"},
                  { "data": "Negocio"},
                  { "data": "Totales"},
                  { "data": "Detalles"},
                  { "data": "Acciones"}
              ],
              "initComplete": function(settings, json) {
                  //console.log(json);
                  moneda();
              },
              "stateSave": true,
              "stateSaveParams": function (settings, data) {
                  data.search.search = "";
              },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
        });
    }

    function TablaPedidosAceptados() {
        /*$.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidosAceptados",
                "tipo": "ConsultarAceptados"
            }
        })
        .done(function(res) {
            console.log(res);
        })
        .fail(function() {
            console.log("error");
        });*/


        $("#TablaPedidosAceptados").dataTable({
              "destroy": true,
              "order": [0, 'desc'],
              "pageLength": 10,
              "ajax":{
                  "url": 'index.php',
                  "method": 'POST',
                  "data": {
                      "metodo": "consultar",
                      "accion": "pedidosAceptados",
                      "tipo": "ConsultarAceptados"
                  }
              },
              "autoWidth": false,
              "columns": [
                  { "data": "Cliente"},
                  { "data": "Negocio"},
                  { "data": "Totales"},
                  { "data": "Detalles"},
                  { "data": "Acciones"}
              ],
              "initComplete": function(settings, json) {
                  //console.log(json);
                  moneda();
              },
              "stateSave": true,
              "stateSaveParams": function (settings, data) {
                  data.search.search = "";
              },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
        });
    }

    function TablaPedidosEnCurso() {
      $("#TablaPedidosEnCurso").dataTable({
              "destroy": true,
              "order": [0, 'desc'],
              "pageLength": 10,
              "ajax":{
                  "url": 'index.php',
                  "method": 'POST',
                  "data": {
                      "metodo": "consultar",
                      "accion": "pedidos",
                      "tipo": "ConsultarEnCurso"
                  }
              },
              "autoWidth": false,
              "columns": [
                  { "data": "Cliente"},
                  { "data": "Negocio"},
                  { "data": "Repartidor"},
                  { "data": "Totales"},
                  { "data": "Detalles"}
              ],
              "initComplete": function(settings, json) {
                  //console.log(json);
                  moneda();
              },
              "stateSave": true,
              "stateSaveParams": function (settings, data) {
                  data.search.search = "";
              },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
          }); 
    }

    function TablaHistorialPedidos() {
        /*$.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidosAceptados",
                "tipo": "ConsultarAceptados"
            }
        })
        .done(function(res) {
            console.log(res);
        })
        .fail(function() {
            console.log("error");
        });*/


        $("#TablaHistorialPedidos").dataTable({
              "destroy": true,
              "order": [0, 'desc'],
              "pageLength": 10,
              "ajax":{
                  "url": 'index.php',
                  "method": 'POST',
                  "data": {
                      "metodo": "consultar",
                      "accion": "historialPedidos",
                      "tipo": "ConsultarHistorial"
                  }
              },
              "autoWidth": false,
              "columns": [
                  { "data": "Fecha"},
                  { "data": "Cliente"},
                  { "data": "Negocio"},
                  { "data": "Repartidor"},
                  { "data": "Totales"},
                  { "data": "Detalles"}
              ],
              "initComplete": function(settings, json) {
                  //console.log(json);
                  moneda();
              },
              "stateSave": true,
              "stateSaveParams": function (settings, data) {
                  data.search.search = "";
              },
              "deferRender": true,
              "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún dato disponible en esta tabla",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "",
                  "searchPlaceholder": "Buscar . . .",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  },
                  buttons: {
                      copy: 'Copiar',
                      copySuccess: {
                          1: "Se ha copiado una fila",
                          _: "Se han copiado %d filas"
                      },
                      copyTitle: 'Elementos copiados'
                  }
              },
              dom:"<'row mb-3'<'col-sm-12 text-end espacio'B>>"+ 
                  "<'row mb-3'<'col-sm-6 text-start'l><'col-sm-12 col-md-6 text-end'f>>" +
                  "<'row mb-3'<'col-sm-12'tr>>" +
                  "<'row paginacion'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",//'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-secondary btn-sm',
                      text: "<i class='fas fa-copy'></i>",
                      titleAttr: 'Copiar',
                      footer: true
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-success btn-sm',
                      text: "<i class='fas fa-file-excel'></i>",
                      titleAttr: 'Excel',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      footer: true
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-danger btn-sm',
                      text: "<i class='fas fa-file-pdf'></i>",
                      titleAttr: 'PDF',
                      filename: 'SPIDI',
                      title: 'SPIDI',
                      orientation: 'portrait',
                      pageSize: 'LETTER',
                      customize: function(doc) {
                          doc.defaultStyle.fontSize = 11;
                          doc.styles.tableHeader.fontSize = 14;
                          doc.defaultStyle.alignment = 'center';
                      },
                      footer: true
                  }
              ]
        });
    }

    const socket = io('https://spidi.smartpoint.com.mx', {
        reconnection: true,
        reconnectionDelayMax: 1000
    });

    socket.on("connect", () => {
        console.log(socket.id); 
    });

    socket.on("pedidoCliente", (data) => {
        console.log(data);
        TablaPedidosPendientes();
    });

    socket.on("pedidoNegocio", (data) => {
        console.log(data);
        TablaPedidosPendientes();
    });

    /*socket.on("apartadoPedido", (data) => {
        //ID_Pedido, Repartidor
        var boton = $(".botonAceptarPedido"+data.ID_Pedido);
        var table = $('#TablaPedidosPendientes').DataTable();
        table.row( boton.parent().parent() ).remove().draw(false);
        var data = "metodo=consultar&accion=pedidos&idPedido="+data.ID_Pedido+"&tipo=ConsultarPendientesNuevo";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log(res);
            var arreglo = JSON.parse(res);
            //console.log(arreglo);
            table.row.add( {
                "Folio": arreglo.data[0]["Folio"],
                "Cliente": arreglo.data[0]["Cliente"],
                "Negocio": arreglo.data[0].Negocio,
                "Totales": arreglo.data[0]["Totales"],
                "Detalles": arreglo.data[0]["Detalles"],
                "Acciones": arreglo.data[0]["Acciones"]
            }).draw(); 
            moneda();
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });*/

    socket.on("pedidoRepartidor", (data) => {
        console.log(data);
        var table = $('#TablaPedidosEnCurso').DataTable();
        var fila = null;

        if (data.Estatus == "Completado") {
            /*var table = $('#TablaPedidosEnCurso').DataTable();
            table.row( boton.parent().parent() ).remove().draw(false);*/
            fila = table.row('#PEDIDOCURSO-'+data.ID_Pedido);
            table.row(fila).remove().draw(false);
        }else{

            fila = table.row('#PEDIDOCURSO-'+data.ID_Pedido);
            table.row(fila).remove().draw(false);
            var data = "metodo=consultar&accion=pedidos&idPedido="+data.ID_Pedido+"&tipo=ConsultarEnCursoNuevo";
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                //console.log(res);
                var arreglo = JSON.parse(res);
                //console.log(arreglo);
                table.row.add( {
                    "DT_RowId": arreglo.data[0]["DT_RowId"],
                    "Cliente": arreglo.data[0]["Cliente"],
                    "Negocio": arreglo.data[0].Negocio,
                    "Repartidor": arreglo.data[0]["Repartidor"],
                    "Totales": arreglo.data[0]["Totales"],
                    "Detalles": arreglo.data[0]["Detalles"]
                }).draw(); 
                moneda();
            })
            .fail(function() {
                console.log("Error ajax");
            });

            //TablaPedidosEnCurso(); 
            //TablaPedidosAceptados(); 
        }
    });
    //.emit('pedidoRepartidor', {ID_Pedido: id, Cliente: cliente, Negocio: negocio, Estatus: estatus});



    var map;

    function cargarMapa() {
        map = new google.maps.Map(document.getElementById('mapa'), {
            center: {lat: 20.70876190388258, lng: -102.3520653367936},
            scrollwheel: false,
            zoom: 15,
            zoomControl: true,
            rotateControl : false,
            mapTypeControl: true,
            streetViewControl: false,
        });

        //agregarMarcadores();
    }
    var imagenMarcador = null;
    function agregarMarcadores(arreglo){
        console.log(arreglo);
        if (arreglo != undefined) {
            for (var i = 0; i < arreglo.length; i++) {
                if (marcadores[arreglo[i].ID] != undefined){
                    /*for (var x = 0; x < marcadores.length; x++) {
                        marcadores[0].setMap(null);
                    }*/
                    marcadores[arreglo[i].ID].setMap(null);
                }

                imagenMarcador = {
                    //url: '../../server_SPIDI_APP/images/repartidores/'+arreglo[i].Foto,
                    url: 'vistas/assets/img/repartidor.png',
                    scaledSize : new google.maps.Size(50, 50),
                    borderRadius: 15
                };

                marcadores[arreglo[i].ID] = new google.maps.Marker({
                    position: {lat: parseFloat(arreglo[i].Latitud), lng: parseFloat(arreglo[i].Longitud)},
                    map: map,
                    icon: imagenMarcador,
                });

                marcadores[arreglo[i].ID].setMap(map);     
            }
        }
    }
