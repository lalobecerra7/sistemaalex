function moneda() {
    $(".dinero").each(function(index, el) {
        if(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) < 0){
            $(this).html(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * -1);
            $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
            $(this).html('-'+$(this).html());
        }else{
            $(this).html('$'+new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
        }
    });

    $(".porcentaje").each(function(index, el) {
        if(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) < 0){
            $(this).html(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * -1);
            $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * 100) / 100)+'%');
            $(this).html('-'+$(this).html());
        }else{
            $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('%', '').replace(/,/g, '')) * 100) / 100)+'%');
        }
    });

    $(".cantidad").each(function(index, el) {
        $(this).html(new Intl.NumberFormat('en-US').format(Math.round(parseFloat($(this).html().replace('$', '').replace(/,/g, '')) * 100) / 100));
    });
}


jQuery(document).ready(function($) {
  permisos();
  setTimeout(function(){
    $("#cargarInicio").trigger("click");
  }, 10);

  $(document).on('click', '.cargarVista', function() {
    var nombre = $(this).attr('carga'), titulo = $(this).attr('titulo'), id = $(this).attr('id'), atri = $(this).attr('atri'), pesta = $(this).attr('pesta'); 
    var data = "metodo=cambiar&accion="+nombre+"&atri="+atri+"&pesta="+pesta;
    var itemVista = $(this);
    var icono = $(this).find(".menuIcono");
    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: data,
      beforeSend: function() {
        //$("#carga").show();
      }
    })
    .done(function(res) {
      $("#VerVistas").html(res);
      $(".vistaTitulo").html(titulo);
      $(".cargarVista").removeClass("active");
      $(".menuIcono").removeClass("menuIconoActivo");
      if (itemVista.attr("id") != "cargarHacerVenta") {
        itemVista.addClass("active");
        icono.addClass("menuIconoActivo");
      }
      if (nombre == "v_inicio") {
        chartVentasSemana();
        chartRentasSemana();
      }
      crearDataTable();
      if(typeof window[nombre] === 'function') {
        window[nombre]();
      }

    })
    .fail(function() {
      console.log("Error ajax");
    }).always(function() {
      //$("#carga").hide();
    }); 
  });

  $(document).on("click", "#CerrarSesion", function(){
    cerrarSesion();
  });

 
});

function cerrarSesion(){
  var data="metodo=eliminar&accion=login";
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: data,
  })
  .done(function(res) {
    window.location.reload();
  })
  .fail(function() {
    console.log("Error ajax");
  });
}

function readURL(input,ima) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $(ima).html("<img src='"+e.target.result+"' style='width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;' class='img-thumbnail'><br>");
    }
    reader.readAsDataURL(input.files[0]);
  }
}


function chartVentasSemana() {
   var padre = $("#chartdivVentas").parent();
   $("#chartdivVentas").remove();
   padre.html('<div class="col-12" id="chartdivVentas" style="height: 500px;"></div>');
    var data = "metodo=detalles&accion=ventas&tipo=GraficaInicio";
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
      var datos = JSON.parse($.trim(res));
      //console.log(datos);
            
       am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("chartdivVentas");

            root.locale = am5locales_es_ES;
            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
              am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
              panX: true,
              panY: true,
              wheelX: "panX",
              wheelY: "zoomX",
              pinchZoomX:true
            }));


            // Add cursor
            // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
              behavior: "none"
            }));
            cursor.lineY.set("visible", false);

            // Create axes
            // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
              maxDeviation: 0.2,
              baseInterval: {
                timeUnit: "day",
                count: 1
              },
              renderer: am5xy.AxisRendererX.new(root, {}),
              tooltip: am5.Tooltip.new(root, {})
            }));

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
              renderer: am5xy.AxisRendererY.new(root, {})
            }));


            // Add series
            // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            var series = chart.series.push(am5xy.LineSeries.new(root, {
              minBulletDistance: 10,
              name: "Series",
              xAxis: xAxis,
              yAxis: yAxis,
              valueYField: "value",
              valueXField: "date",
              tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
              })
            }));

            series.fills.template.setAll({
              visible: true,
              fillOpacity: 0.2
            });


            // Add scrollbar
            // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
            chart.set("scrollbarX", am5.Scrollbar.new(root, {
              orientation: "horizontal"
            }));

            var data = [];
            datos.forEach(dato => {
                var fecha = new Date(dato.date);
                data.push({
                    date: fecha.getTime(),
                    value: parseFloat(dato.value),
                });
            });

            // Set data
            series.data.setAll(data);
            
            series.bullets.push(function () {
              return am5.Bullet.new(root, {
                locationX:undefined,
                sprite: am5.Circle.new(root, {
                  radius: 4,
                  fill: series.get("fill")
                })
              })
            });

            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear(1000);
            chart.appear(1000, 100);

            }); // end am5.ready()
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        $("#carga").hide();
    });
    
}

