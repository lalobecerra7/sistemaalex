function v_facturacion() {
	$('#formFacturacion').validate({
        rules: {
            rfcFacturacion: {
            	required: true
            },
			nombreFacturacion: {
				required: true
			},
			regimenFacturacion: {
				required: true
			}
        },
        messages: {
            rfcFacturacion: {
            	required: "El RFC es requerido."
            },
			nombreFacturacion: {
				required: "El nombre es requerido."
			},
			regimenFacturacion: {
				required: "El régimen es requerido."
			}
        },
        submitHandler: function(form) { 
            var data = new FormData(document.getElementById('formFacturacion'));
            data.append('metodo', 'insertar');
            data.append('accion', 'facturacion');
                
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
                        title: 'Los datos han sido guardados correctamente'
                    });

                    $("#contraFacturacion").val("");
                    $("#certificadoFacturacion").val("");
					$("#keyFacturacion").val("");
                }else if($.trim(res) == 'Error 2 Formato'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El formato del certificado no está permitido, el formato permitido es .cer'
                    });
                }else if($.trim(res) == 'Error 3 Peso') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El tamaño del certificado excedió el peso máximo permitido, el peso máximo es de 10MB.'
                    });
                }else if($.trim(res) == 'Error 4 Formato'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El formato de la key no está permitido, el formato permitido es .key'
                    });
                }else if($.trim(res) == 'Error 5 Peso') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El tamaño de la key excedió el peso máximo permitido, el peso máximo es de 10MB.'
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error inesperado al guardar los datos.'
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

if($('#formFacturar').length > 0){
    $('#formFacturar').validate({
        rules: {
            serieCFDI: {
                required: true
            },
            folioCFDI: {
                required: true
            },
            formaPagoCFDI: {
                required: true
            },
            monedaCFDI: {
                required: true
            },
            tipoCFDI: {
                required: true
            },
            exportacionCFDI: {
                required: true
            },
            metodoCFDI: {
                required: true
            },
            lugarCFDI: {
                required: true
            },
            relacionCFDI: {
                required: true
            },
            usoCFDI: {
                required: true
            },
            periodicidadCFDI: {
                required: true
            },
            mesesCFDI: {
                required: true
            },
            anoCFDI: {
                required: true
            }
        },
        messages: {
            serieCFDI: {
                required: "El no. de serie es requerido."
            },
            folioCFDI: {
                required: "El folio es requerido."
            },
            formaPagoCFDI: {
                required: "La forma de pago es requerido."
            },
            monedaCFDI: {
                required: "La moneda es requerida."
            },
            tipoCFDI: {
                required: "El tipo de comprobante es requerido."
            },
            exportacionCFDI: {
                required: "La exportación es requerida."
            },
            metodoCFDI: {
                required: "El método de pago es requerido."
            },
            lugarCFDI: {
                required: "El lugar de expedición es requerido."
            },
            relacionCFDI: {
                required: "El tipo de relación es requerida."
            },
            usoCFDI: {
                required: "El uso del CFDI es requerido."
            },
            periodicidadCFDI: {
                required: "La periodicidad es requerida."
            },
            mesesCFDI: {
                required: "El mes es requerido."
            },
            anoCFDI: {
                required: "El año es requerido."
            }
        },
        submitHandler: function(form) { 
            var relaciones = [];
            $("#verFoliosCFDI").children('tr').each(function(index, el){
                relaciones.push({'UUID': $.trim($(this).children('td:eq(0)').text())});
            });

            var data = 'metodo=modificar&accion=facturacion&id='+$.trim($("#bTimbrarFactura").attr('attrID'))+'&formaPagoCFDI='+$("#formaPagoCFDI").val()+'&relacionCFDI='+$("#relacionCFDI").val()+'&usoCFDI='+$("#usoCFDI").val()+'&periodicidadCFDI='+$("#periodicidadCFDI").val()+'&mesesCFDI='+$("#mesesCFDI").val()+'&anoCFDI='+$.trim($("#anoCFDI").val())+'&relaciones='+JSON.stringify(relaciones);

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $("#carga").show();
                }
            })
            .done(function(res) {
                if ($.trim(res) == "Correcto") {
                    Swal.fire({
                        icon: 'success',
                        title: 'La factura ha sido timbrada correctamente'
                    });

                    TablaReporteVentas();
                    $("#modalFacturar").modal('hide');
                    window.open("controladores/pdf/factura.php?id="+$.trim($("#bTimbrarFactura").attr('attrID')));
                    window.open("controladores/xml/xml.php?id="+$.trim($("#bTimbrarFactura").attr('attrID')));
                }else if($.trim(res) == "Error 2 Datos Facturacion"){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Por favor llena todos los datos del menú facturación 4.0.'
                    }); 
                }else if($.trim(res) == "Error 12 Tabla datos generales"){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Hay un problema con los datos del menú facturación 4.0, contacta con los desarrolladores.'
                    }); 
                }else if($.trim(res) == 'Error 4 Razon y Regimen Cliente'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'La razón social y el régimen fiscal del cliente son requeridos, por favor llena los datos.'
                    });
                }else if($.trim(res) == 'Error 5 Domicilio Cliente'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El domicilio fiscal del cliente es requerido, por favor llena los datos.'
                    });
                }else if($.trim(res) == 'Error 6 Domicilio Sucursal'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'El domicilio de la sucursal es requerido, por favor llena los datos.'
                    });
                }else if($.trim(res) == "Error 13 No encontro"){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'La venta ha sido cancelada o ya fue facturada.'
                    });
                }else{
                    var separa = $.trim(res).split('~');
                    if(separa[0] == 'Error 7 Codigo'){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'El código del producto '+separa[1]+' es requerido.'
                        });
                    }else if(separa[0] == 'Error 8 Clave y Unidad'){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'La clave de unidad y el nombre de la unidad del producto '+separa[1]+' son requeridas.'
                        });
                    }else if(separa[0] == 'Error 9 Clave producto y Objeto Impuesto'){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'La clave y el objeto de impuesto del producto '+separa[1]+' son requeridos.'
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error inesperado al crear la factura: '+$.trim(res)
                        });
                        
                        console.log($.trim(res));
                    }
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

