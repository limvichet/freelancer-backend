<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldOfStudy extends Model
{
    use HasFactory;

    protected $table = 'cpd_field_studies';
    protected $primaryKey = 'cpd_field_id';

    protected $fillable = [
            'cpd_field_code', 
            'cpd_field_kh', 
            'cpd_field_en', 
            'active', 
            'cpd_field_desc_kh', 
            'cpd_field_desc_en',
            'created_by', 
            'updated_by'
        ];

    //protected $guarded = ['cpd_field_id'];
    protected $casts = [];

    // Only active
    public function scopeActive($query)
    {
    	return $query->where('active', 1);
    }

    // Combine field_kh with code
    public function getFieldCodeKHAttribute()
    {
        return $this->cpd_field_code.' - '.$this->cpd_field_kh;
    }
}
