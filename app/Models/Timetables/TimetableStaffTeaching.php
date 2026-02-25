<?php

namespace App\Models\Timetables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableStaffTeaching extends Model
{
    use HasFactory;

    protected $table = 'sys_timetable_staff_teachings';
    protected $primaryKey = 'tteaching_id';

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [];

    // Academic year
    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'academic_id', 'year_id');
    }

    // Staff
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'payroll_id');
    }
}