function facturarVenta(id) {
    $("#bTimbrarFactura").attr('attrID', id);
    document.getElementById('formFacturar').reset();
    $("#datosEmisorCFDI").html("");
    $("#datosReceptoCFDI").html("");
    $("#verNodoGlobal").html("");
    $("#conceptosCFDI").html("");
    $("#verFoliosCFDI").html("");
    $("#subtotalCFDI").html('0');
    $("#totalDescuentoCFDI").html('0');
    $("#impuetosTrasCFDI").html('0');
    $("#impuestosRetCFDI").html('0');
    $("#totalCFDI").html('0');
    $("#serieCFDI").val(id);
    $("#folioCFDI").val(id.toString().padStart(8, '0'));
    $("#usoCFDI").prop('disabled', false);
    $("#relacionCFDI").prop('disabled', true);

    var data = "metodo=consultar&accion=facturacion&id="+id;

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data,
        beforeSend: function() {
            $("#carga").show();
        }
    })
    .done(function(res) {
        //console.log($.trim(res));
        if($.trim(res) == "Error 2 Datos Facturacion"){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Por favor llena todos los datos del menú facturación 4.0.'
            }); 
        }else if($.trim(res) == "Error 6 Tabla datos generales"){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Hay un problema con los datos del menú facturación 4.0, contacta con los desarrolladores.'
            }); 
        }else if($.trim(res) == "null"){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'La venta ha sido facturada o cancelada previamente.'
            }); 
        }else{
            var datos = JSON.parse($.trim(res));

            $("#lugarCFDI").val(datos.CP_Sucursal);

            var domicilioGeneral = datos.Calle_Sucursal+' #'+datos.No_Exterior_Sucursal;
            if(datos.No_Interior_Sucursal != ''){
                domicilioGeneral += ' - '+datos.No_Interior_Sucursal+',';
            }

            if(datos.Colonia_Sucursal != ''){
                domicilioGeneral += ' '+datos.Colonia_Sucursal;
            }

            domicilioGeneral += ' C.P. '+datos.CP_Sucursal+', '+datos.Ciudad_Sucursal+', '+datos.Estado_Sucursal+' '+datos.Pais_Sucursal+'.';

            var regimen = {
                '601': 'General de Ley Personas Morales',
                '603': 'Personas Morales con Fines no Lucrativos',
                '605': 'Sueldos y Salarios e Ingresos Asimilados a Salarios',
                '606': 'Arrendamiento',
                '607': 'Régimen de Enajenación o Adquisición de Bienes',
                '608': 'Demás ingresos',
                '610': 'Residentes en el Extranjero sin Establecimiento Permanente en México',
                '611': 'Ingresos por Dividendos (socios y accionistas)',
                '612': 'Personas Físicas con Actividades Empresariales y Profesionales',
                '614': 'Ingresos por intereses',
                '615': 'Régimen de los ingresos por obtención de premios',
                '616': 'Sin obligaciones fiscales',
                '620': 'Sociedades Cooperativas de Producción que optan por diferir sus ingresos',
                '621': 'Incorporación Fiscal',
                '622': 'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras',
                '623': 'Opcional para Grupos de Sociedades',
                '624': 'Coordinados',
                '625': 'Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas',
                '626': 'Régimen Simplificado de Confianza'
            };

            $("#datosEmisorCFDI").html(`<div class="row">
                <div class="col-12">
                    <h3>`+datos.Nombre_General+`</h3>
                    <p><b>RFC:</b> `+datos.RFC_General+`</p>
                    <p><b>Domicilio:</b> `+domicilioGeneral+`</p>
                    <p><b>Régimen Fiscal:</b> `+datos.Regimen_General+' - '+regimen[datos.Regimen_General]+`</p>
                    <p><b>Lugar de expedición:</b> `+datos.CP_Sucursal+`</p>
                </div>
            </div>`);

            var domicilioCliente = datos.Calle_Cliente+' #'+datos.No_Exterior_Cliente;
            if(datos.No_Interior_Cliente != ''){
                domicilioCliente += ' - '+datos.No_Interior_Cliente+',';
            }

            if(datos.Colonia_Cliente != ''){
                domicilioCliente += ' '+datos.Colonia_Cliente;
            }

            domicilioCliente += ' C.P. '+datos.CP_Cliente+', '+datos.Ciudad_Cliente+', '+datos.Estado_Cliente+' '+datos.Pais_Cliente+'.';

            if(datos.FK_Cliente == '1'){
                domicilioCliente = 'C.P. '+datos.CP_Sucursal;
                $("#usoCFDI").prop('disabled', true);
                $("#usoCFDI").val('S01');

                var opcionesPerio = '', opcionesMeses = '';
                if(datos.Regimen_General == '621'){
                    opcionesPerio = '<option value="05">05 - Bimestral</option>';
                    opcionesMeses = `<option value="13">13 - Enero-Febrero</option>
                        <option value="14">14 - Marzo-Abril</option>
                        <option value="15">15 - Mayo-Junio</option>
                        <option value="16">16 - Julio-Agosto</option>
                        <option value="17">17 - Septiembre-Octubre</option>
                        <option value="18">18 - Noviembre-Diciembre</option>`;
                }

                $("#verNodoGlobal").html(`
                    <div class="col-md-4 col-sm-12 mb-3">
                      <div class="form-floating mb-3">
                        <select class="form-select" name="periodicidadCFDI" id="periodicidadCFDI">
                            <option value="">--Selecciona una opción--</option>
                            <option value="01">01 - Diario</option>
                            <option value="02">02 - Semanal</option>
                            <option value="03">03 - Quincenal</option>
                            <option value="04">04 - Mensual</option>
                            `+opcionesPerio+`
                        </select>
                        <label>Periodicidad</label>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                      <div class="form-floating mb-3">
                        <select class="form-select" name="mesesCFDI" id="mesesCFDI">
                            <option value="">--Selecciona una opción--</option>
                            <option value="01">01 - Enero</option>
                            <option value="02">02 - Febrero</option>
                            <option value="03">03 - Marzo</option>
                            <option value="04">04 - Abril</option>
                            <option value="05">05 - Mayo</option>
                            <option value="06">06 - Junio</option>
                            <option value="07">07 - Julio</option>
                            <option value="08">08 - Agosto</option>
                            <option value="09">09 - Septiembre</option>
                            <option value="10">10 - Octubre</option>
                            <option value="11">11 - Noviembre</option>
                            <option value="12">12 - Diciembre</option>
                            `+opcionesMeses+`
                        </select>
                        <label>Meses</label>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="anoCFDI" name="anoCFDI" placeholder="Año">
                            <label>Año</label>
                        </div>
                    </div>
                `);
            }

            $("#datosReceptoCFDI").html(`<div class="row">
                <div class="col-12">
                    <h3>`+datos.Razon_CFDI+`</h3>
                    <p><b>RFC:</b> `+datos.RFC_Cliente+`</p>
                    <p><b>Domicilio:</b> `+domicilioCliente+`</p>
                    <p><b>Régimen Fiscal:</b> `+datos.Regimen_CFDI+' - '+regimen[datos.Regimen_CFDI]+`</p>
                </div>
            </div>`);

            var subtotal = 0, descuentos = 0, trasladados = 0, retenidos = 0, total = 0;
            datos.Productos.forEach(prod => {
                var impuestos = '';
                if(prod.Impuestos != null) {
                    prod.Impuestos.forEach(imp => {
                        if(imp.Tipo_Factor_CFDI != "Exento"){ 
                            if(imp.Tipo_Impuesto_CFDI == 'Trasladado'){
                                trasladados += ((parseFloat(prod.Cantidad) * parseFloat(prod.Precio)) - parseFloat(prod.Descuento)) * (imp.Tasa_Cuota_CFDI / 100);
                            }else{
                                retenidos += ((parseFloat(prod.Cantidad) * parseFloat(prod.Precio)) - parseFloat(prod.Descuento)) * (imp.Tasa_Cuota_CFDI / 100);
                            }
                        }

                        if(imp.Tipo_Factor_CFDI != "Exento"){
                            impuestos += '<p>(<span class="dinero">0</span>) '+imp.Impuesto_CFDI+' <span class="cantidad">'+imp.Tasa_Cuota_CFDI+'</span>%</p>';
                        }else{
                            impuestos += '<p>(<span class="dinero">'+(((parseFloat(prod.Cantidad) * parseFloat(prod.Precio)) - parseFloat(prod.Descuento)) * (imp.Tasa_Cuota_CFDI / 100))+'</span>) '+imp.Impuesto_CFDI+' <span class="cantidad">'+imp.Tasa_Cuota_CFDI+'</span>%</p>';
                        }
                    });
                }

                $("#conceptosCFDI").append(`<tr>
                    <td>`+prod.Clave_ProdServ_CFDI+`</td> 
                    <td>`+prod.Codigo+`</td> 
                    <td>`+prod.Descripcion+`</td>  
                    <td>`+prod.Clave_Unidad_CFDI+`</td> 
                    <td>`+prod.Nombre_Presentacion+`</td> 
                    <td><span class="cantidad">`+prod.Cantidad+`</span></td> 
                    <td><span class="dinero">`+prod.Precio+`</span></td>  
                    <td><span class="dinero">`+(parseFloat(prod.Cantidad) * parseFloat(prod.Precio))+`</span></td>
                    <td><span class="dinero">`+prod.Descuento+`</span></td>
                    <td>`+impuestos+`</td> 
                    <td><span class="dinero">`+prod.Total+`</span></td>
                </tr>`);  

                subtotal += (parseFloat(prod.Cantidad) * parseFloat(prod.Precio));
                descuentos += parseFloat(prod.Descuento);
                total += parseFloat(prod.Total);
            });

            $("#subtotalCFDI").html(subtotal);
            $("#totalDescuentoCFDI").html(descuentos);
            $("#impuetosTrasCFDI").html(trasladados);
            $("#impuestosRetCFDI").html(retenidos);
            $("#totalCFDI").html(total);

            moneda();
            $("#modalFacturar").modal('show');
        }
    })
    .fail(function() {
        console.log("Error ajax");
    })
    .always(function() {
        $("#carga").hide();
    });    
}

