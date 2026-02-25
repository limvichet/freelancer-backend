<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAudience extends Model
{
    use HasFactory;

    protected $table = 'cpd_course_audiences';
    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [];
}
