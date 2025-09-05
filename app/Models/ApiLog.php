<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'method', 'url', 'ip', 'headers', 'params', 'user_id'
    ];

    protected $casts = [
        'headers' => 'array',
        'params'  => 'array',
    ];
}
