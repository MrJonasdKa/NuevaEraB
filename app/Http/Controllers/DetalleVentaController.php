<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class DetalleVentaController extends Controller
{
    // Obtener todos los detalles de venta
    public function index()
    {
        try {
            $detalles = DetalleVenta::with(['venta', 'repuesto'])->get(); // Cargar detalles con sus relaciones
            return response()->json([
                'detalles' => $detalles,
                'message' => 'Listado correcto de detalles de venta'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'messageError' => 'Ocurrió un error al obtener el listado de detalles de venta.',
                'detailsError' => $e->getMessage()
            ], 500);
        }
    }

    // Crear un nuevo detalle de venta
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_venta' => 'required|exists:ventas,id',
                'id_repuesto' => 'required|exists:repuestos,id',
                'cantidad' => 'required|integer|min:1',
                'monto' => 'required|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0'
            ], [
                'id_venta.required' => 'El campo de venta es obligatorio.',
                'id_venta.exists' => 'La venta seleccionada no existe.',
                'id_repuesto.required' => 'El campo de repuesto es obligatorio.',
                'id_repuesto.exists' => 'El repuesto seleccionado no existe.',
                'cantidad.required' => 'La cantidad es obligatoria.',
                'cantidad.integer' => 'La cantidad debe ser un número entero.',
                'cantidad.min' => 'La cantidad debe ser al menos 1.',
                'monto.required' => 'El monto es obligatorio.',
                'monto.numeric' => 'El monto debe ser un número.',
                'monto.min' => 'El monto debe ser al menos 0.',
                'subtotal.required' => 'El subtotal es obligatorio.',
                'subtotal.numeric' => 'El subtotal debe ser un número.',
                'subtotal.min' => 'El subtotal debe ser al menos 0.',
                'total.required' => 'El total es obligatorio.',
                'total.numeric' => 'El total debe ser un número.',
                'total.min' => 'El total debe ser al menos 0.'
            ]);

            $detalleVenta = DetalleVenta::create($validatedData);
            return response()->json([
                'message' => 'Detalle de venta creado con éxito',
                'nuevo_detalle' => $detalleVenta
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al crear el detalle de venta', 'detailsError' => $e->getMessage()], 500);
        }
    }

    // Mostrar un detalle de venta específico
    public function show($id)
    {
        try {
            $detalleVenta = DetalleVenta::with(['venta', 'repuesto'])->findOrFail($id);
            return response()->json($detalleVenta);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Detalle de venta no encontrado', 'detailsError' => $e->getMessage()], 404);
        }
    }

    // Actualizar un detalle de venta existente
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'id_venta' => 'required|exists:ventas,id',
                'id_repuesto' => 'required|exists:repuestos,id',
                'cantidad' => 'required|integer|min:1',
                'monto' => 'required|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0'
            ], [
                'id_venta.required' => 'El campo de venta es obligatorio.',
                'id_venta.exists' => 'La venta seleccionada no existe.',
                'id_repuesto.required' => 'El campo de repuesto es obligatorio.',
                'id_repuesto.exists' => 'El repuesto seleccionado no existe.',
                'cantidad.required' => 'La cantidad es obligatoria.',
                'cantidad.integer' => 'La cantidad debe ser un número entero.',
                'cantidad.min' => 'La cantidad debe ser al menos 1.',
                'monto.required' => 'El monto es obligatorio.',
                'monto.numeric' => 'El monto debe ser un número.',
                'monto.min' => 'El monto debe ser al menos 0.',
                'subtotal.required' => 'El subtotal es obligatorio.',
                'subtotal.numeric' => 'El subtotal debe ser un número.',
                'subtotal.min' => 'El subtotal debe ser al menos 0.',
                'total.required' => 'El total es obligatorio.',
                'total.numeric' => 'El total debe ser un número.',
                'total.min' => 'El total debe ser al menos 0.'
            ]);

            $detalleVenta = DetalleVenta::findOrFail($id);
            $detalleVenta->update($validatedData);
            return response()->json([
                'message' => 'Detalle de venta actualizado con éxito',
                'detalle_actualizado' => $detalleVenta
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al actualizar el detalle de venta', 'detailsError' => $e->getMessage()], 500);
        }
    }

    // Eliminar un detalle de venta
    public function destroy($id)
    {
        try {
            $detalleVenta = DetalleVenta::findOrFail($id);
            $detalleVenta->delete();
            return response()->json(['message' => 'Detalle de venta eliminado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al eliminar el detalle de venta', 'detailsError' => $e->getMessage()], 500);
        }
    }
}
