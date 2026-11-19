<?php

namespace App\Http\Controllers;

use App\Models\Quete;
use Illuminate\Http\Request;

class QueteController extends Controller
{
    // GET /api/quetes
    public function index()
    {
        return response()->json(Quete::all());
    }

    // POST /api/quetes
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
            'exclamation' => 'sometimes|boolean',
        ]);

        $quete = Quete::create($data);

        return response()->json($quete, 201);
    }

    // GET /api/quetes/1
    public function show(Quete $quete)
    {
        return response()->json($quete);
    }

    // PATCH /api/quetes/1
    public function update(Request $request, Quete $quete)
    {
        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:150',
            'description' => 'sometimes|nullable|string',
            'exclamation' => 'sometimes|boolean',
        ]);

        $quete->update($data);

        return response()->json($quete);
    }

    // DELETE /api/quetes/1
    public function destroy(Quete $quete)
    {
        $quete->delete();

        return response()->noContent();
    }
}
