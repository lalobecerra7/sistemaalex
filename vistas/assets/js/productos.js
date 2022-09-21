var detallesProducto = [];
var cont = 1;
var impuesto = [];
function v_productos() {
    console.log("entro a la funcion");
    
    TablaProductos(); 
    $('#FormProductos').validate({
        rules: {
            CodigoBarras: {
                required: true
            },
            Descripcion: {
                required: true
            },
            ClaseProducto: {
                required: true
            },
            TipoUnidad: {
                required: true
            },
            Unidad: {
                required: true
            },
            Categoria: {
                required: true
            },
            CostoProducto: {
                required: true
            },
            PrecioProducto: {
                required: true
            },
            PrecioMayoreo: {
                required: true
            },
            Area: {
                required: true
            },
            PonerUnidad: {
                required: true
            },
        },
        messages: {
            CodigoBarras: {
                required: "El código de barras del producto es obligatorio"
            },
            Descripcion: {
                required: "La descripción del producto es obligatorio"
            },
            ClaseProducto: {
                required: "La clase del producto es obligatorio"
            },
            TipoUnidad: {
                required: "El tipo de unidad del producto es obligatorio"
            },
            Unidad: {
                required: "La unidad del producto es obligatorio"
            },
            Categorias: {
                required: "La categoria del producto es obligatorio"
            },
            CostoProducto: {
                required: "El costo del producto es obligatorio"
            },
            PrecioProducto: {
                required: "El precio del producto es obligatorio"
            },
            PrecioMayoreo: {
                required: "El precio de mayoreo del producto es obligatorio"
            },
            Area: {
                required: "El area donde se encuentra el producto es obligatorio"
            },
            PonerUnidad: {
                required: "Se debe seleccionar si poner la unidad en el ticket"
            },
        },
        submitHandler: function(form) { 
            if($('#Maximo').val()!= '' && $('#Minimo').val() != '' && $('#Maximo').val()<=$('#Minimo').val()){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El stock máximo debe ser mayor al stock mínimo'
                });
            }else if(detallesProducto.length == 0 && $("#GuardarProducto").attr("tipo") == "insertar"){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Debe ingresar el precio de al menos una sucursal'
                });
            } else {
                var data = new FormData(document.getElementById('FormProductos'));
                data.append('metodo', $('#GuardarProducto').attr('tipo'));
                data.append('accion', 'productos');
                data.append('IDProducto', $('#GuardarProducto').attr('attrid'));
                data.append('detalleProducto', detallesProducto);
                data.append('impuestos', impuesto);

                var btn = $('#GuardarProducto');
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
                        console.log($.trim(res));
                        if ($("#GuardarProducto").attr("tipo") == "modificar") {
                            var tipoAlerta = "modificado";
                        }else{
                            var tipoAlerta = "guardado";
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Producto '+tipoAlerta+' correctamente'
                        });
                        TablaProductos(); 
                        $("#ModalProductos").modal("hide");
                        detallesProducto = [];
                    } else if ($.trim(res) == "Error: Duplicate entry '"+$("#CodigoBarras").val()+"' for key 'Codigo'"){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'El código del producto ya fue registrado, intenta con otro.'
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al '+$("#GuardarProducto").attr("tipo")+' producto.'
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
        }
    });  
  
}

