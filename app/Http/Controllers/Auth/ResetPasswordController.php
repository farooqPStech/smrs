<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo(){
        
        // User role
        $type = auth()->user()->type; 
        $status = auth()->user()->active_status;   

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
                return '/home';
                break;
        }
    }
}
