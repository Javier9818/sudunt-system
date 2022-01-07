<?php

namespace App\Http\Controllers;

use App\Logs;
use App\Mail\DatosDocenteActualizados;
use App\Mail\InvitacionVoto;
use App\Mail\SolicitudNoAptos;
use App\Teacher;
use App\UserActivity;
use App\Vote;
use App\VoteRegister;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Ver lista de empadronados'
            ]);
        return view('admin.padron.list', ["teachers" => $teachers, "total" => count($teachers)]);
    }

    public function noAptos()
    {
        //$this->setAptos();
        $teachers = Teacher::select('dni', 'nombres', 'correo_personal', 'correo_institucional', 'code')->where('status', 1)->orderBy('nombres', 'asc')->get();
        $padron = Teacher::select('dni', 'nombres', 'correo_personal', 'correo_institucional')->orderBy('nombres', 'asc')->get();
        $aptos = $this->getAptos();
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

        $repetidos_institucional = Teacher::selectRaw('correo_institucional, GROUP_CONCAT(code) as codigos, GROUP_CONCAT(nombres) as nombres, count(id) as repetidos')->groupBy('correo_institucional')
        ->having('repetidos', '>', 1)
        ->get();

        $repetidos_personal = Teacher::selectRaw('correo_personal, GROUP_CONCAT(code) as codigos, GROUP_CONCAT(nombres) as nombres, count(id) as repetidos')->groupBy('correo_personal')
        ->having('repetidos', '>', 1)
        ->get();

        // return response()->json(["docentes" => $repetidos_institucional]);

        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Ver lista de no aptos'
            ]);

        $votantes = VoteRegister::join('teachers', 'teachers.id', '=', 'vote_register.teacher_id')->orderBy('teachers.nombres', 'asc')->get();
        $no_votantes = $this->noVotantes($aptos);
        return view('admin.padron.list_no_aptos', [
            "teachers" => $no_aptos,
            "total" => count($no_aptos),
            "inlocalizables" => $sin_correo,
            "repetidos_personal" => $repetidos_personal,
            "repetidos_institucional" => $repetidos_institucional,
            "padron" => $padron,
            "aptos" => $aptos,
            "votantes" => $votantes,
            "no_votantes" => $no_votantes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Vista registro empadronado'
            ]);
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
        $this->validarEmail($request, null);

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
        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Vista edicion empadronado'
            ]);
        $teacher = Teacher::find($id);
         $teacher->token = '';
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
        $this->validarEmail($request, $teacher);
        if($teacher->correo_institucional !== $request->correo_institucional && strlen($request->correo_institucional) > 0)
            Mail::to($request->correo_institucional)->queue(new DatosDocenteActualizados($teacher));
        else if($teacher->correo_personal !== $request->correo_personal && strlen($request->correo_personal) > 0)
            Mail::to($request->correo_personal)->queue(new DatosDocenteActualizados($teacher));

        $teacher->update([
            "correo_personal" => $request->correo_personal,
            "correo_institucional" => $request->correo_institucional,
            "facultad" => $request->facultad,
            "departamento" => $request->departamento,
            "categoria" => $request->categoria,
            "sexo" => $request->sexo,
            "nombres" => $request->nombres,
            // "status" => $request->status
        ]);

        // $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        // $rg = '/[\w\-.]+@(gmail.com)/m';

        // $valida_institucional = preg_match($re, $request->correo_institucional);
        // $valida_personal = preg_match($rg, $request->correo_personal);
        // $valida_personal_i = preg_match($re, $request->correo_personal);

        // $correo_apto = $valida_institucional  || $valida_personal  || $valida_personal_i;

        // if(!$correo_apto){
        //     $teacher->status = 0;
        //     $teacher->save();
        // }

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
        if(VoteRegister::where('teacher_id', $id)->exists())
            return redirect('/padron');
        else
            Teacher::find($id)->delete();
            return redirect('/padron');

    }

    public function obtenerEmpadronado($code){
        if($code > 0)
            $empadronado = Teacher::select('nombres', 'correo_institucional', 'correo_personal', 'categoria')->where('code', $code)->first();

        return response()->json(["empadronado" => $empadronado ?? null]);
    }

    public function generateTokens(){
        ini_set('max_execution_time', 180000);
        $empadronados = Teacher::all();
        foreach ($empadronados as $empadronado) {
            $empadronado->update([
                "token" =>  preg_replace("/\//i", "online", Hash::make($empadronado->code.$empadronado->id))
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

    public function trimear(){
        ini_set('max_execution_time', 180000);
        $teachers = Teacher::all();
        $correo = '/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/';
        foreach ($teachers as $teacher ) {
            preg_match($correo, $teacher->correo_personal, $personal, PREG_OFFSET_CAPTURE);
            preg_match($correo, $teacher->correo_institucional, $institucional, PREG_OFFSET_CAPTURE);
            $teacher->update([
                "correo_personal" => $personal[0][0] ?? null,
                "correo_institucional" => $institucional[0][0] ?? null
            ]);
        }
        return "Se trimero correctamente";
    }

    public function correosRepetidos(){
        ini_set('max_execution_time', 180000);

        $teachers = Teacher::selectRaw('correo_institucional, GROUP_CONCAT(code) as codigos, count(id) as repetidos')->groupBy('correo_institucional')
                    ->having('repetidos', '>', 1)
                    ->get();

        $teachers2 = Teacher::selectRaw('correo_personal, GROUP_CONCAT(code) as codigos, count(id) as repetidos')->groupBy('correo_personal')
        ->having('repetidos', '>', 1)
        ->get();

        return response()->json([
            "docentes_institucional" => $teachers,
            "docentes_personal" => $teachers2
        ]);
    }

    public function difCreatedUpdated(){
        $votos = Vote::select('id', 'created_at', 'updated_at')->whereRaw('timestampdiff(HOUR, created_at, updated_at) != 0 OR timestampdiff(MINUTE, created_at, updated_at) != 0 OR timestampdiff(SECOND, created_at, updated_at) != 0')->get();
        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Vista auditoria/registros con fecha alterada'
            ]);
        return view('admin.auditoria.fecha_alterada', ["votos" => $votos]);
    }

    public function actividades(){
        $actividades = UserActivity::select('hora_entrada', 'user_id', 'actividad', 'created_at')->with('usuario')->orderBy('created_at', 'desc')->get();
        return view('admin.auditoria.registro_votos', ["actividades" => $actividades]);
    }

    public function logs(){
        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Vista auditoria/logs con fecha alterada'
            ]);
        $logs = Logs::orderBy('fecha', 'desc')->whereRaw('typo = ? OR typo = ?', ['UPDATE', 'DELETE'])->get();
        return view('admin.auditoria.logs', ["logs" => $logs]);
    }

    public function setCesantes(){
        ini_set('max_execution_time', 180000);
        $cesantes = DB::table('cesantes')->get();
        foreach ($cesantes as $cesante) {
            $teacher = Teacher::where('code', $cesante->code)->first();
            if($teacher !== null){
                $teacher->is_activo = false;
                $teacher->save();
            }
        }

        return response()->json(["message" => "Se actualizó cesantes de manera satisfactoria."]);
    }

    public function setAptos(){
        ini_set('max_execution_time', 180000);

        Teacher::where('is_activo', 1)->update([
            "status" => 1
        ]);

        Teacher::where('is_activo', 0)->update([
            "status" => 0
        ]);

        // $teachers = Teacher::all();
        // $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        // $rg = '/[\w\-.]+@(gmail.com)/m';
        // foreach ($teachers as $teacher) {
        //     $valida_institucional = preg_match($re, $teacher->correo_institucional);
        //     $valida_personal = preg_match($rg, $teacher->correo_personal);
        //     $valida_personal_i = preg_match($re, $teacher->correo_personal);

        //     $correo_no_apto = ($valida_institucional == 0 || $valida_institucional == false) && ($valida_personal == 0 || $valida_personal == false) && ($valida_personal_i == 0 || $valida_personal_i == false);

        //     if($correo_no_apto){ // AQUI PONER CONDICIONAL EXTRA POR SI CESANTES SON NO APTOS
        //         $teacher->status = 0;
        //         $teacher->save();
        //     }
        // }
        return "Se actualizó padrón satisfactoriamente.";
    }

    public function validarEmail($request, $teacher){
        if($teacher !== null){
            if(strlen($request->correo_institucional) > 0 && trim($request->correo_institucional) !== $teacher->correo_institucional)
            $request->validate([
                'correo_institucional' => 'unique:teachers',
            ]);

            if(strlen($request->correo_personal) > 0 && trim($request->correo_personal) !== $teacher->correo_personal)
            $request->validate([
                'correo_personal' => 'unique:teachers',
            ]);
        }else{
            if(strlen($request->correo_institucional) > 0)
                $request->validate([
                    'correo_institucional' => 'unique:teachers',
                ]);

            if(strlen($request->correo_personal) > 0)
                $request->validate([
                    'correo_personal' => 'unique:teachers',
                ]);
        }

    }

    public function  getAptos(){
        $aptos = Teacher::select('dni', 'id','nombres', 'correo_personal', 'correo_institucional')->where('status', 1)->orderBy('nombres', 'asc')->get();
        $re = '/[\w\-.]+@(unitru.edu.pe)/m';
        $rg = '/[\w\-.]+@(gmail.com)/m';
        $response = [];
        foreach ($aptos as $teacher) {
            $valida_institucional = preg_match($re, $teacher->correo_institucional);
            $valida_personal = preg_match($rg, $teacher->correo_personal);
            $valida_personal_i = preg_match($re, $teacher->correo_personal);

            if( $valida_institucional == 1 || $valida_personal == 1 || $valida_personal_i == 1){
                if($valida_institucional == 1)
                    $teacher->correo = trim($teacher->correo_institucional);
                elseif($valida_personal == 1 || $valida_personal_i == 1)
                    $teacher->correo = trim($teacher->correo_personal);
                else
                    $teacher->correo = '-';

                array_push($response, $teacher);
            }

        }

        return $response;
    }

    public function updateEmails(){
        ini_set('max_execution_time', 180000);
        $docentes_update = DB::table('docentes_correo')->get();
        foreach ($docentes_update as $docente) {
            $teacher = Teacher::where('code', $docente->code)->first();
            if($teacher){
                $teacher->correo_institucional = $docente->correo;
                $teacher->update();
            }
        }

        return response()->json(["response" => "Todo bien."]);
    }

    public function noVotantes($aptos){
        $no_votantes = [];

        foreach ($aptos as $key => $apto) {
            if( !VoteRegister::where('teacher_id', $apto->id)->exists())
                array_push($no_votantes, $apto);
        }

        return $no_votantes;
    }
}
