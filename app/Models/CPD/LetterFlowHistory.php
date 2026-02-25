<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterFlowHistory extends Model
{
    use HasFactory;

    protected $table = 'letter_flow_histories';
    protected $primaryKey = 'id';

    protected $guarded = [];
    protected $casts = [];
}
