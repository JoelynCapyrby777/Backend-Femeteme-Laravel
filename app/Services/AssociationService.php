<?php

namespace App\Services;

use App\Models\Association;
use Illuminate\Support\Facades\Validator;

class AssociationService
{
    public function obtenerTodas()
    {
        $associations = Association::all();

        if ($associations->isEmpty()) {
            return ['error' => 'No hay asociaciones registradas', 'status' => 404];
        }

        return ['data' => $associations, 'status' => 200];
    }

    public function crear(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:associations,name',
            'abbreviation' => 'required|string|max:255|unique:associations,abbreviation',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors(), 'status' => 400];
        }

        Association::create($data);

        return ['message' => 'Asociación creada correctamente', 'status' => 201];
    }

    public function obtenerPorId($id)
    {
        $association = Association::find($id);

        if (!$association) {
            return ['error' => 'Asociación no encontrada', 'status' => 404];
        }

        return ['data' => $association, 'status' => 200];
    }

    public function modificar(array $data, $id)
    {
        $association = Association::find($id);

        if (!$association) {
            return ['error' => 'Asociación no encontrada', 'status' => 404];
        }

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:associations,name,' . $id,
            'abbreviation' => 'required|string|max:255|unique:associations,abbreviation,' . $id,
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors(), 'status' => 422];
        }

        $association->update($data);

        return ['message' => 'La asociación se ha actualizado correctamente', 'status' => 200];
    }

    public function eliminar($id)
    {
        $association = Association::find($id);

        if (!$association) {
            return ['error' => 'Asociación no encontrada', 'status' => 404];
        }

        $association->delete();

        return ['message' => 'Asociación eliminada correctamente', 'status' => 204];
    }
}
