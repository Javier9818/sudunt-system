@extends('layouts.admin')
@section('title') 
    Registrar empadronado  
@endsection

@section('side-nav') 
    <x-side-nav tab="2" selected='1'/>
@endsection

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">
                        @if(($teacher ?? null) != null)
                            Editar empadronado
                        @else
                            Nuevo empadronado
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
                <form action="{{($teacher ?? null) == null ? '/padron':'/padron/'.$teacher->id}}" method="POST">
                @csrf
                @if(($teacher ?? null) != null)
                    @method('PUT')
                @endif
                    <empadronado-form edit="{{($teacher ?? null) == null ? 0:1}}" data="{{$teacher ?? null}}"></empadronado-form>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection