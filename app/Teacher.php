<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'code',
        'correo_institucional',
        'correo_personal',
        'sexo',
        'facultad',
        'departamento',
        'categoria',
        'nombres',
        'token',
        'is_activo',
        'status'
    ];

    public static function validation($token)
    {
        return Teacher::where('token','=',$token)->get();
    }
}