function chartRentasSemana() {
   var padre = $("#chartdivRentas").parent();
   $("#chartdivRentas").remove();
   padre.html('<div class="col-12" id="chartdivRentas" style="height: 500px;"></div>');
    var data = "metodo=detalles&accion=cotizaciones&tipo=GraficaInicio";
    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: data,
      beforeSend: function() {
        $("#carga").show();
      }
    })
    .done(function(res) {
      var datos = JSON.parse($.trim(res));
      //console.log(datos);
            
       am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("chartdivRentas");

            root.locale = am5locales_es_ES;
            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
              am5themes_Animated.new(root)
            ]);


            // Create chart
            // https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
              panX: true,
              panY: true,
              wheelX: "panX",
              wheelY: "zoomX",
              pinchZoomX:true
            }));


            // Add cursor
            // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
              behavior: "none"
            }));
            cursor.lineY.set("visible", false);

            // Create axes
            // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
              maxDeviation: 0.2,
              baseInterval: {
                timeUnit: "day",
                count: 1
              },
              renderer: am5xy.AxisRendererX.new(root, {}),
              tooltip: am5.Tooltip.new(root, {})
            }));

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
              renderer: am5xy.AxisRendererY.new(root, {})
            }));


            // Add series
            // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            var series = chart.series.push(am5xy.LineSeries.new(root, {
              minBulletDistance: 10,
              name: "Series",
              xAxis: xAxis,
              yAxis: yAxis,
              valueYField: "value",
              valueXField: "date",
              tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
              })
            }));

            series.fills.template.setAll({
              visible: true,
              fillOpacity: 0.2
            });


            // Add scrollbar
            // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
            chart.set("scrollbarX", am5.Scrollbar.new(root, {
              orientation: "horizontal"
            }));

            var data = [];
            datos.forEach(dato => {
                var fecha = new Date(dato.date);
                data.push({
                    date: fecha.getTime(),
                    value: parseFloat(dato.value),
                });
            });

            // Set data
            series.data.setAll(data);

            series.bullets.push(function () {
              return am5.Bullet.new(root, {
                locationX:undefined,
                sprite: am5.Circle.new(root, {
                  radius: 4,
                  fill: series.get("fill")
                })
              })
            });

            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear(1000);
            chart.appear(1000, 100);

            }); // end am5.ready()
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        $("#carga").hide();
    });
    
}

function permisos() {
    var data = "metodo=detalles&accion=usuarios&tipo=ConsultarPermisosUsuario";
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: data
    })
    .done(function(res) {
        var resA = JSON.parse(res);
        if(resA.Tipo != "Administrador"){
          //$(".cargarVista").hide();
          //$(".cargarVista[carga='v_usuarios']").remove();
          var cadena = resA.Cadena.split('~');
          var permisos = "";
          //console.log(cadena);

          for (var i = cadena.length - 1; i >= 0; i--) {
            permisos = cadena[i].split(',');
            if(permisos[0] == "v_inicio"){
              if(permisos[1] == '1'){
                setTimeout(function(){
                    $("#cargarInicio").trigger("click");
                }, 10);
              }else{
                $(".cargarVista[carga='"+permisos[0]+"']").remove();
              }
            }else{
              if(permisos[1] == '0'){
                $(".cargarVista[carga='"+permisos[0]+"']").remove();
              }       
            }                
          }
        }else{
          setTimeout(function(){
            $("#cargarInicio").trigger("click");
          }, 10);
        }
    })
    .fail(function() {
        console.log("Error ajax");
    })
    .always(function() {
        //console.log("complete");
    });
}