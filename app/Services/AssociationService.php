<?php

namespace App\Services;

use App\Models\Association;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\Association\AssociationNotFoundException;
use App\Exceptions\Association\AssociationValidationException;
use App\Exceptions\Association\AssociationConflictException;

class AssociationService
{
    public function obtenerTodas()
    {
        return Association::all();
    }

    public function crear(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new AssociationValidationException($validator->errors()->toJson());
        }

        if (Association::where('name', $data['name'])->exists()) {
            throw new AssociationConflictException("Ya existe una asociación con ese nombre.");
        }

        if (Association::where('abbreviation', $data['abbreviation'])->exists()) {
            throw new AssociationConflictException("Ya existe una asociación con esa abreviatura.");
        }

        return Association::create($data);
    }

    public function obtenerPorId($id)
    {
        $association = Association::find($id);

        if (! $association) {
            throw new AssociationNotFoundException();
        }

        return $association;
    }

    public function modificar(array $data, $id)
    {
        $association = Association::find($id);

        if (! $association) {
            throw new AssociationNotFoundException();
        }

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new AssociationValidationException($validator->errors()->toJson());
        }

        // Verifica si el nombre ya está en uso por otra asociación
        if (Association::where('name', $data['name'])->where('id', '!=', $id)->exists()) {
            throw new AssociationConflictException("El nombre ya está en uso por otra asociación.");
        }

        if (Association::where('abbreviation', $data['abbreviation'])->where('id', '!=', $id)->exists()) {
            throw new AssociationConflictException("La abreviatura ya está en uso por otra asociación.");
        }

        $association->update($data);

        return $association;
    }

    public function eliminar($id)
    {
        $association = Association::find($id);

        if (! $association) {
            throw new AssociationNotFoundException();
        }

        $association->delete();
    }
}
