<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\User;
class Reading extends Model
{
    use Notifiable;

    protected $table = 'readings';
    protected $fillable = ['created_at'];

    public function meter() {
        return $this->belongsTo(Meter::class);
    }

    public function meterReader() {
        return $this->belongsTo(User::class, 'meter_reader_id', 'id');
    }

    public function obstacleCode(){
        return $this->belongsTo(ObstacleCode::class)->withDefault([
            'description' => '',
        ]);
    }

    // public function meterLocation(){
    //     return $this->belongsTo(MeterLocation::class);
    // }

    public function issueCode(){
        return $this->belongsTo(IssueCode::class)->withDefault(
            [
                'description' => '',
            ]
        );
    }

    public function rejectCode1(){
        return $this->belongsTo(RejectCode::class, 'reject_code1', 'id');
    }

    public function rejectCode2(){
        return $this->belongsTo(RejectCode::class, 'reject_code2', 'id');
    }

    // public function consumer() {
    //     return $this->hasOne(Consumer::class);
    // }
}
