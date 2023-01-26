function v_reporteProductos() {
	var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaFinProd").val(today);
    
    now.setDate(now.getDate() - 30);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaInicioProd").val(today);

	chartProductos();
}

function v_reporteClientes() {
	var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaFinCliente").val(today);
    
    now.setDate(now.getDate() - 30);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaInicioCliente").val(today);

	chartClientes();
}

function v_reporteVentas() {
	var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaFinVenta").val(today);
    
    now.setDate(now.getDate() - 365);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaInicioVenta").val(today);

	chartVentas();
}

function v_reporteCompras() {
	var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaFinCompra").val(today);
    
    now.setDate(now.getDate() - 365);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month)+"-"+(day);

    $("#fechaInicioCompra").val(today);

	chartCompras();
}

function v_reporteFinanzas() {
	var now = new Date();
    var day = now.getDate().toString().padStart(2, '0');
    var month = (now.getMonth() + 1).toString().padStart(2, '0');
    var today = now.getFullYear()+"-"+(month);

    $("#fechaFinFinanzas").val(today);
    
    now.setDate(now.getDate() - 365);
    day = now.getDate().toString().padStart(2, '0');
    month = (now.getMonth() + 1).toString().padStart(2, '0');
    today = now.getFullYear()+"-"+(month);

    $("#fechaInicioFinanzas").val(today);

    tablaFinanzas();
}

function chartProductos() {
	if(new Date($("#fechaInicioProd").val()) > new Date($("#fechaFinProd").val())){
		Swal.fire({
			icon: 'warning',
			title: 'Oops...',
			text: 'La fecha de inicio debe ser menor a la fecha final.'
		});
	}else{
		var padre = $("#chartdivProductos").parent();
		$("#chartdivProductos").remove();
		padre.html('<div class="col-12" id="chartdivProductos" style="height: 500px;"></div>');

		var data = "metodo=consultar&accion=reportes&tipo=productos&fechaInicio="+$("#fechaInicioProd").val()+"&fechaFin="+$("#fechaFinProd").val();

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
			datos = JSON.parse($.trim(res));
			//console.log(datos);
			
			am5.ready(function() {

			// Create root element
			// https://www.amcharts.com/docs/v5/getting-started/#Root_element
			var root = am5.Root.new("chartdivProductos");
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
				 	country: dato.Producto,
				  	visits: parseFloat(dato.Cantidad),
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

function chartClientes() {
	if(new Date($("#fechaInicioCliente").val()) > new Date($("#fechaFinCliente").val())){
		Swal.fire({
			icon: 'warning',
			title: 'Oops...',
			text: 'La fecha de inicio debe ser menor a la fecha final.'
		});
	}else{
		var padre = $("#chartdivClientes").parent();
		$("#chartdivClientes").remove();
		padre.html('<div class="col-12" id="chartdivClientes" style="height: 500px;"></div>');

		var data = "metodo=consultar&accion=reportes&tipo=clientes&fechaInicio="+$("#fechaInicioCliente").val()+"&fechaFin="+$("#fechaFinCliente").val();

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
			var root = am5.Root.new("chartdivClientes");
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
				 	country: dato.Cliente,
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
		padre.html('<div class="col-12" id="chartdivVentas" style="height: 600px;"></div>');

		var data = "metodo=consultar&accion=reportes&tipo=ventas&fechaInicio="+$("#fechaInicioVenta").val()+"&fechaFin="+$("#fechaFinVenta").val();

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
			  baseInterval: { timeUnit: "day", count: 1 },
			  renderer: am5xy.AxisRendererX.new(root, {}),
			  tooltip: am5.Tooltip.new(root, {})
			}));

			var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
			  renderer: am5xy.AxisRendererY.new(root, {})
			}));

			// Add series
			// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
			var series = chart.series.push(am5xy.LineSeries.new(root, {
			  name: "Series",
			  xAxis: xAxis,
			  yAxis: yAxis,
			  valueYField: "value",
			  valueXField: "date",
			  tooltip: am5.Tooltip.new(root, {
			    labelText: "{valueY}"
			  })
			}));


			// Add scrollbar
			// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
			var scrollbar = chart.set("scrollbarX", am5xy.XYChartScrollbar.new(root, {
			  orientation: "horizontal",
			  height: 60
			}));

			var sbDateAxis = scrollbar.chart.xAxes.push(am5xy.DateAxis.new(root, {
			  baseInterval: {
			    timeUnit: "day",
			    count: 1
			  },
			  renderer: am5xy.AxisRendererX.new(root, {})
			}));

			var sbValueAxis = scrollbar.chart.yAxes.push(
			  am5xy.ValueAxis.new(root, {
			    renderer: am5xy.AxisRendererY.new(root, {})
			  })
			);

			var sbSeries = scrollbar.chart.series.push(am5xy.LineSeries.new(root, {
			  valueYField: "value",
			  valueXField: "date",
			  xAxis: sbDateAxis,
			  yAxis: sbValueAxis
			}));

			
			var data = [];
			datos.forEach(dato => {
				data.push({ 
					date: new Date(dato.Fecha).getTime(), 
					value: parseFloat(dato.Total) 
				});
			});

			series.data.setAll(data);
			sbSeries.data.setAll(data);

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
}

