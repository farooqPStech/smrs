<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueCode extends Model
{
    protected $table = 'issue_codes';
    public function readings()
    {
        return $this->belongsTo(Reading::class);
    }
}