function TablaProductos(){
    ajaxMyDatatable({
        "table": $("#TablaProductos"), 
        "colums": [
            "Codigo",
	        "Descripcion",
		    "Tipo",
		    "Costo",
		    "Precio",
		    "PrecioMayoreo",
            "Detalles",
		    "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "productos"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevoProductos', function() {
        $("#GuardarProducto").attr('tipo', "insertar");
        $("#GuardarProducto").attr('attrid', "");
        $("#FormProductos").trigger('reset');
        $("#TituloModalProductos").text("Agregar nuevo");
        $('#tbodyDetallesProducto').html('');
        $('#verImagenProducto').html('<img src="vistas/assets/archivos/fotosProductos/default.jpg" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>');
        $('#PreciosSucursal').show();
        JsBarcode("#CodigoB", "CODIGO");
        impuesto = [];
    });

    $(document).on('click', '#EliminarProducto', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("descripcion");
        console.log(id);
        Swal.fire({
          title: '¿Estás a punto de eliminar el producto '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=productos&IDProducto="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                console.log(res);
                if ($.trim(res) == "Correcto") {
                    TablaProductos();
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto eliminado correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar el producto.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#EliminarDetalle', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        console.log(id);
        Swal.fire({
          title: '¿Estás a punto de eliminar el precio de esta sucursal?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=detalles&accion=productos&tipo=eliminarPrecio&IDPrecio="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                console.log(res);
                if ($.trim(res) == "Correcto") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Precio eliminado correctamente'
                    });
                    var id = $('#PSucursal').attr('idProducto');
                    var data = "metodo=detalles&accion=productos&tipo=detalle&IDProducto="+id;
                        $.ajax({
                            url: 'index.php',
                            type: 'POST',
                            data: data
                        })
                        .done(function(res) {
                            console.log(res);
                            var datos = JSON.parse($.trim(res));
                            $('#tbodyPreciosSucursal').html(datos);
                        })
                        .fail(function() {
                            console.log("Error ajax");
                        });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar el precio.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('click', '#ModificarProducto', function() {
        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=productos&tipo=modificarProducto&IDProducto="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log(res);
            $("#GuardarProducto").attr('tipo', 'modificar');
            $("#GuardarProducto").attr('attrid', id);
            $("#TituloModalProductos").text("Modificar");
            var datos = JSON.parse($.trim(res));
            $("#CodigoBarras").val(datos.Codigo);
            $("#Descripcion").val(datos.Descripcion);
            $("#ClaseProducto").val(datos.Clase);
            $("#TipoUnidad").val(datos.Tipo);
            $("#Unidad").val(datos.FK_Unidad);
            $("#Categoria").val(datos.FK_Categoria);
            $("#CostoProducto").val(datos.Costo);
            $("#PrecioProducto").val(datos.Precio);
            $("#PrecioMayoreo").val(datos.Precio_Mayoreo);
            $("#Area").val(datos.FK_Area);
            $("#Minimo").val(datos.Minimo);
            $("#Maximo").val(datos.Maximo);
            $("#PonerUnidad").val(datos.Poner_Unidad);
            $("#DetallesProducto").val(datos.Detalles);
            $('#verImagenProducto').html('<img src="vistas/assets/archivos/fotosProductos/' + datos.Imagen + '" width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>');
            $('img').each(function(){
                if($(this)[0].naturalHeight == 0){
                $(this).attr('src','vistas/assets/archivos/fotosProductos/default.jpg');
                }
            });
            $("#CodigoBarras").trigger('keyup');
            $('#PreciosSucursal').hide();
            $('#ModalProductos').modal('show');

        })
        .fail(function() {
            console.log("Error ajax");
        });
    });
});

$(document).on('click', '#verImagenProducto', function () {
    $("#ImagenProducto").trigger("click");
});

$(document).on('change', '#ImagenProducto', function() {
    readURL(this, $("#verImagenProducto"));
});

function readURL(input,ima) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $(ima).html("<img src='"+e.target.result+"' style='width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;' class='img-thumbnail'><br>");
      }
      reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('click', '#EliminarFila', function() {
    $(this).parent().parent().remove();
    detallesProducto.splice($(this).attr('fila')-1,2);
    console.log(detallesProducto);
});

$(document).on('click', '#DetalleProductoSucursal', function () {
    var sucursal = [];
    var agregar = 'si';
    if ($('#Sucursales').val() !='' && $('#CostoProductoD').val() !='' && $('#PrecioProductoD').val() !='' && $('#PrecioMayoreoD').val() !='' && $('#MinimoD').val() !='' && $('#MaximoD').val() !=''){
        console.log(detallesProducto);
        if (detallesProducto != ''){
            for (var i=0; i< detallesProducto.length; i++ ){
                if(detallesProducto[i][0] == $('#Sucursales').val()){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'La sucursal seleccionada ya ha sido agregada, intenta con otra.'
                    });
                    agregar = 'no';
                    break;
                }else {
                    agregar = 'si';
                } 
                console.log(i);
            }
        }
        if(agregar == 'si'){
            sucursal.push($('#Sucursales').val(), $('#CostoProductoD').val(), $('#PrecioProductoD').val(), $('#PrecioMayoreoD').val(), $('#MinimoD').val(), $('#MaximoD').val());
            detallesProducto.push('~',sucursal);
            console.log(detallesProducto);
    
            $('#tbodyDetallesProducto').append('\
                <tr>\
                    <td>' +$('select[name="Sucursales"] option:selected').text()+ '</td>\
                    <td>' +$('#CostoProductoD').val()+ '</td>\
                    <td>' +$('#PrecioProductoD').val()+ '</td>\
                    <td>' +$('#PrecioMayoreoD').val()+ '</td>\
                    <td>' +$('#MinimoD').val()+ '</td>\
                    <td>' +$('#MaximoD').val()+ '</td>\
                    <td>' +$('#impuestosProducto').val()+ '</td>\
                    <td><button class="btn btn-danger btn-sm" type="button" id="EliminarFila" fila="'+cont+'" class="borrar" value="Eliminar"><i class="fas fa-trash"></i></button></td>\
                </tr>'
            );
    
            $('#Sucursales').val('');
            $('#CostoProductoD').val('');
            $('#PrecioProductoD').val('');
            $('#PrecioMayoreoD').val('');
            $('#MinimoD').val('');
            $('#MaximoD').val('');
            $('#impuestosProducto').val('Impuestos');
        }
        }else if($('#Sucursales').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes seleccionar una sucursal.'
            });
        }else if($('#CostoProductoD').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el costo del producto.'
            });
        }else if($('#PrecioProductoD').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el precio del producto.'
            });
        }else if($('#PrecioMayoreoD').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes insertar el precio de mayoreo del producto.'
            });
        }else if($('#MinimoD').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el stock minimo del producto.'
            });
        }else if($('#MaximoD').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el stock maximo del producto.'
            });
        }
        cont+2;
});

