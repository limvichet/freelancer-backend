<?php

namespace App\Models\Timetables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableGeneratedPrimary extends Model
{
    use HasFactory;

    protected $table = 'sys_timetable_generated_primaries';
    protected $primaryKey = 'tprimary_id';

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [];

    // Academic year
    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'academic_id', 'year_id');
    }


}
