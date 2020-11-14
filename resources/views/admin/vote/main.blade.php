@extends('layouts.vote')

@section('styles')
<style>
    .item__opc{
        border: solid 1px grey;
        cursor: pointer;
    }
    .item__opc:hover{
        border: solid 4px grey;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row align-items-center" style="height: 100vh;">
        <div class="card shadow col-12">
            <div class="card-body">
                <h3 class="text-center">Bienvenido al sistema de sufragio sudunt</h3>
                <div class="row justify-content-center">
                    <div class="col-md-2 col-4 m-4 p-2 item__opc" onclick="location.href='/sufragio-sudunt/busqueda-empadronados'">
                        <img src="https://www.ecured.cu/images/7/70/Disciplinas.png" alt="..." width="100%">
                        <h5 class="text-center">Información de docente</h5>
                    </div>  
                    <div class="col-md-2 col-4 m-4 item__opc" onclick="location.href='/sufragio-sudunt/autenticar-empadronado'">
                        <img src="https://static.vecteezy.com/system/resources/previews/000/593/650/non_2x/voting-in-thailand-and-political-party-campaigns-vector.jpg" alt="..." width="100%">
                        <h5 class="text-center mt-3">Realizar voto</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
