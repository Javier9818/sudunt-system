<?php

namespace App\Http\Controllers;

use App\Events\VoteEvent;
use App\Form;
use App\Logs;
use App\Mail\VoteSuccess;
use App\Teacher;
use App\UserActivity;
use App\Vote;
use App\VoteRegister;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VoteController extends Controller
{
  public $list_elections;
  public $list_nombres;
  public $list_nombres_listas;
  function __construct() {
      $this->list_elections = [
        'Lista X', 'Lista Y', 'Blanco', 'Nulo o Viciado'
      ];//df

      $this->list_nombres = [
        'DANIEL RUIZ VILLA', 'MARCOS GUERRA MEDINA', '', ''
      ];//ff

      $this->list_nombres_listas = [
        'UNIDAD E INTEGRACIÓN SINDICAL DOCENTE DE LA UNT', 'UNIDAD E INDEPENDENCIA GREMIAL', '', ''
      ];//ff
  }

  public function validation($token){
    $form = Form::orderBy('id', 'desc')->first();
    $form = Form::dateVerify(0, $form);
    if($form->status == 0)
      return view('admin.vote.index',["context" => 5, "form" => $form]); //EL VOTO YA FUE CERRADO
    else{
      $teacher = Teacher::where('token', $token)->where('status', 1)->first();
      if ($teacher !== null)
        {
          // if(Vote::where('teacher_id', $teacher->id)->where('form_id', $form->id)->exists())
          if(VoteRegister::where('teacher_id', $teacher->id)->where('form_id', $form->id)->exists())
            return view('admin.vote.index',["teacher" => $teacher,"context" => 1]); //VOTO PREVIO EXISTENTE
          else
            return view('admin.vote.index',[  // ACCESO PERMITIDO
              "list_elections" => $this->list_elections,
              "list_nombres" => $this->list_nombres,
              "teacher" => $teacher,
              "form" => $form,
              "context" => 2
            ]);
        }
      else
        return view('admin.vote.index',["context" => 3]); //Usuario inválido
    }
  }

  public function store(Request $request){
    $form = Form::orderBy('id', 'desc')->first();
    if($form->status == 0)
      return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors([
        "validation-error" => "El formulario de votación ha cerrado."
      ]);
    else{
      $teacher = Teacher::where('token', $request->token)->where('status', 1)->first();
      if ($teacher !== null)
        {
          if(VoteRegister::where('teacher_id', $teacher->id)->where('form_id', $form->id)->exists())
          return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors([
            "validation-error" => $teacher->nombres.", su voto ya se encuentra registrado."
          ]);
          else{
            DB::transaction(function () use ($form, $request, $teacher){
              Vote::create([
                "form_id" => $form->id,
                "response" => $request->vote,
                "ip" => $request->ip
              ]);
              VoteRegister::create([
                "teacher_id" => $teacher->id,
                "form_id" => $form->id
              ]);
            });
            $cantidad = Vote::where('form_id',  $form->id)->count();
            event(new VoteEvent($cantidad));
            try {
              $email = $teacher->correo_institucional ?? $teacher->correo_personal;
              Mail::to([$email])->queue(new VoteSuccess($teacher->nombres));
            } catch (\Throwable $th) {}
            return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors([
              "validation-error" => $teacher->nombres.", su voto se registro exitosamente."
            ]);
          }
        }
      else
      return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors([
        "validation-error" => "El correo electrónico no se encuetra registrado."
      ]);
    }
  }

  public function statistics($id){
    $form = Form::dateVerify($id);
    $votes = Vote::where('form_id', $form->id)->get();
    $teachers = $this->get_aptos();
    $summary = Vote::where('form_id', $form->id)->selectRaw('response, count(response) as total')->groupBy('response')->get();

    $fecha_close = Carbon::parse($form->close_time);
    $now = new Carbon(now('America/Lima'));

    $res_h = $fecha_close->diffInHours($now);
    $res_m = $fecha_close->diffInMinutes($now);
    $res_s = $fecha_close->diffInSeconds($now);

    return view('admin.formularios.index', [
      "votes" => count($votes),
      "id_votes" => $votes->pluck('id'),
      "total" => count($teachers),
      "summary" => $summary,
      "list_elections" => $this->list_elections,
      "form" => $form,
      "res_h" => $res_h,
      "res_m" => $res_m % 60,
      "res_s" => $res_s % 3600
    ]);
  }

  public function resultados($id){
    $form = Form::dateVerify($id);
    $votes = Vote::where('form_id', $form->id)->get();
    $teachers = $this->get_aptos();
    $summary = Vote::where('form_id', $form->id)->selectRaw('response, count(response) as total')->groupBy('response')->orderBy('total', 'desc')->get();
    $ganador = Vote::where('form_id', $form->id)
      ->selectRaw('response, count(response) as total')
      ->groupBy('response')
      ->orderBy('total', 'desc')
      ->first();

    $votes_teachers_summary = DB::table('vote_register')
                              ->selectRaw('count(teachers.is_activo) as activos, count(vote_register.id) as total')
                              ->where('form_id', $form->id)
                              ->groupBy('form_id')
                              ->join('teachers', 'teachers.id', '=', 'vote_register.teacher_id')
                              ->get();

    $votos_blanco = Vote::where('form_id', $form->id)
      ->selectRaw('response, count(response) as total')
      ->groupBy('response')
      ->where('response', 'Blanco')
      ->first();

    $votos_viciado = Vote::where('form_id', $form->id)
      ->selectRaw('response, count(response) as total')
      ->groupBy('response')
      ->where('response', 'Nulo o Viciado')
      ->first();

    $index_ganador = array_search($ganador->response, $this->list_elections);
    $fecha = new Carbon(now('America/Lima'));

    $empate = false;
    $empate_1 = '';
    $empate_2 = '';

    if(count($summary) >= 2)
      if($summary[0]->total == $summary[1]->total){
        $empate = true;
        $empate_1 = $summary[0]->response;
        $empate_2 = $summary[1]->response;
      }

    return view('admin.formularios.resultados', [
      "votes" => count($votes),
      "total" => count($teachers),
      "summary" => $summary,
      "list_elections" => $this->list_elections,
      "form" => $form,
      "fecha" => $fecha->format('Y-m-d H:i:s'),
      "ganador" => $ganador,
      "index_ganador" => $index_ganador,
      "nombre_lista_ganador" => $this->list_nombres_listas[$index_ganador],
      "lista_ganador" => $this->list_elections[$index_ganador],
      "hora_actual" => $fecha->format('H:i'),
      "dia_actual" => $fecha->format('d'),
      "mes_actual" => $this->nombreMes(number_format($fecha->format('m'),0)),
      "anio_actual" => $fecha->format('Y'),
      "votos_blanco" => $votos_blanco,
      "votos_viciado" => $votos_viciado,
      "empate_listas" =>  $empate,
      "empate_1" => $empate_1,
      "empate_2" => $empate_2,
      "votes_teachers_summary" => $votes_teachers_summary
    ]);
  }

  public function nombreMes($mes){
    $res = null;
    switch ($mes) {
        case 1:
            $res = 'Enero';
            break;
        case 2:
            $res = 'Febrero';
            break;
        case 3:
            $res = 'Marzo';
            break;
        case 4:
            $res = 'Abril';
            break;
        case 5:
            $res = 'Mayo';
            break;
        case 6:
            $res = 'Junio';
            break;
        case 7:
            $res = 'Julio';
            break;
        case 8:
            $res = 'Agosto';
            break;
        case 9:
            $res = 'Setiembre';
            break;
        case 10:
            $res = 'Octubre';
            break;
        case 11:
            $res = 'Noviembre';
            break;
        case 12:
            $res = 'Diciembre';
            break;
    }

    return $res;
}

  //NO SUBIR
  public function getDataSimulacion($formID){

    $form = Form::find($formID);
    $this->truncar_tablas();
    $votes = Vote::where('form_id', $formID)->count();

    if( $votes > 0 )
      return response()->json(["error" => true, "message" => "Error. El formulario contiene votos registrados."]);
    else{
       $empadronados = Teacher::where('status', 1)->get();
       return response()->json(["error" => false, "form" => $form, "votes" => $votes, "empadronados" => $empadronados, "listas" => $this->list_elections]);
    }
  }

  public function puestaCero($formID){
    $this->truncar_tablas();
    return response()->json([ "error" => false]);
  }

  public function setVotoSimulado(Request $request){
      Vote::create([
        "form_id" => $request->form,
        "response" => $request->lista,
        "ip" => '127.0.0.1'
      ]);
      VoteRegister::create([
        "teacher_id" => $request->teacher,
        "form_id" => $request->form
      ]);
      $cantidad = Vote::where('form_id', $request->form)->count();
      event(new VoteEvent($cantidad));
      return response()->json([ "error" => false ]);
  }

  public function truncar_tablas(){
    Vote::truncate();
    VoteRegister::truncate();
    Logs::truncate();
    UserActivity::truncate();
  }

  public function get_aptos(){
    $aptos = Teacher::select('nombres', 'correo_personal', 'correo_institucional')->where('status', 1)->orderBy('nombres', 'asc')->get();
        $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        $rg = '/[\w\-.]+@(gmail.com)/m';
        $response = [];
        foreach ($aptos as $teacher) {
            $valida_institucional = preg_match($re, $teacher->correo_institucional);
            $valida_personal = preg_match($rg, $teacher->correo_personal);
            $valida_personal_i = preg_match($re, $teacher->correo_personal);

            if( $valida_institucional == 1 || $valida_personal == 1 || $valida_personal_i == 1){
                if($valida_institucional == 1)
                    $teacher->correo = trim($teacher->correo_institucional);
                elseif($valida_personal == 1 || $valida_personal_i == 1)
                    $teacher->correo = trim($teacher->correo_personal);
                else
                    $teacher->correo = '-';

                array_push($response, $teacher);
            }

        }

        return $response;
  }

}
