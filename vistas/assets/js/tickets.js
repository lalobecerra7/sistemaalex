function v_tickets() { 
    console.log('entro a la funcion');
        consultarGeneral();

    // $('#FormCategorias').validate({
    //     rules: {
    //         NombreCategoria: {
    //             required: true
    //         },
    //     },
    //     messages: {
    //         NombreCategoria: {
    //             required: "El nombre de la categoria o familia es obligatorio"
    //         },
    //     },
    //     submitHandler: function(form) { 
    //         var data = "metodo="+$("#GuardarCategoria").attr("tipo")+"&accion=categorias&IDCategoria="+$("#GuardarCategoria").attr("attrid")+"&Nombre="+$("#NombreCategoria").val()+"&Descripcion="+$("#DescripcionCategoria").val()
    //         var btn = $('#GuardarCategoria');
    //         $.ajax({
    //             url: 'index.php',
    //             type: 'POST',
    //             data: data,
    //             beforeSend: function() {
    //                 $("#carga").show();
    //             }
    //         })
    //         .done(function(res) {
    //             if ($.trim(res) == "Correcto") {
    //                 if ($("#GuardarCategoria").attr("tipo") == "modificar") {
    //                     var tipoAlerta = "modificada";
    //                 }else{
    //                     var tipoAlerta = "guardada";
    //                 }
    //                 Swal.fire({
    //                     icon: 'success',
    //                     title: 'Categoria / familia '+tipoAlerta+' correctamente'
    //                 });
    //                 TablaCategorias(); 
    //                 $("#ModalCategorias").modal("hide");
    //             }else{
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Oops...',
    //                     text: 'Error inesperado al '+$("#GuardarCategoria").attr("tipo")+' categoria / familia.'
    //                 });
    //                 console.log($.trim(res));
    //             }
    //         })
    //         .fail(function() {
    //             console.log("Error ajax");
    //         })
    //         .always(function() {
    //             $("#carga").hide();
    //         });                    
    //     }
    // });  
  
}

jQuery(document).ready(function($) {

    $(document).on('change', '#checkDireccion', function() {
        if ($("#checkDireccion").prop('checked')){
            $("#checkCalle").prop('checked', false).prop('disabled', false);
            $("#checkNoExt").prop('checked', false).prop('disabled', false);
            $("#checkNoInt").prop('checked', false).prop('disabled', false);
            $("#checkColonia").prop('checked', false).prop('disabled', false);
            $("#checkCP").prop('checked', false).prop('disabled', false);
            $("#checkCiudad").prop('checked', false).prop('disabled', false);
            $("#checkEstado").prop('checked', false).prop('disabled', false);
            $("#checkPais").prop('checked', false).prop('disabled', false);
        }else{
            $("#checkCalle").prop('checked', false).prop('disabled', true);
            $("#checkNoExt").prop('checked', false).prop('disabled', true);
            $("#checkNoInt").prop('checked', false).prop('disabled', true);
            $("#checkColonia").prop('checked', false).prop('disabled', true);
            $("#checkCP").prop('checked', false).prop('disabled', true);
            $("#checkCiudad").prop('checked', false).prop('disabled', true);
            $("#checkEstado").prop('checked', false).prop('disabled', true);
            $("#checkPais").prop('checked', false).prop('disabled', true);
        }
    });

    $(document).on('click', '#EliminarCategoria', function() {
        var boton = $(this);
        var id = $(this).attr("attrid");
        var nombre = $(this).attr("nombre");
        console.log(id);
        Swal.fire({
          title: '¿Estás a punto de eliminar la categoria / familia '+nombre+'?',
          text: "Una vez eliminado ya no podrá ser recuperado",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No, cancelar',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            var data = "metodo=eliminar&accion=categorias&IDCategoria="+id;
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                console.log(res);
                if ($.trim(res) == "Correcto") {
                    TablaCategorias();
                    Swal.fire({
                        icon: 'success',
                        title: 'Categoria / familia eliminada correctamente'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al eliminar categoria / familia.'
                    });
                }
            })
            .fail(function() {
                console.log("Error ajax");
            });  
          }
        });
    });

    $(document).on('change', '#SucursalTicket', function() {
        var data = "metodo=detalles&accion=tickets&tipo=Sucursal&IDSucursal="+$(this).val();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log(res);
            var datos = JSON.parse($.trim(res));
            $("#CalleSucTicket").val(datos.Calle);
            $("#NoExtTicket").val(datos.No_Exterior);
            $("#NoIntTicket").val(datos.No_Interior);
            $("#ColoniaTicket").val(datos.Colonia);
            $("#CPTicket").val(datos.CP);
            $("#CiudadTicket").val(datos.Ciudad);
            $("#EstadoTicket").val(datos.Estado);
            $("#PaisTicket").val(datos.Pais);
            $("#TelefonoTicket").val(datos.Telefono);
            $("#EmailTicket").val(datos.Email);
            var data = "metodo=detalles&accion=tickets&tipo=ticketSucursal&IDSucursal="+$('#SucursalTicket').val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data
            })
            .done(function(res) {
                console.log(res);
                var datos = JSON.parse($.trim(res));
                $("#CalleSucTicket").val(datos.Calle);
                $("#NoExtTicket").val(datos.No_Exterior);
                $("#NoIntTicket").val(datos.No_Interior);
                $("#ColoniaTicket").val(datos.Colonia);
                $("#CPTicket").val(datos.CP);
                $("#CiudadTicket").val(datos.Ciudad);
                $("#EstadoTicket").val(datos.Estado);
                $("#PaisTicket").val(datos.Pais);
                $("#TelefonoTicket").val(datos.Telefono);
                $("#EmailTicket").val(datos.Email);
            })
            .fail(function() {
                console.log("Error ajax");
            });
        })
        .fail(function() {
            console.log("Error ajax");
        });
    });


    

});

