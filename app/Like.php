<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "user_id",
        "bit_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bit()
    {
        return $this->belongsTo(Bit::class);
    }
}
