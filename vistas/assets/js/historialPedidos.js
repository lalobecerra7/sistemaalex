    
jQuery(document).ready(function() {
    $(document).on('click', '.DetallesHistorialPedidos', function(){
        $("#ModalVerDetallesHistorialPedidos").modal("show");
        var id = $(this).attr("attrid");
        $("#folioOrdenH").html("#"+$(this).attr("folio"));
        $("#TotalPedidoH").html(parseFloat($(this).attr('totalpedido'))+parseFloat($(this).attr('costoenvio')));
        $("#MetodoPagoH").html($(this).attr('metodopago'));
        $("#FechaPedidoH").html($(this).attr('fechapedido'));
        $("#HoraPedidoH").html($(this).attr('horapedido'));
        $("#SubtotalPedidoH").html($(this).attr('totalpedido'));
        $("#CostoEnvioPedidoH").html($(this).attr('costoenvio'));
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "historialPedidos",
                "tipo": "ConsultarOrden",
                "idPedido": id
            }
        })
        .done(function(res) {
            $('#tbodyDetallesProductoH').html(res);
        })
        .fail(function() {
            console.log("error");
        });
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                "metodo": "consultar",
                "accion": "historialPedidos",
                "tipo": "ConsultarInformacionPedidoHistorial",
                "idPedido": id
            }
        })
        .done(function(res) {
            console.log(res);
            $('#InformacionPedidoH').html(res);
        })
        .fail(function() {
            console.log("error");
        });
        moneda();
    });
});
