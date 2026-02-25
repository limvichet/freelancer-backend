<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'location_type_id' => $this->location_type_id,
            'edu_level_id' => $this->edu_level_id,
            'pro_code' => $this->pro_code,
            'dis_code' => $this->dis_code,
            'com_code' => $this->com_code,
            'vil_code' => $this->vil_code,
            'location_code' => $this->location_code,
            'temporary_code' => $this->temporary_code,
            'emis_code' => $this->emis_code,
            'location_kh' => $this->location_kh,
            'schoolclaster' => $this->schoolclaster,
            'school_annex' => $this->school_annex,
            'main_school' => $this->main_school,
            'region_id' => $this->region_id,
            'multi_level_edu' => $this->multi_level_edu,
            'disadvantage' => $this->disadvantage,
            'location_history' => $this->location_history,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
