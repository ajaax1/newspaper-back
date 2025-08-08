<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMagazineRequest extends FormRequest
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
            'title' => 'sometimes|string',
            'file' => 'sometimes|file|mimes:pdf',
            'description' => 'nullable|string',
        ];
    }


    public function messages()
    {
        return [
            'title.string' => 'O título deve ser um texto válido.',
            'file.file' => 'O arquivo enviado não é válido.',
            'file.mimes' => 'O arquivo deve estar no formato PDF.',
            'description.string' => 'A descrição deve ser um texto válido.',
        ];
    }
}