$(document).on('click', '#PSucursal', function() {
    console.log('entro');
    $('#FormPrecios').validate({
        rules: {
            Sucursal: {
                required: true
            },
            CostoProductoE: {
                required: true
            },
            PrecioProductoE: {
                required: true
            },
            PrecioMayoreoE: {
                required: true
            },
            MinimoE: {
                required: true
            },
            MaximoE: {
                required: true
            },
        },
        messages: {
            Sucursal: {
                required: "La sucursal es requerida"
            },
            CostoProductoE: {
                required: "El costo del producto es requerido"
            },
            PrecioProductoE: {
                required: "El precio del producto es requerido"
            },
            PrecioMayoreoE: {
                required: "El precio de mayoreo del producto es requerido"
            },
            MinimoE: {
                required: "El stock minimo del producto es requerido"
            },
            MaximoE: {
                required: "El stock maximo del producto es requerido"
            },
        },
        submitHandler: function(form) {
            console.log('entro2');
            if($('#MaximoE').val()<=$('#MinimoE').val()){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El stock máximo debe ser mayor al stock mínimo'
                });
            }else{
                var data = "metodo=detalles&accion=productos&tipo="+$('#PSucursal').attr('tipo')+"&IdDetalle=" + $('#PSucursal').attr('attrid') + "&IdProducto=" + $('#PSucursal').attr('idProducto') + "&Sucursal=" + $("#Sucursal").val() + "&CostoProductoE=" + $("#CostoProductoE").val() + "&PrecioProductoE=" + $("#PrecioProductoE").val() + "&PrecioMayoreoE=" + $("#PrecioMayoreoE").val() + "&MinimoE=" + $("#MinimoE").val() + "&MaximoE=" + $("#MaximoE").val()+"&impuestos="+impuesto;
                console.log(data);
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {
                        console.log(res);
                        Swal.fire({
                            icon: 'success',
                            title: 'Precio modificado correctamente'
                        }); 
                        $("#PSucursal").attr("tipo", 'agregar');
                        $("#NombreBoton").text("Agregar");
                        var id = $('#PSucursal').attr('idProducto');
                        var data = "metodo=detalles&accion=productos&tipo=detalle&IDProducto="+id;
                        $.ajax({
                            url: 'index.php',
                            type: 'POST',
                            data: data
                        })
                        .done(function(res) {
                            console.log(res);
                            var datos = JSON.parse($.trim(res));
                            $('#tbodyPreciosSucursal').html(datos);
                            $('#Sucursal').val('');
                            $('#CostoProductoE').val('');
                            $('#PrecioProductoE').val('');
                            $('#PrecioMayoreoE').val('');
                            $('#MinimoE').val('');
                            $('#MaximoE').val('');
                            $('#impuestosProductoE').val('Impuestos');
                            impuesto = [];
                        })
                        .fail(function() {
                            console.log("Error ajax");
                        });
                        
                    } else if ($.trim(res) == "Duplicado"){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'La sucursal que seleccionaste ya esta registrada, prueba con otra.'
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al modificar precio.'
                        });
                        console.log($.trim(res));
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                })
            }
        }
    });
});

