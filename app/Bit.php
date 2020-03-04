<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Bit extends Model
{
    protected $fillable = [
        "title",
        "snippet"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'bit_id', 'user_id');
    }

    public function isLiked(): bool
    {
        return $this->likes->contains('id', auth()->id());
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'bit_id', 'user_id');
    }

    public function isBookmarked(): bool
    {
        return $this->bookmarks->contains('id', auth()->id());
    }
}
