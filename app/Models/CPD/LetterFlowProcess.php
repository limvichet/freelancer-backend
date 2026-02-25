<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterFlowProcess extends Model
{
    use HasFactory;

    protected $table = 'letter_flow_process';
    protected $primaryKey = 'id';

    protected $guarded = [];
    protected $casts = [];
}
