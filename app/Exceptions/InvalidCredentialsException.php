<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'success' => false,
            'messageError' => 'Credenciales inválidas',
            'errors' => [
                'general' => ['Las credenciales no coinciden con nuestros registros.']
            ]
        ], 401);
    }
}
