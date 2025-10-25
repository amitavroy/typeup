<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'metadata' => 'sometimes|array',
            'metadata.query' => 'sometimes|string|max:255',
            'metadata.user_agent' => 'sometimes|string|max:500',
            'metadata.referrer' => 'sometimes|string|max:500',
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
            'metadata.array' => 'Metadata must be an object.',
            'metadata.query.string' => 'Search query must be a string.',
            'metadata.query.max' => 'Search query cannot exceed 255 characters.',
            'metadata.user_agent.max' => 'User agent cannot exceed 500 characters.',
            'metadata.referrer.max' => 'Referrer cannot exceed 500 characters.',
        ];
    }
}
