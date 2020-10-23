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
                    <h2 class="text-white pb-2 fw-bold">Datos de Votacion</h2>
                    <h5 class="text-white op-7 mb-2">Resumen de las votaciones</h5>
                </div>
                {{-- <div class="ml-md-auto py-2 py-md-0">
                    <a href="#" class="btn btn-white btn-border btn-round mr-2">Manage</a>
                    <a href="#" class="btn btn-secondary btn-round">Add Customer</a>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-6">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Estadísticas generales</div>
                        <div class="card-category">Información sobre estadísticas en el sistema acerca de las votaciones</div>
                        <div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-1"></div>
                                <h6 class="fw-bold mt-3 mb-0">Votos Validos</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-2"></div>
                                <h6 class="fw-bold mt-3 mb-0">Votos Faltantes</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="circles-3"></div>
                                <h6 class="fw-bold mt-3 mb-0">Total de Votantes</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Estadísticas de Lista 1 y Lista 2</div>
                        <div class="row py-3">
                            <div class="col-md-4 d-flex flex-column justify-content-around">
                                <div>
                                    <h6 class="fw-bold text-uppercase text-success op-8">Total Lista 1</h6>
                                    <h3 class="fw-bold">3000</h3>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-uppercase text-danger op-8">Total Lista 2</h6>
                                    <h3 class="fw-bold">2000</h3>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="chart-container">
                                    <canvas id="totalIncomeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tipos de votos</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex">
                            <div class="avatar">
                                <img src="../assets/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="flex-1 pt-1 ml-2">
                                <h6 class="fw-bold mb-1">Votos Validos</h6>
                                <small class="text-muted">Descripcion</small>
                            </div>
                            <div class="d-flex ml-auto align-items-center">
                                <h3 class="text-info fw-bold">20%</h3>
                            </div>
                        </div>
                        <div class="separator-dashed"></div>
                        <div class="d-flex">
                            <div class="avatar">
                                <img src="../assets/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="flex-1 pt-1 ml-2">
                                <h6 class="fw-bold mb-1">Votos Invalidos</h6>
                                <small class="text-muted">Descripcion</small>
                            </div>
                            <div class="d-flex ml-auto align-items-center">
                                <h3 class="text-info fw-bold">30%</h3>
                            </div>
                        </div>
                        <div class="separator-dashed"></div>
                        <div class="d-flex">
                            <div class="avatar">
                                <img src="../assets/img/logoproduct3.svg" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="flex-1 pt-1 ml-2">
                                <h6 class="fw-bold mb-1">Votos nulo o vacio</h6>
                                <small class="text-muted">Descripcion</small>
                            </div>
                            <div class="d-flex ml-auto align-items-center">
                                <h3 class="text-info fw-bold">50%</h3>
                            </div>
                        </div>
                        <div class="separator-dashed"></div>
                        <div class="pull-in">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title fw-mediumbold">Ultimas Personas votantes</div>
                        <div class="card-list">
                            <div class="item-list">
                                <div class="avatar">
                                    <img src="/assets/img/jm_denis.jpg" alt="..." class="avatar-img rounded-circle">
                                </div>
                                <div class="info-user ml-3">
                                    <div class="username">Jimmy Denis</div>
                                    <div class="status">Graphic Designer</div>
                                </div>
                                <button class="btn btn-icon btn-primary btn-round btn-xs">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div class="item-list">
                                <div class="avatar">
                                    <img src="/assets/img/chadengle.jpg" alt="..." class="avatar-img rounded-circle">
                                </div>
                                <div class="info-user ml-3">
                                    <div class="username">Chad</div>
                                    <div class="status">CEO Zeleaf</div>
                                </div>
                                <button class="btn btn-icon btn-primary btn-round btn-xs">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title">Resumen</div>
                        <div class="card-category">Noviembre 25 </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="mb-4 mt-2">
                            <h1>80%</h1>
                        </div>
                        <div class="pull-in">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="h1 fw-bold float-right text-warning">+7%</div>
                        <h2 class="mb-2">213</h2>
                        <p class="text-muted">Transactions</p>
                        <div class="pull-in sparkline-fix">
                            <div id="lineChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('script')
<script>
    Circles.create({
        id:'circles-1',
        radius:45,
        value:60,
        maxValue:100,
        width:7,
        text: 5,
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
        value:70,
        maxValue:100,
        width:7,
        text: 36,
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
        value:40,
        maxValue:100,
        width:7,
        text: 12,
        colors:['#f1f1f1', '#F25961'],
        duration:400,
        wrpClass:'circles-wrp',
        textClass:'circles-text',
        styleWrapper:true,
        styleText:true
    })

    var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

    var mytotalIncomeChart = new Chart(totalIncomeChart, {
        type: 'bar',
        data: {
            labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
            datasets : [{
                label: "Total Income",
                backgroundColor: '#ff9e27',
                borderColor: 'rgb(23, 125, 255)',
                data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        display: false //this will remove only the label
                    },
                    gridLines : {
                        drawBorder: false,
                        display : false
                    }
                }],
                xAxes : [ {
                    gridLines : {
                        drawBorder: false,
                        display : false
                    }
                }]
            },
        }
    });

    $('#lineChart').sparkline([105,103,123,100,95,105,115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#ffa534',
        fillColor: 'rgba(255, 165, 52, .14)'
    });
</script>
@endsection
