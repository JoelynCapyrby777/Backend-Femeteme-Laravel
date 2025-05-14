<?php

namespace App\Http\Requests\Association;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssociationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Aquí podrás verificar permisos, o devolver true si cualquiera puede crear
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255|unique:associations,name',
            'abbreviation' => 'required|string|max:255|unique:associations,abbreviation',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'El nombre de la asociación es obligatorio.',
            'name.unique'           => 'Ya existe una asociación con este nombre.',
            'abbreviation.required' => 'La abreviatura es obligatoria.',
            'abbreviation.unique'   => 'Ya existe una asociación con esta abreviatura.',
        ];
    }
}
