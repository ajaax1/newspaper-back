<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
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
        if (is_string($this->category_ids) && !empty($this->category_ids)) {
            $ids = json_decode($this->category_ids, true);
            $this->merge([
                'category_ids' => $ids
            ]);
        }
    }
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'sub_title' => 'sometimes|string',
            'content' => 'sometimes|string',
            'image_url' => 'sometimes|mimes:jpeg,png,jpg,gif,webp,bmp,svg,tiff,tif,ico,heic,heif|max:5120',
            'badge' => 'nullable|string|max:30',
            'top_position' => 'nullable|in:main_top,top_1,top_2,top_3',
            'status' => 'sometimes|in:draft,published',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'O título deve ser um texto.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'sub_title.string' => 'O subtítulo deve ser um texto.',
            'sub_title.max' => 'O subtítulo não pode ter mais de 255 caracteres.',
            'content.string' => 'O conteúdo deve ser um texto.',
            'image_url.url' => 'A imagem deve ser uma URL válida.',
            'badge.string' => 'O selo deve ser um texto.',
            'badge.max' => 'O selo não pode ter mais de 255 caracteres.',
            'user_id.exists' => 'O usuário selecionado é inválido.',
            'top_position.in' => 'A posição deve ser uma das seguintes: main_top, top_1, top_2 ou top_3.',
            'status.in' => 'O status deve ser "draft" ou "published".',
            'category_ids.array' => 'As categorias devem ser enviadas em formato de lista.',
            'category_ids.*.exists' => 'Uma ou mais categorias são inválidas.',
        ];
    }
}
