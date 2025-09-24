<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalType extends Model
{
    use HasFactory;

    protected $table = 'sys_staff_professional_types';

    protected $primaryKey = 'professional_type_id';

    public $timestamps = false;

    protected $fillable = ['professional_type_id', 'professional_type_kh', 'professional_type_en', 'active', 'created_by', 'updated_by'];

    public function scopeActive($query)
    {
    	return $query->where('active', 1);
    }
}
