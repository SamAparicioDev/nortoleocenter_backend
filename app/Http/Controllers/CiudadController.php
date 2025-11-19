<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;
use App\Models\Departamento;


class CiudadController extends Controller
{
    public function index()
    {
        return response()->json(Ciudad::with('departamento')->get());
    }

    public function ciudadesDepartamento($idDepartamento)
{
    // Validar que el departamento exista
    $departamento = Departamento::find($idDepartamento);
    if (!$departamento) {
        return response()->json([
            'message' => 'Departamento no encontrado'
        ], 404);
    }

    // Traer ciudades del departamento
    $ciudades = $departamento->ciudades; // Asegúrate de tener la relación ciudades() en el modelo Departamento

    return response()->json([
        'message' => 'Ciudades del departamento',
        'data' => $ciudades
    ]);
}
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

         if($request->user()->rol != "admin"){
           return response()->json([
            'message' => 'Usuario no autorizado'
        ], 404);
        }

        $ciudad = Ciudad::create($request->only('nombre', 'departamento_id'));

        return response()->json(['message' => 'Ciudad creada', 'data' => $ciudad], 201);
    }

    public function show($id )
    {
        $ciudad = Ciudad::findOrFail($id);
        return response()->json($ciudad->load('departamento'));
    }
public function update(Request $request, $id)
{
    // Validar los datos
    $request->validate([
        'nombre' => 'string',
        'departamento_id' => 'exists:departamentos,id',
    ]);

     if($request->user()->rol != "admin"){
           return response()->json([
            'message' => 'Usuario no autorizado'
        ], 404);
        }

    // Buscar la ciudad por id
    $ciudad = Ciudad::findOrFail($id);

    // Actualizar la ciudad
    $ciudad->update($request->only('nombre', 'departamento_id'));

    // Retornar la ciudad actualizada
    return response()->json([
        'message' => 'Ciudad actualizada',
        'data' => $ciudad
    ]);
}

    public function destroy(Ciudad $ciudad, Request $request)
    {
        if($request->user()->rol != "admin"){
           return response()->json([
            'message' => 'Usuario no autorizado'
        ], 404);
        }
        $ciudad->delete();

        return response()->json(['message' => 'Ciudad eliminada']);
    }
}
