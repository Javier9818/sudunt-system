@extends('layouts.admin')
@section('title') 
    Gestión usuario  
@endsection

@section('side-nav') 
    <x-side-nav tab="1" selected='1'/>
@endsection

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">
                        @if(($user ?? null) != null)
                            Editar usuario
                        @else
                            Nuevo usuario
                        @endif
                        </h2>
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
            <div class="card p-3">
                @error('register-error')
                    <p style="color: red;">{{$message}}</p>
                @enderror
                <form action="{{($user ?? null) == null ? '/user':'/user/'.$user->id}}" method="POST">
                @csrf
                @if(($user ?? null) != null)
                    @method('PUT')
                @endif
                    <p class="ml-2" style="color:blue;"> <i class="fas fa-exclamation"></i> Las credenciales de acceso serán reenviadas directamente al email del usuario registrado</p>
                    <user-form edit="{{($user ?? null) == null ? 0:1}}" user="{{$user ?? null}}" person="{{$person ?? null}}"></user-form>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection