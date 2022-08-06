    
jQuery(document).ready(function() {
    $(document).on('click', '.DetallesPedidoAceptado', function(){
        var id = $(this).attr("attrid");
        $('#tbodyDetallesProductoA').html("");
        $("#folioOrdenA").html("#"+$(this).attr("folio"));
        $("#SubtotalPedidoA").html($(this).attr('totalpedido'));
        $("#CostoEnvioPedidoA").html($(this).attr('costoenvio'));
        $("#TotalPedidoA").html(parseFloat($(this).attr('totalpedido'))+parseFloat($(this).attr('costoenvio')));
        $("#MetodoPagoA").html($(this).attr('metodopago'));
        $("#FechaPedidoA").html($(this).attr('fechapedido'));
        $("#HoraPedidoA").html($(this).attr('horapedido'));
        $("#PedidoListoModal").attr("attrid", id);
        $("#PedidoListoModal").attr("idNegocio", $(this).attr('idNegocio'));
        $("#PedidoListoModal").attr("idCliente", $(this).attr('idCliente'));
        $("#PedidoListoModal").attr("folio", $(this).attr('folio'));
        $("#PedidoListoModal").attr("tiempo", $(this).attr('tiempo'));

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "pedidosAceptados",
                "tipo": "ConsultarOrden",
                "idPedido": id
            }
        })
        .done(function(res) {
            $('#tbodyDetallesProductoA').html(res);
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
                "accion": "pedidosAceptados",
                "tipo": "ConsultarInformacionPedidoCliente",
                "idPedido": id
            }
        })
        .done(function(res) {
            $('#InformacionPedidoCliente').html(res);
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
                "accion": "pedidosAceptados",
                "tipo": "ConsultarInformacionPedidoNegocio",
                "idPedido": id
            }
        })
        .done(function(res) {
            $('#InformacionPedidoNegocio').html(res);
            moneda();
        })
        .fail(function() {
            console.log("error");
        });
    });

    $(document).on('click', '.DetallesPedidoAceptado', function(){
        if ($(this).attr("estatusPedido") == "Esperando") {
            $("#PedidoListoModal").css("display", "none");
        }else{
            $("#PedidoListoModal").css("display", "inline");
        }
    });

    $(document).on('change', '#EstatusPedidoNegocio', function(){
        if ($(this).val() == "TiempoPedido") {
            $(".mostrarTiempoAprox").slideDown();
        }else{
            $(".mostrarTiempoAprox").slideUp();
        }
    });

    $(document).on('click', '.PedidoListo', function(){
        $("#ModalPedidoListo").modal("show");
        $("#BotonPedidoListo").attr("attrid", $(this).attr("attrid"));
        $("#BotonPedidoListo").attr("folio", $(this).attr("folio"));
        $("#BotonPedidoListo").attr("idNegocio", $(this).attr("idNegocio"));
        $("#BotonPedidoListo").attr("idCliente", $(this).attr("idCliente"));
        $("#spanOrdenPedidoListo").text("#"+$(this).attr("folio"));
        $("#EstatusPedidoNegocio").trigger("change");
    });

    $(document).on('click', '.PedidoListoBoton', function(){
        var idPedido = $(this).attr("attrid");
        var idNegocio = $(this).attr("idNegocio");
        var idCliente = $(this).attr("idCliente");
        var table = $('#TablaPedidosAceptados').DataTable();
        var fila = null;

        if ($("#EstatusPedidoNegocio").val() == "TiempoPedido" && $("#TiempoPedidoListo").val() == "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Tienes que ingresar el tiempo de preparación aproximado.'
            });
            return false;
        }else{
            Swal.fire({
              title: '¿La orden #'+$(this).attr("folio")+' está lista?',
              icon: 'warning',
              showCancelButton: true,
              cancelButtonColor: '#d33',
              cancelButtonText: 'No, Cancelar',
              showLoaderOnConfirm: true,
               confirmButtonText: 'Si, Aceptar',
              confirmButtonColor: '#3085d6',
            }).then((result) => {
                if (result.value) {
                    var estatusPedidoNegocio = $("#EstatusPedidoNegocio").val();
                    var tiempo = $("#TiempoPedidoListo").val();
                    $.ajax({
                        url: 'index.php',
                        type: 'POST',
                        data: {
                            "metodo": "modificar",
                            "accion": "pedidosAceptados",
                            "tipo": "PedidoListo",
                            "idNegocio": idNegocio,
                            "idPedido": idPedido,
                            "idCliente": idCliente,
                            "EstatusPedidoNegocio": estatusPedidoNegocio,
                            "Tiempo": tiempo,
                        }
                    })
                    .done(function(res) {
                        console.log(res);
                        var separar = res.split("~");
                        if (separar[0] == "Correcto") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Orden lista, esperando repartidor'
                            });
                            fila = table.row('#PEDIDOACEPTADO-'+idPedido);
                            table.row(fila).remove().draw(false);

                            $("#ModalVerDetallesAceptados").modal("hide");
                            $("#ModalPedidoListo").modal("hide");
                            $("#ModalPedidoListo").modal("hide");
                            $("#BotonPedidoListo").attr("attrid", "");
                            $("#BotonPedidoListo").attr("folio", "");
                            $("#BotonPedidoListo").attr("idNegocio", "");
                            $("#BotonPedidoListo").attr("idCliente", "");
                            $("#TiempoPedidoListo").val("");
                            socket.emit('pedidoNegocio', {ID_Pedido: idPedido, Cliente: idCliente, Negocio: idNegocio});
                            if (separar[1] != "") {
                                socket.emit('pedidoRepartidor', {ID_Pedido: idPedido, Cliente: idCliente, Negocio: idNegocio, Repartidor: separar[1], Estatus: 'Asignado'});
                                console.log("Enviado al repartidor");
                            }
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al poner pedido listo.'
                            });
                            console.log(res);
                        }
                        
                    })
                    .fail(function() {
                        console.log("error");
                    });
                }
            });
        }
    });

    $(document).on('click', '.AsignarPedidoRepartidorAceptado', function(){
        $("#ModalAsignarPedidoAceptados").modal("show");
        $("#folioOrdenAsignarAceptados").text("#"+$(this).attr("folio"));
        $("#AsignarPedidoModalAceptados").attr("attrid", $(this).attr("attrid"));
        $("#AsignarPedidoModalAceptados").attr("folio", $(this).attr("folio"));
        $("#AsignarPedidoModalAceptados").attr("idNegocio", $(this).attr("idNegocio"));
        $("#AsignarPedidoModalAceptados").attr("idCliente", $(this).attr("idCliente"));
    });

    $(document).on('click', '#AsignarPedidoModalAceptados', function(){
        if ($("#RepartidorAsignarAceptados").val() == "") {
            Swal.fire({
                icon: 'info',
                title: 'Seleccione un repartidor'
            });
            return false;
        }
        var idPedido = $(this).attr("attrid");
        var idNegocio = $(this).attr("idNegocio");
        var idCliente = $(this).attr("idCliente");
        
        var table = $('#TablaPedidosAceptados').DataTable();
        var fila = null;

        var idRepartidor = $("#RepartidorAsignarAceptados option:selected").val();
        Swal.fire({
          title: '¿La orden #'+$(this).attr("folio")+' se asignará al repartidor '+$("#RepartidorAsignarAceptados option:selected").text()+'?',
          icon: 'warning',
          showCancelButton: true,
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, Cancelar',
          showLoaderOnConfirm: true,
           confirmButtonText: 'Si, Aceptar',
          confirmButtonColor: '#3085d6',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: {
                        "metodo": "modificar",
                        "accion": "pedidosAceptados",
                        "tipo": "AsignarPedido",
                        "idRepartidor": idRepartidor,
                        "idPedido": idPedido,
                    }
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido asignado correctamente'
                        });
                        socket.emit('pedidoRepartidor', {ID_Pedido: idPedido, Cliente: idCliente, Negocio: idNegocio, Repartidor: idRepartidor, Estatus: 'Asignado'});
                        //ObtenerAceptados();
                        $("#ModalAsignarPedidoAceptados").modal("hide");

                        fila = table.row('#PEDIDOACEPTADO-'+idPedido);
                        table.row(fila).remove().draw(false);


                        var data = "metodo=consultar&accion=pedidosAceptados&idPedido="+idPedido+"&tipo=ConsultarAceptadosNuevo";
                        $.ajax({
                            url: 'index.php',
                            type: 'POST',
                            data: data
                        })
                        .done(function(res) {
                            //console.log(res);
                            var arreglo = JSON.parse(res);
                            console.log(arreglo);
                            table.row.add( {
                                "DT_RowId": arreglo.data[0]["DT_RowId"],
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

                    }else if ($.trim(res) == "Pedido Asignado") {
                        Swal.fire({
                            icon: 'info',
                            title: 'Este pedido ya está asignado'
                        });
                        //ObtenerAceptados();
                        $("#ModalAsignarPedidoAceptados").modal("hide");
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al asignar pedido.'
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
});
