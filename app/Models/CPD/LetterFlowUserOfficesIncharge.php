<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterFlowUserOfficesIncharge extends Model
{
    use HasFactory;

    protected $table = 'letter_flow_user_offices_incharge';
    protected $primaryKey = 'id';

    protected $guarded = [];
    protected $casts = [];
}
