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
            ClaseProducto: {
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
            ClaseProducto: {
                required: "La clase del producto es obligatorio"
            },
            PrecioProducto: {
                required: "El precio del producto es obligatorio"
            }
        },
        submitHandler: function(form) { 
            if($('#Maximo').val()!= '' && $('#Minimo').val() != '' && $('#Maximo').val() > '0' && $('#Minimo').val() > '0' && $('#Maximo').val()<=$('#Minimo').val()){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El stock máximo debe ser mayor al stock mínimo'
                });
            }else {
                var presentaciones = [];
                $("#verPresentaciones").children('tr').each(function(index, el){
                    presentaciones.push({'Clave': $.trim($(this).children('td:eq(0)').text()), 'Nombre': $.trim($(this).children('td:eq(1)').text()), 'Abreviatura': $.trim($(this).children('td:eq(2)').text())});
                });

                var precios = [];
                $("#verPreciosProd").children('tr').each(function(index, el){
                    precios.push({'Zona': $.trim($(this).children('td:eq(0)').attr('attrID')), 'Presentacion': $.trim($(this).children('td:eq(1)').text()), 'Nombre': $.trim($(this).children('td:eq(2)').text()), 'Precio': $.trim($(this).children('td:eq(3)').text()), 'Precio_Mayoreo': $.trim($(this).children('td:eq(4)').text())});
                });

                var impuestos = [];
                if($("#verImpuetsosProd").children('tr').length > 0){
                    $("#verImpuetsosProd").children('tr').each(function(index, el) {
                        impuestos.push({'ID_Impuesto': $(this).attr('attrID')});
                    });
                }

                var data = new FormData(document.getElementById('FormProductos'));
                data.append('metodo', $('#GuardarProducto').attr('tipo'));
                data.append('accion', 'productos');
                data.append('IDProducto', $('#GuardarProducto').attr('attrid'));
                data.append('presentaciones', JSON.stringify(presentaciones));
                data.append('precios', JSON.stringify(precios));
                data.append('impuestos', JSON.stringify(impuestos));
                
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
  
}

function TablaProductos(){
    ajaxMyDatatable({
        "table": $("#TablaProductos"), 
        "colums": [
            "Codigo",
	        "Descripcion",
		    "Costo",
		    "Precio",
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
        $("#GuardarProducto").attr('tipo', 'modificar');
        $("#GuardarProducto").attr('attrid', id);
        $("#TituloModalProductos").text("Modificar");
        $("#presentacionProdSelect").html('<option value="">--Seleccione una opción--</option>');

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
            $("#ClaseProducto").val(datos.Clase);
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
            $("#Minimo").val(datos.Minimo);
            $("#Maximo").val(datos.Maximo);
            $("#DetallesProducto").val(datos.Detalles);
            
            $('#verImagenProducto').html('<img src="vistas/assets/archivos/fotosProductos/' + datos.Imagen + '" width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>');
            
            $('img').each(function(){
                if($(this)[0].naturalHeight == 0){
                    $(this).attr('src','vistas/assets/archivos/fotosProductos/default.jpg');
                }
            });
           
            $("#CodigoBarras").trigger('keyup');
           
            if(datos.Presentaciones != null){
                datos.Presentaciones.forEach(presentacion => {
                    $("#verPresentaciones").append(`<tr attrID="`+presentacion.Nombre+`">
                        <td>`+presentacion.Clave_CFDI+`</td>
                        <td>`+presentacion.Nombre+`</td>
                        <td>`+presentacion.Abreviatura+`</td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarPresenta"><i class="fas fa-trash"></i></button></td>
                    </tr>`);

                    $("#presentacionProdSelect").append('<option value="'+presentacion.Nombre+'">'+presentacion.Nombre+'</option>');
                });
            }

            if(datos.Precios != null){
                datos.Precios.forEach(precio => {
                    $("#verPreciosProd").append(`<tr>
                        <td attrID="`+precio.FK_Zona+`">`+precio.Zona+`</td>
                        <td attrID="`+precio.FK_Presentacion+`">`+precio.Presentacion+`</td>
                        <td>`+precio.Nombre+`</td>
                        <td>`+precio.Precio+`</td>
                        <td>`+precio.Precio_Mayoreo+`</td>
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
        if($("#verImpuetsosProd").children('tr[attrID='+$(this).attr('attrID')+']').length == 0){
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
        }else{
            $("#unidadPresentacion").val($.trim(padre.children('td:eq(0)').text()));

            if($.trim($("#nombrePresentacion").val()) == ""){
                $("#nombrePresentacion").val($.trim(padre.children('td:eq(1)').text()));
            }
            if($.trim($("#abreviaturaPresentacion").val()) == ""){
                $("#abreviaturaPresentacion").val($.trim(padre.children('td:eq(2)').text()));
            }
        }

        $("#modalClavesUnidades").modal('hide');
    });

    $(document).on('click', '#bAgergarPresentacion', function() {
        $("#bGuardarPres").trigger('click');
    });

    $(document).on('submit', '#formPresentaciones', function(event) {
        event.preventDefault();
        if($("#verPresentaciones").children('tr[attrID='+$.trim($("#nombrePresentacion").val())+']').length == 0){
            $("#verPresentaciones").append(`<tr attrID="`+$.trim($("#nombrePresentacion").val())+`">
                <td>`+$.trim($("#unidadPresentacion").val())+`</td>
                <td>`+$.trim($("#nombrePresentacion").val())+`</td>
                <td>`+$.trim($("#abreviaturaPresentacion").val())+`</td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarPresenta"><i class="fas fa-trash"></i></button></td>
            </tr>`);

            $("#presentacionProdSelect").append('<option value="'+$.trim($("#nombrePresentacion").val())+'">'+$.trim($("#nombrePresentacion").val())+'</option>');

            document.getElementById('formPresentaciones').reset();
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
            <td>`+$.trim($("#precioProductoPres").val())+`</td>
            <td>`+$.trim($("#precioProductoMayoreoPres").val())+`</td>
            <td><button type="button" class="btn btn-danger btn-sm bQuitarPrecio"><i class="fas fa-trash"></i></button></td>
        </tr>`);

        document.getElementById('formPreciosProd').reset();
    });

    $(document).on('click', '.bQuitarPrecio', function() {
        $(this).parent().parent().remove();
    });
});