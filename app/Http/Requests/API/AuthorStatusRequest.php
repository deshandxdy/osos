<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class AuthorStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->hasRole('Admin') || $this->user()->hasPermissionTo('change user status')) {
            return true;
        }

        return false;
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
            'status' => 'required'
        ];
    }
}
