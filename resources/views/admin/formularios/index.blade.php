@extends('layouts.admin')
@section('title')
    Inicio
@endsection

@section('side-nav')
    <x-side-nav tab="2" selected='2'/>

@endsection

@section('content')
<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Datos de Votacion - {{$form->title}}</h2>
                    <h5 class="text-white op-7 mb-2">{{$form->description}}</h5>
                    <h3 class="mr-2 text-white pb-2 fw-bold">Estado ({{$form->status == 1 ? 'Abierto' : 'Cerrado'}})</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-6">
                <div class="card full-height">
                    <div class="card-body" id="card1">
                        <div class="card-title">Estadísticas generales</div>
                        <div class="card-category">Información sobre estadísticas en el sistema acerca de las votaciones</div>
                        <div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-1"></div>
                                <h6 class="fw-bold mt-3 mb-0">Votos registrados</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-2"></div>
                                <h6 class="fw-bold mt-3 mb-0">Votos faltantes</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-3"></div>
                                <h6 class="fw-bold mt-3 mb-0">Porcentaje de votación</h6>
                            </div>
                        </div>                        
                    </div>
                    <button class=" btn btn-info" onclick="genReport(1)">Generar reporte</button>
                </div>
            </div>
            @can('rol-admin')
            <div class="col-md-6">
                <div class="card full-height">
                    <div class="card-header">
                        <div class="card-title">Resúmen de resultados</div>
                    </div>
                    <div class="card-body" id="card2">
                        <div class="chart-container">
                            <canvas id="pieChart" style="width: 50%; height: 50%"></canvas>
                        </div>
                    </div>
                    <button class=" btn btn-info" onclick="prinCanvas('pieChart')">Generar reporte</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Resúmen de votos</div>
                    </div>
                    <div class="card-body" id="card3">
                        <div class="chart-container">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                    <button class=" btn btn-info" onclick="prinCanvas('barChart')">Generar reporte</button>
                </div>
            </div>
            @endcan
        </div>
    </div>   
</div>
@endsection
@section('script')
<script>
    let votes = @json($votes);
    let total = @json($total);
    let summary = @json($summary);
    let list_elections = @json($list_elections);

    let summary_values = list_elections.map( l => {
        var my_total = summary.find( s => {
            return s.response === l
        })

        return my_total === undefined ? 0 : Math.round(((my_total.total)/votes)*100, 2)
    });

    let summary_bar_values = list_elections.map( l => {
        var my_total = summary.find( s => {
            return s.response === l
        })

        return my_total === undefined ? 0 : my_total.total
    });

    let colors = ["#1d7af3","#f3545d","#fdaf4b","#adaf5b", "#cdaf5b", "#adaf5b", "#tdaf5b"];
    Circles.create({
        id:'circles-1',
        radius:45,
        value:votes,
        maxValue:total,
        width:7,
        text: votes,
        colors:['#f1f1f1', '#FF9E27'],
        duration:400,
        wrpClass:'circles-wrp',
        textClass:'circles-text',
        styleWrapper:true,
        styleText:true
    })

    Circles.create({
        id:'circles-2',
        radius:45,
        value:parseInt(total) - parseInt(votes),
        maxValue:parseInt(total),
        width:7,
        text: `${parseInt(total) - parseInt(votes)}`,
        colors:['#f1f1f1', '#2BB930'],
        duration:400,
        wrpClass:'circles-wrp',
        textClass:'circles-text',
        styleWrapper:true,
        styleText:true
    })

    Circles.create({
        id:'circles-3',
        radius:45,
        value:(votes/total)*100,
        maxValue:100,
        width:10,
        text: `${Math.round((votes/total)*100)}%`,
        colors:['#f1f1f1', '#F25961'],
        duration:400,
        wrpClass:'circles-wrp',
        textClass:'circles-text',
        styleWrapper:true,
        styleText:true
    })

    var pieChart = document.getElementById('pieChart').getContext('2d'),
    barChart = document.getElementById('barChart').getContext('2d')

    var myPieChart = new Chart(pieChart, {
			type: 'pie',
			data: {
				datasets: [{
					data: summary_values,
					backgroundColor :colors.slice(0, list_elections.count),
					borderWidth: 0
				}],
				labels: list_elections
			},
			options : {
				responsive: true, 
				maintainAspectRatio: false,
				legend: {
					position : 'bottom',
					labels : {
						fontColor: 'rgb(154, 154, 154)',
						fontSize: 11,
						usePointStyle : true,
						padding: 20
					}
				},
				pieceLabel: {
					render: 'percentage',
					fontColor: 'white',
					fontSize: 14,
				},
				tooltips: false,
				layout: {
					padding: {
						left: 20,
						right: 20,
						top: 20,
						bottom: 20
					}
				}
			}
	})

    var myBarChart = new Chart(barChart, {
			type: 'bar',
			data: {
				labels: list_elections,
				datasets : [{
					label: "Votos",
					backgroundColor: 'rgb(23, 125, 255)',
					borderColor: 'rgb(23, 125, 255)',
					data: summary_bar_values,
				}],
			},
			options: {
				responsive: true, 
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				},
			}
	});
</script>
<script>
    function genReport(params) {       
        var contenido= document.getElementById('card'+params).innerHTML;
        var contenido= document.getElementById('pieChart').toDataURL();
		var ventana = window.open('', 'PRINT', 'height=400,width=600');
		ventana.document.write('<html><head><title>' + document.title + '</title>');
		ventana.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">'); //Aquí agregué la hoja de estilos
        ventana.document.write('<link rel="stylesheet" href="/assets/css/atlantis.min.css">'); 
        ventana.document.write('<link rel="stylesheet" href="/assets/css/styles.css">');
		ventana.document.write('</head><body >');        
		ventana.document.write(contenido);
		ventana.document.write('</body>');
        ventana.document.write('<script src="/assets/js/plugin/chart.js/chart.min.js"></'+'script>');
        ventana.document.write('<script src="/assets/js/plugin/chart-circle/circles.min.js"></'+'script>');
        ventana.document.write('<script src="/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></'+'script>');
        ventana.document.write('<script src="/assets/js/atlantis.min.js"></'+'script>');
        ventana.document.write('</html>');
		ventana.document.close();
		ventana.focus();
		ventana.onload = function() {
			ventana.print();
			ventana.close();
		};
		return true;
    }
    function prinCanvas(params) {
        const dataUrl = document.getElementById(params).toDataURL(); 
        let windowContent = '<!DOCTYPE html>';
        windowContent += '<html>';
        windowContent += '<head>';
        windowContent +='<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">';
        windowContent +='</head>';
        windowContent += '<body>';
        windowContent += '<div class=" container-fluid  text-center">';
        windowContent += '<div class="row">';        
        windowContent += '<div class="col-12 mt-3   text-center">';
        windowContent += '<h2>Reporte</h2>';
        windowContent += '<div class="chart-container">';
        windowContent += '<img src="' + dataUrl + '" class=" img-fluid   text-center">';
        windowContent += '</div>';
        windowContent += '</div>';
        windowContent += '</div>';
        windowContent += '</div>';        
        windowContent += '</body>';
        windowContent += '</html>';
        const printWin = window.open('', '', 'width=' + screen.availWidth + ',height=' + screen.availHeight);
        printWin.document.open();
        printWin.document.write(windowContent); 

        printWin.document.addEventListener('load', function() {
            printWin.focus();
            printWin.print();
            printWin.document.close();
            printWin.close();            
        }, true);
    }
</script>
@endsection
