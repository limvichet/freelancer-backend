<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentStatus extends Model
{
    use HasFactory;

    protected $table = 'cpd_enrollment_status';
    protected $primaryKey = 'enroll_status_id';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];
}
