<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'response', 'teacher_id', 'form_id'
    ];

    public static function validationVote($teacher_id)
    {
        return Vote::where('teacher_id','=',$teacher_id)->get();
    }
}
