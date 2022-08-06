    
jQuery(document).ready(function() {
    $(document).on('click', '.DetallesPedidoEnCurso', function(){
        $("#ModalVerDetallesPedidoEnCurso").modal("show");
        var id = $(this).attr("attrid");
        $('#tbodyDetallesProductoC').html("");
        $("#folioOrdenC").html("#"+$(this).attr("folio"));
        $("#TotalPedidoC").html(parseFloat($(this).attr('totalpedido'))+parseFloat($(this).attr('costoenvio')));
        $("#MetodoPagoC").html($(this).attr('metodopago'));
        $("#FechaPedidoC").html($(this).attr('fechapedido'));
        $("#HoraPedidoC").html($(this).attr('horapedido'));
        $("#SubtotalPedidoC").html($(this).attr('totalpedido'));
        $("#CostoEnvioPedidoC").html($(this).attr('costoenvio'));
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
            $('#tbodyDetallesProductoC').html(res);
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
                "tipo": "ConsultarInformacionPedidoEnCurso",
                "idPedido": id
            }
        })
        .done(function(res) {
            $('#InformacionPedidoC').html(res);
        })
        .fail(function() {
            console.log("error");
        });
        moneda();
    });

    $(document).on('click', '.AsignarPedidoRepartidor', function(){
        $("#ModalAsignarPedido").modal("show");
        $("#folioOrdenAsignar").text("#"+$(this).attr("folio"));
        $("#AsignarPedidoModal").attr("attrid", $(this).attr("attrid"));
        $("#AsignarPedidoModal").attr("folio", $(this).attr("folio"));
        $("#AsignarPedidoModal").attr("idNegocio", $(this).attr("idNegocio"));
        $("#AsignarPedidoModal").attr("idCliente", $(this).attr("idCliente"));
    });

    $(document).on('click', '#AsignarPedidoModal', function(){
        if ($("#RepartidorAsignar").val() == "") {
            Swal.fire({
                icon: 'info',
                title: 'Seleccione un repartidor'
            });
            return false;
        }
        var idPedido = $(this).attr("attrid");
        var idNegocio = $(this).attr("idNegocio");
        var idCliente = $(this).attr("idCliente");
        var table = $('#TablaPedidosEnCurso').DataTable();
        var fila = null;
        var idRepartidor = $("#RepartidorAsignar option:selected").val();
        Swal.fire({
          title: '¿La orden #'+$(this).attr("folio")+' se asignará al repartidor '+$("#RepartidorAsignar option:selected").text()+'?',
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
                        "accion": "pedidos",
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
                        $("#ModalAsignarPedido").modal("hide");
                        fila = table.row('#PEDIDOCURSO-'+idPedido);
                        table.row(fila).remove().draw(false);
                        var data = "metodo=consultar&accion=pedidos&idPedido="+idPedido+"&tipo=ConsultarEnCursoNuevo";
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

                    }else if ($.trim(res) == "Pedido Asignado") {
                        Swal.fire({
                            icon: 'info',
                            title: 'Este pedido ya está asignado'
                        });
                        //ObtenerAceptados();
                        $("#ModalApartarPedido").modal("hide");
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
