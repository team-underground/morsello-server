<?php

namespace App;

use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bit extends Model
{
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    protected $fillable = [
        "title",
        "snippet"
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bit) {
            $bit->uuid = (string) Str::uuid();
        });
    }

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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'tags');
    }

    public function associatedTags()
    {
        return $this->tags->sortBy('name')->pluck('name');
    }
}
