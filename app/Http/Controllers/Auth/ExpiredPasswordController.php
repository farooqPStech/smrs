<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\PasswordExpiredRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


use App\PasswordHistory;

class ExpiredPasswordController extends Controller
{
    public function expired()
    {
        return view('auth.passwords.expired');
    }

    public function postExpired(PasswordExpiredRequest $request)
    {
        // Checking current password
        if (!Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is not correct']);
        }

        $request->user()->update([
            'password' => bcrypt($request->password),
            'password_changed_at' => Carbon::now()->toDateTimeString()
        ]);

        $passwordHistory = new PasswordHistory();
        $passwordHistory->user_id = $request->user()->id;
        $passwordHistory->password = $request->user()->password;
        $passwordHistory->save();

        return redirect()->back()->with(['status' => 'Password changed successfully']);
    }
}
