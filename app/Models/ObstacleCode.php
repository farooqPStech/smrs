<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ObstacleCode extends Model
{
    use Notifiable;

    protected $table = 'obstacle_codes';

    public function readings(){
        return $this->hasMany(Reading::class);
    }
}
