@extends('layouts.vote')
@section('content')
    <div class="row justify-content-center align-items-center mt-3" style="height:80vh;">
    <div class="col-sm-10 align-self-center">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center">Bienvenido al sistema de sufragio - SUDUNT</h3>
                <h5 class="text-center grey-text">Por favor inicie sesi&oacute;n con su cuenta unitru para continuar</h5>
                <div class="row justify-content-center mt-4">
                    <div class="col-4">
                        <a href="{{route('login-google')}}">
                            <button class="btn border"> <img src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/images/google.png" alt="..alt" width="40px" class="mr-1"> Iniciar sessi&oacute;n con google</button>
                        </a>
                    </div>
                </div>
                @error('login-error')
                    <p class="text-center mt-2" style="color: red;">{{$message}}</p>
                @enderror
                @error('validation-error')
                    <div class="alert alert-success mt-3" role="alert">
                        <h4 class="alert-heading">{{$message}}</h4>
                    </div>
                @enderror
               
            </div>
        </div>
    </div>
    </div> 
@endsection
