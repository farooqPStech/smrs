<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MeterLocation extends Model
{
    use Notifiable;

    protected $table = 'meter_locations';

    // public function meter() {
    //         return $this->hasOne(Meter::class);
    // }
}
