<div class="container" id="page">
            <h3 class="text-center"><b>COMITÉ ELECTORAL AUTÓNOMO SUDUNT 2020</b></h3>
            <h3 class="text-center"><b>ELECCIONES GENERALES SUDUNT 2020</b></h3>
            <br>
            <h3 class="text-center"><b>ACTA DE COMPUTO GENERAL DE VOTOS</b></h3>
            <br>
            <p>
                En la ciudad de Trujillo, en el local del Sindicato Unificado de Docentes de la Universidad Nacional de Trujillo, siendo las {{$hora_actual}} horas del día 30 de diciembre del año 2020, el Comité Electoral Autónomo SUDUNT 2020, con presencia del Notario de la provincia de Trujillo, ..................................................................., recepcionó los resultados del acto electoral de las mesas de sufragio e inmediatamente consolidó los resultados generales siguientes:
            </p>

            <div class="row">
                <div class="col-6"><h4><b>TOTAL DE ELECTORES:</b></h4></div>
                <div class="col-6"><h4><b>{{$total}} (100%)</b></h4></div>

                <div class="col-6"><h4><b>TOTAL DE SUFRAGANTES:</b></h4></div>
                <div class="col-6"><h4><b>{{$votes}} ({{ number_format(($votes/$total)*100,1) }} %)</b></h4></div>

                <div class="col-6"><h4><b>TOTAL DE VOTOS VÁLIDAMENTE EMITIDOS:</b></h4></div>
                <div class="col-6"><h4><b>{{$votes}}</b></h4></div>
            </div>
         
            <p>
                De acuerdo a los resultados indicados, el Comité Electoral, en concordancia con el Art. 28° del Reglamento Electoral SUDUNT 2020, declara válidas las elecciones.
            </p>
       
            <p>El resultado del acto eleccionario es el siguiente:</p>

            @if($empate_listas == true)
                <h4><b>Empate entre votos {{$empate_1}} y votos {{$empate_2}}</b></h4>  
            @else
                <h4 class="mb-1"><b>{{$lista_ganador}}  {{$nombre_lista_ganador}}</b></h4>
                <div class="row">
                    <div class="col-5"><h4><b>N° Votos a favor</b></h4></div>
                    <div class="col-6"><h4><b>.......................................................</b></h4></div>
                    <div class="col-1"><h4><b>{{$ganador->total ?? '0'}}</b></h4></div>
                </div>    
            @endif
            <hr>
            @foreach($list_elections as $lista)
            <?php  $found = false; $lista_found = ''; ?>
                @foreach($summary as $sum)
                    @if($sum->response == $lista ) 
                        <?php  $found = true; $lista_found = $sum;?>
                    @endif
                @endforeach
                @if($found)
                <div class="row">
                    <div class="col-5"><h4><b>N° Votos {{$lista_found->response}}</b></h4></div>
                    <div class="col-6"><h4><b>.......................................................</b></h4></div>
                    <div class="col-1"><h4><b>{{$lista_found->total}}</b></h4></div>
                </div>
                @else
                    <div class="row">
                        <div class="col-5"><h4><b>N° Votos {{$lista}}</b></h4></div>
                        <div class="col-6"><h4><b>.......................................................</b></h4></div>
                        <div class="col-1"><h4><b>0</b></h4></div>
                    </div>      
                @endif
            @endforeach
            <!-- <div class="row">
                <div class="col-5"><h4><b>N° Votos en blanco</b></h4></div>
                <div class="col-6"><h4><b>.......................................................</b></h4></div>
                <div class="col-1"><h4><b>{{$votos_blanco->total ?? '0'}}</b></h4></div>
            </div>
            <div class="row">
                <div class="col-5"><h4><b>N° Votos nulos o viciados</b></h4></div>
                <div class="col-6"><h4><b>.......................................................</b></h4></div>
                <div class="col-1"><h4><b>{{$votos_viciado->total ?? '0'}}</b></h4></div>
            </div> -->
            <div class="row">
                <div class="col-5"><h4><b>Total de votos</b></h4></div>
                <div class="col-6"><h4><b>.......................................................</b></h4></div>
                <div class="col-1"><h4><b>{{$votes}}</b></h4></div>
            </div>
            <p class="mt-3">
                Luego de dar lectura a los artículos pertinentes del Reglamento de Elecciones SUDUNT 2020, el Presidente del Comité Electoral proclamó a la Lista Ganadora: 
                @if($empate_listas == true)
                    <b>Empate entre votos {{$empate_1}} y votos {{$empate_2}}</b>
                @else
                    <b>{{$lista_ganador}} - {{$nombre_lista_ganador}}</b>
                @endif
                
            </p>
            <p class="mt-1">
                Dándose por finalizado el Cómputo General de Votos a las 16:00 horas del día miercoles 30 de diciembre del 2020.
            </p>
            
            <div class="row mt-2 justify-content-around">
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Ing. Alberto Carlos Mendoza De Los Santos</p>
                    <p class="text-center m-0">PRESEDENTE</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Ing. Joe Alexis González Vásquez</p>
                    <p class="text-center m-0">SECRETARIO</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Abog. Lizardo Reyes Barrutia</p>
                    <p class="text-center m-0">VOCAL</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Lic. Jaime Enrique Agreda Gaitán</p>
                    <p class="text-center m-0">VOCAL</p>
                </div>
                <div class="col-5 my-4 text-center p-1">
                    <p class="text-center m-0">Ing. Alejandro Wilber Padilla Sevillano</p>
                    <p class="text-center m-0">VOCAL</p>
                </div>
            </div>
        </div>