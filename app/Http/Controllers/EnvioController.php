<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnvioController extends Controller
{
    /**
     * Listar todos los envíos
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Solo admin o productor pueden acceder
        if (! in_array($user->rol, ['admin', 'productor'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        // Si es productor, solo ve sus envíos
        $query = Envio::with(['productor', 'finca', 'lote'])
            ->orderBy('fecha_envio', 'desc');

        if ($user->rol === 'productor') {
            $query->where('productor_id', $user->id);
        }

        $envios = $query->get();

        return response()->json($envios);
    }

    public function misEnvios(Request $request)
    {
        $user = $request->user();

        if (! in_array($user->rol, ['productor', 'admin'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $query = Envio::with(['finca', 'lote'])
            ->orderBy('fecha_envio', 'desc');

        if ($user->rol === 'productor') {
            $query->where('productor_id', $user->id);
        }

        $envios = $query->get();

        return response()->json($envios);
    }

    /**
     * Crear un nuevo envío (productor o admin)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (! in_array($user->rol, ['productor', 'admin'])) {
            return response()->json(['message' => 'Solo los productores o administradores pueden registrar envíos.'], 403);
        }

        $request->validate([
            'finca_id' => 'required|exists:fincas,id',
            'lote_id' => 'nullable|exists:lotes,id',
            'fecha_envio' => 'nullable|date',
            'peso_kg' => 'required|numeric|min:0', // mejor numérico para operaciones
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Generar código único de envío
        $codigoEnvio = 'ENV-'.strtoupper(Str::random(8));

        $envio = Envio::create([
            'codigo_envio' => $codigoEnvio,
            'fecha_envio' => $request->fecha_envio ?? now(),
            'productor_id' => $user->id,
            'finca_id' => $request->finca_id,
            'lote_id' => $request->lote_id,
            'peso_kg' => $request->peso_kg,
            'observaciones' => $request->observaciones,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Envío registrado exitosamente.',
            'data' => $envio->load(['finca', 'lote']),
        ], 201);
    }

    /**
     * Mostrar un envío específico
     */
    public function show(Envio $envio, Request $request)
    {
        $user = $request->user();

        // Solo admin o el productor dueño del envío pueden ver
        if ($user->rol === 'productor' && $envio->productor_id !== $user->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if (! in_array($user->rol, ['admin', 'productor'])) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($envio->load(['productor', 'finca', 'lote']));
    }
     public function update(Request $request, Envio $envio)
    {
        $request->validate([
            'finca_id' => 'required|exists:fincas,id',
            'lote_id' => 'nullable|exists:lotes,id',
            'fecha_envio' => 'nullable|date',
            'peso_kg' => 'required|numeric|min:0', // mejor numérico para operaciones
            'observaciones' => 'nullable|string|max:255',
        ]);

        $envio->update($request->all());

        return response()->json(['message' => 'Envio actualizado', 'data' => $envio]);
    }

    public function updateStatus($id, $status, Request $request)
{
    $envio = Envio::findOrFail($id);

    // Validación correcta
  if ($status === "recibido" && !in_array($request->user()->rol, ['admin', 'empleado'])) {
    return response()->json([
        'message' => 'Solo los administradores y empleados pueden cambiar el estado de un envío a recibido'
    ], 403);
}
    if ($status === "enviado" && !in_array($request->user()->rol, ['admin', 'productor'])) {
    return response()->json([
        'message' => 'Solo los productores y administradores pueden cambiar el estado de un envío a enviado'
    ], 403);
}
    $envio->estado = $status;
    $envio->save();

    return response()->json([
        'message' => 'Estado actualizado correctamente',
        'envio' => $envio
    ], 200);
}

    /**
     * Eliminar un envío (solo admin)
     */
    public function destroy(Envio $envio, Request $request)
    {
        if ($request->user()->rol !== 'admin') {
            return response()->json(['message' => 'Solo los administradores pueden eliminar envíos.'], 403);
        }

        $envio->delete();

        return response()->json(['message' => 'Envío eliminado correctamente.']);
    }
}
