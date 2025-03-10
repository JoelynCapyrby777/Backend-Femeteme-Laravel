<?php

namespace App\Http\Controllers;

use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssociationController extends Controller
{
    /**
     * Obtener todas las asociaciones registradas.
     */
    public function obtenerAsociaciones()
    {
        // Obtener todas las asociaciones
        $associations = Association::all();

        if($associations->isEmpty()){
            return response()->json(['message' => 'No hay asociaciones registradas'], 404);
        }
        
        // Retornar las asociaciones en formato JSON
        return response()->json($associations, 200);
    }

    /**
     * Crear una nueva asociación en la base de datos.
     */
    public function crearAsociacion(Request $request)
    {
        // Validación de los datos de la solicitud
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:associations,name',
            'abbreviation' => 'required|string|max:255|unique:associations,abbreviation',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Crear una nueva asociación con los datos validados
        Association::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
        ]);

        // Retornar la asociación recién creada con un estado 201 (creado)
        return response()->json(['message' => 'Asociación creada correctamente'], 201);
    }

    /**
     * Ver los detalles de una asociación específica.
     */
    public function obtenerAsociacion($id)
    {
        // Buscar la asociación por ID
        $association = Association::find($id);

        // Si la asociación no existe, retornar un error 404
        if (!$association) {
            return response()->json(['message' => 'Asociación no encontrada'], 404);
        }

        // Retornar la asociación
        return response()->json($association, 200);
    }

    /**
     * Modificar los datos de una asociación existente.
     */
    public function modificarAsociacion(Request $request, $id)
    {
        // Buscar la asociación por ID
        $association = Association::find($id);

        // Si la asociación no existe, retornar un error 404
        if (!$association) {
            return response()->json(['message' => 'Asociación no encontrada'], 404);
        }

        // Validar los datos de la solicitud
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:associations,name,' . $association->id,
            'abbreviation' => 'required|string|max:255|unique:associations,abbreviation,' . $association->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Actualizar los campos solo si son proporcionados en la solicitud
        if($request->has('name')){
            $association->name = $request->name;
        }

        if($request->has('abbreviation')){
            $association->abbreviation = $request->abbreviation; // Corregido aquí
        }

        // Actualizar la asociación
        $association->update();

        // Retornar la asociación actualizada
        return response()->json(['message' => 'La asociación se ha actualizado correctamente'], 200);
    }

    /**
     * Eliminar una asociación específica.
     */
    public function eliminarAsociacion($id)
    {
        // Buscar la asociación por ID
        $association = Association::find($id);

        // Si la asociación no existe, retornar un error 404
        if (!$association) {
            return response()->json(['message' => 'Asociación no encontrada'], 404);
        }

        // Eliminar la asociación
        $association->delete();

        // Retornar una respuesta vacía con estado 204 (eliminado)
        return response()->json(['message' => 'Asociación eliminada correctamente'], 204);
    }
}
