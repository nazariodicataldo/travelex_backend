<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelPostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'min:2', 'max:60'],
            'description' => ['sometimes', 'string', 'min:2', 'max:600'],
            'country' => ['sometimes', 'string', 'min:2', 'max: 3'],
            'img' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
