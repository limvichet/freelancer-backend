<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPosition extends Model
{
    use HasFactory;

    protected $table = 'cpd_teps_positions';
    protected $primaryKey = 'teps_position_id';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];
}
