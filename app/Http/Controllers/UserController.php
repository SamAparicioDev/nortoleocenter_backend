<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios (solo admin)
     */
    public function index(Request $request)
    {
        if ($request->user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuarios = User::select('id', 'name', 'email', 'rol', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($usuarios);
    }

    /**
     * Ver un usuario por ID
     */
    public function show($id, Request $request)
    {
        $usuario = User::find($id);

        if (! $usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Admin o el usuario dueño del perfil
        if ($request->user()->rol !== 'admin' && $request->user()->id !== $usuario->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($usuario);
    }

    /**
     * Crear usuario
     */
    public function store(Request $request)
    {
        $data = $request->input('userDTO');

        $validated = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'rol' => 'required|in:admin,empleado,productor'
        ])->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => $validated['rol']
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'user' => $user
        ], 201);
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $data = $request->input('userDTO');

        $validated = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'rol' => 'sometimes|in:admin,empleado,productor',
            'password' => 'sometimes|confirmed|min:8',
        ])->validate();

        $user = User::findOrFail($id);

        // Si mandó contraseña, la encripta
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'user' => $user
        ]);
    }

    /**
     * Eliminar usuario
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }

    /**
     * Actualizar solo el rol
     */
    public function actualizarRol($id, Request $request)
    {
        if ($request->user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'rol' => 'required|in:admin,empleado,productor',
        ]);

        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->rol = $request->rol;
        $user->save();

        return response()->json([
            'message' => 'Rol actualizado correctamente',
            'user' => $user,
        ]);
    }
}
