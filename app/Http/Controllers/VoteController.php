<?php

namespace App\Http\Controllers;

use App\Form;
use App\Mail\VoteSuccess;
use App\Teacher;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VoteController extends Controller
{
  public $list_elections;
  function __construct() {
      $this->list_elections = [
        'Lista 1', 'Lista 2', 'Voto nulo', 'Voto viciado'
      ];
  }

  public function validation($token){
    $form = Form::orderBy('created_at', 'desc')->first();
    $form = Form::dateVerify(0, $form);
    if($form->status == 0)
      return view('admin.vote.index',["context" => 5]); //EL VOTO YA FUE CERRADO
    else{
      $teacher = Teacher::where('token', $token)->first();
      if ($teacher !== null)
        {
          if(Vote::where('teacher_id', $teacher->id)->where('form_id', $form->id)->exists())
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
    $form = Form::orderBy('created_at', 'desc')->first();
    if($form->status == 0)
      return view('admin.vote.index',["context" => 5]); //EL VOTO YA FUE CERRADO
    else{
      $teacher = Teacher::where('token', $request->token)->first();
      if ($teacher !== null)
        {
          if(Vote::where('teacher_id', $teacher->id)->where('form_id', $form->id)->exists())
            return view('admin.vote.index',["teacher" => $teacher,"context" => 1]); //Voto ya realizado
          else{
            Vote::create([
              "teacher_id" => $teacher->id,
              "form_id" => $form->id,
              "response" => $request->vote
            ]);
            try {
              Mail::to([$teacher->email])->queue(new VoteSuccess($teacher->names));
            } catch (\Throwable $th) {}
            return view('admin.vote.index',["teacher" => $teacher,"context" => 4]); //VOTO REGISTRADO CORRECTAMENTE
          } 
        }
      else
        return view('admin.vote.index',["context" => 3]); //Usuario inválido
    }
  }

  public function statistics($id){
    $form = Form::dateVerify($id);
    $votes = Vote::where('form_id', $form->id)->get();
    $teachers = Teacher::all();
    $summary = Vote::where('form_id', $form->id)->selectRaw('response, count(response) as total')->groupBy('response')->get();
    return view('admin.formularios.index', [
      "votes" => count($votes), 
      "total" => count($teachers),
      "summary" => $summary, 
      "list_elections" => $this->list_elections,
      "form" => $form
    ]);
  }
}
