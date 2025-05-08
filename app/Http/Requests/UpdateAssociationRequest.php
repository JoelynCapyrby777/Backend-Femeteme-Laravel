<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssociationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('association') ?? $this->route('id');

        return [
            'name'         => "required|string|max:255|unique:associations,name,{$id}",
            'abbreviation' => "required|string|max:255|unique:associations,abbreviation,{$id}",
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
