@extends('layouts.vote')
@section('content')
<div class="row justify-content-center h-100 mt-3">
  <div class="col-sm-10 align-self-center">
    <div class="card shadow">
      <div class="card-body">
        @switch($context)
          @case(1)
            <h5 class="card-title text-center mt-2">{{$teacher->nombres}} su voto ya se encuentra registrado.</h5>
          @break
          @case(2)
            <h5 class="card-title text-center mt-2">Bienvenido {{$teacher->nombres}}, porfavor eliga la opción de su preferencia.</h5>
            <p class="text-center">
              El sistema de sufragio solo estará activo desde el día {{substr($form->open_time, 0, 10)}} a las {{substr($form->open_time, 11, 5)}} horas, hasta el día {{substr($form->close_time, 0, 10)}} a las {{substr($form->close_time, 11, 5)}} horas.
            </p>
            <div>
              <form id="regForm" action="/vote" method="POST">
                @csrf
                <div>
                  <ul class="list-group list-group-flush">
                    @foreach ($list_elections as $value => $item)
                      <li
                        class="list-group-item"                   
                      >
                        <div class="row">
                          <div class="col-11">
                            <label
                              class="form-check-label pl-2"
                              ><b>{{$item}}</b> {{$list_nombres[$value]}}</label
                            >
                          </div>
                          <div class="col-1 text-left">
                            <input
                              value="{{$item}}"
                              class="form-check-input"
                              type="radio"
                              name="vote"
                            />
                            <input type="text" value="{{$teacher->token}}"  name="token" style="display: none;">
                          </div>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>              
                <div style="overflow: auto" class="mt-2 text-center">
                  <input type="submit" value="Enviar voto" class="btn btn-success" >
                </div>             
              </form>
            </div>
          @break
          @case(3)
            <h5 class="card-title text-center mt-2">El enlace accedido no es válido.</h5>
          @break
          @case(4)
            <h5 class="card-title text-center mt-2"> {{$teacher->nombres}} su voto ha sido registrado exitosamente.</h5>
          @break
          @case(5)
            <h5 class="card-title text-center mt-2">Sistema de votación cerrado.</h5>
            <p class="text-center">
              El sistema de sufragio solo estará activo desde el día {{substr($form->open_time, 0, 10)}} a las {{substr($form->open_time, 11, 5)}} horas, hasta el día {{substr($form->close_time, 0, 10)}} a las {{substr($form->close_time, 11, 5)}} horas.
            </p>
          @break
        @endswitch
      </div>
    </div>
  </div>
</div> 
@endsection
