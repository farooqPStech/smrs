<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'full_name',
        'username',
        'email',
        'mobile_phone',
        'type',
        'active_status',
        'image',
        'branch_id',
        'email_verified_at',
        'password',
        'remember_token',
        'uuid',
        'first_time_login',
        'created_at',
        'updated_at',
        'password_changed_at'
    ];

    public function branch() {
        return $this->belongsTo('App\Models\Branch');
    }
}
