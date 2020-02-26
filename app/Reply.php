<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        "user_id",
        "bit_id",
        "reply"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