function consultarGeneral(){
    var data = "metodo=consultar&accion=tickets";
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: data
        })
        .done(function(res) {
            console.log(res);
            var datos = JSON.parse($.trim(res));
            $("#CalleSucTicket").val(datos.Calle);
            $("#NoExtTicket").val(datos.No_Exterior);
            $("#NoIntTicket").val(datos.No_Interior);
            $("#ColoniaTicket").val(datos.Colonia);
            $("#CPTicket").val(datos.CP);
            $("#CiudadTicket").val(datos.Ciudad);
            $("#EstadoTicket").val(datos.Estado);
            $("#PaisTicket").val(datos.Pais);
            $("#TelefonoTicket").val(datos.Telefono);
            $("#EmailTicket").val(datos.Email);
            $("#checkDireccion").prop('checked', true);
            $("#checkCalle").prop('checked', true);
            $("#checkNoExt").prop('checked', true);
            $("#checkNoInt").prop('checked', true);
            $("#checkColonia").prop('checked', true);
            $("#checkCP").prop('checked', true);
            $("#checkCiudad").prop('checked', true);
            $("#checkEstado").prop('checked', true);
            $("#checkPais").prop('checked', true);
            $("#checkTelefono").prop('checked', true);
            $("#checkEmail").prop('checked', true);
            mostrarTicket();
        })
        .fail(function() {
            console.log("Error ajax");
        });
}

