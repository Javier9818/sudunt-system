<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteRegister extends Model
{
    protected $table = 'vote_register';
    protected $fillable = ['teacher_id', 'form_id'];
}
