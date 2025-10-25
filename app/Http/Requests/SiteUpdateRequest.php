<?php

namespace App\Http\Requests;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $site = $this->route('site');

        return [
            'name' => 'required|string|max:255',
            'domain' => [
                'required',
                'string',
                'max:255',
                'url',
                Rule::unique(Site::class)->ignore($site),
            ],
        ];
    }
}
