<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'code', 'email', 'names', 'last_names', 'token', 'status'
    ];

    public static function validation($token)
    {
        return Teacher::where('token','=',$token)->get();
    }
}
