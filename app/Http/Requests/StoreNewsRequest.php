<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string',
            'content' => 'required|string',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'badge' => 'nullable|string|max:30',
            'top_position' => 'nullable|in:main_top,top_1,top_2,top_3',
            'status' => 'required|in:draft,published',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Por favor, informe o título da notícia.',
            'title.string' => 'O título deve ser um texto válido.',
            'title.max' => 'O título não pode ter mais que 255 caracteres.',
            'content.required' => 'O conteúdo da notícia é obrigatório.',
            'content.string' => 'O conteúdo deve ser um texto válido.',
            'sub_title.required' => 'O subtítulo é obrigatório.',
            'sub_title.string' => 'O subtítulo deve ser um texto válido.',
            'image_url.required' => 'A URL da imagem é obrigatória.',
            'badge.string' => 'A badge deve ser um texto válido.',
            'badge.max' => 'A badge não pode ter mais que 30 caracteres.',
            'user_id.required' => 'Informe o usuário responsável pela publicação.',
            'user_id.exists' => 'O usuário informado não foi encontrado.',
            'top_position.in' => 'A posição selecionada é inválida. Escolha entre: main_top, top_1, top_2 ou top_3.',
            'status.required' => 'O status da publicação é obrigatório.',
            'status.in' => 'O status deve ser "draft" ou "published".',
            'category_ids.array' => 'As categorias devem ser enviadas como uma lista.',
            'category_ids.*.exists' => 'Uma ou mais categorias selecionadas não existem.',
        ];
    }
}
