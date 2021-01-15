<?php

namespace App\Http\Controllers;

use App\Mail\CredentialsUser;
use App\Person;
use App\Scope;
use App\ScopeUser;
use App\User;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Vista lista de usuarios'
            ]);
        $users = DB::table('users')
                ->selectRaw('users.*, person.names, person.last_names')
                ->join('person', 'users.person_id', '=', 'person.id')
                ->get();
        
        return view('admin.users.list', ["users" => $users]);
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
                "actividad" => 'Vista crear usuario'
            ]);
        $scopes = Scope::all();
        return view('admin.users.create', ["scopes" => $scopes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $password = Str::random(10);
            DB::transaction(function () use ($request, $password){
                $person = Person::create([
                    "names" => $request->names,
                    "last_names" => $request->last_names,
                    "address" => $request->address,
                    "phone" => $request->phone,
                    "dni" => $request->dni
                ]);
    
                $user = User::create([
                    "email" => $request->email,
                    "password" => Hash::make($password),
                    "is_admin" => $request->scope == 1 ? true : false,
                    "person_id" => $person->id
                ]);
    
                ScopeUser::create([
                    "user_id" => $user->id,
                    "scope_id" => 1
                ]);
            });
            Mail::to($request->only(['email']))->queue(new CredentialsUser($password,$request->email, $request->names));
            return redirect(route('user.index'));
        } catch (\Throwable $th) {
            return redirect(route('user.create'))->withErrors(["register-error" => "Email ingresado ya se encuentra registrado."]);
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
        $user  = User::find($id);
        $person = Person::find($user->person_id);

        $fecha_actual = new Carbon(now('America/Lima'));
        if(Auth::check())
            UserActivity::create([
                "hora_entrada" => $fecha_actual->format('H:i:s'),
                "user_id" => Auth::id(),
                "actividad" => 'Vista editar usuario'
            ]);

        return view('admin.users.create', ["user" => $user, "person" => $person]);
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
        try {
            DB::transaction(function () use ($request, $id){
                $user = User::find($id);
                $person = Person::find($user->person_id);
                $person->update($request->only(['names', 'last_names', 'address', 'phone', 'dni']));
                $user->update([
                    "email" => $request->email,
                    "is_admin" => $request->scope == 1 ? true : false,
                ]);
            });
            return redirect(route('user.index'));
        } catch (\Throwable $th) {
            return redirect(route('user.create'))->withErrors(["register-error" => "Email ingresado ya se encuentra registrado."]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $person = Person::find($user->person_id);

        $user->delete();
        $person->delete();

        return redirect()->back();
    }
}
