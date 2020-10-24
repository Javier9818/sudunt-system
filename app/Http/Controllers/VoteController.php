<?php

namespace App\Http\Controllers;

use App\Form;
use App\Teacher;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VoteController extends Controller
{
  public $list_elections;
  function __construct() {
      $this->list_elections = [
        'Lista 1', 'Lista 2', 'Voto nulo', 'Voto viciado'
      ];
  }

  public function validation($token){
    $form = Form::dateVerify(1);
    if($form->status == 0)
      return view('admin.vote.index',["context" => 5]); //EL VOTO YA FUE CERRADO
    else{
      $teacher = Teacher::where('token', $token)->first();
      if ($teacher !== null)
        {
          if(Vote::where('teacher_id', $teacher->id)->exists())
            return view('admin.vote.index',["teacher" => $teacher,"context" => 1]); //VOTO PREVIO EXISTENTE
          else
            return view('admin.vote.index',[  // ACCESO PERMITIDO
              "list_elections" => $this->list_elections,
              "teacher" => $teacher,
              "context" => 2
            ]);
        }
      else
        return view('admin.vote.index',["context" => 3]); //Usuario inválido
    }
  }

  public function store(Request $request){
    if(Form::where('status',0)->exists())
      return view('admin.vote.index',["context" => 5]); //EL VOTO YA FUE CERRADO
    else{
      $teacher = Teacher::where('token', $request->token)->first();
      if ($teacher !== null)
        {
          if(Vote::where('teacher_id', $teacher->id)->exists())
            return view('admin.vote.index',["teacher" => $teacher,"context" => 1]); //Voto ya realizado
          else{
            Vote::create([
              "teacher_id" => $teacher->id,
              "form_id" => 1,
              "response" => $request->vote
            ]);
            return view('admin.vote.index',["teacher" => $teacher,"context" => 4]); //VOTO REGISTRADO CORRECTAMENTE
          } 
        }
      else
        return view('admin.vote.index',["context" => 3]); //Usuario inválido
    }
  }

  public function statistics(){
    $form = Form::dateVerify(1);
    $votes = Vote::all();
    $teachers = Teacher::all();
    $summary = Vote::selectRaw('response, count(response) as total')->groupBy('response')->get();
    return view('admin.formularios.index', [
      "votes" => count($votes), 
      "total" => count($teachers),
      "summary" => $summary, 
      "list_elections" => $this->list_elections,
      "form" => $form
    ]);
  }
}
