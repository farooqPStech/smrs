<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use Notifiable;

    protected $table = 'consumers';

    public function meter() {
        return $this->hasMany(Meter::class);
    }


    public function reading()
    {
        return $this->hasManyThrough(Reading::class, Meter::class, 'consumer_id', 'meter_id', 'id', 'id');
    }
}
