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
            <div class="col-md-6">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Formulario de votación ({{$form->status == 1 ? 'Abierto' : 'Cerrado'}})</div>
                        <form action="/form/1" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">   
                                <!-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="last_names">Estado (Cerrado)</label>
                                        <p><input type="checkbox" id="toggle1" name="status" class="offscreen" ><label for="toggle1" class="switch"></label></p>
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de apertura</label>
                                        <input type="datetime-local" id="open-time"
                                        name="open_time" value="{{$form->open_time}}"
                                        min="2020-01-01T00:00" max="2021-12-31T00:00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de clausura</label>
                                        <input type="datetime-local" id="close-time"
                                        name="close_time" value="{{$form->close_time}}"
                                        min="2020-01-01T00:00" max="2021-12-31T00:00">
                                    </div>
                                </div>
                                @error('error_time')
                                    <div class="col-md-12 ml-2">
                                        <h5 style="color:red">{{$message}}</h5>
                                    </div>
                                @enderror
                                <div class="col-md-6 mt-2">
                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                </div>
                            </div>
                        </form>   
                    </div>
                </div>
            </div>
        </div>
        

    </div>
</div>
@endsection
