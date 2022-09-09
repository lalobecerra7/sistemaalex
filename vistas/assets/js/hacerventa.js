jQuery(document).ready(function($) {
   // TablaProductosVenta();

    ///////////////////////////////////////////////////////////////////////////
    ///// AUDIOS PARA CUANDO AGREGUEN O ELIMINEN UN PRODUCTO DE LA VENTA  /////
    ///////////////////////////////////////////////////////////////////////////
    var audio1 = new Audio('vistas/assets/sounds/addBip.mp3');
    var audio2 = new Audio('vistas/assets/sounds/notBip.mp3');

    ///////////////////////////////////////////////////////////////////////////
    ///// CONSULTAR LAS CAJAS Y SI ESTA ABIERTA MOSTRAR INTERFAZ DE VENTA /////
    ///////////////////////////////////////////////////////////////////////////

    $(document).on('click', '#cargarVenta', function() {
        $("#carga").show();

        var data = "metodo=detalles&accion=hacerventa&tipo=ConsultarCajas";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            
            var res = JSON.parse($.trim(res));

            var estado = "", usuario = "", cajas = "", tieneAbierta = false; 
            for (var i = 0; i < res.length; i++) {
                //console.log(res[i]);

                if(res[i].FK_Usuario == $("#bUsuario").attr("attrUsuario")){
                    if($("#vistaCaja").attr('attrCaja') != res[i].ID_Caja){
                        cajas = verCajaAbierta(res[i].ID_Caja, res[i].FK_Sucursal, res[i].ID_Detalle_Caja);
                    }else{
                        cajas =  $("#verCaja").html();
                        
                        setTimeout(function() {
                            $("#barCodeV").focus();
                        }, 200);
                    }
                    break;
                }else{
                    estado = '<span class="badge rounded-pill bg-danger">Cerrada</span><br><br><h6>Haz click para abrir la caja</h6>';
                    usuario = '';
                    if(res[i].Estado == 1){
                        var abrio = res[i].Abrio;
                        if(res[i].FK_Usuario_Abrir == $("#bUsuario").attr("attrUsuario")){
                            abrio = "Tú";
                        }

                        estado = '<span class="badge rounded-pill bg-success">Abierta</span>';
                        usuario = '<br><br><h6>No hay usuarios utilizando la caja</h6><h6><b>Usuario que abrio:</b> '+abrio+'</h6><h6><b>Fecha de apertura:</b> '+res[i].Fecha_Abrir+'</h6><br><br><h6>Haz click para usar la caja</h6>';
                        if(res[i].Usuario != '' && res[i].Usuario != null){
                            estado = '<br><span class="badge rounded-pill bg-success">Abierta</span>';
                            usuario = '<br><br><h6><b>En uso por:</b> '+res[i].Usuario+'</h6><h6><b>Fecha de apertura:</b> '+res[i].Fecha_Abrir+'</h6>';
                        }
                    }

                    cajas += `<div class="col-md-3 text-center caja" attrID="`+res[i].ID_Caja+`" attrEstatus="`+res[i].Estado+`" attrDetalle="`+res[i].ID_Detalle_Caja+`" attrSucursal="`+res[i].FK_Sucursal+`">
                        <i class="fas fa-cash-register" style="font-size: 80px;"></i>
                        <br><br>
                        <h5>`+res[i].Caja+`</h5>
                        `+estado+`
                        `+usuario+`
                    </div>`;
                }
            }
            $("#verCaja").html(cajas);

            $("#carga").hide(); 
            $("#caja").show(); 
            document.documentElement.requestFullscreen(); 

        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
    });

    $(document).on('click', '.caja', function() {
        $("#bAbrirCaja").attr('attrID', $(this).attr('attrID'));
        $("#bAbrirCaja").attr('attrEstatus', $(this).attr('attrEstatus'));
        $("#bAbrirCaja").attr('attrDetalle', $(this).attr('attrDetalle'));
        $("#bAbrirCaja").attr('attrSucursal', $(this).attr('attrSucursal'));

        if($(this).attr('attrEstatus') == "0"){
            $("#MAbrirCaja").modal('show');
        }else{
            $('#formAbrirCaja').trigger('submit');
        }
    });

    $('#formAbrirCaja').validate({
        rules: {
            montoAperCaja: {
                required: true
            }
        },
        messages: {
            montoAperCaja: {
                required: "El monto inicial de la caja es requerido"
            }
        },
        submitHandler: function(form) {
            $("#carga").show(); 

            var data = "metodo=detalles&accion=hacerventa&tipo=AbrirCaja&Caja="+$("#bAbrirCaja").attr('attrID')+"&Monto="+$("#montoAperCaja").val()+"&Estatus="+$("#bAbrirCaja").attr('attrEstatus')+"&Detalle="+$("#bAbrirCaja").attr('attrDetalle');
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                if($.trim(res) == "Correcto"){
                    $("#MAbrirCaja").modal('hide');
                    $("#verCaja").html(verCajaAbierta($("#bAbrirCaja").attr('attrID'), $("#bAbrirCaja").attr('attrSucursal'), $("#bAbrirCaja").attr('attrDetalle')));
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al abrir la caja.',
                        footer: '¿Por qué tengo este error? Contáctanos en smartpoint@gmail.com'
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

    $(document).on('click', '#bCerrarVenCaja', function() {
        $("#caja").hide();
        $("#carga").hide();
        document.exitFullscreen();
    });

    $(document).on('webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange', function() {
        if(document.webkitFullscreenElement == null){
            $("#caja").hide();
            swal.close(); 
            $("#MAbrirCaja").modal('hide');
            $("#MIntVarios").modal('hide');
            $("#MGranel").modal('hide');
            $("#MProdComun").modal('hide');
            $("#MBuscarProd").modal('hide');
            $("#ModalDescuentoProd").modal('hide');
            $("#ModalEntradaDinero").modal('hide');
            $("#ModalSalidaDinero").modal('hide');
            $("#ModalImpuestosVenta").modal('hide');
            //ir agregando las demas modales
        }
    });

    ////////////////////////////////////////////////////////////////////////////
    // CIERRA CONSULTAR LAS CAJAS Y SI ESTA ABIERTA MOSTRAR INTERFAZ DE VENTA //
    ////////////////////////////////////////////////////////////////////////////


    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if(evt.key === "F1"){
            $("#bVenta").trigger('click');   
        }else if($('#caja').is(':visible')){
            if($("#barCodeV").is(":focus")){
                var fila = $("#tablaCaja").children('tbody').children('tr.activa').index();
                
                if($("#tablaCaja").children('tbody').children('tr').length > 1){
                    if(evt.key === "ArrowUp"){
                        if((fila - 1) >= 0){
                            $("#tablaCaja").children('tbody').children('tr').removeClass('activa');
                            $("#tablaCaja").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                        }
                    }else if(evt.key === "ArrowDown"){
                        if($("#tablaCaja").children('tbody').children('tr:eq('+(fila + 1)+')').length > 0){
                            $("#tablaCaja").children('tbody').children('tr').removeClass('activa');
                            $("#tablaCaja").children('tbody').children('tr:eq('+(fila + 1)+')').addClass('activa');
                        }
                    }
                }

                if($("#tablaCaja").children('tbody').children('tr').length > 0){
                    var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
                    var descuento = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').children('span.dinero').text().replace('$', '').replace(',', ''));
                    var precio = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(2)').children('span.dinero').text().replace('$', '').replace(',', ''));
                    var descuHtml = '';
                    if(evt.key === "+"){
                        descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
                        if(descuento > 0){
                            descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad + 1))) * 100)+'</span>)';
                        }

                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(3)').children('span.cantidad').html(cantidad + 1);
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').html(descuHtml);
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(5)').children('span.dinero').html((precio * (cantidad + 1)) - descuento);
                        
                        totalCaja();
                    }else if(evt.key === "-"){
                        if((cantidad - 1) <= 0){
                            Swal.fire({
                                title: '¿Estás seguro que quieres quitar el producto?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                cancelButtonText: '¡No, cancelar!',
                                confirmButtonText: '¡Si, quitar!'
                            }).then((result) => {
                                if (result.value) {
                                    $("#tablaCaja").children('tbody').children('tr.activa').remove();

                                    if((fila - 1) >= 0){
                                        $("#tablaCaja").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                                    }else{
                                        $("#tablaCaja").children('tbody').children('tr:eq(0)').addClass('activa');
                                    }
                                    $("#barCodeV").focus();
                                    totalCaja();
                                }
                            });
                        }else{
                            var total = ((precio * (cantidad - 1)) - descuento);
                            
                            if (total < 0) {
                                $("#noNegativos").show();
                                setTimeout(function() {
                                    $("#noNegativos").hide();
                                }, 1000);
                                audio2.play();
                            }else{
                                 descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
                                if(descuento > 0){
                                    descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad - 1))) * 100)+'</span>)';
                                }

                                $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(3)').children('span.cantidad').html(cantidad - 1);
                                $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').html(descuHtml);
                                $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(5)').children('span.dinero').html((precio * (cantidad - 1)) - descuento);
                                
                                totalCaja();
                            }
                        }
                    }
                }

                if(evt.key === "Tab"){
                    event.preventDefault();
                    $("#barCodeV").focus();
                }
            }

            if(evt.key === "F2"){
                $("#MIntVarios").modal('show');
            }else if(event.altKey && evt.key === "c"){
                $("#MProdComun").modal('show');
            }else if(evt.key === "F10"){
                $("#MBuscarProd").modal('show');
            }else if(event.altKey && evt.key === "q"){
                $("#bPrecioMayoreo").trigger("click");
            }else if(evt.key === "F7"){
                $("#bEntradaDinero").trigger("click");
            }else if(evt.key === "F8"){
                $("#bSalidaDinero").trigger("click");
            }else if(event.altKey && evt.key === "d"){
                $("#bDescuentoProd").trigger("click");
            }else if(evt.key === "Delete"){
                if ($("#tablaCaja").children('tbody').children('tr').length > 0) {
                    Swal.fire({
                        title: '¿Estás seguro que quieres quitar el producto?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: '¡No, cancelar!',
                        confirmButtonText: '¡Si, quitar!'
                    }).then((result) => {
                        if (result.value) {
                            var fila = $("#tablaCaja").children('tbody').children('tr.activa').index();
                            $("#tablaCaja").children('tbody').children('tr.activa').remove();

                            if((fila - 1) >= 0){
                                $("#tablaCaja").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                            }else{
                                $("#tablaCaja").children('tbody').children('tr:eq(0)').addClass('activa');
                            }
                            $("#barCodeV").focus();
                            totalCaja();
                        }
                    });
                }
            }else if(evt.key === "F3"){
                //console.log("Cambiar");
            }else if(evt.key === "F6"){
                //console.log("Pendiente");
            }else if(event.altKey && evt.key === "e"){
                //console.log("Eliminar");
            }else if(event.altKey && evt.key === "a"){
                //console.log("Asignar");
            }else if(evt.key === "F12"){
                //console.log("Cobrar");
            }else if(event.altKey && evt.key === "u"){
                //console.log("Ultimo ticket");
            }else if(event.altKey && evt.key === "v"){
                //console.log("Ventas y Devoluciones");
            }
        }
    };

    ////////////////////////////////////////
    ///// AGREGAR PRODUCTOS A LA TABLA /////
    ////////////////////////////////////////

    $(document).on('submit', '#formAgreProd', function(event) {
        event.preventDefault();
        
        $("#carga").show(); 

        var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+$("#barCodeV").val()+"&sucursal="+$("#vistaCaja").attr('attrSucursal');
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            //console.log(res);
            $("#barCodeV").val("");

            if($.trim(res) == "No encontrado"){
                $("#noEncontrado").show();
                
                setTimeout(function() {
                    $("#noEncontrado").hide();
                }, 1000);

                audio2.play();
            }else{       
                var datos = JSON.parse($.trim(res));
                var precio = datos.Precio_General;
                if(datos.Precio != null){
                    precio = datos.Precio;
                }

                var precioMayoreo = datos.Precio_Mayoreo_General;
                if(datos.Precio_Mayoreo != null){
                    precioMayoreo = datos.Precio_Mayoreo;
                }
              
                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                if(datos.Clase == "Granel"){
                    $("#datosGranel").attr('attrID', datos.ID_Producto);
                    $("#datosGranel").attr('attrCodigo', datos.Codigo);
                    $("#datosGranel").attr('attrExistencia', existencia);
                    $("#datosGranel").attr('precioMayoreo', precioMayoreo);
                    $("#datosGranel").attr('precio', precio);
                    $("#datosGranel").html('<h4 class="text-center">'+datos.Descripcion+'</h4><h5 class="text-center"><b>Precio Unitario:</b> <span class="dinero">'+precio+'</span></h5>');
                    $("#importeGranel").val(precio);
                    $("#MGranel").modal('show');

                    moneda();
                }else{
                    $("#tablaCaja").children('tbody').children('tr').removeClass('activa');

                    if($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').length > 0){
                        var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
                        var descuento = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(4)').children('span.dinero').text().replace('$', '').replace(',', ''));
                        var descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
                        if ($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').hasClass("mayoreo")) {
                            var precio = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').attr("preciomayoreo"));
                            if(descuento > 0){
                                descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad))) * 100)+'</span>)';
                            }
                        }else{
                            var precio = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').attr("precio").replace('$', '').replace(',', ''));   
                            if(descuento > 0){
                                descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad))) * 100)+'</span>)';
                            }
                        }

                        $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').addClass('activa');

                        $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').html(`
                            <td>`+datos.Codigo+`</td>
                            <td>`+datos.Descripcion+`</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">`+(cantidad + 1)+`</span></td>
                            <td>`+descuHtml+`</td>
                            <td><span class="dinero">`+((precio * (cantidad + 1)) - descuento)+`</span></td>
                            <td><span class="cantidad">`+existencia+`</span></td>
                        `);
                    }else{
                        $("#tablaCaja").children('tbody').prepend(`<tr attrID="`+datos.ID_Producto+`" precio="`+precio+`"  precioMayoreo="`+precioMayoreo+`" class="activa normal">
                            <td>`+datos.Codigo+`</td>
                            <td>`+datos.Descripcion+`</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">1</span></td>
                            <td><span class="dinero">0</span>(<span class="porcentaje">0</span>)</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">`+existencia+`</span></td>
                        </tr>`);
                    }

                    audio1.play();

                    totalCaja();  
                }
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
    });

    $(document).on('submit', '#AgregarProdTabla', function(event) {
        event.preventDefault();
        
        $("#carga").show(); 

        var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+$(".buscadorMyDataTable[tabla='TablaProductosVenta']").val()+"&sucursal="+$("#vistaCaja").attr('attrSucursal');
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            //console.log(res);
            $("#barCodeV").val("");

            if($.trim(res) == "No encontrado"){
                $("#noEncontrado").show();
                
                setTimeout(function() {
                    $("#noEncontrado").hide();
                }, 1000);

                audio2.play();
            }else{       
                var datos = JSON.parse($.trim(res));
                var precio = datos.Precio_General;
                if(datos.Precio != null){
                    precio = datos.Precio;
                }

                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                var precioMayoreo = datos.Precio_Mayoreo_General;
                if(datos.Precio_Mayoreo != null){
                    precioMayoreo = datos.Precio_Mayoreo;
                }

                if(datos.Clase == "Granel"){
                    $("#datosGranel").attr('attrID', datos.ID_Producto);
                    $("#datosGranel").attr('attrCodigo', datos.Codigo);
                    $("#datosGranel").attr('attrExistencia', existencia);
                    $("#datosGranel").attr('precioMayoreo', precioMayoreo);
                    $("#datosGranel").attr('precio', precio);
                    $("#datosGranel").html('<h4 class="text-center">'+datos.Descripcion+'</h4><h5 class="text-center"><b>Precio Unitario:</b> <span class="dinero">'+precio+'</span></h5>');
                    $("#importeGranel").val(precio);
                    $("#MGranel").modal('show');

                    moneda();
                }else{
                    $("#tablaCaja").children('tbody').children('tr').removeClass('activa');

                    if($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').length > 0){
                        var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
                        var descuento = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(4)').children('span.dinero').text().replace('$', '').replace(',', ''));

                        var descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
                        if(descuento > 0){
                            descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad + 1))) * 100)+'</span>)';
                        }

                        $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').addClass('activa');

                        $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').html(`
                            <td>`+datos.Codigo+`</td>
                            <td>`+datos.Descripcion+`</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">`+(cantidad + 1)+`</span></td>
                            <td>`+descuHtml+`</td>
                            <td><span class="dinero">`+((precio * (cantidad + 1)) - descuento)+`</span></td>
                            <td><span class="cantidad">`+existencia+`</span></td>
                        `);
                    }else{
                        $("#tablaCaja").children('tbody').prepend(`<tr attrID="`+datos.ID_Producto+`" precio="`+precio+`" precioMayoreo="`+precioMayoreo+`" class="activa normal">
                            <td>`+datos.Codigo+`</td>
                            <td>`+datos.Descripcion+`</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">1</span></td>
                            <td><span class="dinero">0</span>(<span class="porcentaje">0</span>)</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">`+existencia+`</span></td>
                        </tr>`);
                    }
                    audio1.play();
                    totalCaja();  
                }
                $("#MBuscarProd").modal("hide");
            }
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
    });

    //AGREGAR PRODUCTO AL HACERLE CLICK
    $(document).on('click', '#TablaProductosVenta tbody tr', function(event) {
        event.preventDefault();
        
        $("#carga").show(); 
        var codigo = $(this).children("td:eq(0)").text();

        var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+codigo+"&sucursal="+$("#vistaCaja").attr('attrSucursal');
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data,
            beforeSend: function() {
                $("#carga").show();
            }
        })
        .done(function(res) {
            //console.log(res);
            $("#barCodeV").val("");

            if($.trim(res) == "No encontrado"){
                $("#noEncontrado").show();
                
                setTimeout(function() {
                    $("#noEncontrado").hide();
                }, 1000);

                audio2.play();
            }else{       
                var datos = JSON.parse($.trim(res));
                var precio = datos.Precio_General;
                if(datos.Precio != null){
                    precio = datos.Precio;
                }

                var existencia = 0;
                if(datos.Existencia != null){
                    existencia = datos.Existencia;
                }

                var precioMayoreo = datos.Precio_Mayoreo_General;
                if(datos.Precio_Mayoreo != null){
                    precioMayoreo = datos.Precio_Mayoreo;
                }

                if(datos.Clase == "Granel"){
                    $("#datosGranel").attr('attrID', datos.ID_Producto);
                    $("#datosGranel").attr('attrCodigo', datos.Codigo);
                    $("#datosGranel").attr('attrExistencia', existencia);
                    $("#datosGranel").attr('precioMayoreo', precioMayoreo);
                    $("#datosGranel").attr('precio', precio);
                    $("#datosGranel").html('<h4 class="text-center">'+datos.Descripcion+'</h4><h5 class="text-center"><b>Precio Unitario:</b> <span class="dinero">'+precio+'</span></h5>');
                    $("#importeGranel").val(precio);
                    $("#MGranel").modal('show');

                    moneda();
                }else{
                    $("#tablaCaja").children('tbody').children('tr').removeClass('activa');
                    if($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').length > 0){
                        var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
                        var descuento = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(4)').children('span.dinero').text().replace('$', '').replace(',', ''));
                        var descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';

                        if ($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').hasClass("mayoreo")) {
                            var precio = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').attr("preciomayoreo"));
                            if(descuento > 0){
                                descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad))) * 100)+'</span>)';
                            }
                        }else{
                            var precio = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').attr("precio").replace('$', '').replace(',', ''));   
                            if(descuento > 0){
                                descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad))) * 100)+'</span>)';
                            }
                        }

                        $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').addClass('activa');

                        $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').html(`
                            <td>`+datos.Codigo+`</td>
                            <td>`+datos.Descripcion+`</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">`+(cantidad + 1)+`</span></td>
                            <td>`+descuHtml+`</td>
                            <td><span class="dinero">`+((precio * (cantidad + 1)) - descuento)+`</span></td>
                            <td><span class="cantidad">`+existencia+`</span></td>
                        `);
                    }else{
                        $("#tablaCaja").children('tbody').prepend(`<tr attrID="`+datos.ID_Producto+`" precio="`+precio+`" precioMayoreo="`+precioMayoreo+`" class="activa normal">
                            <td>`+datos.Codigo+`</td>
                            <td>`+datos.Descripcion+`</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">1</span></td>
                            <td><span class="dinero">0</span>(<span class="porcentaje">0</span>)</td>
                            <td><span class="dinero">`+precio+`</span></td>
                            <td><span class="cantidad">`+existencia+`</span></td>
                        </tr>`);
                    }

                    audio1.play();

                    totalCaja();  
                }
            }
            $("#MBuscarProd").modal("hide");
        })
        .fail(function() {
            console.log("Error ajax");
        })
        .always(function() {
            $("#carga").hide();
        }); 
    });

    function totalCaja() {
        var suma = 0, contador = 0;
        $("#tablaCaja").children('tbody').children('tr').each(function(index, el) {
            suma += parseFloat($(this).children('td:eq(5)').children('span.dinero').text().replace('$', '').replace(',', ''));
            contador ++;
        });

        $("#totalCaja").html(suma);
        $("#cantidadCajaProd").html(contador);

        moneda();
    }

    $(document).on('shown.bs.modal', '#MIntVarios', function(){
        $(this).find('#barCodeIntVatios').focus();
    });

    $(document).on('hidden.bs.modal', '#MIntVarios',function(){
        $("#barCodeIntVatios").val("");
        $("#cantidadIntVatios").val("");
        var validator = $("#formIntVarios").validate();
        validator.resetForm();

        if($(".swal2-container").is(':visible')){
            $("button.swal2-confirm").focus();
        }else{
            $("#barCodeV").focus();
        }
    });

    $(document).on('click', '#bIntVarios', function() {
        $("#MIntVarios").modal('show');
    });

    $(document).on('click', '#bProdComun', function() {
        $("#MProdComun").modal('show');
    });

    $(document).on('click', '#bBuscarProd', function() {
        $("#MBuscarProd").modal('show');
    });

    $(document).on('keypress', '#barCodeV', function(event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = event.key;
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }    
    });

    $(document).on('click', function(event) {
        if($('#caja').is(':visible')){ 
            //agergar las demas modales
            //$(".swal2-container").is(':visible') == false && 
            if($("#MBuscarProd").is(':visible')){
               $(".buscadorMyDataTable[tabla='TablaProductosVenta']").focus();
            }else if($("#MIntVarios").is(':visible') == false && $("#MGranel").is(':visible') == false && $("#MProdComun").is(':visible') == false && $("#MBuscarProd").is(':visible') == false && $("#ModalDescuentoProd").is(':visible') == false && $("#ModalEntradaDinero").is(':visible') == false  && $("#ModalSalidaDinero").is(':visible') == false && $("#ModalImpuestosVenta").is(':visible') == false){
                $("#barCodeV").focus();
            }
        }
    });

    $(document).on('shown.bs.modal', '#MGranel', function(){
        $(this).find('#cantidadGranel').focus();
    });

    $(document).on('hidden.bs.modal', '#MGranel', function(){
        $("#cantidadGranel").val("1.00");
        $("#importeGranel").val("");
        var validator = $("#formGranel").validate();
        validator.resetForm();

        if($(".swal2-container").is(':visible')){
            $("button.swal2-confirm").focus();
        }else{
            $("#barCodeV").focus();
        }
    });

    $(document).on('shown.bs.modal', '#MBuscarProd', function(){
        $(this).find(".buscadorMyDataTable[tabla='TablaProductosVenta']").focus();
        TablaProductosVenta();
    });

    $(document).on('hidden.bs.modal', '#MBuscarProd', function(){
        $(this).find(".buscadorMyDataTable[tabla='TablaProductosVenta']").val('');
        if($(".swal2-container").is(':visible')){
            $("button.swal2-confirm").focus();
        }else{
            $("#barCodeV").focus();
        }
    });

    $(document).on('keyup change', '#cantidadGranel', function(event) {
        $("#importeGranel").val(parseFloat($(this).val()) * parseFloat($("#datosGranel").children('h5').children('span.dinero').text().replace('$', '').replace(',', '')));
    }); 

    $(document).on('keyup change', '#importeGranel', function(event) {
        $("#cantidadGranel").val(Math.round((parseFloat($(this).val()) / parseFloat($("#datosGranel").children('h5').children('span.dinero').text().replace('$', '').replace(',', ''))) * 100) / 100);
    }); 

    $('#formGranel').validate({
        rules: {
            cantidadGranel: {
                required: true,
                min: 0.01
            },
            importeGranel: {
                required: true
            }
        },
        messages: {
            cantidadGranel: {
                required: "La cantidad es requerida",
                min: "La cantidad debe ser al menos 0.01"
            },
            importeGranel: {
                required: "El importe es requerido"
            }
        },
        submitHandler: function(form) {
            $("#carga").show(); 

            var cantidadInput = parseFloat($("#cantidadGranel").val());
            var precio = parseFloat($("#datosGranel").children('h5').children('span.dinero').text().replace('$', '').replace(',', ''));
            $("#tablaCaja").children('tbody').children('tr').removeClass('activa');

            if($("#tablaCaja").children('tbody').children('tr[attrID='+$("#datosGranel").attr('attrID')+']').length > 0){
                var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+$("#datosGranel").attr('attrID')+']').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
                var descuento = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+$("#datosGranel").attr('attrID')+']').children('td:eq(4)').children('span.dinero').text().replace('$', '').replace(',', ''));

                var descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
                if(descuento > 0){
                    descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad + cantidadInput))) * 100)+'</span>)';
                }

                $("#tablaCaja").children('tbody').children('tr[attrID='+$("#datosGranel").attr('attrID')+']').addClass('activa');

                $("#tablaCaja").children('tbody').children('tr[attrID='+$("#datosGranel").attr('attrID')+']').html(`
                    <td>`+$("#datosGranel").attr('attrCodigo')+`</td>
                    <td>`+$("#datosGranel").children('h4').text()+`</td>
                    <td><span class="dinero">`+precio+`</span></td>
                    <td><span class="cantidad">`+(cantidad + cantidadInput)+`</span></td>
                    <td>`+descuHtml+`</td>
                    <td><span class="dinero">`+((precio * (cantidad + cantidadInput)) - descuento)+`</span></td>
                    <td><span class="cantidad">`+$("#datosGranel").attr('attrExistencia')+`</span></td>
                `);
            }else{
                $("#tablaCaja").children('tbody').prepend(`<tr attrID="`+$("#datosGranel").attr('attrID')+`"  precio="`+precio+`" precioMayoreo="`+$("#datosGranel").attr('precioMayoreo')+`" class="activa normal">
                    <td>`+$("#datosGranel").attr('attrCodigo')+`</td>
                    <td>`+$("#datosGranel").children('h4').text()+`</td>
                    <td><span class="dinero">`+precio+`</span></td>
                    <td><span class="cantidad">`+cantidadInput+`</span></td>
                    <td><span class="dinero">0</span>(<span class="porcentaje">0</span>)</td>
                    <td><span class="dinero">`+(precio * cantidadInput)+`</span></td>
                    <td><span class="cantidad">`+$("#datosGranel").attr('attrExistencia')+`</span></td>
                </tr>`);
            }

            audio1.play();

            totalCaja();
            $("#MGranel").modal('hide');
            $("#carga").hide();   
        }   
    });

    $(document).on('keypress', '#cantidadGranel', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#importeGranel").focus();
            break;
        }
    });

    $(document).on('keypress', '#importeGranel', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#bAgregarGranel").focus();
            break;
        }
    });

    $(document).on('click', '#bAgregarGranel', function() {
        $('#formGranel').trigger('submit');
    });

    $('#formIntVarios').validate({
        rules: {
            barCodeIntVatios: {
                required: true
            },
            cantidadIntVatios: {
                required: true,
                min: 0.01
            }
        },
        messages: {
            barCodeIntVatios: {
                required: "El código del producto es requerido"
            },
            cantidadIntVatios: {
                required: "La cantidad es requerida",
                min: "La cantidad debe ser al menos 0.01"
            }
        },
        submitHandler: function(form) {
            $("#carga").show(); 

            var data = "metodo=detalles&accion=hacerventa&tipo=AgregarProducto&codigo="+$("#barCodeIntVatios").val()+"&sucursal="+$("#vistaCaja").attr('attrSucursal');
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                if($.trim(res) == "No encontrado"){
                    $("#noEncontrado").show();
                    
                    setTimeout(function() {
                        $("#noEncontrado").hide();
                    }, 1000);

                    audio2.play();
                }else{       
                    var datos = JSON.parse($.trim(res));
                    console.log(datos);

                    var cantidadInput = parseFloat($("#cantidadIntVatios").val());
                    if(datos.Clase == "Pieza" && (cantidadInput % 1) > 0){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'El producto solo puede ser vendido en unidades o enteros, si deseas puedes configurarlo para venderlo en granel.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        });
                    }else{
                        $("#tablaCaja").children('tbody').children('tr').removeClass('activa');

                        var existencia = 0;
                        if(datos.Existencia != null){
                            existencia = datos.Existencia;
                        }

                        var precio = datos.Precio_General;
                        if(datos.Precio != null){
                            precio = datos.Precio;
                        }

                        var preciomayoreo = datos.Precio_Mayoreo_General;
                        if(datos.Precio_Mayoreo != null){
                            preciomayoreo = datos.Precio_Mayoreo;
                        }

                        if($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').length > 0){
                            var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
                            var descuento = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').children('td:eq(4)').children('span.dinero').text().replace('$', '').replace(',', ''));

                            var descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
                            if(descuento > 0){
                                descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad + cantidadInput))) * 100)+'</span>)';
                            }

                            $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').addClass('activa');

                            if ($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').hasClass("mayoreo")) {
                                precio = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').attr("preciomayoreo"));
                            }else{
                                precio = parseFloat($("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').attr("precio").replace('$', '').replace(',', ''));   
                            }



                            $("#tablaCaja").children('tbody').children('tr[attrID='+datos.ID_Producto+']').html(`
                                <td>`+datos.Codigo+`</td>
                                <td>`+datos.Descripcion+`</td>
                                <td><span class="dinero">`+precio+`</span></td>
                                <td><span class="cantidad">`+(cantidad + cantidadInput)+`</span></td>
                                <td>`+descuHtml+`</td>
                                <td><span class="dinero">`+((precio * (cantidad + cantidadInput)) - descuento)+`</span></td>
                                <td><span class="cantidad">`+existencia+`</span></td>
                            `);
                        }else{
                            $("#tablaCaja").children('tbody').prepend(`<tr attrID="`+datos.ID_Producto+`" precio="`+precio+`" precioMayoreo="`+preciomayoreo+`" class="activa normal">
                                <td>`+datos.Codigo+`</td>
                                <td>`+datos.Descripcion+`</td>
                                <td><span class="dinero">`+precio+`</span></td>
                                <td><span class="cantidad">`+cantidadInput+`</span></td>
                                <td><span class="dinero">0</span>(<span class="porcentaje">0</span>)</td>
                                <td><span class="dinero">`+(precio * cantidadInput)+`</span></td>
                                <td><span class="cantidad">`+existencia+`</span></td>
                            </tr>`);
                        }

                        audio1.play();

                        totalCaja();
                    }
                }

                $("#MIntVarios").modal('hide');
            })
            .fail(function() {
                console.log("Error ajax");
            })
            .always(function() {
                $("#carga").hide();
            }); 
        }   
    });

    $(document).on('keypress', '#barCodeIntVatios', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#cantidadIntVatios").focus();
            break;
        }
    });

    $(document).on('click', '#bDescuentoProd', function() {
        if ($("#tablaCaja tbody tr").length > 0) {
            $("#ModalDescuentoProd").modal('show');
        }else{
            $("#barCodeV").focus();
        }  
    });

    $(document).on('shown.bs.modal', '#ModalDescuentoProd', function(){
        $(this).find('#CantidadDescuento').focus();
        $("#CantidadDescuento").val("");
        $("#PorcentajeDescuento").val("");
    });

    $(document).on('hidden.bs.modal', '#ModalDescuentoProd', function(){
        $("#barCodeV").focus();
    });

    $(document).on('click', '#bAgregarDescuento', function(event) {
        $("#formDescuentoProd").trigger("submit");
    });

    $(document).on('keypress', '#CantidadDescuento', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#bAgregarDescuento").focus();
            break;
        }
    });

    $(document).on('keypress', '#PorcentajeDescuento', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#bAgregarDescuento").focus();
            break;
        }
    });

    $(document).on('change keyup', '#CantidadDescuento', function() {
        $("#PorcentajeDescuento").val(0);
    });

    $(document).on('change keyup', '#PorcentajeDescuento', function() {
        $("#CantidadDescuento").val(0);
    });

    $('#formDescuentoProd').validate({
        rules: {
            CantidadDescuento: {
                required: true,
                min: 0
            },
            PorcentajeDescuento: {
                required: true,
                min: 0
            },
        },
        messages: {
            CantidadDescuento: {
                required: "La cantidad de descuento es obligatoria"
            },
            PorcentajeDescuento: {
                required: "El porcentaje de descuento es obligatorio",
            },
        },
        submitHandler: function(form) {
            //$("#carga").show();
            var descuento = 0; 
            var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
            var precio = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(2)').children('span.dinero').text().replace('$', '').replace(',', ''));
            if ($("#CantidadDescuento").val() > 0 && $("#CantidadDescuento").val() != "") {
                descuento = $("#CantidadDescuento").val(); 
            }else if($("#PorcentajeDescuento").val() > 0 && $("#PorcentajeDescuento").val() != ""){
                descuento = Math.round((parseFloat($("#PorcentajeDescuento").val()) * (cantidad * precio)) / 100);
            }
            var total = ((precio * (cantidad)) - descuento);
            if (total < 0) {
                $("#noNegativos").show();
                setTimeout(function() {
                    $("#noNegativos").hide();
                }, 1000);
                $("#ModalDescuentoProd").modal('hide');
                audio2.play();
            }else{
                var descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
            
                if(descuento > 0){
                    descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad))) * 100)+'</span>)';
                }

                $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(2)').children('span.dinero').html(precio);
                $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').html(descuHtml);
                $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(5)').children('span.dinero').html((precio * (cantidad)) - descuento);
                
                audio1.play();
                $("#ModalDescuentoProd").modal('hide');
                totalCaja();
                moneda();
            }
        }   
    });

    $(document).on('keypress', '#CantidadEntrada', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#MotivoEntrada").focus();
            break;
        }
    });

    $(document).on('keypress', '#MotivoEntrada', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#bAgregarEntrada").focus();
            break;
        }
    });

    $(document).on('click', '#bAgregarEntrada', function() {
        $('#formEntradaDinero').trigger("submit");
    });

    $(document).on('click', '#bEntradaDinero', function() {
        $("#ModalEntradaDinero").modal('show');
        $("#CantidadEntrada").val("");
        $("#MotivoEntrada").val("Entrada de dinero");
    });
    // console.log("detalle: "+$("#vistaCaja").attr("attrDetalle"));

    $('#formEntradaDinero').validate({
        rules: {
            CantidadEntrada: {
                required: true,
                min: 0.01
            },
            MotivoEntrada: {
                required: true,
            },
        },
        messages: {
            CantidadEntrada: {
                required: "La cantidad de entrada es obligatoria",
                min: "El valor debe de ser mayor o igual a 0.01"
            },
            MotivoEntrada: {
                required: "El motivo de la entrada es obligatoria"
            },
        },
        submitHandler: function(form) {
            var data = "metodo=detalles&accion=hacerventa&tipo=GuardarEntradaDinero&DetalleCaja="+$("#vistaCaja").attr('attrDetalle')+"&Cantidad="+$("#CantidadEntrada").val()+"&Motivo="+$("#MotivoEntrada").val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                if($.trim(res) == "Correcto"){
                    Swal.fire({
                        icon: 'success',
                        title: 'Entrada guardada correctamente',
                        timer: 700
                    });
                    $("#ModalEntradaDinero").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al guardar la entrada.',
                        footer: '¿Por qué tengo este error? Contáctanos en smartpoint@gmail.com'
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

    $(document).on('shown.bs.modal', '#ModalEntradaDinero', function(){
        $(this).find('#CantidadEntrada').focus();
        $("#CantidadEntrada").val("");
        TablaEntradas(); 
    });

    $(document).on('click', '#CargarEntradasRecientes', function(){
        if ($(".MostrarTablaEntradas").hasClass("oculto")) {
            $(".MostrarTablaEntradas").removeClass("oculto")
        }else{
            $(".MostrarTablaEntradas").addClass("oculto")
        }
    });


    $(document).on('hidden.bs.modal', '#ModalEntradaDinero', function(){
        $("#barCodeV").focus();
    });

    $(document).on('keypress', '#CantidadSalida', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#MotivoSalida").focus();
            break;
        }
    });

    $(document).on('keypress', '#MotivoSalida', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#bAgregarSalida").focus();
            break;
        }
    });

    $(document).on('click', '#bSalidaDinero', function() {
        $("#CantidadSalida").val("");
        $("#MotivoSalida").val("Salida de dinero");
        $("#ModalSalidaDinero").modal('show');
    });

    $(document).on('click', '#bAgregarSalida', function() {
        $('#formSalidaDinero').trigger("submit");
    });

    $('#formSalidaDinero').validate({
        rules: {
            CantidadSalida: {
                required: true,
                min: 0.01
            },
            MotivoSalida: {
                required: true,
            },
        },
        messages: {
            CantidadSalida: {
                required: "La cantidad de salida es obligatoria",
                min: "El valor debe de ser mayor o igual a 0.01"
            },
            MotivoSalida: {
                required: "El motivo de la salida es obligatoria"
            },
        },
        submitHandler: function(form) {
            var data = "metodo=detalles&accion=hacerventa&tipo=GuardarSalidaDinero&DetalleCaja="+$("#vistaCaja").attr('attrDetalle')+"&Cantidad="+$("#CantidadSalida").val()+"&Motivo="+$("#MotivoSalida").val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                if($.trim(res) == "Correcto"){
                    Swal.fire({
                        icon: 'success',
                        title: 'Salida guardada correctamente',
                        timer: 700
                    });
                    $("#ModalSalidaDinero").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al guardar la salida.',
                        footer: '¿Por qué tengo este error? Contáctanos en smartpoint@gmail.com'
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

    $(document).on('shown.bs.modal', '#ModalSalidaDinero', function(){
        $(this).find('#CantidadSalida').focus();
        $("#CantidadSalida").val("");
        TablaSalidas();
    });

    $(document).on('click', '#CargarSalidasRecientes', function(){
        if ($(".MostrarTablaSalidas").hasClass("oculto")) {
            $(".MostrarTablaSalidas").removeClass("oculto")
        }else{
            $(".MostrarTablaSalidas").addClass("oculto")
        }
    });

    $(document).on('hidden.bs.modal', '#ModalSalidaDinero', function(){
        $("#barCodeV").focus();
    });

    $(document).on('click', '#bEliminarProducto', function() {
        if ($("#tablaCaja").children('tbody').children('tr').length > 0) {
            Swal.fire({
                title: '¿Estás seguro que quieres quitar el producto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '¡No, cancelar!',
                confirmButtonText: '¡Si, quitar!'
            }).then((result) => {
                if (result.value) {
                    var fila = $("#tablaCaja").children('tbody').children('tr.activa').index();
                    $("#tablaCaja").children('tbody').children('tr.activa').remove();

                    if((fila - 1) >= 0){
                        $("#tablaCaja").children('tbody').children('tr:eq('+(fila - 1)+')').addClass('activa');
                    }else{
                        $("#tablaCaja").children('tbody').children('tr:eq(0)').addClass('activa');
                    }
                    totalCaja();
                    $("#barCodeV").focus();
                }
            });
        }
    });

    $(document).on('keypress', '#cantidadIntVatios', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#bAgregarVarios").focus();
            break;
        }
    });

    $(document).on('click', '#bAgregarVarios', function() {
        $('#formIntVarios').trigger('submit');
    });

    $('#formProdComun').validate({
        rules: {
            descripcionProdComun: {
                required: true
            },
            cantidadProdComun: {
                required: true,
                min: 0.01
            },
            precioProdComun: {
                required: true,
                min: 0.01
            }
        },
        messages: {
            descripcionProdComun: {
                required: "La descripción del producto es requerida"
            },
            cantidadProdComun: {
                required: "La cantidad es requerida",
                min: "La cantidad debe ser al menos 0.01"
            },
            precioProdComun: {
                required: "El precio es requerido",
                min: "El precio debe ser al menos 0.01"
            }
        },
        submitHandler: function(form) {
            $("#carga").show(); 

            $("#tablaCaja").children('tbody').children('tr').removeClass('activa');

            $("#tablaCaja").children('tbody').prepend(`<tr attrID="0" precio="0" precioMayoreo="0" class="activa">
                <td>0</td>
                <td>`+$.trim($("#descripcionProdComun").val())+`</td>
                <td><span class="dinero">`+$("#precioProdComun").val()+`</span></td>
                <td><span class="cantidad">`+$("#cantidadProdComun").val()+`</span></td>
                <td><span class="dinero">0</span>(<span class="porcentaje">0</span>)</td>
                <td><span class="dinero">`+(parseFloat($("#precioProdComun").val()) * parseFloat($("#cantidadProdComun").val()))+`</span></td>
                <td>Ilim</td>
            </tr>`);
            

            audio1.play();

            totalCaja();
            $("#MProdComun").modal('hide');
            $("#carga").hide();   
        }   
    });
    
    $(document).on('shown.bs.modal', '#MProdComun', function(){
        $(this).find('#descripcionProdComun').focus();
    });

    $(document).on('hidden.bs.modal', '#MProdComun', function(){
        $("#descripcionProdComun").val("");
        $("#cantidadProdComun").val("1.00");
        $("#precioProdComun").val("");
        $("#totalProdComun").html("0");
        moneda();

        var validator = $("#formProdComun").validate();
        validator.resetForm();

        if($(".swal2-container").is(':visible')){
            $("button.swal2-confirm").focus();
        }else{
            $("#barCodeV").focus();
        }
    });

    $(document).on('keypress', '#descripcionProdComun', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#cantidadProdComun").focus();
            break;
        }
    });

    $(document).on('keypress', '#cantidadProdComun', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#precioProdComun").focus();
            break;
        }
    });

    $(document).on('keypress', '#precioProdComun', function(event) {
        switch (event.keyCode) {
            case 13: // Enter
                $("#bAgregarProdComun").focus();
            break;
        }
    });

    $(document).on('click', '#bAgregarProdComun', function() {
        $('#formProdComun').trigger('submit');
    });

    $(document).on('click', '#bPrecioMayoreo', function() {
        if ($("#tablaCaja tbody tr").length > 0) {
            if (isNaN($("#tablaCaja").children('tbody').children('tr.activa').attr("preciomayoreo")) || $("#tablaCaja").children('tbody').children('tr.activa').attr("preciomayoreo") <= 0) {
                $("#noMayoreo").show();
                    
                setTimeout(function() {
                    $("#noMayoreo").hide();
                }, 1000);
                audio2.play();
            }else{
                var cantidad = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(3)').children('span.cantidad').text().replace(',', ''));
                var descuento = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').children('span.dinero').text().replace('$', '').replace(',', ''));
                var precio = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').attr("precio").replace('$', '').replace(',', ''));
                var descuHtml = '<span class="dinero">0</span>(<span class="porcentaje">0</span>)';
                var precioMayoreo = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').attr("preciomayoreo"));
                var precioActual = parseFloat($("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(2)').children('span.dinero').text().replace('$', '').replace(',', ''));
                if ($("#tablaCaja").children('tbody').children('tr.activa').hasClass("mayoreo")) {
                    var total = parseFloat((precio * (cantidad)) - descuento);
                }else{
                    var total = parseFloat((precioMayoreo * (cantidad)) - descuento);
                }
                
                if (total < 0) {
                    $("#noNegativos").show();
                
                    setTimeout(function() {
                        $("#noNegativos").hide();
                    }, 1000);

                    audio2.play();
                    $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').html(descuHtml);
                    $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(5)').children('span.dinero').html((precioActual * (cantidad)));
                }else{
                    if ($("#tablaCaja").children('tbody').children('tr.activa').hasClass("mayoreo")) {
                        if(descuento > 0){
                            descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precio * (cantidad))) * 100)+'</span>)';
                        }
                        $("#tablaCaja").children('tbody').children('tr.activa').removeClass("mayoreo");
                        $("#tablaCaja").children('tbody').children('tr.activa').addClass("normal");
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(2)').children('span.dinero').html(precio);
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').html(descuHtml);
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(5)').children('span.dinero').html((precio * (cantidad)) - descuento);
                    }else{
                        if(descuento > 0){
                            descuHtml = '<span class="dinero">'+descuento+'</span>(<span class="porcentaje">'+((descuento / (precioMayoreo * (cantidad))) * 100)+'</span>)';
                        }
                        $("#tablaCaja").children('tbody').children('tr.activa').addClass("mayoreo");
                        $("#tablaCaja").children('tbody').children('tr.activa').removeClass("normal");
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(2)').children('span.dinero').html(precioMayoreo);
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(4)').html(descuHtml);
                        $("#tablaCaja").children('tbody').children('tr.activa').children('td:eq(5)').children('span.dinero').html((precioMayoreo * (cantidad)) - descuento);
                    }
                }
                
            }
            moneda();
            totalCaja(); 
        }else{
            $("#barCodeV").focus();
        }   
    });

    $(document).on('click', '#bImpuestoProd', function() {
        if ($("#tablaCaja tbody tr").length > 0) {
            $("#ModalImpuestosVenta").modal('show');
        }else{
            $("#barCodeV").focus();
        }  
    });

    $(document).on('shown.bs.modal', '#ModalImpuestosVenta', function(){
        $(this).find('#CantidadDescuento').focus();
        $("#CantidadDescuento").val("");
        $("#PorcentajeDescuento").val("");
    });

    $(document).on('hidden.bs.modal', '#ModalImpuestosVenta', function(){
        $("#barCodeV").focus();
    });

});

function verCajaAbierta(id, sucursal, detalle_caja) {
    var html = `<div class="col-12" id="vistaCaja" attrCaja="`+id+`" attrSucursal="`+sucursal+`" attrDetalle="`+detalle_caja+`">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-muted">Ticket 1</h5>
            </div>
            <div class="col-md-6 text-end">
                <button type="submit" class="btn btn-outline-secondary btn-sm" id="bEnterBarCode">Hacer corte de caja <i class="fas fa-calculator"></i></button>
                <button type="submit" class="btn btn-outline-secondary btn-sm" id="bEnterBarCode">Cerrar caja <i class="fas fa-times"></i></button>
            </div>
        </div>
        <form id="formAgreProd" class="row" autocomplete="off">
            <div class="col-md-5 col-sm-7">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    <input type="text" class="form-control" placeholder="Código" name="barCodeV" id="barCodeV" required>
                </div>    
            </div>
            <div class="col-md-4 col-sm-5 mb-3 d-grid">
                <button type="submit" class="btn btn-outline-danger" id="bEnterBarCode">Enter/Agregar Producto <i class="fas fa-check"></i></button>
            </div>
        </form>
        <div class="row">
            <div class="col-12 mb-3">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" id="bIntVarios"><b>F2</b> <i class="fas fa-clipboard"></i> Insert. Varios</button>
                    <button type="button" class="btn btn-outline-secondary" id="bProdComun"><b>ALT + C</b> <i class="fas fa-file"></i> Prod. Común</button>
                    <button type="button" class="btn btn-outline-secondary" id="bBuscarProd"><b>F10</b> <i class="fas fa-search"></i> Buscar</button>
                    <button type="button" class="btn btn-outline-secondary" id="bPrecioMayoreo"><b>ALT + Q</b> <i class="fas fa-certificate"></i> Mayoreo</button>
                    <button type="button" class="btn btn-outline-secondary" id="bImpuestoProd"><b>ALT + I</b> <i class="fas fa-dollar"></i> Impuestos</button>
                    <button type="button" class="btn btn-outline-secondary" id="bDescuentoProd"><b>ALT + D</b> <i class="fas fa-percent"></i> Descuento</button>
                    <button type="button" class="btn btn-outline-secondary" id="bEntradaDinero"><b>F7</b> <i class="fas fa-plus"></i> Entrada</button>
                    <button type="button" class="btn btn-outline-secondary" id="bSalidaDinero"><b>F8</b> <i class="fas fa-minus"></i> Salida</button>
                    <button type="button" class="btn btn-outline-secondary" id="bEliminarProducto"><b>DEL</b> <i class="fas fa-trash"></i> Quitar Pord.</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <nav>
                    <div class="nav nav-tabs" id="navtabTickets" role="tablist">
                        <button class="nav-link active" id="tab_ticket_1" data-bs-toggle="tab" data-bs-target="#nav_ticket_1" type="button" role="tab" aria-selected="true">Ticket 1</button>
                        <!--<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav_ticket_2" type="button" role="tab" aria-selected="false">Ticket 2</button>-->
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav_ticket_1" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row">
                            <div class="col-12 table-responsive" style="height: 45vh; background-color:#F0F0F0;">
                                <table class="table table-hover text-center" id="tablaCaja" style="width: 100%; font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Total</th>
                                            <th>Existencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-10 col-sm-8">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted"><b class="cantidad" id="cantidadCajaProd">0</b> Productos en la venta actual</h6>  
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 col-sm-8">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary"><b>F3</b> <i class="fas fa-exchange-alt"></i> Cambiar</button>
                                            <button type="button" class="btn btn-outline-secondary"><b>F6</b> <i class="fas fa-thumbtack"></i> Pendiente</button>
                                            <button type="button" class="btn btn-outline-secondary"><b>ALT + E</b> <i class="fas fa-trash"></i> Eliminar</button>
                                            <button type="button" class="btn btn-outline-secondary"><b>ALT + A</b> <i class="fas fa-user-tag"></i> Asignar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-4 d-grid">
                                        <button type="button" class="btn btn-secondary btn-lg">F12 <i class="fas fa-cart-plus"></i> Cobrar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4" style="background-color: #E9E9E9; color: blue; padding-top: 5px;">
                                <h2 class="dinero text-center" style="margin: 0" id="totalCaja">0</h2>
                                <p class="text-center" style="margin: 0;"><b>Total</b><p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary"><b>ALT + U</b> <i class="fas fa-print"></i> Reimprimir Último Ticket</button>
                                    <button type="button" class="btn btn-outline-secondary"><b>ALT + V</b> <i class="fas fa-file-alt"></i> Ventas y Devoluciones</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="tab-pane fade" id="nav_ticket_2" role="tabpanel" aria-labelledby="nav-profile-tab"> 2 </div>-->
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col-12 text-end">
                <h6 class="text-muted" id="fechaHoraCaja">`+display()+`</h6>
            </div>
        </div>  
    </div>`;

    setTimeout(function() {
        $("#barCodeV").focus();
    }, 500);
    return html;
}

function display(){
    var today = new Date();
    var month = today.getMonth() + 1;
    var day = today.getDate();
    var year = today.getFullYear();

    var hour = today.getHours() > 12 ? today.getHours() - 12 : today.getHours();
    var minute = today.getMinutes();
    var seconds = today.getSeconds();
    //var milliseconds = today.getMilliseconds();

    var tipo = " a.m."
    if(today.getHours() >= 12){
        tipo = " p.m."
    }

    var output = (("" + hour).length < 2 ? "0" : "") + hour + ':' + (("" + minute).length < 2 ? "0" : "") + minute + ':'+ (("" + seconds).length < 2 ? "0" : "") + seconds + tipo + ' - ' + 
    (("" + day).length < 2 ? "0" : "") + day + '/' + (("" + month).length < 2 ? "0" : "") + month + '/' + year;// + ':' + milliseconds;

    return output;
}

setInterval(function() {    
    $("#fechaHoraCaja").html(display());
}, 1000);

function TablaProductosVenta(){
    ajaxMyDatatable({
        "table": $("#TablaProductosVenta"), 
        "colums": [
            "Codigo",
            "Descripcion",
            "Clase",
            "Precio",
            "Precio Mayoreo",
            "Area",
            "Existencia"
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "hacerventa",
            "tipo": "ConsultarProductos",
            "sucursal": $("#vistaCaja").attr('attrSucursal'),
        }
    });
}

function TablaEntradas(){
    ajaxMyDatatable({
        "table": $("#TablaEntradasRecientes"), 
        "colums": [
            "Fecha",
            "Motivo",
            "Cantidad",
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "hacerventa",
            "tipo": "ConsultarEntradasTurno",
            "DetalleCaja": $("#vistaCaja").attr('attrDetalle'),
        }
    });
}

function TablaSalidas(){
    ajaxMyDatatable({
        "table": $("#TablaSalidasRecientes"), 
        "colums": [
            "Fecha",
            "Motivo",
            "Cantidad",
        ], 
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "hacerventa",
            "tipo": "ConsultarSalidasTurno",
            "DetalleCaja": $("#vistaCaja").attr('attrDetalle'),
        }
    });
}