$(document).on('click', '#EditarDetalle', function () {
    var id = $(this).attr('attrid');
    var data = "metodo=detalles&accion=productos&tipo=detalleSucursal&IdDetalle="+id;
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        console.log(res);
        var datos = JSON.parse($.trim(res));
        $("#Sucursal").val(datos.FK_Sucursal);
        $("#CostoProductoE").val(datos.Costo);
        $("#PrecioProductoE").val(datos.Precio);
        $("#PrecioMayoreoE").val(datos.Precio_Mayoreo);
        $("#MinimoE").val(datos.Minimo);
        $("#MaximoE").val(datos.Maximo);
        $("#PSucursal").attr("tipo", 'modificar');
        $("#PSucursal").attr("attrid", datos.ID_Detalle_Producto);
        $("#PSucursal").attr("idProducto", datos.FK_Producto);
        $("#NombreBoton").text("Guardar cambios");

        var data = "metodo=detalles&accion=productos&tipo=impuestosSucursalP&IdSucursal="+datos.FK_Sucursal+"&IdProducto="+$('#PSucursal').attr('idproducto');
        console.log(data);
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log($.trim(res));
            var nombres = '';
            if($.trim(res) != ''){
                var dato = JSON.parse($.trim(res));
                console.log(dato);
                var separa = dato.split(',');
                console.log(separa);
                for(var i=0; i<separa.length; i++){
                    impuesto.push(separa[i]);
                    nombres += separa[i+1]+', ';
                    i++;
                }
                console.log(impuesto);
                console.log(nombres);
                var nom = nombres.substring(0, nombres.length - 12);
                console.log(nom);
                impuesto.pop();
                console.log(impuesto);
                $('#impuestosProductoE').val(nom);
            }else {
                $('#impuestosProductoE').val('Impuestos');
            }
            
        })
        .fail(function() {
            console.log("Error ajax");
        });

    })
    .fail(function() {
        console.log("Error ajax");
    });
});

$(document).on('click', '#EditarPrecios', function () {
    $('#TituloModalPrecios').text($(this).attr('descripcion'));
    var id = $(this).attr('attrid');
    var data = "metodo=detalles&accion=productos&tipo=detalle&IDProducto="+id;
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        console.log(res);
        var datos = JSON.parse($.trim(res));
        $('#Sucursal').val('');
        $('#CostoProductoE').val('');
        $('#PrecioProductoE').val('');
        $('#PrecioMayoreoE').val('');
        $('#MinimoE').val('');
        $('#MaximoE').val('');
        impuesto = [];
        $('#tbodyPreciosSucursal').html(datos);
        $('#ModalPreciosSucursal').modal('show');
        $("#NombreBoton").text("Agregar");
        $("#PSucursal").attr("idProducto", id);
        $('#impuestosProductoE').val('Impuestos');
       
    })
    .fail(function() {
        console.log("Error ajax");
    });
});

