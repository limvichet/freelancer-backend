<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectReason extends Model
{
    use HasFactory;

    protected $table = 'cpd_reject_reasons';
    protected $primaryKey = 'reason_id';

    protected $guarded = [];
    protected $casts = [];
}
