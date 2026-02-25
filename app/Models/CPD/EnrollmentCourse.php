<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Staff;
use App\Models\CPD\ScheduleCourse;
class EnrollmentCourse extends Model
{
    use HasFactory;

    protected $table = 'cpd_enrollment_courses';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];

    // Staff info
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'payroll_id');
    }
    public function scheduled_course()
    {
        return $this->belongsTo(ScheduleCourse::class, 'schedule_course_id');
    }
}