$(document).on('keyup', '#CodigoBarras', function() {
    JsBarcode("#CodigoB", $(this).val());
});

$(document).on('click', '#botonimpuestosProducto', function() {
    var imp = '';
    if(impuesto.length>0){
        for(var i=0; i<impuesto.length; i++){
             var separa = impuesto[i].split("~");
             console.log(separa);
             if ($('#Sucursales').val() != ''){
                if (separa[0] == $('#Sucursales').val()){
                    imp += separa[1]+',';
                }
             }else if ($('#Sucursal').val() != ''){
                if (separa[0] == $('#Sucursal').val()){
                    imp += separa[1]+',';
                }
             }
            
        }
    }
    var data = "metodo=detalles&accion=productos&tipo=impuestos&impuestos="+imp;
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
            console.log(res);
            $('#tbodyImpuestosProducto').html($.trim(res));
            $('#ModalImpuestosProducto').modal('show');

    })
    .fail(function() {
        console.log("Error ajax");
    });
});

$(document).on('change', '#checkImpuesto', function() {
    if (impuesto != ''){
        if($(this).prop("checked")){
            if($('#Sucursales').val() != ''){
                impuesto.push($('#Sucursales').val()+'~'+$(this).attr('attrid'));
            }else{
                impuesto.push($('#Sucursal').val()+'~'+$(this).attr('attrid'));
            }
            console.log(impuesto);
        }else {
            for (var i=0; i< impuesto.length; i++ ){
                if($('#Sucursales').val() != ''){
                    if(impuesto[i] == ($('#Sucursales').val()+'~'+$(this).attr('attrid'))){
                        impuesto.splice(i,1);
                        console.log(impuesto);
                    }
                }else{
                    if(impuesto[i] == ($('#Sucursal').val()+'~'+$(this).attr('attrid'))){
                        impuesto.splice(i,1);
                        console.log(impuesto);
                    }
                }
            }
        }
    }else{
        if($('#Sucursales').val() != ''){
            impuesto.push($('#Sucursales').val()+'~'+$(this).attr('attrid'));
        }else{
            impuesto.push($('#Sucursal').val()+'~'+$(this).attr('attrid'));
        }
        console.log(impuesto);
    }
});

$(document).on('change', '#Sucursal', function() {
    if (impuesto != ''){
        var imp = '';
        var imp2 = [];
        var suc = $(this).val();;
        for(var i=0; i<impuesto.length; i++){
            imp = impuesto[i].split('~');
            imp2.push(imp[1]);
        }
        impuesto=[];
        for(var j=0; j<imp2.length; j++){ 
            impuesto.push(suc+'~'+imp2[j]);
        }
        
    }
});

$(document).on('click', '#GuardarImpuestos', function() {
        var imp = '';
        if(impuesto.length>0){
            for(var i=0; i<impuesto.length; i++){
                var separa = impuesto[i].split("~");
                console.log(separa);
                if($('#Sucursales').val() != ''){
                    if (separa[0] == $('#Sucursales').val()){
                        imp += separa[1]+',';
                    }
                }else {
                    if (separa[0] == $('#Sucursal').val()){
                        imp += separa[1]+',';
                    }
                }
                
            }
        }
        if(imp != ''){
            var data = "metodo=detalles&accion=productos&tipo=impuestosSeleccionados&impuestos="+imp;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                    console.log(res);
                    if($('#Sucursales').val() != ''){
                        $('#impuestosProducto').val($.trim(res));
                    }else{
                        $('#impuestosProductoE').val($.trim(res));
                    }
                    $('#ModalImpuestosProducto').modal('hide');
            })
            .fail(function() {
                console.log("Error ajax");
            });
        }else{
            if($('#Sucursales').val() != ''){
                $('#impuestosProducto').val('Impuestos');
            }else{
                $('#impuestosProductoE').val('Impuestos');
            }
            $('#ModalImpuestosProducto').modal('hide');
        }
        
});
