@extends('layouts.admin')
@section('title')
    Editar formulario
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
                    <h2 class="text-white pb-2 fw-bold">Editar formulario de Votacion</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Formulario de votación 
                            @if(($form ?? null) != null)
                                ({{$form->status == 1 ? 'Abierto' : 'Cerrado'}})
                            @endif    
                        </div>
                        <form action="{{($form ?? null) == null ? '/form':'/form/'.$form->id}}" method="POST" id="vote-form">
                            @csrf
                            @if(($form ?? null) != null)
                                @method('PUT')
                            @endif  
                            <div class="row"> 
                                @error('error_time')
                                    <div class="col-md-12 ml-2">
                                        <h5 style="color:red">{{$message}}</h5>
                                    </div>
                                @enderror
                                <vote-form edit="{{($form ?? null) == null ? 0:1}}" data="{{$form ?? null}}"></vote-form>
                            </div>
                        </form>   
                    </div>
                </div>
            </div>
        </div>
        

    </div>
</div>
@endsection
