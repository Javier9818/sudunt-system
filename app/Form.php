<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'title', 'description', 'status', 'open_time', 'close_time'
    ];

    public static function dateVerify($id){
        $form  = Form::find($id);

        $fecha_open = Carbon::parse($form->open_time);
        $fecha_close = Carbon::parse($form->close_time);
        $now = new Carbon(now('America/Lima'));

        if($fecha_open->lt($now))
            $status = $fecha_close->lt($now) ? 0 : 1;
        else
            $status = 0;
        
        if($form->status !== $status){
            $form->status = $status;
            $form->save();
        }

        return $form;
    }
}
