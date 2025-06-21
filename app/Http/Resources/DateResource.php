<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'date'  => $this->date,
            'lunar_date' => $this->lunar_date,
            'soriyakitek_date' => $this->soriyakitek_date,
        ];
    }
}
