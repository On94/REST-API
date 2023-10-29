<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tags.*' => 'nullable|string|max:55',
            'name'=>'required|string|max:55',
            'description' => 'required|string|max:2000',
            'image' => [
                'required',
                'string',
                'regex:/^data:image\/(jpeg|png|jpg|gif);base64,/',
                'base64image'
            ],
        ];
    }
}
