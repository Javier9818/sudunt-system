<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de resultados - SUDUNT</title>
    <!-- CSS Files -->
	<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/atlantis.min.css">
	<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/styles.css">


	<!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/demo.css">
    <style>
        .firma{
            border-top: solid 1px black;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="page-inner mt-5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <button class="btn btn-warning" onclick="imprimirElemento('card_resultados')">Imprimir</button>
                    <div class="card-body px-5" id="card_resultados">
                        <h1 class="text-center">Resultados de proceso electoral SUDUNT</h1>
                        <h2 class="text-center">{{ $fecha }}</h2>
                        
                        <b>Resúmen de sufragio</b>
                        <ul>
                            <li> <b>Total de docentes empadronados: </b>{{$total}}</li>
                            <li> <b>Total votos registrados: </b>{{$votes}}</li>
                        </ul>

                        <b class="mt-3">Resultados</b>
                        <ul>
                            @foreach($summary as $item)
                                <li> <b>{{$item->response}}: </b> {{$item->total}} votos</li>
                            @endforeach
                        </ul>

                        <div class="row mt-5 justify-content-around">
                            <div class="col-2 firma text-center p-1">
                                <b>Presidente general de SUDUNT</b>
                            </div>
                            <div class="col-2 firma text-center p-1">
                                <b>Personero Lista 1</b>
                            </div>
                            <div class="col-2 firma text-center p-1">
                                <b>Personero Lista 2</b>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>   
</div>


<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/core/popper.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/core/bootstrap.min.js"></script>

	<!-- jQuery UI -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


	<!-- Chart JS -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Bootstrap Notify -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- jQuery Vector Maps -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

	<!-- Sweet Alert -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Atlantis JS -->
    <script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/atlantis.min.js"></script>
    
    <script>
    let votes = @json($votes);
    let total = @json($total);
    let summary = @json($summary);
    let list_elections = @json($list_elections);
    let user = @json(Auth::user());

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
    
    // Circles.create({
    //     id:'circles-1',
    //     radius:45,
    //     value:votes,
    //     maxValue:total,
    //     width:7,
    //     text: votes,
    //     colors:['#f1f1f1', '#FF9E27'],
    //     duration:400,
    //     wrpClass:'circles-wrp',
    //     textClass:'circles-text',
    //     styleWrapper:true,
    //     styleText:true
    // })

    // Circles.create({
    //     id:'circles-2',
    //     radius:45,
    //     value:parseInt(total) - parseInt(votes),
    //     maxValue:parseInt(total),
    //     width:7,
    //     text: `${parseInt(total) - parseInt(votes)}`,
    //     colors:['#f1f1f1', '#2BB930'],
    //     duration:400,
    //     wrpClass:'circles-wrp',
    //     textClass:'circles-text',
    //     styleWrapper:true,
    //     styleText:true
    // })

    // Circles.create({
    //     id:'circles-3',
    //     radius:45,
    //     value:(votes/total)*100,
    //     maxValue:100,
    //     width:10,
    //     text: `${Math.round((votes/total)*100)}%`,
    //     colors:['#f1f1f1', '#F25961'],
    //     duration:400,
    //     wrpClass:'circles-wrp',
    //     textClass:'circles-text',
    //     styleWrapper:true,
    //     styleText:true
    // })

    // var pieChart = document.getElementById('pieChart').getContext('2d'),
    // barChart = document.getElementById('barChart').getContext('2d')

    // var myPieChart = new Chart(pieChart, {
	// 		type: 'pie',
	// 		data: {
	// 			datasets: [{
	// 				data: summary_values,
	// 				backgroundColor :colors.slice(0, list_elections.count),
	// 				borderWidth: 0
	// 			}],
	// 			labels: list_elections
	// 		},
	// 		options : {
	// 			responsive: true, 
	// 			maintainAspectRatio: false,
	// 			legend: {
	// 				position : 'bottom',
	// 				labels : {
	// 					fontColor: 'rgb(154, 154, 154)',
	// 					fontSize: 11,
	// 					usePointStyle : true,
	// 					padding: 20
	// 				}
	// 			},
	// 			pieceLabel: {
	// 				render: 'percentage',
	// 				fontColor: 'white',
	// 				fontSize: 14,
	// 			},
	// 			tooltips: false,
	// 			layout: {
	// 				padding: {
	// 					left: 20,
	// 					right: 20,
	// 					top: 20,
	// 					bottom: 20
	// 				}
	// 			}
	// 		}
	// })

    // var myBarChart = new Chart(barChart, {
	// 		type: 'bar',
	// 		data: {
	// 			labels: list_elections,
	// 			datasets : [{
	// 				label: "Votos",
	// 				backgroundColor: 'rgb(23, 125, 255)',
	// 				borderColor: 'rgb(23, 125, 255)',
	// 				data: summary_bar_values,
	// 			}],
	// 		},
	// 		options: {
	// 			responsive: true, 
	// 			maintainAspectRatio: false,
	// 			scales: {
	// 				yAxes: [{
	// 					ticks: {
	// 						beginAtZero:true
	// 					}
	// 				}]
	// 			},
	// 		}
	// });

    function imprimirElemento(id){
        var elemento = document.getElementById(id) 
        var ventana = window.open('', 'PRINT');
        ventana.document.write('<html><head><title>' + document.title + '</title>');
        ventana.document.write(`<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/atlantis.min.css">
        <link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/styles.css">
        <link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/demo.css">
        <style>
            .firma{
                border-top: solid 1px black;
            }
        </style>`);
            ventana.document.write('</head><body >');
            ventana.document.write(elemento.innerHTML);
            ventana.document.write('</body></html>');
            ventana.document.close();
            ventana.focus();
            ventana.onload = function() {
                ventana.print();
                ventana.close();
            };
            return true;
        }
</script>
<script>
    function genReport(params) {       
        var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        var f=new Date();
        var hora = f.getHours() + ':' + f.getMinutes() + ':' + f.getSeconds();
        var contenido= document.getElementById('card'+params).innerHTML;
        // var contenido= document.getElementById('pieChart').toDataURL();
		var ventana = window.open('', 'PRINT', 'height=400,width=600');
		ventana.document.write('<html><head><title>' + document.title + '</title>');
		ventana.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">'); //Aquí agregué la hoja de estilos
        ventana.document.write('<link rel="stylesheet" href="/assets/css/atlantis.min.css">'); 
        ventana.document.write('<link rel="stylesheet" href="/assets/css/styles.css">');
		ventana.document.write('</head><body >');        
		ventana.document.write(contenido);
        ventana.document.write(`<hr>`);
        ventana.document.write(`<p><b>Usuario: </b>${user.email}</p>`);
        ventana.document.write(`<p><b>Rol: </b>${user.is_admin ? 'Administrador' : 'Personero'}</p>`);
        ventana.document.write(`<p><b>Fecha y hora: </b>${f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear() + " - " + hora}</p>`);
        ventana.document.write(`<hr>`);
        ventana.document.write(`<b>Sistema de sufragio SUDUNT 2020</b>`);
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
        var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        var f=new Date();
        var hora = f.getHours() + ':' + f.getMinutes() + ':' + f.getSeconds();
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
        windowContent += `<hr>
        <p><b>Usuario: </b>${user.email}</p>
        <p><b>Rol: </b>${user.is_admin ? 'Administrador' : 'Personero'}</p>
        <p><b>Fecha y hora: </b>${f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear() + " - " + hora}</p>
        <hr>
        <b>Sistema de sufragio SUDUNT 2020</b>`;       
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
</body>
</html>
