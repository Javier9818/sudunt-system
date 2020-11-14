@extends('layouts.vote')

@section('styles')

@endsection

@section('content')
<div class="container" id='app'>
    <div class="row align-items-start pt-4" style="height: 100vh;">
        <div class="card shadow col-12">
            <div class="card-body">
                <h3 class="text-center">Verificar datos de empradronado</h3>
                <p>Puede acceder a su información ingresando su código de docente, en caso de ser un docente con información no válida, porfavor descargue el formato indicado para solicitar una actualización de datos.</p>
                <seach-component></seach-component>
            </div>
        </div>
    </div> 
</div>
@endsection
