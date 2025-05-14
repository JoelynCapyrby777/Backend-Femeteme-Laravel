<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'curp' => 'required|string|max:18|unique:players',
            'age' => 'required|integer|min:5|max:100',
            'category' => 'required|in:femenil,varonil',
            'association_id' => 'required|exists:associations,id',	
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'curp.required' => 'El CURP es obligatorio.',
            'age.required' => 'La edad es obligatoria.',
            'category.required' => 'La categoría es obligatoria.',
            'association_id.required' => 'El ID de la asociación es obligatorio.',
        ];
    }
}
