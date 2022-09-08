var detallesProducto = [];
var cont = 1;
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
            if($('#Maximo').val()<=$('#Minimo').val()){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El stock máximo debe ser mayor al stock mínimo'
                });
            }else if(detallesProducto == ''){
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

                var btn = $('#GuardarProducto');
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        progressBoton(btn);
                    }
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {
                        if ($("#GuardarProducto").attr("tipo") == "modificar") {
                            var tipoAlerta = "modificada";
                        }else{
                            var tipoAlerta = "guardada";
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Producto '+tipoAlerta+' correctamente'
                        });
                        TablaProductos(); 
                        $("#ModalProductos").modal("hide");
                        detallesProducto = [];
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
                    unprogressBoton(btn);
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
        $('#verImagenProducto').html('<img src="vistas/assets/archivos/fotosProductos/default.jpg" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>');
        $('#PreciosSucursal').show();
        JsBarcode("#CodigoB", "CODIGO");
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
                    <td><button class="btn btn-danger btn-sm" type="button" id="EliminarFila" fila="'+cont+'" class="borrar" value="Eliminar"><i class="fas fa-trash"></i></button></td>\
                </tr>'
            );
    
            $('#Sucursales').val('');
            $('#CostoProductoD').val('');
            $('#PrecioProductoD').val('');
            $('#PrecioMayoreoD').val('');
            $('#MinimoD').val('');
            $('#MaximoD').val('');
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

$(document).on('click', '#PSucursal', function () {
        console.log('Ya entro');
        if($('#Sucursal').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes seleccionar una sucursal.'
            });
        }else if($('#CostoProductoE').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el costo del producto.'
            });
        }else if($('#PrecioProductoE').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el precio del producto.'
            });
        }else if($('#PrecioMayoreoE').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes insertar el precio de mayoreo del producto.'
            });
        }else if($('#MinimoE').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el stock minimo del producto.'
            });
        }else if($('#MaximoE').val() ==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debes ingresar el stock maximo del producto.'
            });
        }else{
                var data = new FormData(document.getElementById('FormPrecios'));
                data.append('metodo', 'detalles');
                data.append('accion', 'productos');
                data.append('tipo', $(this).attr('tipo'));
                data.append('IdDetalle', $(this).attr('attrid'));
                data.append('IdProducto', $(this).attr('idProducto'));
    
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {
                        console.log(res);
                        Swal.fire({
                            icon: 'success',
                            title: 'Precio modificado correctamente'
                        }); 
                        var id = $(this).attr('idProducto');
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
    })
    .fail(function() {
        console.log("Error ajax");
    });
});

$(document).on('click', '#EditarPrecios', function () {
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
        $('#tbodyPreciosSucursal').html(datos);
        $('#ModalPreciosSucursal').modal('show');
        $("#PSucursal").attr("idProducto", id);
    })
    .fail(function() {
        console.log("Error ajax");
    });
});

$(document).on('keyup', '#CodigoBarras', function() {
    JsBarcode("#CodigoB", $(this).val());
});
