@extends('layouts.admin')
@section('title') 
    Padrón de docentes  
@endsection

@section('side-nav') 
    <x-side-nav tab="2" selected='3'/>
@endsection

@section('style') 
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

        table,td, th{
            border: solid 1px black !important;
        }

        
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Padrón de docentes no aptos</h2>
                        <h1 class="text-white pb-2 fw-bold">{{$total}} docentes no aptos</h1>
                        <h1 class="text-white pb-2 fw-bold">{{count($aptos)}} docentes aptos</h1>
                        <h1 class="text-white pb-2 fw-bold">{{$inlocalizables}} docentes sin correo</h1>
                        <button class="btn btn-success" onclick="generarPDF(1)">Reporte no aptos</button>
                        <!-- <button class="btn btn-warning" onclick="generarPDF(2)">Reporte repetidos</button> -->
                        <button class="btn btn-alert" onclick="generarPDF(3)">Reporte padrón</button>
                        <!-- <h5 class="text-white op-7 mb-2">Lista de usuarios del sistema</h5> -->
                    </div>
                    <!-- <div class="ml-md-auto py-2 py-md-0">
                        <a href="#" class="btn btn-white btn-border btn-round mr-2">Manage</a>
                        <a href="#" class="btn btn-secondary btn-round">Add Customer</a>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Padrón de docentes no aptos</h4>
                            <!-- <a href="/padron/create" class="btn btn-sm btn-success float-right">Nuevo</a> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="multi-filter-select" class="display table table-striped table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Nombres</th>
                                            <th>Código</th>
                                            <th>Correo personal</th>
                                            <th>Correo institucional</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nombres</th>
                                            <th>Código</th>
                                            <th>Correo personal</th>
                                            <th>Correo institucional</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($teachers as $teacher)
                                            <tr>
                                                <td>{{$teacher->nombres}}</td>
                                                <td>{{$teacher->code}}</td>
                                                <td>{{$teacher->correo_personal}}</td>
                                                <td>{{$teacher->correo_institucional}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div class="d-none">
        <div class="container" id="listDocentes">
                <h3 class="text-center" style="text-align: center;"><b>COMITÉ ELECTORAL AUTÓNOMO SUDUNT 2020</b></h3>
                <h3 class="text-center"style="text-align: center;"><b>ELECCIONES GENERALES SUDUNT 2020</b></h3>
                <br>
                <h3 class="text-center"style="text-align: center;"><b>LISTA DE DOCENTES CON CORREOS NO VÁLIDOS PARA SUFRAGIO</b></h3>
                
                <div class="row justify-content-center aligin-items-center">
                    <table style="width: 100%;">
                        <thead>
                            <tr style="text-align: center;">
                                <th>N°</th>
                                <th>Docente</th>
                                <th>Correo personal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($teachers as $index => $teacher)
                                <tr>    
                                    <td style="text-align: center;">{{$index + 1}}</td>
                                    <td style="padding-left: 1em;">{{$teacher->nombres}}</td>
                                    <td style="padding-left: 1em;">{{$teacher->correo_personal ?? '-'}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>

        <div class="container" id="listDocentesRepetidos">
                <h3 class="text-center"style="text-align: center;"><b>COMITÉ ELECTORAL AUTÓNOMO SUDUNT 2020</b></h3>
                <h3 class="text-center"style="text-align: center;"><b>ELECCIONES GENERALES SUDUNT 2020</b></h3>
                <br>
                <h3 class="text-center"style="text-align: center;"><b>LISTA DE DOCENTES CON CORREOS REPETIDOS</b></h3>
                
                <div class="ml-4">
                    @foreach($repetidos_institucional as $index => $teacher)
                        @if(preg_match('/[\w\-.]+@[\w\-.]+.[\w\-.]/m', $teacher->correo_institucional) == 1)
                        <h4 class="mt-5"> <b>Correo institucional: {{$teacher->correo_institucional}}</b> </h4>
                        <h4> <b>Docentes:</b> </h4>
                        <ul>
                            @foreach(explode(',', $teacher->nombres) as $nombre)
                                <li>{{$nombre}}</li>
                            @endforeach
                        </ul>
                        @endif
                    @endforeach
                    @foreach($repetidos_personal as $index => $teacher)
                        @if(preg_match('/[\w\-.]+@[\w\-.]+.[\w\-.]/m', $teacher->correo_personal) == 1)
                        <h4 class="mt-5"> <b>Correo personal: {{$teacher->correo_personal}}</b> </h4>
                        <h4> <b>Docentes:</b> </h4>
                        <ul>
                            @foreach(explode(',', $teacher->nombres) as $nombre)
                                <li>{{$nombre}}</li>
                            @endforeach
                        </ul>
                        @endif
                    @endforeach
                </div>
        </div>

        <div class="container" id="padronCompleto">
                <h3 class="text-center"style="text-align: center;"><b>COMITÉ ELECTORAL AUTÓNOMO SUDUNT 2020</b></h3>
                <h3 class="text-center"style="text-align: center;"><b>ELECCIONES GENERALES SUDUNT 2020</b></h3>
                <br>
                <h3 class="text-center"style="text-align: center;"><b>LISTA DE DOCENTES APTOS PARA SUFRAGIO</b></h3>
                
                <div class="row justify-content-center aligin-items-center">
                    <table style="width: 100%;">
                        <thead>
                            <tr style="text-align: center;">
                                <th>N°</th>
                                <th>Docente</th>
                                <th>Correo</th>
                                <!-- <th>Correo personal</th>
                                <th>Correo institucional</th> -->
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($aptos as $index => $teacher)
                                <tr>    
                                    <td style="text-align: center;">{{$index + 1}}</td>
                                    <td style="padding-left: 1em;">{{$teacher->nombres}}</td>
                                    <td style="padding-left: 1em;">{{$teacher->correo}}</td>
                                    <!-- <td style="padding-left: 1em;">{{$teacher->correo_personal ?? '-'}}</td>
                                    <td style="padding-left: 1em;">{{$teacher->correo_institucional ?? '-'}}</td> -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
   </div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js" integrity="sha512-pdCVFUWsxl1A4g0uV6fyJ3nrnTGeWnZN2Tl/56j45UvZ1OMdm9CIbctuIHj+yBIRTUUyv6I9+OivXj4i0LPEYA==" crossorigin="anonymous"></script>
<script >
    $(document).ready(function() {
        $('#basic-datatables').DataTable({
        });

        $('#multi-filter-select').DataTable( {
            "language": {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "pageLength": 5,
            initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    var select = $('<select class="form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                            );

                        column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                    } );

                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            }
        });

        // Add Row
        $('#add-row').DataTable({
            "pageLength": 5,
        });

        var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $('#addRowButton').click(function() {
            $('#add-row').dataTable().fnAddData([
                $("#addName").val(),
                $("#addPosition").val(),
                $("#addOffice").val(),
                action
                ]);
            $('#addRowModal').modal('hide');

        });
    });

    function generarPDF_(report){
        if(report === 1)
            var listDocentes = document.getElementById('listDocentes');
        else if(report === 2)
            var listDocentes = document.getElementById('listDocentesRepetidos');
        else if(report === 3)
            var listDocentes = document.getElementById('padronCompleto');
        alert('generando...')
        var opt = {
        margin:       0.60,
        filename:     'LISTA DE DOCENTES CON CORREOS NO VÁLIDOS PARA SUFRAGIO',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 3, letterRendering: true },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        
        // New Promise-based usage:
        // html2pdf().from(element).set(opt).save();
        //
        html2pdf().from(listDocentes).set(opt).toContainer().toCanvas().toPdf().save();
        // Old monolithic-style usage:
        html2pdf(listDocentes, opt);
    }

    function generarPDF(report){
        if(report === 1)
            var listDocentes = document.getElementById('listDocentes');
        else if(report === 2)
            var listDocentes = document.getElementById('listDocentesRepetidos');
        else if(report === 3)
            var listDocentes = document.getElementById('padronCompleto');

        var mywindow = window.open('', 'PRINT');
        mywindow.document.write('<html><head>');
        mywindow.document.write('<style>body{font-family: Arial, Helvetica, sans-serif !important;color: black !important;}.firma{margin-bottom: 1.8em;}h1{color: black !important;}h3{font-size: 1.2em !important;}h4{font-size: 1em !important;}h5{font-size: 0.8em !important;}p{text-align: justify !important;font-size: 0.8em;color: black !important;}.cuadro{height: 40px !important;border: black 1px solid;}td, th{border: solid 1px black !important;}</style>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(listDocentes.innerHTML);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necesario para IE >= 10
        mywindow.focus(); // necesario para IE >= 10
        mywindow.print();
        mywindow.close();
        return true;
    }
</script>
@endsection