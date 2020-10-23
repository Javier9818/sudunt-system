<?php

namespace App\Http\Controllers;

use App\Teacher;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VoteController extends Controller
{
  public static function validation($token)
  {
    $let = Teacher::validation($token);
    $let = json_decode($let[0]);
    $teacher_vote = Vote::validationVote($let->id);
    $count = count($teacher_vote);

    $list_elections = array(
      array('name'=>'Lista 1'),
      array('name'=>'Lista 2'),
      array('name'=>'Voto nulo'),
      array('name'=>'Voto viciado'),
    );
    $data = [
      'voted'=>$count ,
      'teacher_vote'=>$teacher_vote,
      'teache' =>$let,
      'list_elections'=>$list_elections
    ];
    return view('vote.index',$data);
  }
}
