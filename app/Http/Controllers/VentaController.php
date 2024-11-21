<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class VentaController extends Controller
{
    // Obtener todas las ventas
    public function index()
    {
        try {
            $ventas = Venta::with(['cliente', 'empleado', 'detalles'])->get(); // Cargar ventas con relaciones
            return response()->json([
                'ventas' => $ventas,
                'message' => 'Listado correcto de ventas'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'messageError' => 'Ocurrió un error al obtener el listado de ventas.',
                'detailsError' => $e->getMessage()
            ], 500);
        }
    }

    // Crear una nueva venta
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_cliente' => 'required|exists:clientes,id',
                'empleados_id' => 'required|exists:empleados,id',
                'fecha_venta' => 'required|date',
            ], [
                'id_cliente.required' => 'El campo de cliente es obligatorio.',
                'id_cliente.exists' => 'El cliente seleccionado no existe.',
                'empleados_id.required' => 'El campo de empleado es obligatorio.',
                'empleados_id.exists' => 'El empleado seleccionado no existe.',
                'fecha_venta.required' => 'La fecha de venta es obligatoria.',
                'fecha_venta.date' => 'La fecha de venta debe ser una fecha válida.',
            ]);

            $venta = Venta::create($validatedData);
            return response()->json([
                'message' => 'Venta creada con éxito',
                'nueva_venta' => $venta
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al crear la venta', 'detailsError' => $e->getMessage()], 500);
        }
    }

    // Mostrar una venta específica
    public function show($id)
    {
        try {
            $venta = Venta::with(['cliente', 'empleado', 'detalles'])->findOrFail($id);
            return response()->json($venta);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Venta no encontrada', 'detailsError' => $e->getMessage()], 404);
        }
    }

    // Actualizar una venta existente
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'id_cliente' => 'required|exists:clientes,id',
                'empleados_id' => 'required|exists:empleados,id',
                'fecha_venta' => 'required|date',
            ], [
                'id_cliente.required' => 'El campo de cliente es obligatorio.',
                'id_cliente.exists' => 'El cliente seleccionado no existe.',
                'empleados_id.required' => 'El campo de empleado es obligatorio.',
                'empleados_id.exists' => 'El empleado seleccionado no existe.',
                'fecha_venta.required' => 'La fecha de venta es obligatoria.',
                'fecha_venta.date' => 'La fecha de venta debe ser una fecha válida.',
            ]);

            $venta = Venta::findOrFail($id);
            $venta->update($validatedData);
            return response()->json([
                'message' => 'Venta actualizada con éxito',
                'venta_actualizada' => $venta
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al actualizar la venta', 'detailsError' => $e->getMessage()], 500);
        }
    }

    // Eliminar una venta
    public function destroy($id)
    {
        try {
            $venta = Venta::findOrFail($id);
            $venta->delete();
            return response()->json(['message' => 'Venta eliminada con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al eliminar la venta', 'detailsError' => $e->getMessage()], 500);
        }
    }
}
