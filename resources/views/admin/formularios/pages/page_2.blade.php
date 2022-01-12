<div class="container" id="page2">
            <h3 class="text-center"><b>COMITÉ ELECTORAL AUTÓNOMO SUDUNT 2021</b></h3>
            <h3 class="text-center"><b>ELECCIONES GENERALES SUDUNT 2021</b></h3>
            <br>
            <h3 class="text-center"><b>ACTA DE COMPUTO GENERAL DE VOTOS</b></h3>
            <br>
            <p>
                En la ciudad de Trujillo, en el local del Sindicato Unificado de Docentes de la Universidad Nacional de Trujillo, siendo las {{$hora_actual}} horas del día {{$dia_actual}} de {{$mes_actual}} del año {{$anio_actual}}, el Comité Electoral Autónomo SUDUNT 2021, recepcionó los resultados del acto electoral e inmediatamente consolidó los resultados generales siguientes:
            </p>
            @if( $votes >= (($total / 2) + 1 ))
                @if($empate_listas == true)
                    <h4><b>Empate entre votos {{$empate_1}} y votos {{$empate_2}}</b></h4>  
                @else
                    <h4 class="mb-1 mt-2"><b>{{$lista_ganador}}  {{$nombre_lista_ganador}}</b></h4>
                    <div class="row">
                        <div class="col-5"><h4><b>N° Votos válidamente emitidos</b></h4></div>
                        <div class="col-5"><h4><b>.......................................................</b></h4></div>
                        <div class="col-1">{{$ganador->total ?? '0'}}</div>
                    </div>  
                @endif
            @else
             <h4>No se superó el 50% + 1 de votos válidamente emitidos</h4>
            @endif
            <br>
            @if( $votes >= (($total / 2) + 1 ))
                <p>
                    Luego de dar lectura a los artículos pertinentes del Reglamento de Elecciones SUDUNT 2021, el Presidente del Comité Electoral proclamó a la Lista Ganadora.
                </p>
            @else
                <p>Luego de dar lectura a los artículos pertinentes del Reglamento de Elecciones SUDUNT 2021, el Presidente del Comité Electoral proclamó inválida las elecciones.</p>    
            @endif
            <p>
                Dándose por finalizado el Cómputo General de Votos a las 16:00 horas del día {{$dia_actual}} de {{$mes_actual}} del año {{$anio_actual}},.
            </p>

            <div class="row justify-content-around" style="margin-top: 100px;">
				<div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Abog. Miguel Rodriguez Albán</p>
                    <p class="text-center m-0">PRESEDENTE</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Lic. Antonio E. Gordillo Vega</p>
                    <p class="text-center m-0">SECRETARIO</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Ing. Betzabe R. Argomedo Arteaga</p>
                    <p class="text-center m-0">VOCAL</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Ing. Juan Luis Córdova Otero</p>
                    <p class="text-center m-0">VOCAL</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Ing. Alberto Carlos Mendoza De Los Santos</p>
                    <p class="text-center m-0">VOCAL</p>
                </div>
            </div>
        </div>