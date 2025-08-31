<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        'footballPlayerName'
    ];
    public function User() :BelongsTo {
        return $this->belongsTo(User::class);
    }
}
