@extends('layouts.admin')
@section('title') 
    Formularios de sufragio  
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
                        <h2 class="text-white pb-2 fw-bold">Formularios de sufragio</h2>
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
                            <h4 class="card-title">Formularios de sufragio </h4>
                            @can('rol-admin')
                                <a href="/form/create" class="btn btn-sm btn-success float-right">Nuevo</a>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="multi-filter-select" class="display table table-striped table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Titulo</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                            <th>Fecha de creación</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Titulo</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                            <th>Fecha de creación</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($forms as $form)
                                            <tr>
                                                <td>{{$form->title}}</td>
                                                <td>{{$form->description}}</td>
                                                <td>{{$form->status == 0 ? 'Cerrado' : 'Abierto'}}</td>
                                                <td>{{$form->created_at}}</td>
                                                <td> 
                                                    @can('rol-admin')
                                                        @if ($loop->first)
                                                            <a href="/form/{{$form->id}}/edit"> <i class="fas fa-edit"></i></a> 
                                                            <a href="javascript:void(0)" class="ml-2" onclick="event.preventDefault();
                                                            if(confirm('¿Está seguro de realizar está operación?'))document.getElementById('delete-form').submit();"><i class="fas fa-trash" style="color:lightcoral;"></i></a>
                                                            <form id="delete-form" action="/form/{{$form->id}}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form> 
                                                            <a href="javascript:void(0)" title="simular" onclick="puestaCero('{{$form->id}}')"> <i class="fas fa-desktop ml-2"></i></a> 
                                                        @endif
                                                    @endcan
                                                    <a href="/form-statistics/{{$form->id}}" class="ml-2"> <i class="fas fa-eye" style="color:green;"></i> </a>
                                                </td>
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
    
    <div class="modal" tabindex="-1" id="modalPruebas">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generando pruebas...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h1 id="data-modal"></h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Cancelar</button>
            </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
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

    var intervalo;

    function simular(formID){
        $('#modalPruebas').modal('show')
        $('#data-modal').html('Simulando votación');
        axios.get(`api/data-simulacion/${formID}`).then( ({data}) => {
           let { error, message} = data
           if(error)
            $('#data-modal').html(message);
           else{
            let { empadronados, listas, form} = data
            var indice = 0
            
            intervalo = setInterval(() => {
                axios.post('api/voto-simulado',{
                    teacher: empadronados[indice].id, 
                    form: form.id , 
                    lista: listas[Math.floor(Math.random() * listas.length)]
                }).then( () => {
                    $('#data-modal').html(`Simulando votación - ${indice + 1}  de ${empadronados.length} votos resgistrados`);
                }).then( () => {
                   if( indice >= empadronados.length )
                    clearInterval(intervalo);
                    indice += 1
                }).catch((err) => {
                    console.log(err)
                });
            }, 1000);
           }
        })
    }

    function puestaCero(formID){
        axios.get(`api/data-simulacion-reset/${formID}`).then( ({data}) => {
           alert( "El formulario se reinició a votos 0");
        })
    }

    $('#close').on('click', () => {
        clearInterval(intervalo);
    });
</script>
@endsection