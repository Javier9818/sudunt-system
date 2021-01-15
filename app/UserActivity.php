<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = ['id', 'hora_entrada', 'user_id', 'actividad'];
    protected $table = 'user_activity';

    public function usuario()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
