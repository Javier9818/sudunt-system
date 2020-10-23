@extends('layouts.vote')
@section('content')
<div class="row justify-content-center h-100 mt-3">
  <div class="col-sm-10 align-self-center">
    <div class="card shadow">
      <div class="card-body">
        <h5 class="card-title text-center mt-2">Eliga la opción de su preferencia</h5>
        <div>
          <form id="regForm" action="">
            <div>
              <ul class="list-group list-group-flush">
                @foreach ($list_elections as $item)
                  <li
                    class="list-group-item"                   
                  >
                    <div class="row">
                      <div class="col-6">
                        <label
                          class="form-check-label pl-2"
                          >{{$item['name']}}</label
                        >
                      </div>
                      <div class="col-6 text-left">
                        <input
                          value="{{$item['name']}}"
                          class="form-check-input"
                          type="radio"
                          name="voto"
                        />
                      </div>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>              
            <div style="overflow: auto" class="mt-2 text-center">
              <input type="submit" value="Votar" class="btn btn-success" >
            </div>             
          </form>
        </div>
      </div>
    </div>
  </div>
</div> 
@endsection
