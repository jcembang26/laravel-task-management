<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
        return [
            'title' => 'required|max:100',
            'content' => 'required',
            'status' => 'required',
            'image' => 'image|max:45',
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'The image field must not be greater than 45 MB.'
        ];
    }
}
