<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'logs';
    protected $fillable = [
        'id',
        'typo',
        'tabla',
        'old',
        'new',
        'valor_alterado',
        'usuario',
        'ip',
        'fecha'
    ];
}