function mostrarTicket() {
    var columnas = 3, contenido="", calle="", noExt="", noInt="", colonia="", cp="", ciudad="", estado="", pais=""; 
    $("#datosTicket").html("");
    if($("#formTickets").find('input[name=checkCalle]').prop('checked')){
      
    }
    if($("#formTickets").find('input[name=checkDireccion]').prop('checked')){
        if($("#formTickets").find('input[name=checkCalle]').prop('checked')){
           calle = $("#CalleSucTicket").val();
        }
        if($("#formTickets").find('input[name=checkNoExt]').prop('checked')){
            noExt = " #"+$("#NoExtTicket").val();
        }
        if($("#formTickets").find('input[name=checkNoInt]').prop('checked')){
            noInt = " int."+$("#NoIntTicket").val();
        }
        if($("#formTickets").find('input[name=checkColonia]').prop('checked')){
            colonia = $("#ColoniaTicket").val();
        }
        if($("#formTickets").find('input[name=checkCP]').prop('checked')){
            cp = " CP.: "+$("#CPTicket").val();
        }
        if($("#formTickets").find('input[name=checkCiudad]').prop('checked')){
            ciudad = $("#CiudadTicket").val();
        }
        if($("#formTickets").find('input[name=checkEstado]').prop('checked')){
            estado = ", "+$("#EstadoTicket").val();
        }
        if($("#formTickets").find('input[name=checkPais]').prop('checked')){
            pais = ", "+$("#PaisTicket").val();
        }
        $("#datosTicket").append("<div class='row col-sm-12'><div class='col-sm-12'><h8>"+calle+noExt+noInt+"</h8></div></div><div class='row col-sm-12'><div class='col-sm-6'><h8>"+colonia+cp+"</h8></div></div><div class='row col-sm-12'><div class='col-sm-12'><h8>"+ciudad+estado+pais+"</h8></div></div>");
    }
    if($("#formTickets").find('input[name=checkTelefono]').prop('checked')){
        $("#datosTicket").append("<div class='col-sm-12'><h8>"+$("#TelefonoTicket").val()+"</h8></div>");
    }
    if($("#formTickets").find('input[name=checkEmail]').prop('checked')){
        $("#datosTicket").append("<div class='col-sm-12'><h8>"+$("#EmailTicket").val()+"</h8></div>");
    }

    $("#tablaTitulos").html("");
    $("#tablaCuerpo").html("");
    $("#tablaTitulos").append("<th>Cód.</th>");
    $("#tablaCuerpo").append("<td>P10</td>");
    columnas++;
    $("#tablaTitulos").append("<th>Cant.</th>");
    $("#tablaCuerpo").append("<td>2</td>");
    $("#tablaTitulos").append("<th>Producto</th>");
    $("#tablaCuerpo").append("<td>Producto 1</td>");

    $("#tablaTitulos").append("<th>Descripción</th>");
    $("#tablaCuerpo").append("<td>Ejemplo de des...</td>");
    columnas++;

    $("#tablaTitulos").append("<th>Prec. Unit.</th>");
    $("#tablaCuerpo").append("<td>22.5</td>");
    columnas++;
    $("#tablaTitulos").append("<th>Importe</th>");
    $("#tablaCuerpo").append("<td>45</td>");
    $("#numArticulos").html("");
    for(var x=0; x<columnas; x++){
      contenido += "<td>...</td>";
    }
    for(var x=0; x<7; x++){
      $("#numArticulos").append('<tr>'+contenido+'</tr>');
    }
    $("#totalesTicket").html("");
    $("#totalesTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>No. de Articulos: 8</h5></div>");
    $("#totalesTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>Descuento: ...</h5></div>");
    
    $("#totalesTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>Subtotal: ...</h5></div>");

    $("#cambioTicket").html("");
    $("#cambioTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h4>Total: ....</h4></div>");
    $("#cambioTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>Importe Pagado: ...</h5></div>");
    $("#cambioTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>Cambio: ...</h5></div>");
    $("#extrasTicket").html("");
    $("#extrasTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>Cajero: ...</h5></div>");
    $("#extrasTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>Tipo de Venta: ...</h5></div>");
    $("#extrasTicket").append("<div class='col-sm-10 col-sm-offset-1 text-right'><h5>Folio de Venta: ...</h5></div>");

    $("#finalTicket").html("");
    if($("#formTickets").find('input[name=MensajeTicket]').prop('checked')){
      $("#finalTicket").append("<div class='col-sm-12 text-center'><h4>"+$("#MensajeTicket").val()+"</h4></div>");
    }
  }