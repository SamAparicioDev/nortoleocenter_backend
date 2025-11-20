<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use Illuminate\Http\Request;

class RecepcionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! in_array($user->rol, ['admin', 'empleado'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $recepciones = Recepcion::with(['empleado', 'envio'])
            ->orderBy('fecha_recepcion', 'desc')
            ->get();

        return response()->json($recepciones);
    }

    public function misRecepciones(Request $request)
    {
        $user = $request->user();

        if ($user->rol !== 'productor' && $user->rol !== 'admin') {
            return response()->json(['message' => 'Solo productores y administradores pueden ver sus recepciones.'], 403);
        }

        $recepciones = Recepcion::whereHas('envio', function ($query) use ($user) {
            $query->where('productor_id', $user->id);
        })
            ->with(['envio', 'empleado'])
            ->orderBy('fecha_recepcion', 'desc')
            ->get();

        return response()->json($recepciones);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! in_array($user->rol, ['admin', 'empleado'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $validated = $request->validate([
            'envio_id' => 'required|exists:envios,id',
            'precio_kg' => 'required|numeric|min:0',
            'peso_recibido_kg' => 'required|numeric|min:0',
        ]);

        $recepcion = Recepcion::create([
            'envio_id' => $validated['envio_id'],
            'empleado_id' => $user->id,
            'precio_kg' => $validated['precio_kg'],
            'peso_recibido_kg' => $validated['peso_recibido_kg'],
        ]);

        $recepcion->load(['empleado', 'envio']);

        return response()->json([
            'message' => 'Recepción registrada exitosamente.',
            'data' => $recepcion,
        ], 201);
    }

    public function show($id, Request $request)
    {
        $user = $request->user();

        $recepcion = Recepcion::with(['empleado', 'envio.productor', 'envio.finca', 'envio.lote'])
            ->find($id);

        if (! $recepcion) {
            return response()->json(['message' => 'Recepción no encontrada.'], 404);
        }

        if ($user->rol === 'productor' &&
            $recepcion->envio->productor_id !== $user->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if (! in_array($user->rol, ['admin', 'empleado', 'productor'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($recepcion);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (! in_array($user->rol, ['admin'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $recepcion = Recepcion::find($id);

        if (! $recepcion) {
            return response()->json(['message' => 'Recepción no encontrada.'], 404);
        }

        $validated = $request->validate([
            'precio_kg' => 'numeric|min:0|nullable',
            'peso_recibido_kg' => 'numeric|min:0|nullable',
        ]);

        $recepcion->fill($validated);

        // El modelo recalcula total y total_kg_perdidos automáticamente
        $recepcion->save();

        $recepcion->load(['empleado', 'envio']);

        return response()->json([
            'message' => 'Recepción actualizada correctamente.',
            'data' => $recepcion,
        ]);
    }

    public function destroy($id)
    {
        $recepcion = Recepcion::find($id);

        if (! $recepcion) {
            return response()->json(['message' => 'Recepción no encontrada.'], 404);
        }

        $recepcion->delete();

        return response()->json(['message' => 'Recepción eliminada correctamente.']);
    }
}
