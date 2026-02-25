<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEducatorCourse extends Model
{
    use HasFactory;

    protected $table = 'cpd_educator_courses';
    protected $primaryKey = 'educator_course_id';

    protected $guarded = [];
    protected $casts = [];

    // Teacher educator info
    public function teacherEducator()
    {
        return $this->belongsTo(TeacherEducator::class, 'teacher_educator_id');
    }

    // CPD course info
    public function CPDCourse()
    {
        return $this->belongsTo(Course::class, 'cpd_course_id');
    }
}
