<?php

namespace App\Models;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use Notifiable;

    protected $table = 'routes';

    function Branch(){
        return $this->belongsTo(Branch::class, 'branch_code', 'code')->withDefault([
            'code' => 'No Branch',
            'name' => 'No Branch',
        ]);
    }

    function User(){
        return $this->belongsTo(User::class, 'id', 'user_id')->withDefault([
            'user_id' => ''
        ]);
    }

    function Consumers() {
        return $this->hasMany(Consumer::class);
    }


}
