<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMPHRAPI extends Model
{
    use HasFactory;

    protected $table = 'temp_hr_api';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];
}
