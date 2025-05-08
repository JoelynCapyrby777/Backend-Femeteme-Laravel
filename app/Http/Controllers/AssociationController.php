<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Http\Resources\AssociationResource;
use Illuminate\Http\Request;
use App\Exceptions\AssociationNotFoundException;

class AssociationController extends Controller
{
    public function index()
    {
        // Devuelve todas las asociaciones
        return AssociationResource::collection(Association::all());
    }

    public function show($id)
    {
        $association = Association::find($id);

        if (! $association) {
            throw new AssociationNotFoundException;
        }

        return new AssociationResource($association);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10',
        ]);

        $association = Association::create($data);

        return (new AssociationResource($association))
                    ->response()
                    ->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $association = Association::find($id);

        if (! $association) {
            throw new AssociationNotFoundException;
        }

        $data = $request->validate([
            'name'         => 'sometimes|required|string|max:255',
            'abbreviation' => 'sometimes|required|string|max:10',
        ]);

        $association->update($data);

        return new AssociationResource($association);
    }

    public function destroy($id)
    {
        $association = Association::find($id);

        if (! $association) {
            throw new AssociationNotFoundException;
        }

        $association->delete();

        return response()->json(null, 204);
    }
}
