<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = 'lists';
    protected $fillable = ['id', 'name', 'status'];
}
