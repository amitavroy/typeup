<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackClickRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint, validation handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_key' => 'required|string|exists:sites,site_key',
            'search_id' => 'required|string|exists:searches,search_id',
            'content_id' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
            'metadata' => 'sometimes|array',
            'metadata.element_type' => 'sometimes|string|max:50',
            'metadata.click_time' => 'sometimes|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'site_key.required' => 'Site key is required.',
            'site_key.exists' => 'Invalid site key.',
            'search_id.required' => 'Search ID is required.',
            'search_id.exists' => 'Invalid search ID.',
            'content_id.required' => 'Content ID is required.',
            'content_id.max' => 'Content ID cannot exceed 255 characters.',
            'position.required' => 'Position is required.',
            'position.integer' => 'Position must be an integer.',
            'position.min' => 'Position must be at least 1.',
            'metadata.array' => 'Metadata must be an object.',
            'metadata.element_type.max' => 'Element type cannot exceed 50 characters.',
            'metadata.click_time.date' => 'Click time must be a valid date.',
        ];
    }
}
