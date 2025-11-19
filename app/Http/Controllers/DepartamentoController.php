<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        return response()->json(Departamento::all());
    }

    public function store(Request $request)
    {
         if($request->user()->rol != "admin"){
           return response()->json([
            'message' => 'Usuario no autorizado'
        ], 404);
        }
        $request->validate(['nombre' => 'required|string|unique:departamentos']);

        $departamento = Departamento::create(['nombre' => $request->nombre]);

        return response()->json(['message' => 'Departamento creado', 'data' => $departamento], 201);
    }

    public function show($idD)
    {
        $departamento = Departamento::with('ciudades')->find($idD);

        if (! $departamento) {
            return response()->json([
                'message' => 'Departamento no encontrado',
            ], 404);
        }

        return response()->json($departamento);
    }

    public function update(Request $request, Departamento $departamento)
    {
         if($request->user()->rol != "admin"){
           return response()->json([
            'message' => 'Usuario no autorizado'
        ], 404);
        }
        $request->validate(['nombre' => 'required|string|unique:departamentos,nombre,'.$departamento->id]);

        $departamento->update(['nombre' => $request->nombre]);

        return response()->json(['message' => 'Departamento actualizado', 'data' => $departamento]);
    }
public function destroy($id, Request $request)
{
    $departamento = Departamento::with('ciudades')->findOrFail($id);

     if($request->user()->rol != "admin"){
           return response()->json([
            'message' => 'Usuario no autorizado'
        ], 404);
        }

    if ($departamento->ciudades->count() > 0) {
        return response()->json([
            'message' => 'No se puede eliminar, tiene ciudades asociadas'
        ], 409);
    }

    $departamento->delete();

    return response()->json(['message' => 'Departamento eliminado']);
}

}
