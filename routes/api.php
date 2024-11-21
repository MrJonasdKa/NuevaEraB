<?php

use App\Http\Controllers\CargoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Cargo;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\regresionController;
use App\Http\Controllers\LlenadoDatosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas (sin protección de autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('users/check/{idEmpleado}', [UserController::class, 'checkEmployee']);
Route::get('users/show/{idEmpleado}', [UserController::class, 'showUser']);
Route::get('/generar-usuario/{idEmpleado}', [EmpleadoController::class, 'generarCodigo']);
Route::get('/verificar-usuario/{idEmpleado}', [UserController::class, 'verificarUsuarioPorEmpleado']);
Route::delete('/eliminar_usuario/{idEmpleado}', [EmpleadoController::class, 'eliminarUser']);
Route::post('/llenar-test-reg', [LlenadoDatosController::class, 'llenarTestReg']);
Route::get('/predict-sales/{producto_id}', [regresionController::class, 'predictSales']);

/*
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');
*/

// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Obtener el usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Obtener el email del usuario autenticado
    Route::get('/user/email', [AuthController::class, 'getUserEmail']);
    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);
    /*Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend'])
        ->name('verification.send');*/
    Route::apiResource('empleados', EmpleadoController::class);
    Route::get('/empleados/filter/{cargo}', [EmpleadoController::class, 'filterByCargo']);
    Route::apiResource('repuestos', RepuestoController::class);
    Route::apiResource('marcas', MarcaController::class);
    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('cargos',CargoController::class);
    Route::apiResource('ventas',VentaController::class);
    Route::apiResource('clientes',ClienteController::class);
    Route::apiResource('detalles_venta',DetalleVentaController::class);
});
