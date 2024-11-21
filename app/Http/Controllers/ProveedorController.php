<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    // Obtener todos los proveedores
    public function index()
    {
        return Proveedor::all();
    }

    // Obtener un proveedor específico
    public function show($id)
    {
        return Proveedor::findOrFail($id);
    }

    // Crear un nuevo proveedor
    public function store(Request $request)
    {
        return Proveedor::create($request->all());
    }

    // Actualizar un proveedor existente
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());
        return $proveedor;
    }

    // Eliminar un proveedor
    public function destroy($id)
    {
        return Proveedor::destroy($id);
    }
}
