<?php

namespace App\Http\Controllers;

use App\Models\Finca;
use Illuminate\Http\Request;

class FincaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->rol === 'productor') {
            return response()->json(
                Finca::where('productor_id', $request->user()->id)
                    ->with('ciudad.departamento')
                    ->get()
            );
        }

        return response()->json(Finca::with(['productor', 'ciudad.departamento'])->get());
    }

    public function misFincas(Request $request)
{
    $user = $request->user();

    if (! in_array($user->rol, ['productor', 'admin'])) {
        return response()->json(['message' => 'No autorizado.'], 403);
    }

    // Siempre mostrar solo las fincas del usuario logueado
    $fincas = Finca::with(['ciudad.departamento'])
        ->where('productor_id', $user->id)
        ->get();

    return response()->json($fincas);
}


   public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'ciudad_id' => 'required|exists:ciudades,id',
    ]);

    $finca = Finca::create([
        'nombre' => $request->nombre,
        'direccion' => $request->direccion,
        'ciudad_id' => $request->ciudad_id,
        'productor_id' => $request->user()->id,  // 🔥 Aquí se llena solo
    ]);

    return response()->json($finca, 201);
}
    public function show(Finca $finca)
    {
        return response()->json($finca->load(['productor', 'ciudad.departamento']));
    }

    public function update(Request $request, Finca $finca)
    {
        $request->validate([
            'nombre' => 'string',
            'ubicacion' => 'string',
            'ciudad_id' => 'exists:ciudades,id',
        ]);

        $finca->update($request->all());

        return response()->json(['message' => 'Finca actualizada', 'data' => $finca]);
    }

    public function destroy(Finca $finca)
    {
        $finca->delete();

        return response()->json(['message' => 'Finca eliminada']);
    }
}
