<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Person;
use App\Providers\RouteServiceProvider;
use App\Teacher;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();
            $person = Person::find($user->person_id);
            session(["names" => $person->names]);
            if($user->is_admin) return redirect()->intended('/user');
            else return redirect(route('form.index'));
        }else
            return redirect('/login')->withErrors(['login-error' => 'Email o contraseña incorrecto']);
    }



	//LOGIN CON GOOGLE
	public function login_google(){
		return Socialite::driver('google')->redirect();
	}
	//LOGIN CON GOOGLE
	public function return_google(){
        try {
            $getInfo = Socialite::driver('google')->user();
            $teacher = Teacher::where('correo_institucional', $getInfo->email)->orWhere('correo_personal', $getInfo->email)->first();
            if($teacher !== null)
                return redirect('/'.'votacion/'.
                $teacher->token);
            else
                return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors(["login-error" => "El usuario no se encuentra registrado."]);
        } catch (\Throwable $th) {
            return redirect('/sufragio-sudunt/autenticar-empadronado')->withErrors(["login-error" => "Ha ocurrido un error, porfavor inténtelo nuevamente."]);
        }
	}

}
