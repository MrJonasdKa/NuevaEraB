<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use \Illuminate\Validation\ValidationException;
use \Illuminate\Database\QueryException;

class MarcaController extends Controller
{
    // Obtener todas las marcas
    public function index()
    {
        $marcas = Marca::all(); // Obtiene todas las marcas
        return response()->json($marcas);
    }

    // Obtener una marca específica
    public function show($id)
    {
        return Marca::findOrFail($id); // Devuelve la marca o un error 404
    }

    // Crear una nueva marca
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => [
                    'required',
                    'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ. ]+$/',
                    'max:30',
                ],
                'pais' => [
                    'required',
                    'regex:/^[a-zA-Z\s]+$/', // Solo letras
                    'max:30',
                ],
                'email' => [
                    'nullable',
                    'email',
                    //'regex:/(.*)@([a-zA-Z0-9.-]+)\.com$/i',
                    'regex:/(.*)@([a-zA-Z0-9.-]+)\.[a-zA-Z]{2,}$/i',
                    //'regex:/(.*)@(gmail|yahoo|outlook)\.com$/i', // Solo Gmail, Yahoo o Outlook
                    'unique:marcas,email',
                ],
                'direccion' => [
                    'nullable',
                    'regex:/^[a-zA-Z0-9. ]+$/',
                    'max:60',
                ],
                'telefono' => [
                    'nullable',
                    'regex:/^\+\d{1,3}\s\d{6,10}$/'
                ],
                'sitio_web' => [
                    'nullable',
                    'string',
                    'max:30',
                    'url'
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:200',
                ]
            ], [
                'nombre.required' => 'El campo de nombre es obligatorio.',
                'nombre.regex' => 'El nombre solo debe contener letras, números, puntos y espacios.',
                'nombre.max' => 'Solo se permite hasta maximo 30 caracteres en el campo nombre.',
                'pais.required' => 'El país es obligatorio.',
                'pais.regex' => 'El país solo debe contener letras.',
                'pais.max' => 'Solo se permite hasta maximo 30 caracteres en el campo pais.',
                //'email.regex' => 'El email solo debe ser de dominio Gmail, Yahoo o Outlook.',
                'email.regex' => 'Introduzca un email valido',
                'email.email' => 'Introduzca un email valido',
                'email.unique' => 'No puede repetir el email',
                'direccion.regex' => 'La dirección solo debe contener letras y números.',
                'direccion.max' => 'Solo se permite hasta maximo 60 caracteres en el campo nombre.',
                'telefono.regex' => 'El celular de contacto debe ser válido',
                'sitio_web.max' => 'El sitio web no puede ser mas de 30 caracteres',
                'sitio_web.url' => 'Error con la url de sitio web.',
                'descripcion.max' => 'La descripcion no puede ser mas de 200 caracteres'
            ]);

            $validatedData['nombre'] = strtoupper($validatedData['nombre']);
            $validatedData['pais'] = strtoupper($validatedData['pais']);

            $marca = Marca::create($validatedData);

            return response()->json([
                'message' => 'Marca creada con éxito',
                'nuevo empleado' => $marca
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al crear el empleado', 'detailsError' => $e], 500);
        }
    }

    // Actualizar una marca existente
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => [
                    'required',
                    'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ. ]+$/',
                    'max:30'
                ],
                'pais' => [
                    'required',
                    'regex:/^[a-zA-Z\s]+$/', // Solo letras
                    'max:30'
                ],
                'email' => [
                    'nullable',
                    'email',
                    'regex:/(.*)@(gmail|yahoo|outlook)\.com$/i', // Solo Gmail, Yahoo o Outlook
                ],
                'direccion' => [
                    'nullable',
                    'regex:/^[a-zA-Z0-9. ]+$/',
                    'max:60',
                ],
                'telefono' => [
                    'nullable',
                    'regex:/^\+\d{1,3}\s\d{6,10}$/',
                ],
                'sitio_web' => [
                    'nullable',
                    'string',
                    'max:30',
                    'url'
                ],
                'descripcion' => [
                    'nullable',
                    'string',
                    'max:200'
                ]
            ], [
                'nombre.required' => 'El campo de nombre es obligatorio.',
                'nombre.regex' => 'El nombre solo debe contener letras, números, puntos y espacios.',
                'nombre.max' => 'Solo se permite hasta maximo 30 caracteres en el campo nombre.',
                'pais.required' => 'El país es obligatorio.',
                'pais.regex' => 'El país solo debe contener letras.',
                'pais.max' => 'Solo se permite hasta maximo 30 caracteres en el campo pais.',
                'email.regex' => 'El email solo debe ser de dominio Gmail, Yahoo o Outlook.',
                'email.email' => 'Introduzca un email valido',
                'email.unique' => 'No puede repetir el email',
                'direccion.regex' => 'La dirección solo debe contener letras y números.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'direccion.max' => 'Solo se permite hasta maximo 60 caracteres en el campo nombre.',
                'telefono.regex' => 'El celular de contacto debe ser válido',
                'sitio_web.max' => 'El sitio web no puede ser mas de 30 caracteres',
                'sitio_web.url' => 'Error con la url de sitio web.',
                'descripcion.max' => 'La descripcion no puede ser mas de 200 caracteres'
            ]);

            $marca = Marca::findOrFail($id); // Busca la marca o lanza un error 404

            $validatedData['nombre'] = strtoupper($validatedData['nombre']);
            $validatedData['pais'] = strtoupper($validatedData['pais']);

            $marca->update($validatedData); // Actualiza la marca
            return response()->json([
                'message' => 'Marca actualizada con éxito',
                'marca actualizada' => $marca
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['messageError' => 'Error de validación', 'validationError' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['messageError' => 'Error al editar la marca', 'detailsError' => $e], 500);
        }
    }

    // Eliminar una marca
    public function destroy($id)
    {
        $marca = Marca::find($id); // Busca la marca

        if ($marca) {
            try {
                $marca->delete(); // Elimina la marca
                return response()->json(['message' => 'Marca eliminada correctamente'], 200);
            } catch (QueryException $e) {
                return response()->json(['messageError' => 'Error con la base de datos', 'errordb' => $e->getMessage()], 400);
            } catch (\Exception $e) {
                return response()->json(['messageError' => 'Error al editar la marca', 'detailsError' => $e], 500);
            }

        } else {
            return response()->json(['message' => 'Marca no encontrada'], 404);
        }
    }
}
