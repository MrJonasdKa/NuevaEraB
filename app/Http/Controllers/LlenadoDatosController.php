<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LlenadoDatosController extends Controller
{
    public function llenarTestReg(Request $request)
    {
        try {
            // Validar los datos del request
            $validated = $request->validate([
                'id_repuesto' => 'required|integer',
                'anio' => 'required|integer',
            ]);

            $id_repuesto = $validated['id_repuesto'];
            $anio = $validated['anio'];

            // verificamos que existan los datos en la tabla
            $existingData = DB::table('regresion')
                ->where('id_repuesto', $id_repuesto)
                ->where('anio', $anio)
                ->first();

            if ($existingData) {
                return response()->json(['message' => 'Los datos ya han sido procesados. Por favor, haga clic en "Ver Gráfico" para ver el gráfico.'], 200);
            }

            // Definir el rango de fechas para el año solicitado
            $fechaInicio = Carbon::createFromDate($anio, 1, 1);
            $fechaFin = Carbon::createFromDate($anio, 12, 31);

            // Obtenemos los movimientos de inventario
            $movimientos = DB::table('inventario_movimientos')
                ->select('fecha_movimiento', 'cantidad')
                ->where('id_repuesto', $id_repuesto)
                ->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin])
                ->orderBy('fecha_movimiento')
                ->get();

            if ($movimientos->isEmpty()) {
                return response()->json(['message' => 'No se encontraron movimientos para el id_repuesto y año proporcionados.'], 404);
            }

            // Variables para procesar las ventas
            $ventasPorMes = [];
            $ventaTemporal = 0;  // Contador de registros de ventas
            $cantidadTemporal = 0;  // Suma de cantidades vendidas
            $fechaInicioPeriodo = null;

            // Procesar movimientos
            foreach ($movimientos as $movimiento) {
                $fechaMovimiento = Carbon::parse($movimiento->fecha_movimiento);
                $mes = $fechaMovimiento->month;

                // Inicializamos la fecha de inicio del primer período
                if ($fechaInicioPeriodo === null) {
                    $fechaInicioPeriodo = $fechaMovimiento;
                }

                // Verificamos si el período actual de 5 días ha pasado
                if ($fechaMovimiento->diffInDays($fechaInicioPeriodo) >= 5) {
                    $ventasPorMes[] = [
                        'id_repuesto' => $id_repuesto,
                        'ventas' => $ventaTemporal,
                        'cantidad' => $cantidadTemporal,
                        'mes' => $fechaInicioPeriodo->month, // Mes del inicio del período
                        'anio' => $anio,
                    ];

                    // Reiniciar para el siguiente período
                    $ventaTemporal = 0;
                    $cantidadTemporal = 0;
                    $fechaInicioPeriodo = $fechaMovimiento;
                }

                // Actualizar acumuladores
                $cantidadTemporal += $movimiento->cantidad;
                $ventaTemporal++;
            }

            // Insertar último período si hay datos acumulados
            if ($ventaTemporal > 0 || $cantidadTemporal > 0) {
                $ventasPorMes[] = [
                    'id_repuesto' => $id_repuesto,
                    'ventas' => $ventaTemporal,
                    'cantidad' => $cantidadTemporal,
                    'mes' => $fechaInicioPeriodo->month,
                    'anio' => $anio,
                ];
            }

            // Insertar datos en testreg dentro de una transacción
            DB::transaction(function () use ($ventasPorMes) {
                foreach ($ventasPorMes as $registro) {
                    DB::table('regresion')->insert($registro);
                }
            });

            return response()->json(['message' => 'Datos procesados e insertados correctamente.'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el procesamiento de datos: ' . $e->getMessage()], 500);
        }
    }
}
