<?php

namespace App\Http\Controllers;

use App\Form;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = Form::orderBy('id', 'desc')->get();
        if(count($forms) > 0)
            Form::dateVerify(0, $forms[0]);
        return view('admin.formularios.list', ["forms" => $forms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('rol-admin');
        return view('admin.formularios.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('rol-admin');
        if(Form::where('status', 1)->exists())
            return redirect()->back()->withErrors(["error_time" => "No puede crear otro formulario mientras exista uno en estado abierto."]); 
        else{
            $fecha_open = Carbon::parse($request->open_time);
            $fecha_close = Carbon::parse($request->close_time);
            $now = new Carbon(now('America/Lima'));
            if($fecha_open->lt($now))
                $status = $fecha_close->lt($now) ? 0 : 1;
            else
                $status = 0;
            
            if($fecha_open->lt($fecha_close)){
                Form::create([
                    "open_time" => $request->open_time,
                    "close_time" => $request->close_time,
                    "status" => $status,
                    "title" => $request->title,
                    "description" => $request->description
                ]);
                return redirect(route('form.index'));
            }
            else
                return redirect()->back()->withErrors(["error_time" => "La fecha de clausura no puede ser menor a la de apertura"]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('rol-admin');
        $form = Form::find($id);
        return view('admin.formularios.form', ["form" => $form]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $this->authorize('rol-admin');
        $fecha_open = Carbon::parse($request->open_time);
        $fecha_close = Carbon::parse($request->close_time);
        $now = new Carbon(now('America/Lima'));

        if($fecha_open->lt($now))
            $status = $fecha_close->lt($now) ? 0 : 1;
        else
            $status = 0;
        
        
        if($fecha_open->lt($fecha_close)){
            $form = Form::find($id);
            $form->open_time = $request->open_time;
            $form->close_time = $request->close_time;
            $form->status = $status;
            $form->save();
            return redirect()->back();
        }
        else
            return redirect()->back()->withErrors(["error_time" => "La fecha de clausura no puede ser menor a la de apertura"]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('rol-admin');
        Vote::where('form_id', $id)->delete();
        Form::find($id)->delete();
        return redirect()->back();
    }
}
