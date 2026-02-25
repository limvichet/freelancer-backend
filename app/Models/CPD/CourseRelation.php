<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRelation extends Model
{
    use HasFactory;

    protected $table = 'cpd_course_relations';
    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [];
}
