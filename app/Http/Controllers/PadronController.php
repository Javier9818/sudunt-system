<?php

namespace App\Http\Controllers;

use App\Teacher;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PadronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('admin.padron.list', ["teachers" => $teachers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.padron.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Teacher::create([
            "nombres" => $request->nombres,
            "correo_institucional" => $request->correo_institucional,
            "correo_personal" => $request->correo_personal,
            "code" => $request->code,
            "departamento" => $request->departamento,
            "facultad" => $request->facultad,
            "categoria" => $request->categoria,
            "sexo" => $request->sexo,
            "token" => preg_replace("/\//i", "online", Hash::make($request->code)) 
        ]);

        return redirect('/padron');
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
        $teacher = Teacher::find($id);
        return view('admin.padron.create', ["teacher" => $teacher]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id)->update($request->all());
        return redirect('/padron');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Vote::where('teacher_id', $id)->exists())
            return redirect('/padron');
        else
            Teacher::find($id)->delete();
            return redirect('/padron');
        
    }

    public function obtenerEmpadronado($code){
        $empadronado = Teacher::where('code', $code)->first();
        return response()->json(["empadronado" => $empadronado ?? null]);
    }
}