function chartCompras() {
	if(new Date($("#fechaInicioCompra").val()) > new Date($("#fechaFinCompra").val())){
		Swal.fire({
			icon: 'warning',
			title: 'Oops...',
			text: 'La fecha de inicio debe ser menor a la fecha final.'
		});
	}else{
		var padre = $("#chartdivCompras").parent();
		$("#chartdivCompras").remove();
		padre.html('<div class="col-12" id="chartdivCompras" style="height: 600px;"></div>');

		var data = "metodo=consultar&accion=reportes&tipo=compras&fechaInicio="+$("#fechaInicioCompra").val()+"&fechaFin="+$("#fechaFinCompra").val();

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
			var root = am5.Root.new("chartdivCompras");
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
			  baseInterval: { timeUnit: "day", count: 1 },
			  renderer: am5xy.AxisRendererX.new(root, {}),
			  tooltip: am5.Tooltip.new(root, {})
			}));

			var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
			  renderer: am5xy.AxisRendererY.new(root, {})
			}));

			// Add series
			// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
			var series = chart.series.push(am5xy.LineSeries.new(root, {
			  name: "Series",
			  xAxis: xAxis,
			  yAxis: yAxis,
			  valueYField: "value",
			  valueXField: "date",
			  tooltip: am5.Tooltip.new(root, {
			    labelText: "{valueY}"
			  })
			}));


			// Add scrollbar
			// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
			var scrollbar = chart.set("scrollbarX", am5xy.XYChartScrollbar.new(root, {
			  orientation: "horizontal",
			  height: 60
			}));

			var sbDateAxis = scrollbar.chart.xAxes.push(am5xy.DateAxis.new(root, {
			  baseInterval: {
			    timeUnit: "day",
			    count: 1
			  },
			  renderer: am5xy.AxisRendererX.new(root, {})
			}));

			var sbValueAxis = scrollbar.chart.yAxes.push(
			  am5xy.ValueAxis.new(root, {
			    renderer: am5xy.AxisRendererY.new(root, {})
			  })
			);

			var sbSeries = scrollbar.chart.series.push(am5xy.LineSeries.new(root, {
			  valueYField: "value",
			  valueXField: "date",
			  xAxis: sbDateAxis,
			  yAxis: sbValueAxis
			}));

			
			var data = [];
			datos.forEach(dato => {
				data.push({ 
					date: new Date(dato.Fecha).getTime(), 
					value: parseFloat(dato.Total) 
				});
			});

			series.data.setAll(data);
			sbSeries.data.setAll(data);

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
}

