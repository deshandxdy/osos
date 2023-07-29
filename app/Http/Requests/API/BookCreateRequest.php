<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class BookCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('create books');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|exists:authors,id',
            'isbn' => 'required',
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'cover_image' => 'required|image|max:1024',
        ];
    }
}
