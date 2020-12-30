<?php

namespace App\Http\Controllers;

use App\Mail\DatosDocenteActualizados;
use App\Mail\InvitacionVoto;
use App\Mail\SolicitudNoAptos;
use App\Teacher;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        return view('admin.padron.list', ["teachers" => $teachers, "total" => count($teachers)]);
    }

    public function noAptos()
    {
        $teachers = Teacher::all();
        $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        $rg = '/[\w\-.]+@(gmail.com)/m';

        $no_aptos = [];
        $sin_correo = 0;
        foreach ($teachers as $empadronado) {
            if($empadronado->correo_institucional == '' && $empadronado->correo_personal == '')
                $sin_correo = $sin_correo + 1;
            $valida_institucional = preg_match($re, $empadronado->correo_institucional);
            $valida_personal = preg_match($rg, $empadronado->correo_personal);
            $valida_personal_i = preg_match($re, $empadronado->correo_personal);

            if( ($valida_institucional == 0 || $valida_institucional == false) && ($valida_personal == 0 || $valida_personal == false) && ($valida_personal_i == 0 || $valida_personal_i == false))
                array_push($no_aptos, $empadronado);
        }
        
        return view('admin.padron.list_no_aptos', ["teachers" => $no_aptos, "total" => count($no_aptos), "inlocalizables" => $sin_correo]);
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
        $teacher = Teacher::find($id);

        if($teacher->correo_institucional !== $request->correo_institucional)
            Mail::to($request->correo_institucional)->queue(new DatosDocenteActualizados($teacher));
        else if($teacher->correo_personal !== $request->correo_personal)
            Mail::to($request->correo_personal)->queue(new DatosDocenteActualizados($teacher));
        
        $teacher->update($request->all());
        
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
        $empadronado->facultad = '-';
        $empadronado->departamento = '-';
        return response()->json(["empadronado" => $empadronado ?? null]);
    }

    public function generateTokens(){
        $empadronados = Teacher::all();

        foreach ($empadronados as $empadronado) {
            if($empadronado->token == null || $empadronado->token == '')
                $empadronado->update([
                    "token" =>  preg_replace("/\//i", "online", Hash::make($empadronado->code))
                ]);
        }

        return "Los tokens fueron generados";
    }


    public function registroAutomaticoCorreoInstitucional(){
        $empadronados = Teacher::all();
        $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        $rg = '/[\w\-.]+@(gmail.com)/m';
        foreach ($empadronados as $empadronado) {
            $correos = explode( ',', $empadronado->correo_personal);
            if(count($correos) > 1){
                if (preg_match($re, $correos[0])){
                    if($empadronado->correo_institucional == null || $empadronado->correo_institucional == '')
                        $empadronado->correo_institucional = $correos[0];
                }
                else 
                    $empadronado->correo_personal = $correos[0];

                if (preg_match($re, $correos[1])){
                    if($empadronado->correo_institucional == null || $empadronado->correo_institucional == '')
                        $empadronado->correo_institucional = $correos[1];
                }
                else 
                    $empadronado->correo_personal = $correos[1];
                
                if (preg_match($rg, $correos[0]))
                    $empadronado->correo_personal = $correos[0];
                else if (preg_match($rg, $correos[1]))
                    $empadronado->correo_personal = $correos[1];
                    
                $empadronado->save();
            }
            else if (preg_match($re, $empadronado->correo_personal ?? '')) 
                $empadronado->update([
                    "correo_institucional" =>  $empadronado->correo_personal
                ]);
        }

        return "Se completó el análisis";
    }

    public function enviarSolicitudNoAptos(){
        ini_set('max_execution_time', 180000);
        $teachers = Teacher::all();
        $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        $rg = '/[\w\-.]+@(gmail.com)/m';
        $correo = '/[\w\-.]+@[\w\-.]+.[\w\-.]/m';
        $total = 0;
        $no_aptos = [];

        foreach ($teachers as $empadronado) {
            $valida_institucional = preg_match($re, $empadronado->correo_institucional);
            $valida_personal = preg_match($rg, $empadronado->correo_personal);
            $valida_personal_i = preg_match($re, $empadronado->correo_personal);

            if( ($valida_institucional == 0 || $valida_institucional == false) && ($valida_personal == 0 || $valida_personal == false) && ($valida_personal_i == 0 || $valida_personal_i == false) ){
                if( $empadronado->correo_personal !== null && $empadronado->correo_personal !== " " ){
                    $total = $total + 1;
                    if(preg_match($correo, $empadronado->correo_personal) && $total > 82){
                        Mail::to(trim($empadronado->correo_personal))->queue(new SolicitudNoAptos($empadronado));
                        array_push($no_aptos, trim($empadronado->correo_personal).'-'.$empadronado->correo_institucional);
                    }
                }
            }
        }
        
        return response()->json(["total" => $total." correos enviados", "no aptos" => $no_aptos]);
    }

    
    public function enviarCorreoInvitacion(){
        ini_set('max_execution_time', 180000);
        $teachers = Teacher::all();
        $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        $rg = '/[\w\-.]+@(gmail.com)/m';
        $correo = '/[\w\-.]+@[\w\-.]+.[\w\-.]/m';
        $total = 0;
        $no_aptos = [];

        foreach ($teachers as $empadronado) {
            $valida_institucional = preg_match($re, $empadronado->correo_institucional);
            $valida_personal = preg_match($rg, $empadronado->correo_personal);
            $valida_personal_i = preg_match($re, $empadronado->correo_personal);

            if( $valida_institucional == 1 ){
                Mail::to(trim($empadronado->correo_institucional))->queue(new InvitacionVoto($empadronado));
                $total = $total + 1;
            } 
            elseif( $valida_personal == 1){
                Mail::to(trim($empadronado->correo_personal))->queue(new InvitacionVoto($empadronado));
                $total = $total + 1;
            }
            elseif( $valida_personal_i == 1){
                Mail::to(trim($empadronado->correo_personal))->queue(new InvitacionVoto($empadronado));
                $total = $total + 1;
            }  
        }
        
        return response()->json(["total" => $total." correos enviados"]);
    }
}
