<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\GraphQL\Traits\SendPasswordResetRequest;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Joselfonseca\LighthouseGraphQLPassport\HasSocialLogin;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SendPasswordResetRequest, HasSocialLogin;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bits()
    {
        return $this->hasMany(Bit::class)->orderBy('created_at', 'DESC');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Bit::class, 'likes', 'user_id', 'bit_id');
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Bit::class, 'bookmarks', 'user_id', 'bit_id');
    }
}
