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
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzOHDCw0qFRB4Q3oWFSycEqKANqIzgKZBPAg&usqp=CAU" alt="..." width="100%">
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
<!-- Modal -->
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Comunicado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <iframe src="/COMUNICADO.pdf" style="width:100%; height:1200px;" frameborder="0" ></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    function launch(){
        $('#exampleModal').modal('show');
    }

    //launch()
</script>
@endsection
