<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use \Illuminate\Validation\ValidationException;
use \Illuminate\Database\QueryException;

class CategoriaController extends Controller
{
    // Obtener todas las categorías
    public function index()
    {
        $categorias = Categoria::all(); // Obtiene todas las categorías
        return response()->json($categorias);
    }

    // Obtener una categoría específica
    public function show($id)
    {
        return Categoria::findOrFail($id); // Devuelve la categoría o un error 404
    }

    // Crear una nueva categoría
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:30|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s.]+$/|not_regex:/^\s*$/',
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:200',
                    //'not_regex:/^\s*$/' // No permite solo espacios en blanco
                ]
            ], [
                'nombre.required' => 'El campo de nombre es obligatorio.',
                'nombre.regex' => 'El nombre solo debe contener letras, números, espacios y puntos.',
                'nombre.max' => 'Solo se permite hasta maximo 30 caracteres en el campo nombre.',
                'descripcion.max' => 'La descripción no debe exceder los 200 caracteres.',
                //'not_regex' => 'El campo no debe contener solo espacios en blanco.'
            ]);

            $validatedData['nombre'] = strtoupper($validatedData['nombre']);

            $categoria = Categoria::create($validatedData); // Crea una nueva categoría
            return response()->json([
                'message' => 'Categoria creada con éxito',
                'nuevaCategoria' => $categoria
            ], 201); // Devuelve la categoría creada con código 201
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al crear la categoria', 'detailsError' => $e], 500);
        }
    }

    // Actualizar una categoría existente
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:30|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s.]+$/|not_regex:/^\s*$/',
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:200',
                    //'not_regex:/^\s*$/'
                ]
            ], [
                'nombre.required' => 'El campo de nombre es obligatorio.',
                'nombre.regex' => 'El nombre solo debe contener letras, números, espacios y puntos.',
                'nombre.max' => 'Solo se permite hasta maximo 30 caracteres en el campo nombre.',
                'descripcion.max' => 'La descripción no debe exceder los 200 caracteres.',
                //'not_regex' => 'El campo no debe contener solo espacios en blanco.'
            ]);
            $validatedData['nombre'] = strtoupper($validatedData['nombre']);

            $categoria = Categoria::findOrFail($id);
            $categoria->update($validatedData);
            return response()->json([
                'message' => 'Categoria actualizada con éxito',
                'categoria actualizado' => $categoria
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al editar el empleado', 'detailsError' => $e], 500);
        }
    }

    // Eliminar una categoría
    public function destroy($id)
    {
        $categoria = Categoria::find($id); // Busca la categoría

        if ($categoria) {
            try {
                $categoria->delete(); // Elimina la categoría
                return response()->json(['message' => 'Categoría eliminada correctamente'], 200);
            } catch (QueryException $e) {
                return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
            } catch (\Exception $e) {
                return response()->json(['messageError' => 'Error al eliminar la categoria', 'detailsError' => $e], 500);
            }

        } else {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }
    }
}


