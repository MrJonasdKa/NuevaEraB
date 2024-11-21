<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function verificarUsuarioPorEmpleado($idEmpleado)
    {
        // Buscar si existe un usuario con el idEmpleado dado
        $usuario = User::where('id_empleado', $idEmpleado)->first();
        $employee = Empleado::where('id', $idEmpleado)->first();
        // Retornar si existe o no
        return response()->json([
            'exists' => $usuario ? true : false,
            'user' => $usuario,
            'empleado' => $employee
        ]);
    }
    public function checkEmployee($idEmpleado)
    {
        // Verifica si existe un usuario con el id_empleado proporcionado
        $userExists = User::where('id_empleado', $idEmpleado)->exists();

        $employee = Empleado::where('id', $idEmpleado)->first();
        $usuario = User::where('id_empleado', $idEmpleado)->first();
        // Retorna una respuesta JSON dependiendo de si se encontró el usuario o no
        return response()->json([
            'exists' => $userExists,
            'usuario' => $usuario,
            'empleado' => $employee,
        ]);
    }
    public function showUser($idEmpleado)
    {
        // Busca el usuario con el id_empleado proporcionado
        $user = User::where('id_empleado', $idEmpleado)->first();
        // Verifica si se encontró el usuario y retorna su información en formato JSON
        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }
        // Si no se encontró, retorna un mensaje indicando que no se encontró el usuario
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ], 404);
    }


    // Show the form for creating a new resource
    public function create()
    {
        // Return a view for creating a user (if using views)
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user,fiance',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // Display the specified resource
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        // Return a view for editing the user (if using views)
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role' => 'sometimes|required|string|in:admin,user,fiance',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}