<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumAccessToken;

/**
 * We must extend the base Sanctum model and explicitly define $fillable
 * to allow the 'expires_at' field to be mass assigned when we call
 * $user->createToken(...) with an expiration date.
 */
class PersonalAccessToken extends SanctumAccessToken
{
     protected $casts = [
        'abilities' => 'json',
        'expires_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
    ];
}
