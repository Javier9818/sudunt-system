<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'response', 'form_id', 'created_at', 'updated_at', 'ip'
    ];

    // public static function validationVote($teacher_id)
    // {
    //     return Vote::where('teacher_id','=',$teacher_id)->get();
    // }
}
