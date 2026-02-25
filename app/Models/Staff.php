<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'sys_staffs';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'staff_name',
        'staff_gender',
        'staff_dob',
        'position_id',
        'start_qualification_id',
        'start_qualification_institution_id',
        'start_qualification_date',
        'current_qualification_id',
        'current_qualification_institution_id',
        'current_qualification_subject_id',
        'current_qualification_date',
        'start_professional_id',
        'start_professional_subject_id_1',
        'start_professional_subject_id_2',
        'start_professional_type_id',
        'start_professional_institution_id',
        'start_professional_date',
        'status_id',
        'current_professional_id',
        'current_professional_subject_id_1',
        'current_professional_subject_id_2',
        'current_professional_type_id',
        'current_professional_institution_id',
        'current_professional_date',
        'start_salary_level_id',
        'start_salary_degree',
        'start_salary_date',
        'current_salary_level_id',
        'current_salary_degree',
        'current_salary_date',
        'payroll_id',
        'location_code',
        'staff_phone',
        'staff_email',
        'staff_account_number',
        'staff_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];



    public $casts = [
        'location_code' => 'string',
    ];
}