function tablaFinanzas() {
	if(new Date($("#fechaInicioFinanzas").val()+'-01') > new Date($("#fechaFinFinanzas").val()+'-01')){
		Swal.fire({
			icon: 'warning',
			title: 'Oops...',
			text: 'La fecha de inicio debe ser menor a la fecha final.'
		});
	}else{
		var data = "metodo=consultar&accion=reportes&tipo=finanzas&fechaInicio="+$("#fechaInicioFinanzas").val()+"&fechaFin="+$("#fechaFinFinanzas").val();

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
			console.log(datos);

			$("#chartdivFinanzas").html('');
			var fecha = new Date($("#fechaInicioFinanzas").val()+'-15');
			var month = '';
			var today = '';
			var meses = {'01': 'Enero', '02': 'Febrero', '03': 'Marzo', '04': 'Abril', '05': 'Mayo', '06': 'Junio', '07': 'Julio', '08': 'Agosto', '09': 'Septiembre', '10': 'Octubre', '11': 'Noviembre', '12': 'Diciembre'};
			var totalC = 0, totalV = 0, totalD = 0;

			while (fecha <= new Date($("#fechaFinFinanzas").val()+'-15')){
				var compras = 0, ventas = 0;
				month = (fecha.getMonth() + 1).toString().padStart(2, '0');
    			today = meses[month]+' '+fecha.getFullYear();

    			for (var i = datos['Compras'].length - 1; i >= 0; i--) {
    				if(datos['Compras'][i]['Fecha'] == fecha.getFullYear()+'-'+(fecha.getMonth() + 1).toString().padStart(2, '0')){
    					compras = parseFloat(datos['Compras'][i]['Total']);
    					totalC += parseFloat(datos['Compras'][i]['Total']);
    					break;
    				}
    			}

    			for (var i = datos['Ventas'].length - 1; i >= 0; i--) {
    				if(datos['Ventas'][i]['Fecha'] == fecha.getFullYear()+'-'+(fecha.getMonth() + 1).toString().padStart(2, '0')){
    					ventas = parseFloat(datos['Ventas'][i]['Total']);
    					totalV += parseFloat(datos['Ventas'][i]['Total']);
    					break;
    				}
    			}

    			var color = 'green';
    			if((ventas - compras) < 0){
    				color = 'red';
    			}

    			$("#chartdivFinanzas").append(`<tr>
    				<td>`+today+`</td>
    				<td><span class="dinero">`+compras+`</span></td>
    				<td><span class="dinero">`+ventas+`</span></td>
    				<td><b class="dinero" style="color: `+color+`">`+(ventas - compras)+`</b></td>
    			</tr>`);

    			totalD += (ventas - compras);
    			fecha.setMonth(fecha.getMonth() + 1);
			}

			var color = 'green';
    		if(totalD < 0){
    			color = 'red';
    		}

			$("#totalCompras").html(totalC);
			$("#totalVentas").html(totalV);
			$("#totalDiferencia").html(totalD);
			$("#totalDiferencia").css('color', color);

			moneda();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			$("#carga").hide();
		});
	}
}

jQuery(document).ready(function($) {
	
	$(document).on('change', '#fechaInicioProd', function() {
		chartProductos();
	});
	
	$(document).on('change', '#fechaFinProd', function() {
		chartProductos();
	});

	$(document).on('change', '#fechaInicioCliente', function() {
		chartClientes();
	});
	
	$(document).on('change', '#fechaFinCliente', function() {
		chartClientes();
	});

	$(document).on('change', '#fechaInicioVenta', function() {
		chartVentas();
	});
	
	$(document).on('change', '#fechaFinVenta', function() {
		chartVentas();
	});

	$(document).on('change', '#fechaInicioCompra', function() {
		chartCompras();
	});
	
	$(document).on('change', '#fechaFinCompra', function() {
		chartCompras();
	});

	$(document).on('change', '#fechaInicioFinanzas', function() {
		tablaFinanzas();
	});
	
	$(document).on('change', '#fechaFinFinanzas', function() {
		tablaFinanzas();
	});
});