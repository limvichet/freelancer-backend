<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Province;
use App\Models\District;
use App\Models\QualificationCode;
use App\Models\TrainingPartnerType;

class ScheduleCourse extends Model
{
    use HasFactory;

    protected $table = 'cpd_schedule_courses';
    protected $primaryKey = 'schedule_course_id';

    protected $guarded = [];
    protected $casts = [];

    // CPD structure course info
    public function CPDCourse()
    {
        return $this->belongsTo(Course::class, 'cpd_course_id');
    }

    // Qualification info
    public function qualification()
    {
        return $this->belongsTo(QualificationCode::class, 'qualification_code');
    }

    // Partner type info
    public function partnerType()
    {
        return $this->belongsTo(TrainingPartnerType::class, 'partner_type_id');
    }

    // Teacher educator info
    public function teacherEducator()
    {
        return $this->belongsTo(TeacherEducator::class, 'teacher_educator_id');
    }

    // Learning option info
    public function learningOption()
    {
        return $this->belongsTo(LearningOption::class, 'learning_option_id');
    }

    // Province info
    public function province()
    {
        return $this->belongsTo(Province::class, 'pro_code');
    }

    // District info
    public function district()
    {
        return $this->belongsTo(District::class, 'dis_code');
    }

    // Target audiences
    public function targetAudiences()
    {
        return $this->hasMany(CourseAudience::class, 'schedule_course_id');
    }

    public function targetAudiencesCourse()
    {
        return $this->targetAudiences()
            ->join('cpd_schedule_courses','cpd_course_audiences.schedule_course_id','=','cpd_schedule_courses.schedule_course_id')
            ->get()
        ;
    }

    public function targetAudiencesPosition()
    {
        return $this->targetAudiences()
            ->join('sys_positions','cpd_course_audiences.position_id','=','sys_positions.position_id')
            ->get()
        ;
    }

    // Enrollment Courses
    public function enrollmentCourses()
    {
        return $this->hasMany(EnrollmentCourse::class, 'schedule_course_id');
    }

    // Provider info
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
