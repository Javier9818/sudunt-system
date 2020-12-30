<?php

namespace App\Http\Controllers;

use App\Events\VoteEvent;
use App\Form;
use App\Mail\VoteSuccess;
use App\Teacher;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
      $teacher = Teacher::where('token', $token)->first();
      if ($teacher !== null)
        {
          if(Vote::where('teacher_id', $teacher->id)->where('form_id', $form->id)->exists())
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
      ]);// return view('admin.vote.index',["context" => 5]); //EL VOTO YA FUE CERRADO
    else{
      $teacher = Teacher::where('token', $request->token)->first();
      if ($teacher !== null)
        {
          if(Vote::where('teacher_id', $teacher->id)->where('form_id', $form->id)->exists())
          return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors([
            "validation-error" => $teacher->nombres.", su voto ya se encuentra registrado."
          ]); //return view('admin.vote.index',["teacher" => $teacher,"context" => 1]); //Voto ya realizado
          else{
            Vote::create([
              "teacher_id" => $teacher->id,
              "form_id" => $form->id,
              "response" => $request->vote
            ]);
            $cantidad = Vote::where('form_id',  $form->id)->count();
            event(new VoteEvent($cantidad));
            try {
              $email = $teacher->correo_institucional ?? $teacher->correo_personal;
              Mail::to([$email])->queue(new VoteSuccess($teacher->nombres));
            } catch (\Throwable $th) {}
            return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors([
              "validation-error" => $teacher->nombres.", su voto se registro exitosamente."
            ]);// return view('admin.vote.index',["teacher" => $teacher,"context" => 4]); //VOTO REGISTRADO CORRECTAMENTE
          } 
        }
      else
      return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors([
        "validation-error" => "El correo electrónico no se encuetra registrado."
      ]);//return view('admin.vote.index',["context" => 3]); //Usuario inválido
    }
  }

  public function statistics($id){
    $form = Form::dateVerify($id);
    $votes = Vote::where('form_id', $form->id)->get();
    $teachers = Teacher::all();
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
    $teachers = Teacher::all();
    $summary = Vote::where('form_id', $form->id)->selectRaw('response, count(response) as total')->groupBy('response')->orderBy('total', 'desc')->get();
    $ganador = Vote::where('form_id', $form->id)
      ->selectRaw('response, count(response) as total')
      ->groupBy('response')
      ->orderBy('total', 'desc')
      ->first();

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

    $votos_lista_1 = Vote::where('form_id', $form->id)
      ->selectRaw('response, count(response) as total')
      ->groupBy('response')
      ->where('response', 'Lista 1')
      ->first();

    $votos_lista_2 = Vote::where('form_id', $form->id)
      ->selectRaw('response, count(response) as total')
      ->groupBy('response')
      ->where('response', 'Lista 2')
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
      "votos_blanco" => $votos_blanco,
      "votos_viciado" => $votos_viciado,
      "empate_listas" =>  $empate,
      "empate_1" => $empate_1,
      "empate_2" => $empate_2
    ]);
  }

  //NO SUBIR
  public function getDataSimulacion($formID){
    
    $form = Form::find($formID);
    Vote::where('form_id', $formID)->delete();
    $votes = Vote::where('form_id', $formID)->count();

    if( $votes > 0 )
      return response()->json(["error" => true, "message" => "Error. El formulario contiene votos registrados."]);
    else{
       $empadronados = Teacher::all();
       return response()->json(["error" => false, "form" => $form, "votes" => $votes, "empadronados" => $empadronados, "listas" => $this->list_elections]);
    }
  }

  public function puestaCero($formID){
    Vote::where('form_id', $formID)->delete();
    return response()->json([ "error" => false]);
  }

  public function setVotoSimulado(Request $request){
      Vote::create([
        "response" => $request->lista,
        "teacher_id" => $request->teacher,
        "form_id" => $request->form
      ]);
      $cantidad = Vote::where('form_id', $request->form)->count();
      event(new VoteEvent($cantidad));
      return response()->json([ "error" => false ]);
  }
}
