<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'staff_id' => $this->staff_id,
            'staff_name' => $this->staff_name,
            'staff_gender' => $this->staff_gender,
            'staff_dob' => $this->staff_dob,
            'position_id' => $this->position_id,
            'start_qualification_id' => $this->start_qualification_id,
            'start_qualification_institution_id' => $this->start_qualification_institution_id,
            'start_qualification_date' => $this->start_qualification_date,
            'current_qualification_id' => $this->current_qualification_id,
            'current_qualification_institution_id' => $this->current_qualification_institution_id,
            'current_qualification_subject_id' => $this->current_qualification_subject_id,
            'current_qualification_date' => $this->current_qualification_date,
            'start_professional_id' => $this->start_professional_id,
            'start_professional_subject_id_1' => $this->start_professional_subject_id_1,
            'start_professional_subject_id_2' => $this->start_professional_subject_id_2,
            'start_professional_type_id' => $this->start_professional_type_id,
            'start_professional_institution_id' => $this->start_professional_institution_id,
            'start_professional_date' => $this->start_professional_date,
            'status_id' => $this->status_id,
            'current_professional_id' => $this->current_professional_id,
            'current_professional_subject_id_1' => $this->current_professional_subject_id_1,
            'current_professional_subject_id_2' => $this->current_professional_subject_id_2,
            'current_professional_type_id' => $this->current_professional_type_id,
            'current_professional_institution_id' => $this->current_professional_institution_id,
            'current_professional_date' => $this->current_professional_date,
            'start_salary_level_id' => $this->start_salary_level_id,
            'start_salary_degree' => $this->start_salary_degree,
            'start_salary_date' => $this->start_salary_date,
            'current_salary_level_id' => $this->current_salary_level_id,
            'current_salary_degree' => $this->current_salary_degree,
            'current_salary_date' => $this->current_salary_date,
            'payroll_id' => $this->payroll_id,
            'location_code' => $this->location_code,
            'staff_phone' => $this->staff_phone,
            'staff_email' => $this->staff_email,
            'staff_account_number' => $this->staff_account_number,
            'staff_active' => $this->staff_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
