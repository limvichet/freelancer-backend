<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'cpd_courses';
    protected $primaryKey = 'cpd_course_id';

    protected $guarded = [];
    protected $casts = [];

    // Only active
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    // Course type
    public function courseType()
    {
        return $this->belongsTo(CourseType::class, 'cpd_course_type_id');
    }

    // Learning option info
    public function learningOption()
    {
        return $this->belongsTo(LearningOption::class, 'learning_option_id');
    }

     // Learning option info
    public function creditOption()
    {
        return $this->belongsTo(CreditOption::class, 'credit_option_id');
    }


    // Course relations
    public function courseRelations()
    {
        return $this->hasMany(CourseRelation::class, 'cpd_course_id');
    }

    public function fieldOfStudy()
    {
        return $this->courseRelations()
            ->join('cpd_field_studies','cpd_course_relations.cpd_field_id','=','cpd_field_studies.cpd_field_id')
            ->get()
        ;
    }

    public function subjectOfStudy()
    {
        return $this->courseRelations()
            ->join('cpd_subjects','cpd_course_relations.cpd_subject_id','=','cpd_subjects.cpd_subject_id')
            ->get()
        ;
    }

    // Custom field
    public function getFullCourseAttribute()
    {
        return $this->cpd_course_code.' - '.$this->cpd_course_kh;
    }

    // Providers
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'cpd_course_providers', 'cpd_course_id', 'provider_id');
    }
}
