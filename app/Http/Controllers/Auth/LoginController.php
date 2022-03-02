<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo(){
        
        // User role
        $type = auth()->user()->type;   
        $status = auth()->user()->active_status;   
        $login = auth()->user()->first_time_login;   


        if ($status == 0) {
            auth()->logout();
            return '/login'; 
        }
        
        // Check user role
        switch ($type) {
            case 4:
                auth()->logout();
                return '/login'; 
                break;
            default:
                if ($login == 1)
                {
                    return '/user/changepassword';
                    break;
                }
                else{
                    return '/home';
                    break;
                }
        }
    }
}
