<?php
// app/Services/AuthService.php

namespace App\Services;

use App\Models\Empleado;

class AuthService
{
    public function getCurrentUser()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();

        // Obtener el cargo (rol) del usuario
        $cargo = $user->cargo; // Asegúrate de que tienes la relación definida en el modelo Empleado

        // Añadir el nombre del cargo al usuario
        $user->role = $cargo->nombre;

        return $user;
    }
}
