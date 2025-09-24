<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
  

    protected $table = 'admin_password_resets';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'expires_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

}
