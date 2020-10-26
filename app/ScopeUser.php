<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScopeUser extends Model
{
    protected $fillable = ['scope_id', 'user_id'];
    protected $table = 'scope_users';
}
