<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use Notifiable;

    protected $table = 'meters';

    public function readings() 
    {
        return $this->hasMany(Reading::class);
    }

    public function meterLocation(){
        return $this->belongsTo(MeterLocation::class);
    }

    public function consumer(){
        return $this->belongsTo(Consumer::class);
    }
}
