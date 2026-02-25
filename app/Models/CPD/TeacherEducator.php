<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Staff;

class TeacherEducator extends Model
{
    use HasFactory;

    protected $table = 'cpd_teacher_educators';
    protected $primaryKey = 'teacher_educator_id';

    protected $guarded = [];
    protected $casts = [];

    // Staff info
    public function staffInfo()
    {
        return $this->belongsTo(Staff::class, 'payroll_id');
    }

    // Temporary position
    public function tempPosition()
    {
        return $this->belongsTo(TempPosition::class, 'teps_position_id');
    }

    // Teacher educator courses
    public function teacherEDUCourses()
    {
        return $this->hasMany(TeacherEducatorCourse::class, 'teacher_educator_id');
    }
}
