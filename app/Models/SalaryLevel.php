<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLevel extends Model
{
    use HasFactory;

    protected $table = 'sys_staff_salary_levels';

    protected $primaryKey = 'salary_level_id';

    public $timestamps = false;

    protected $fillable = ['salary_level_id', 'salary_level_kh', 'salary_level_en'];

    public function scopeActive($query)
    {
    	return $query->where('active', 1);
    }

}
