<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class HandheldRoute extends Model
{
    use Notifiable;

    protected $table = 'handheld_routes';

    function Route() {
        return $this->belongsTo(Route::class)->withDefault([
            'route' => 'Unassigned'
        ]);
    }

    function Handheld() {
        return $this->belongsTo(Handheld::class)->withDefault([
            'label' => '-',
            'uuid' => ''
        ]);
    }
}
