<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    public function bits(): BelongsToMany
    {
        return $this->belongsToMany(Bit::class, 'tags');
    }
}
