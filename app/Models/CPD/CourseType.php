<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    use HasFactory;

    protected $table = 'cpd_course_types';
    protected $primaryKey = 'cpd_course_type_id';

    protected $guarded = [];
    protected $casts = [];
}
