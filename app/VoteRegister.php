<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteRegister extends Model
{
    protected $table = 'vote_register';
    protected $fillable = ['teacher_id', 'form_id'];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'teacher_id', 'id');
    }
}
