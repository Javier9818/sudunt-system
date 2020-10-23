<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
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
            if($user->is_admin) return redirect()->intended('/');
        }else
            return redirect('/login')->withErrors(['login-error' => 'Email o contraseña incorrecto']);
    }



	//LOGIN CON GOOGLE
	public function login_google(){
		//dd(Socialite::driver('google')->redirect());
		return Socialite::driver('google')->redirect();
	}
	//LOGIN CON GOOGLE
	public function return_google(){
		$getInfo = Socialite::driver('google')->user();
		$email = $getInfo->email;
		$email = base64_encode($email);
		return redirect('/'.'votacion-desde-google/'.$email);
		//dd();
	}

}
