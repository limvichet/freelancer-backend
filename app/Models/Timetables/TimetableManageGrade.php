<?php

namespace App\Models\Timetables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableManageGrade extends Model
{
    use HasFactory;

    protected $table = 'sys_timetable_manage_grades';
    protected $primaryKey = 'tgrade_id';

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [];

    // Academic year
    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'academic_id', 'year_id');
    }

    // Grade
    public function grade()
    {
        return $this->belongsTo('App\Models\Grade', 'grade_id', 'grade_id');
    }

    // Staff
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'payroll_id');
    }
}
