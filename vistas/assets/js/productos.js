var filasPre = null;

function v_productos() {
    
    TablaProductos(); 
    tablaImpuestosProd();
    tablaClavesProdServ();
    tablaClavesUnidades();

    $('#FormProductos').validate({
        rules: {
            CodigoBarras: {
                required: true
            },
            Descripcion: {
                required: true
            },
            PrecioProducto: {
                required: true
            }
        },
        messages: {
            CodigoBarras: {
                required: "El código de barras del producto es obligatorio"
            },
            Descripcion: {
                required: "La descripción del producto es obligatorio"
            },
            PrecioProducto: {
                required: "El precio del producto es obligatorio"
            }
        },
        submitHandler: function(form) { 
            const searchRegExp = new RegExp(',', 'g');
            var presentaciones = [];
            if($("#verPresentaciones").children('tr').length > 0){
                $("#verPresentaciones").children('tr').each(function(index, el){
                    presentaciones.push({'ID_Presentacion': $.trim($(this).attr('id')),'Clave': $.trim($(this).children('td:eq(0)').text()), 'Nombre': $.trim($(this).children('td:eq(1)').text()), 'Abreviatura': $.trim($(this).children('td:eq(2)').text()), 'Costo': $.trim($(this).children('td:eq(3)').text().replace('$', '').replace(searchRegExp, '')), 'Importe': $.trim($(this).children('td:eq(4)').text().replace('$', '').replace(searchRegExp, '')), 'Codigo': $.trim($(this).children('td:eq(5)').text())});
                });
            }

            var precios = [];
            if($("#verPreciosProd").children('tr').length > 0){
                $("#verPreciosProd").children('tr').each(function(index, el){
                    precios.push({'ID_Precio': $.trim($(this).attr('id')),'Zona': $.trim($(this).children('td:eq(0)').attr('attrID')), 'Presentacion': $.trim($(this).children('td:eq(1)').text()), 'Nombre': $.trim($(this).children('td:eq(2)').text()), 'Precio': $.trim($(this).children('td:eq(3)').text().replace('$', '').replace(searchRegExp, '')), 'Precio_Mayoreo': $.trim($(this).children('td:eq(4)').text().replace('$', '').replace(searchRegExp, ''))});
                });
            }

            var impuestos = [];
            if($("#verImpuetsosProd").children('tr').length > 0){
                $("#verImpuetsosProd").children('tr').each(function(index, el) {
                    impuestos.push({'ID_Impuesto': $.trim($(this).attr('attrID'))});
                });
            }

            var proveedores = [];
            if($("#verProveedoresProd").children('tr').length > 0){
                $("#verProveedoresProd").children('tr').each(function(index, el) {
                    proveedores.push({'ID_Proveedor': $.trim($(this).children('td:eq(0)').attr('attrID'))});
                });
            }

            var stock = [];
            if($("#verStockProd").children('tr').length > 0){
                $("#verStockProd").children('tr').each(function(index, el){
                    stock.push({'ID_Sucursal': $.trim($(this).children('td:eq(0)').attr('attrID')), 'Presentacion': $.trim($(this).children('td:eq(1)').text()),'Minimo': $.trim($(this).children('td:eq(2)').text().replace(searchRegExp, '')), 'Maximo': $.trim($(this).children('td:eq(3)').text().replace(searchRegExp, ''))});
                });
            }

            var bloqueado = '0';
            if($("#bloquearProducto").prop('checked')){
                bloqueado = '1';
            }
            
            var data = new FormData(document.getElementById('FormProductos'));
            data.append('metodo', $('#GuardarProducto').attr('tipo'));
            data.append('accion', 'productos');
            data.append('IDProducto', $('#GuardarProducto').attr('attrid'));
            data.append('presentaciones', JSON.stringify(presentaciones));
            data.append('precios', JSON.stringify(precios));
            data.append('impuestos', JSON.stringify(impuestos));
            data.append('proveedores', JSON.stringify(proveedores));
            data.append('stock', JSON.stringify(stock));
            data.append('bloqueado', bloqueado);
                
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
    });  

    $('#FormExistenciaProducto').validate({
        rules: {
            SucursalExistencia: {
                required: true
            },
            CantidadExistencia: {
                required: true
            },
        },
        messages: {
            SucursalExistencia: {
                required: "La sucursal es obligatoria"
            },
            CantidadExistencia: {
                required: "La cantidad es obligatoria"
            },
        },
        submitHandler: function(form) { 
            var data = new FormData(document.getElementById('FormExistenciaProducto'));
            data.append('metodo', "detalles");
            data.append('accion', 'productos');
            data.append('tipo', 'AgregarExistenciaProducto');
            data.append('IDProducto',  $("#GuardarExistenciaProducto").attr("attrid"));

            var btn = $('#GuardarExistenciaProducto');
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Existencia agregada correctamente'
                    });
                    $("#FormExistenciaProducto").trigger("reset");
                    $("#ModalExistenciasProducto").modal("hide");
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al agregar existencia.'
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
    
    $('#formModiPresentacion').validate({
        rules: {
            nombrePresentacionM: {
                required: true
            },
            abreviaturaPresentacionM: {
                required: true
            },
            costoPresentacionM: {
                required: true,
                min: 0
            },
            importePresentacionM: {
                required: true,
                min: 0
            },
            CodigoPresentacionM: {
                required: true
            },
        },
        messages: {
            nombrePresentacionM: {
                required: "El nombre es requerido."
            },
            abreviaturaPresentacionM: {
                required: "La abreviatura es requerida."
            },
            costoPresentacionM: {
                required: "El costo es requerido.",
                min: "El minimo es 0"
            },
            importePresentacionM: {
                required: "El importe es requerido.",
                min: "El minimo es 0"
            },
            CodigoPresentacionM: {
                required: "El codigo de la presentacion es requerido"
            },
        },
        submitHandler: function(form) {
            var filas = $("#verPresentaciones").children('tr[attrID="'+$.trim($("#nombrePresentacion").val())+'"]'); 
            var codigoActual = $("#bGuardarPresenta").attr("codigoActual");
            var codigo = $("#CodigoPresentacionM").val();
            if (codigoActual == codigo) {
                if(filas.length <= 1){
                    var nombre = $.trim(filaPre.attr('attrID'));
                    filaPre.attr('attrID', $.trim($("#nombrePresentacionM").val()));
                    filaPre.children('td:eq(0)').html($.trim($("#unidadPresentacionM").val()));
                    filaPre.children('td:eq(1)').html($.trim($("#nombrePresentacionM").val()));
                    filaPre.children('td:eq(2)').html($.trim($("#abreviaturaPresentacionM").val()));
                    filaPre.children('td:eq(3)').html('<span class="dinero">'+$.trim($("#costoPresentacionM").val())+'</span>');
                    filaPre.children('td:eq(4)').html('<span class="dinero">'+$.trim($("#importePresentacionM").val())+'</span>');
                    filaPre.children('td:eq(5)').html($.trim($("#CodigoPresentacionM").val()));

                    $("#presentacionProdSelect").children('option[value="'+nombre+'"]').attr('value', $.trim($("#nombrePresentacionM").val()));
                    $("#presentacionProdSelect").children('option[value="'+nombre+'"]').html($.trim($("#nombrePresentacionM").val()));
                    $("#presentacionProdSelect1").children('option[value="'+nombre+'"]').attr('value', $.trim($("#nombrePresentacionM").val()));
                    $("#presentacionProdSelect1").children('option[value="'+nombre+'"]').html($.trim($("#nombrePresentacionM").val()));
                    $("#modalPresentaciones").modal('hide');
                    moneda();
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'La presentación ya existe, por favor utiliza otra.'
                    });
                }
            }else{
                var data = "metodo=detalles&accion=productos&tipo=ConsultarValidezCodigo&Codigo="+codigo;
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data
                })
                .done(function(res) {
                    if ($.trim(res) == "Valido") {
                        if(filas.length <= 1){
                            var nombre = $.trim(filaPre.attr('attrID'));
                            filaPre.attr('attrID', $.trim($("#nombrePresentacionM").val()));
                            filaPre.children('td:eq(0)').html($.trim($("#unidadPresentacionM").val()));
                            filaPre.children('td:eq(1)').html($.trim($("#nombrePresentacionM").val()));
                            filaPre.children('td:eq(2)').html($.trim($("#abreviaturaPresentacionM").val()));
                            filaPre.children('td:eq(3)').html('<span class="dinero">'+$.trim($("#costoPresentacionM").val())+'</span>');
                            filaPre.children('td:eq(4)').html('<span class="dinero">'+$.trim($("#importePresentacionM").val())+'</span>');
                            filaPre.children('td:eq(5)').html($.trim($("#CodigoPresentacionM").val()));

                            $("#presentacionProdSelect").children('option[value="'+nombre+'"]').attr('value', $.trim($("#nombrePresentacionM").val()));
                            $("#presentacionProdSelect").children('option[value="'+nombre+'"]').html($.trim($("#nombrePresentacionM").val()));
                            $("#presentacionProdSelect1").children('option[value="'+nombre+'"]').attr('value', $.trim($("#nombrePresentacionM").val()));
                            $("#presentacionProdSelect1").children('option[value="'+nombre+'"]').html($.trim($("#nombrePresentacionM").val()));
                            $("#modalPresentaciones").modal('hide');

                            moneda();
                        }else{
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'La presentación ya existe, por favor utiliza otra.'
                            });
                        }
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Este codigo ya existe, intenta con otro.'
                        });  
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                });
            }
        }              
    });

    $('#FormExistenciaProductoMod').validate({
        rules: {
            SucursalExistenciaMod: {
                required: true
            },
            ExistenciaProductoMod: {
                required: true
            },
        },
        messages: {
            SucursalExistenciaMod: {
                required: "La sucursal es obligatoria"
            },
            ExistenciaProductoMod: {
                required: "La existencia es obligatoria"
            },
        },
        submitHandler: function(form) { 
            Swal.fire({
              title: '¿Estás a punto de modificar las existencias de este producto?',
              footer: "<b style='color: red;'>Una vez modificada ya no podrá recuperarse la existencia anterior</b>",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              cancelButtonText: 'No, cancelar',
              confirmButtonText: 'Si, continuar'
            }).then((result) => {
                if (result.value) {
                    var data = new FormData(document.getElementById('FormExistenciaProductoMod'));
                    data.append('metodo', "detalles");
                    data.append('accion', 'productos');
                    data.append('tipo', 'ModificarExistenciaProducto');
                    data.append('IDProducto',  $("#GuardarExistenciaProductoMod").attr("attrid"));

                    var btn = $('#GuardarExistenciaProductoMod');
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Existencia modificada correctamente'
                            });
                            $("#FormExistenciaProductoMod").trigger("reset");
                            $("#ModalModificarExistencias").modal("hide");
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error inesperado al modificar la existencia.'
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
        }              
    }); 

    $(document).on('change', '#SucursalExistenciaMod', function() {
        var sucursal = $(this).val();
        var presentacion = $("#PresentacionesProductoMod").val() || 0;
        var idproducto = $("#GuardarExistenciaProductoMod").attr("attrid");

        var data = "metodo=detalles&accion=productos&tipo=ConsultarExistenciaActual&IDProducto="+idproducto+"&sucursal="+sucursal+"&presentacion="+presentacion;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            $("#ExistenciaProductoMod").val(parseFloat($.trim(res)));
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('change', '#PresentacionesProductoMod', function() {
        var sucursal = $("#SucursalExistenciaMod").val();
        var presentacion = $(this).val() || 0;
        var idproducto = $("#GuardarExistenciaProductoMod").attr("attrid");

        var data = "metodo=detalles&accion=productos&tipo=ConsultarExistenciaActual&IDProducto="+idproducto+"&sucursal="+sucursal+"&presentacion="+presentacion;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            $("#ExistenciaProductoMod").val(res);
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });
}

function TablaProductos(){
    ajaxMyDatatable({
        "table": $("#TablaProductos"), 
        "colums": [
            "Codigo",
	        "Descripcion",
		    "Precio",
            "Detalles",
		    "Acciones"
        ],
        "sort": [
            0,
            "asc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "consultar",
            "accion": "productos"
        }
    });
}

function tablaImpuestosProd(){
    ajaxMyDatatable({
        "table": $("#tablaImpuestosProd"), 
        "colums": [
            "Nombre",
            "Porcentaje",
            "Clave",
            "Tipo",
            "Clase",
            "Acciones"
        ],
        "sort": [
            0,
            "desc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "productos",
            "tipo": "impuestos"
        }
    });
}

function tablaClavesProdServ(){
    ajaxMyDatatable({
        "table": $("#tablaClavesProdServ"), 
        "colums": [
            "Clave",
            "Descripcion",
            "Palabras",
            "Acciones"
        ],
        "sort": [
            0,
            "asc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "productos",
            "tipo": "clavesProdServ"
        }
    });
}

function tablaClavesUnidades(){
    ajaxMyDatatable({
        "table": $("#tablaClavesUnidades"), 
        "colums": [
            "Clave",
            "Nombre",
            "Simbolo",
            "Acciones"
        ],
        "sort": [
            0,
            "asc"
        ],
        "url": "index.php", 
        "params":{
            "metodo": "detalles",
            "accion": "productos",
            "tipo": "clavesUnidades"
        }
    });
}

jQuery(document).ready(function($) {

    $(document).on('click', '#botonNuevoProductos', function() {
        $("#GuardarProducto").attr('tipo', "insertar");
        $("#GuardarProducto").attr('attrid', "");
        document.getElementById('formPreciosProd').reset();
        $("#verPreciosProd").html("");
        $("#FormProductos").trigger('reset');
        $("#TituloModalProductos").text("Agregar nuevo");
        document.getElementById('formPresentaciones').reset();
        $("#verPresentaciones").html("");
        $("#verImpuetsosProd").html("");
        $("#verProveedoresProd").html("");
        $("#verStockProd").html("");
        $("#bloquearProducto").prop('checked', false);
        $('#verImagenProducto').html('<img src="vistas/assets/archivos/fotosProductos/default.jpg" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>');
        JsBarcode("#CodigoB", "CODIGO");
    });

    $(document).on('click', '.EliminarProducto', function() {
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
            var data = "metodo=eliminar&accion=productos&tipo=EliminarProducto&IDProducto="+id;
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

    $(document).on('click', '.ModificarProducto', function() {
        var id = $(this).attr('attrid');
        $("#verPresentaciones").html("");
        $("#verPreciosProd").html("");
        $("#verImpuetsosProd").html("");
        $("#verProveedoresProd").html("");
        $("#verStockProd").html("");
        $("#GuardarProducto").attr('tipo', 'modificar');
        $("#GuardarProducto").attr('attrid', id);
        $("#TituloModalProductos").text("Modificar");
        $("#presentacionProdSelect").html('<option value="">--Seleccione una opción--</option>');
        $("#presentacionProdSelect1").html('<option value="">--Seleccione una opción--</option>');
        $("#bloquearProducto").prop('checked', false);

        var data = "metodo=detalles&accion=productos&tipo=modificarProducto&IDProducto="+id;
        
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            //console.log($.trim(res));
            var datos = JSON.parse($.trim(res));

            $("#CodigoBarras").val(datos.Codigo);
            $("#Descripcion").val(datos.Descripcion);
            if(datos.FK_Categoria == '0'){
                datos.FK_Categoria = '';
            }
            $("#Categoria").val(datos.FK_Categoria);
            $("#CostoProducto").val(datos.Costo);
            $("#PrecioProducto").val(datos.Precio);
            $("#PrecioMayoreo").val(datos.Precio_Mayoreo);
            if(datos.FK_Area == '0'){
                datos.FK_Area = '';
            }
            $("#Area").val(datos.FK_Area);
            $("#DetallesProducto").val(datos.Detalles);
            $("#ImporteProducto").val(datos.Importe);
            $("#claveProdServ").val(datos.Clave_ProdServ_CFDI);
            $("#claveUnidadProd").val(datos.Clave_Unidad_CFDI);
            $("#unidadProd").val(datos.Nombre_Unidad);
            $("#abreUnudadProd").val(datos.Abreviatura_Unidad);
            $("#objImProducto").val(datos.Objeto_Impuesto_CFDI);

            if(datos.Bloqueado == '1'){
                $("#bloquearProducto").prop('checked', true);
            }
            
            $('#verImagenProducto').html('<img src="vistas/assets/archivos/fotosProductos/' + datos.Imagen + '" width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>');
            
            $('img').each(function(){
                if($(this)[0].naturalHeight == 0){
                    $(this).attr('src','vistas/assets/archivos/fotosProductos/default.jpg');
                }
            });
           
            $("#CodigoBarras").trigger('keyup');
           
            if(datos.Presentaciones != null){
                datos.Presentaciones.forEach(presentacion => {
                    var botonEli = '';
                    if(parseInt(presentacion.NumProd) == 0){
                        botonEli = '<button type="button" class="btn btn-danger btn-sm bQuitarPresenta"><i class="fas fa-trash"></i></button>';
                    }

                    $("#verPresentaciones").append(`<tr id="`+presentacion.ID_Presentacion+`" attrID="`+presentacion.Nombre+`">
                        <td>`+presentacion.Clave_CFDI+`</td>
                        <td>`+presentacion.Nombre+`</td>
                        <td>`+presentacion.Abreviatura+`</td>
                        <td><span class="dinero">`+presentacion.Costo+`</span></td>
                        <td><span class="dinero">`+presentacion.Importe+`</span></td>
                        <td>`+presentacion.Codigo+`</td>
                        <td>`+botonEli+` <button type="button" class="btn btn-warning btn-sm bModificarPresenta" attrID="`+presentacion.ID_Presentacion+`"><i class="fas fa-pencil"></i></button></td>
                    </tr>`);

                    $("#presentacionProdSelect").append('<option value="'+presentacion.Nombre+'">'+presentacion.Nombre+'</option>');
                    $("#presentacionProdSelect1").append('<option value="'+presentacion.Nombre+'">'+presentacion.Nombre+'</option>');
                });
            }

            if(datos.Precios != null){
                datos.Precios.forEach(precio => {
                    $("#verPreciosProd").append(`<tr id="`+precio.ID_Precio+`">
                        <td attrID="`+precio.FK_Zona+`">`+precio.Zona+`</td>
                        <td attrID="`+precio.FK_Presentacion+`">`+precio.Presentacion+`</td>
                        <td>`+precio.Nombre+`</td>
                        <td><span class="dinero">`+precio.Precio+`</span></td>
                        <td><span class="dinero">`+precio.Precio_Mayoreo+`</span></td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarPrecio"><i class="fas fa-trash"></i></button></td>
                    </tr>`);
                });
            }
            
            if(datos.Impuestos != null){
                datos.Impuestos.forEach(impuesto => {
                    $("#verImpuetsosProd").append(`<tr attrID="`+impuesto.ID_Impuesto+`">
                        <td>`+impuesto.Nombre+`</td>
                        <td>`+impuesto.Porcentaje+`</td>
                        <td>`+impuesto.Clave+`</td>
                        <td>`+impuesto.Tipo+`</td>
                        <td>`+impuesto.Clase+`</td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarImpu"><i class="fas fa-trash"></i></button></td>
                    </tr>`);
                });
            }

            if(datos.Proveedores != null){
                datos.Proveedores.forEach(proveedor => {
                    $("#verProveedoresProd").append(`<tr id="`+proveedor.FK_Proveedor+`">
                        <td attrID="`+proveedor.FK_Proveedor+`">`+proveedor.Empresa+`/`+proveedor.Nombre+`</td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarProveedor"><i class="fas fa-trash"></i></button></td>
                    </tr>`);
                });
            }

            if(datos.Stocks != null){
                datos.Stocks.forEach(stock => {
                    $("#verStockProd").append(`<tr>
                        <td attrID="`+stock.FK_Sucursal+`">`+stock.Sucursal+`</td>
                        <td attrID="`+stock.FK_Presentacion+`">`+stock.Presentacion+`</td>
                        <td><span class="cantidad">`+stock.Minimo+`</span></td>
                        <td><span class="cantidad">`+stock.Maximo+`</span></td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarStock"><i class="fas fa-trash"></i></button></td>
                    </tr>`);
                });
            }

            moneda();
            $('#ModalProductos').modal('show');
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '.AumentarExistencias', function() {
        $("#PresentacionesProducto").html("");
        $("#GuardarExistenciaProducto").attr("attrid", "");
        $("#FormExistenciaProducto").trigger("reset");

        var id = $(this).attr('attrid');
        var data = "metodo=detalles&accion=productos&tipo=ConsultarPresentacionesExistencia&IDProducto="+id;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            $("#PresentacionesProducto").html(res);
            $("#GuardarExistenciaProducto").attr("attrid", id);
            $('#ModalExistenciasProducto').modal('show');
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('click', '.ModificarExistencia', function() {
        var id = $(this).attr('attrid');
        Swal.fire({
          title: 'Ingresa la contraseña',
          text: 'Solo los administradores pueden ingresar a esta opción',
          html: "<input class='form-control' type='password' id='contraAdmin' placeholder='Ingresa la contraseña'>",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Continuar'
        }).then((result) => {
            if (result.value) {
                var data = "metodo=detalles&accion=productos&tipo=ConsultarContraAdmin&contrasena="+$("#contraAdmin").val();
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: data
                })
                .done(function(res) {
                    if ($.trim(res) == "Correcto") {
                        $("#ModalModificarExistencias").modal("show");
                        $("#PresentacionesProductoMod").html("");
                        $("#GuardarExistenciaProductoMod").attr("attrid", "");
                        $("#FormExistenciaProductoMod").trigger("reset");

                        var data = "metodo=detalles&accion=productos&tipo=ConsultarPresentacionesExistencia&IDProducto="+id;
                        $.ajax({
                            url: 'index.php',
                            type: 'POST',
                            data: data
                        })
                        .done(function(res) {
                            $("#PresentacionesProductoMod").html(res);
                            $("#GuardarExistenciaProductoMod").attr("attrid", id);
                            $('#ModalExistenciasProductoMod').modal('show');
                        })
                        .fail(function() {
                            console.log("Error ajax");
                        });
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'No tienes permiso de acceder a esta función'
                        });
                    }
                })
                .fail(function() {
                    console.log("Error ajax");
                });
            }
        });
    });

    $(document).on('click', '#verImagenProducto', function () {
        $("#ImagenProducto").trigger("click");
    });

    $(document).on('change', '#ImagenProducto', function() {
        readURL(this, $("#verImagenProducto"));
    });

    $(document).on('click', '#bAgregarImpuestoProd', function() {
        $("#modalImpuestosProducto").modal('show');
    });

    $(document).on('click', '.bSeleccionarIm', function() {
        if($("#verImpuetsosProd").children('tr[attrID="'+$(this).attr('attrID')+'"]').length == 0){
            var padre = $(this).parent().parent();
            $("#verImpuetsosProd").append(`<tr attrID="`+$(this).attr('attrID')+`">
                <td>`+$.trim(padre.children('td:eq(0)').text())+`</td>
                <td>`+$.trim(padre.children('td:eq(1)').text())+`</td>
                <td>`+$.trim(padre.children('td:eq(2)').text())+`</td>
                <td>`+$.trim(padre.children('td:eq(3)').text())+`</td>
                <td>`+$.trim(padre.children('td:eq(4)').text())+`</td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarImpu"><i class="fas fa-trash"></i></button></td>
            </tr>`);
        }

        $("#modalImpuestosProducto").modal('hide');
    });

    $(document).on('click', '.bQuitarImpu', function() {
        $(this).parent().parent().remove();
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

    $(document).on('keyup', '#CodigoBarras', function() {
        JsBarcode("#CodigoB", $(this).val());
    });

    $(document).on('click', '#bBuscarClaveProd', function() {
        $("#modalClavesProdServ").modal('show');
    });

    var tipoClaveU = 'prod';
    $(document).on('click', '#bBuscarUnidadProd', function() {
        tipoClaveU = 'prod';
        $("#modalClavesUnidades").modal('show');
    });

    $(document).on('click', '#bBuscarUnidadPres', function() {
        tipoClaveU = 'pres';
        $("#modalClavesUnidades").modal('show');
    });

    $(document).on('click', '#bBuscarUnidadPresM', function() {
        tipoClaveU = 'presM';
        $("#modalClavesUnidades").modal('show');
    });

    $(document).on('click', '.bSeleccionarClaveProdServ', function() {
        $("#claveProdServ").val($.trim($(this).parent().parent().children('td:eq(0)').text()));        

        $("#modalClavesProdServ").modal('hide');
    });

    $(document).on('click', '.bSeleccionarClaveUnidad', function() {
        var padre = $(this).parent().parent();
        if(tipoClaveU == 'prod'){
            $("#claveUnidadProd").val($.trim(padre.children('td:eq(0)').text()));

            if($.trim($("#unidadProd").val()) == ""){
                $("#unidadProd").val($.trim(padre.children('td:eq(1)').text()));
            }
            if($.trim($("#abreUnudadProd").val()) == ""){
                $("#abreUnudadProd").val($.trim(padre.children('td:eq(2)').text()));
            }
        }else if(tipoClaveU == 'pres'){
            $("#unidadPresentacion").val($.trim(padre.children('td:eq(0)').text()));

            if($.trim($("#nombrePresentacion").val()) == ""){
                $("#nombrePresentacion").val($.trim(padre.children('td:eq(1)').text()));
            }
            if($.trim($("#abreviaturaPresentacion").val()) == ""){
                $("#abreviaturaPresentacion").val($.trim(padre.children('td:eq(2)').text()));
            }
        }else{
            $("#unidadPresentacionM").val($.trim(padre.children('td:eq(0)').text()));

            if($.trim($("#nombrePresentacionM").val()) == ""){
                $("#nombrePresentacionM").val($.trim(padre.children('td:eq(1)').text()));
            }
            if($.trim($("#abreviaturaPresentacionM").val()) == ""){
                $("#abreviaturaPresentacionM").val($.trim(padre.children('td:eq(2)').text()));
            }
        }

        $("#modalClavesUnidades").modal('hide');
    });

    $(document).on('click', '#bAgergarPresentacion', function() {
        var codigo = $(this).parent().parent().children("td:eq(5)").find("#CodigoPresentacion").val();
        var data = "metodo=detalles&accion=productos&tipo=ConsultarValidezCodigo&Codigo="+codigo;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            if ($.trim(res) == "Valido") {
                $("#bGuardarPres").trigger('click');
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Este codigo ya existe, intenta con otro.'
                });  
            }
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });

    $(document).on('submit', '#formPresentaciones', function(event) {
        event.preventDefault();
        if($("#verPresentaciones").children('tr[attrID="'+$.trim($("#nombrePresentacion").val())+'"]').length == 0){
            $("#verPresentaciones").append(`<tr attrID="`+$.trim($("#nombrePresentacion").val())+`">
                <td>`+$.trim($("#unidadPresentacion").val())+`</td>
                <td>`+$.trim($("#nombrePresentacion").val())+`</td>
                <td>`+$.trim($("#abreviaturaPresentacion").val())+`</td>
                <td><span class="dinero">`+$.trim($("#costoPresentacion").val())+`</span></td>
                <td><span class="dinero">`+$.trim($("#importePresentacion").val())+`</span></td>
                <td>`+$.trim($("#CodigoPresentacion").val())+`</td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarPresenta"><i class="fas fa-trash"></i></button> <button type="button" class="btn btn-warning btn-sm bModificarPresenta"><i class="fas fa-pencil"></i></button></td>
            </tr>`);

            $("#presentacionProdSelect").append('<option value="'+$.trim($("#nombrePresentacion").val())+'">'+$.trim($("#nombrePresentacion").val())+'</option>');
            $("#presentacionProdSelect1").append('<option value="'+$.trim($("#nombrePresentacion").val())+'">'+$.trim($("#nombrePresentacion").val())+'</option>');

            document.getElementById('formPresentaciones').reset();
            moneda();
        }else{
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'La presentación ya existe, por favor utiliza otra.'
            });
        }
    });

    $(document).on('click', '.bQuitarPresenta', function() {
        $(this).parent().parent().remove();
    });

    $(document).on('click', '#bAgergarPrecio', function() {
        $("#bGuardarPrecio").trigger('click');
    });

    $(document).on('submit', '#formPreciosProd', function(event) {
        event.preventDefault();
        $("#verPreciosProd").append(`<tr>
            <td attrID="`+$.trim($("#zonaPrecioProducto").val())+`">`+$.trim($('#zonaPrecioProducto option:selected').text())+`</td>
            <td attrID="`+$.trim($("#presentacionProdSelect").val())+`">`+$.trim($('#presentacionProdSelect').val())+`</td>
            <td>`+$.trim($("#nombrePrecio").val())+`</td>
            <td><span class="dinero">`+$.trim($("#precioProductoPres").val())+`</span></td>
            <td><span class="dinero">`+$.trim($("#precioProductoMayoreoPres").val())+`</span></td>
            <td><button type="button" class="btn btn-danger btn-sm bQuitarPrecio"><i class="fas fa-trash"></i></button></td>
        </tr>`);

        document.getElementById('formPreciosProd').reset();
        moneda();
    });

    $(document).on('click', '.bQuitarPrecio', function() {
        $(this).parent().parent().remove();
    });

    $(document).on('click', '.bModificarPresenta', function() {
        filaPre = $(this).parent().parent();
        const searchRegExp = new RegExp(',', 'g');
        $("#unidadPresentacionM").val($.trim(filaPre.children('td:eq(0)').text()));
        $("#nombrePresentacionM").val($.trim(filaPre.children('td:eq(1)').text()));
        $("#abreviaturaPresentacionM").val($.trim(filaPre.children('td:eq(2)').text()));
        $("#costoPresentacionM").val($.trim(filaPre.children('td:eq(3)').text().replace('$', '').replace(searchRegExp, '')));
        $("#importePresentacionM").val($.trim(filaPre.children('td:eq(4)').text().replace('$', '').replace(searchRegExp, '')));
        $("#CodigoPresentacionM").val($.trim(filaPre.children('td:eq(5)').text()));
        $("#bGuardarPresenta").attr('attrID', $(this).attr('attrID'));
        $("#bGuardarPresenta").attr("codigoActual", filaPre.children('td:eq(5)').text());
        $("#modalPresentaciones").modal('show');
    });

    $(document).on('click', '#bAgergarProveedor', function() {
        $("#bGuardarProveedor").trigger('click');
    });

    $(document).on('submit', '#formProveedoresProd', function(event) {
        event.preventDefault();

        if($("#verProveedoresProd").children('tr[id="'+$.trim($("#proveedorProducto").val())+'"]').length == 0){
            $("#verProveedoresProd").append(`<tr id="`+$.trim($("#proveedorProducto").val())+`">
                <td attrID="`+$.trim($("#proveedorProducto").val())+`">`+$.trim($('#proveedorProducto option:selected').text())+`</td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarProveedor"><i class="fas fa-trash"></i></button></td>
            </tr>`);

            document.getElementById('formProveedoresProd').reset();
            moneda();
        }else{
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'El proveedor ya existe, por favor agrega otro.'
            });  
        }
    });

    $(document).on('click', '.bQuitarProveedor', function() {
        $(this).parent().parent().remove();
    });

    $(document).on('click', '#bAgergarStock', function() {
        $("#bGuardarStock").trigger('click');
    });

    $(document).on('submit', '#formStockProd', function(event) {
        event.preventDefault();
        if(parseFloat($("#minimoStock").val()) < parseFloat($("#maximoStock").val())){
            var encontro = false;
            if($("#verStockProd").children('tr').length > 0){
                for (var i = $("#verStockProd").children('tr').length - 1; i >= 0; i--) {
                    if($.trim($("#sucursalProducto").val()) == $.trim($("#verStockProd").children('tr:eq('+i+')').children('td:eq(0)').attr('attrID')) && $.trim($("#presentacionProdSelect1").val()) == $.trim($("#verStockProd").children('tr:eq('+i+')').children('td:eq(1)').text())){
                        encontro = true;
                        break;
                    }
                }
            }

            if(encontro == false){
                $("#verStockProd").append(`<tr>
                    <td attrID="`+$.trim($("#sucursalProducto").val())+`">`+$.trim($('#sucursalProducto option:selected').text())+`</td>
                    <td>`+$.trim($("#presentacionProdSelect1").val())+`</td>
                    <td><span class="cantidad">`+$("#minimoStock").val()+`</span></td>
                    <td><span class="cantidad">`+$("#maximoStock").val()+`</span></td>
                    <td><button type="button" class="btn btn-danger btn-sm bQuitarStock"><i class="fas fa-trash"></i></button></td>
                </tr>`);

                document.getElementById('formStockProd').reset();
                moneda();
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'El stock de la presentación en la sucursal ya existe, por favor utiliza otra.'
                });
            }
        }else{
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'El stock mínimo debe ser menor al stock máximo.'
            });
        }
    });

    $(document).on('click', '.bQuitarStock', function() {
        $(this).parent().parent().remove();
    });
});