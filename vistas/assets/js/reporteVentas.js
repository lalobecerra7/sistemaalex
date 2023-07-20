function v_reporteVentas() {
	var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaFinVenta").val(today);
    
    now.setDate(now.getDate() - 30);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaInicioVenta").val(today);

	chartVentas();
}

jQuery(document).ready(function($) {
	
	/*$(document).on('click', '#ReimprimirTicketCaja', function() {
		var iddetalle = $(this).attr("attrid");
		var sucursal = $(this).attr("sucursal");
		var altura=50;
        var anchura=310;
        var y= parseInt((window.screen.height/2)-(altura/2));
        var x= parseInt((window.screen.width/2)-(anchura/2));
		window.open("controladores/ticketCaja.php?id="+iddetalle+"&idsucursal="+sucursal, '_blank', "width="+anchura+", height="+altura+", top="+y+", left="+x+"");
	});*/

});

function chartVentas() {
	if(new Date($("#fechaInicioVenta").val()) > new Date($("#fechaFinVenta").val())){
		Swal.fire({
			icon: 'warning',
			title: 'Oops...',
			text: 'La fecha de inicio debe ser menor a la fecha final.'
		});
	}else{
		var padre = $("#chartdivVentas").parent();
		$("#chartdivVentas").remove();
		padre.html('<div class="col-12" id="chartdivVentas" style="height: 500px;"></div>');

		var data = "metodo=consultar&accion=reporteVentas&fechaInicio="+$("#fechaInicioVenta").val()+"&fechaFin="+$("#fechaFinVenta").val();

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
			  panX: false,
			  panY: false,
			  wheelX: "panX",
			  wheelY: "zoomX",
			  layout: root.verticalLayout
			}));

			// Data
			var colors = chart.get("colors");

			var data = [];
			datos.forEach(dato => {
				data.push({
				 	country: dato.Usuario,
				  	visits: parseFloat(dato.Total),
				  	icon: dato.Foto,
				  	columnSettings: { fill: colors.next() }
				});
			});

			// Create axes
			// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
			var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
			  categoryField: "country",
			  renderer: am5xy.AxisRendererX.new(root, {
			    minGridDistance: 30
			  }),
			  bullet: function (root, axis, dataItem) {
			    return am5xy.AxisBullet.new(root, {
			      location: 0.5,
			      sprite: am5.Picture.new(root, {
			        width: 35,
			        height: 35,
			        centerY: am5.p50,
			        centerX: am5.p50,
			        src: dataItem.dataContext.icon
			      })
			    });
			  }
			}));

			xAxis.get("renderer").labels.template.setAll({
			  paddingTop: 20
			});

			xAxis.data.setAll(data);

			var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
			  renderer: am5xy.AxisRendererY.new(root, {})
			}));

			// Add series
			// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
			var series = chart.series.push(am5xy.ColumnSeries.new(root, {
			  xAxis: xAxis,
			  yAxis: yAxis,
			  valueYField: "visits",
			  categoryXField: "country"
			}));

			series.columns.template.setAll({
			  tooltipText: "{categoryX}: {valueY}",
			  tooltipY: 0,
			  strokeOpacity: 0,
			  templateField: "columnSettings"
			});

			series.data.setAll(data);

			// Make stuff animate on load
			// https://www.amcharts.com/docs/v5/concepts/animations/
			series.appear();
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
}
