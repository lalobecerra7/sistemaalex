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
        console.log($.trim(res));
        
        if($.trim(res) == "Error 2 Datos Facturacion"){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Por favor llena todos los datos del menú facturación 4.0.'
            }); 
        }else if($.trim(res) == "Error 5 Tabla datos generales"){
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

            //agregar productos/conceptos

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
    
    /*$(document).on('click', '.bImprimirFacPDF', function() {
    
    }); 
    
    $(document).on('click', '.bImprimirFacXml', function() {
       
    }); */
});