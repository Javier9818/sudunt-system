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
  function __construct() {
      $this->list_elections = [
        'Lista 1', 'Lista 2', 'Blanco', 'Viciado'
      ];

      $this->list_nombres = [
        'DEMETRIO RAFAEL JARA AGUILAR', 'CARLOS ANTONIO HONORES YGLESIAS', '', ''
      ];
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
    return view('admin.formularios.index', [
      "votes" => count($votes), 
      "id_votes" => $votes->pluck('id'),
      "total" => count($teachers),
      "summary" => $summary, 
      "list_elections" => $this->list_elections,
      "form" => $form
    ]);
  }

  public function resultados($id){
    $form = Form::dateVerify($id);
    $votes = Vote::where('form_id', $form->id)->get();
    $teachers = Teacher::all();
    $summary = Vote::where('form_id', $form->id)->selectRaw('response, count(response) as total')->groupBy('response')->orderBy('total', 'desc')->get();
    $fecha = new Carbon(now('America/Lima'));
    return view('admin.formularios.resultados', [
      "votes" => count($votes), 
      "total" => count($teachers),
      "summary" => $summary, 
      "list_elections" => $this->list_elections,
      "form" => $form,
      "fecha" => $fecha->format('Y-m-d H:i:s')
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
