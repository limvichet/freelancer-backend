<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectOfStudy extends Model
{
    use HasFactory;

    protected $table = 'cpd_subjects';
    protected $primaryKey = 'cpd_subject_id';

    protected $guarded = [];
    protected $casts = [];

    // Only active
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    // Field of study
    public function fieldOfStudy()
    {
        return $this->belongsTo(FieldOfStudy::class, 'cpd_field_id');
    }

    public function getSubjectCodeKHAttribute()
    {
        return $this->cpd_subject_code.' - '.$this->cpd_subject_kh;
    }
}
