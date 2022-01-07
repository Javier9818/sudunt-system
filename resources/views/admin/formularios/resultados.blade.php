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
        body{
            font-family: Arial, Helvetica, sans-serif !important;
            color: black !important;
        }
        
        .firma{
            /*border-top: solid 1px black;*/
            margin-bottom: 1.8em;
        }
        h1{
            color: black !important;
        }
        h3{
            font-size: 1.2em !important;
        }
        h4{
            font-size: 1em !important;
        }
        h5{
            font-size: 0.8em !important;
        }
        p{
            text-align: justify !important;
            font-size: 0.8em;
            color: black !important;
        }
        .cuadro{
            height: 40px !important; 
            border: black 1px solid;
        }
    </style>
</head>
<body>
<!-- <div class="content">
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
</div> -->
       <div class="container">
       <button class="btn btn-success m-3" onclick="generarPDF()">Descargar resultados</button>
        <div class="card p-4">
            @if( $votes >= (($total / 2) + 1 ))
                @if($empate_listas == true)
                    <h1><b>Se detectó un empate entre votos {{$empate_1}} y votos {{$empate_2}}</b></h1>    
                @elseif($lista_ganador == 'Blanco')
                    <h1><b>La mayor cantidad de votos fueron registrados en Blanco ({{$ganador->total ?? '0'}} votos)</b></h1>
                @elseif($lista_ganador == 'Nulo o Viciado')
                    <h1><b>La mayor cantidad de votos fueron registrados como nulos o viciados ({{$ganador->total ?? '0'}} votos)</b></h1>
                @else
                    <h1>La lista ganadora con {{$ganador->total ?? '0'}} votos es <b>{{$lista_ganador}} - {{$nombre_lista_ganador}}</b>. </h1>
                @endif
            @else
                <h1><b>Con {{$votes}} votos, no se superó el 50% + 1 de votos válidamente emitidos.</b></h1>
            @endif
        </div>
       <div class="row">
		   {{-- 
            <!-- <div class="col-md-12 m-4 card p-4" style="font-size: 1.2em;">
                <div class="row">
                    <div class="col-5"><h4><b>N° votos docentes activos</b></h4></div>
                    <div class="col-6"><h4><b>.......................................................</b></h4></div>
                    <div class="col-1"><h4><b>{{$votes_teachers_summary[0]->activos}}</b></h4></div>
                </div>
                <div class="row">
                    <div class="col-5"><h4><b>N° votos docentes cesantes</b></h4></div>
                    <div class="col-6"><h4><b>.......................................................</b></h4></div>
                    <div class="col-1"><h4><b>{{$votes_teachers_summary[0]->total - $votes_teachers_summary[0]->activos}}</b></h4></div>
                </div>
            </div> -->
			--}}
            <div class="col-md-12 m-4 card p-4" style="font-size: 1.2em;">
                @foreach($list_elections as $lista)
                <?php  $found = false; $lista_found = ''; ?>
                    @foreach($summary as $sum)
                        @if($sum->response == $lista ) 
                            <?php  $found = true; $lista_found = $sum;?>
                        @endif
                    @endforeach
                    @if($found)
                    <div class="row">
                        <div class="col-5"><h4><b>N° Votos {{$lista_found->response}}</b></h4></div>
                        <div class="col-6"><h4><b>.......................................................</b></h4></div>
                        <div class="col-1"><h4><b>{{$lista_found->total}}</b></h4></div>
                    </div>
                    @else
                        <div class="row">
                            <div class="col-5"><h4><b>N° Votos {{$lista}}</b></h4></div>
                            <div class="col-6"><h4><b>.......................................................</b></h4></div>
                            <div class="col-1"><h4><b>0</b></h4></div>
                        </div>      
                    @endif
                @endforeach
            </div>
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
                    <button class=" btn btn-info">Resúmen de resultados</button>
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
                    <button class=" btn btn-info">Resúmen de votos</button>
                </div>
            </div>
            
       </div>
       </div>
       <div class="d-none">
            @include('admin.formularios.pages.page_1')
            @include('admin.formularios.pages.page_2')
       </div>
        <!-- @include('admin.formularios.pages.page_3')
        @include('admin.formularios.pages.page_4')
        @include('admin.formularios.pages.page_5')
        @include('admin.formularios.pages.page_6') -->


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

	<!-- Datatables -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Atlantis JS -->
    <script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/atlantis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js" integrity="sha512-pdCVFUWsxl1A4g0uV6fyJ3nrnTGeWnZN2Tl/56j45UvZ1OMdm9CIbctuIHj+yBIRTUUyv6I9+OivXj4i0LPEYA==" crossorigin="anonymous"></script>
    
    <script>
    let votes = @json($votes);
    let total = @json($total);
    let summary = @json($summary);
    let list_elections = @json($list_elections);
    let user = @json(Auth::user());
    let colors = ["#1d7af3","#f3545d","#fdaf4b","#adaf5b", "#cdaf5b", "#adaf5b", "#tdaf5b"];
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
    // $( document ).ready(function() {
    //     generarPDF()
    //     setTimeout(() => {
    //         window.open('','_parent',''); 
    //         window.close(); 
    //     }, 3000);
    // })
    function generarPDF(){
        var element = document.getElementById('page');
        var page2 = document.getElementById('page2');
        // var page3 = document.getElementById('page3');
        // var page4 = document.getElementById('page4');
        // var page5 = document.getElementById('page5');
        // var page6 = document.getElementById('page6');
        var opt = {
        margin:       0.5,
        filename:     'ACTA DE COMPUTO GENERAL DE VOTOS.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 3, letterRendering: true },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        
        // New Promise-based usage:
        // html2pdf().from(element).set(opt).save();
        //
        html2pdf().from(element).set(opt).toPdf().get('pdf').then(function (pdf) {
            element.innerHTML = page2.innerHTML;
            pdf.addPage();
        }).toContainer().toCanvas().toPdf().save();
        
        // .toContainer().toCanvas().toPdf().get('pdf').then(function (pdf) {
        //     element.innerHTML = page3.innerHTML;
        //     pdf.addPage();
        // }).toContainer().toCanvas().toPdf().get('pdf').then(function (pdf) {
        //     element.innerHTML = page4.innerHTML;
        //     pdf.addPage();
        // }).toContainer().toCanvas().toPdf().get('pdf').then(function (pdf) {
        //     element.innerHTML = page5.innerHTML;
        //     pdf.addPage();
        // }).toContainer().toCanvas().toPdf().get('pdf').then(function (pdf) {
        //     element.innerHTML = page6.innerHTML;
        //     pdf.addPage();
        // }).toContainer().toCanvas().toPdf().save();
        
        // Old monolithic-style usage:
        html2pdf(element, opt);

        
    }

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
</body>
</html>
