<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    protected $fillable = [
        'names', 'last_name_pat', 'last_name_mat', 'address', 'phone', 'dni'
    ];
}
