<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleRepuestoProveedor;

class DetalleRepuestoProveedorController extends Controller
{
    // Obtener todos los detalles
    public function index()
    {
        return DetalleRepuestoProveedor::with(['proveedor', 'repuesto'])->get();
    }

    // Obtener un detalle específico
    public function show($id)
    {
        return DetalleRepuestoProveedor::with(['proveedor', 'repuesto'])->findOrFail($id);
    }

    // Crear un nuevo detalle
    public function store(Request $request)
    {
        return DetalleRepuestoProveedor::create($request->all());
    }

    // Actualizar un detalle existente
    public function update(Request $request, $id)
    {
        $detalle = DetalleRepuestoProveedor::findOrFail($id);
        $detalle->update($request->all());
        return $detalle;
    }

    // Eliminar un detalle
    public function destroy($id)
    {
        return DetalleRepuestoProveedor::destroy($id);
    }
}
