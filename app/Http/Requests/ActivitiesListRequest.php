<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivitiesListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set this to true to allow anyone to use this API endpoint.
        // If you require authentication, you would add logic here.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // Page and item count for pagination
            'page' => ['integer', 'min:1'],
            'item_per_page' => ['integer', 'min:1', 'max:100'],

            // Searchable properties.
            'provider_id' => ['nullable', 'integer'],
            'subject_id' => ['nullable', 'integer'],
            'providers' => ['nullable', 'array'],
            'providers.*' => ['integer'],
            'modes' => ['nullable', 'array'],
            'modes.*' => ['integer'],
            'provinces' => ['nullable', 'array'],
            'provinces.*' => ['string'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'string', 'in:active,inactive,all']
        ];
    }
}
