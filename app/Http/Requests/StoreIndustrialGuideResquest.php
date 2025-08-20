<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIndustrialGuideResquest extends FormRequest
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
    public function prepareForValidation()
    {
        if (is_string($this->sector_ids) && !empty($this->sector_ids)) {
            $ids = json_decode($this->sector_ids, true);
            $this->merge([
                'sector_ids' => $ids
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg,tiff,tif,ico,heic,heif|max:5120',
            'address' => 'nullable|string',
            'number' => 'nullable|string',
            'description' => 'nullable|string',
            'sector_ids' => 'nullable|array',
            'sector_ids.*' => 'exists:sectors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',

            'image_url.required' => 'A imagem é obrigatória.',
            'image_url.file' => 'A imagem deve ser um arquivo válido.',
            'image_url.mimes' => 'A imagem deve estar no formato: jpg, jpeg, png ou webp.',
            'image_url.max' => 'A imagem não pode ter mais de 2MB.',

            'address.string' => 'O endereço deve ser um texto.',
            'number.string' => 'O número deve ser um texto.',
            'description.string' => 'A descrição deve ser um texto.',

            'sector_ids.array' => 'Os setores devem ser enviadas como uma lista.',
            'sector_ids.*.exists' => 'Um ou mais setores selecionadas não existem.',
        ];
    }
}