jQuery(document).ready(function($) {
    //formFacturar
	$(document).on('click', '.bFacturar', function() {
        facturarVenta($.trim($(this).attr('attrID')));
    }); 
    
    $(document).on('click', '.bImprimirFacPDF', function() {
        window.open("controladores/pdf/factura.php?id="+$.trim($(this).attr('attrID')));
    }); 
    
    $(document).on('click', '.bImprimirFacXml', function() {
       window.open("controladores/xml/xml.php?id="+$.trim($(this).attr('attrID')));
    });

    $(document).on('submit', '#formFoliosCFDI', function(event) {
        event.preventDefault();
        $("#verFoliosCFDI").append(`<tr>
            <td>`+$.trim($("#uuidCFDI").val())+`</td>
            <td><button type="button" class="btn btn-danger btn-sm bQuitarUUID"><i class="fas fa-trash"></i></button></td>
        </tr>`);

        if($("#verFoliosCFDI").children('tr').length == 0){
            $("#relacionCFDI").val();
            $("#relacionCFDI").prop('disabled', true);
        }else{
            $("#relacionCFDI").prop('disabled', false);
        }

        document.getElementById('formFoliosCFDI').reset();
    });

    $(document).on('click', '#bAgergarFolioCFDI', function() {
        $("#bGuardarUUID").trigger('click');
    });

    $(document).on('click', '.bQuitarUUID', function() {
        $(this).parent().parent().remove();

        if($("#verFoliosCFDI").children('tr').length == 0){
            $("#relacionCFDI").val("");
            $("#relacionCFDI").prop('disabled', true);
        }else{
            $("#relacionCFDI").prop('disabled', false);
        }
    });
});