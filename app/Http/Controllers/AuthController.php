<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;
use App\Models\Empleado;
use Illuminate\Database\QueryException;
use \Illuminate\Validation\ValidationException;
use App\Exceptions\InvalidCredentialsException;

class AuthController extends Controller
{
    protected $authService;

    // Registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            //'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_empleado' => 'required',
        ], [
            //'name.required' => 'El campo de nombre es obligatorio.',
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Por favor, introduce una dirección de correo válida.',
            'password.required' => 'El campo de contraseña es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'El campo de contraseña debe tener al menos 8 caracteres.',
            'email.unique' => 'El campo email ya fue tomado.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_empleado' => $request->id_empleado,
        ]);
        //$user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Usuario registrado. Revisa tu correo para verificar tu cuenta.']);
    }

    // Login de usuario
    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required',
                'password' => 'required',
            ], [
                'email.required' => 'El campo de codigo es obligatorio.',
                'password.required' => 'El campo de contraseña es obligatorio.',
            ]);

            // Verifica si las credenciales son correctas
            if (!Auth::attempt($request->only('email', 'password'))) {
                throw new InvalidCredentialsException();
            }
            
            $user = Auth::user();

            /*
            // Verifica si el usuario tiene el correo electrónico verificado
            if (is_null($user->email_verified_at)) {
                return response()->json(['message' => 'Debes verificar tu correo electrónico para iniciar sesión'], 403);
            }*/

            $token = $user->createToken('auth_token')->plainTextToken;
            // Obtener el empleado en base al id_empleado del user
            $empleado = Empleado::find($user->id_empleado);
            //obtener el cargo
            $roleId = $empleado->id_cargo;
            //obtener el id del empleado
            $idempleado = $empleado->id;
            return response()->json([
                'message' => 'Login exitoso',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role_id' => $roleId,
                'empleado_id' => $idempleado,
            ]);

        } catch (InvalidCredentialsException $e) {
            return $e->render($request);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al crear el empleado', 'detailsError' => $e], 500);
        }
    }

    //acceder al usuario logueado
    public function getUserEmail(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'No estás autenticado.'], 401);
        }

        return response()->json(['email' => $request->user()->email]);
    }

    //cerrar la sesion de usuario
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout exitoso']);
    }

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function user()
    {
        $user = $this->authService->getCurrentUser();
        return response()->json($user);
    }
    public function getUser(Request $request)
    {
        // Devuelve el usuario autenticado
        return response()->json($request->user());
    }

}
