<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordExpiredRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { //Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character:
        return [
            'current_password' => 'required',
            'password' => ['regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$^',
                    function ($attribute, $value, $fail) {
                        if (Hash::check($value, Auth::user()->password)) {
                            $fail('New Password cant be same with old password');
                        }
                    },
               'confirmed'],
        ];
    }
}
