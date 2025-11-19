<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->rol === 'productor') {
            return response()->json(
                Lote::whereHas('finca', fn ($q) => $q->where('productor_id', $request->user()->id))
                    ->with('finca')
                    ->get()
            );
        }

        return response()->json(Lote::with(['finca.ciudad.departamento'])->get());
    }

    public function lotesFinca(Request $request, $idFinca)
    {
        $user = $request->user();
        if (! in_array($user->rol, ['productor', 'admin'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $query = Lote::with('finca')->where('finca_id', $idFinca);

        if ($user->rol === 'productor') {
            $query->whereHas('finca', fn ($q) => $q->where('productor_id', $user->id));
        }

        $lotes = $query->get();

        return response()->json($lotes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'finca_id' => 'required|exists:fincas,id',
            'area_m2' => 'required|numeric|min:1',
        ]);
        $lote = Lote::create($request->all());

        return response()->json(['message' => 'Lote creado', 'data' => $lote], 201);
    }

    public function show(Lote $lote)
    {
        return response()->json($lote->load('finca.ciudad.departamento'));
    }

    public function update(Request $request, Lote $lote)
    {
        $request->validate([
            'nombre' => 'string',
            'variedad_cafe' => 'string',
            'area_m2' => 'numeric|min:1',
        ]);

        $lote->update($request->all());

        return response()->json(['message' => 'Lote actualizado', 'data' => $lote]);
    }

    public function destroy(Lote $lote)
    {
        $lote->delete();

        return response()->json(['message' => 'Lote eliminado']);
    }
}
