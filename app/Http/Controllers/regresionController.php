<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;

class regresionController extends Controller
{
    public function predictSales($producto_id)
    {
        // Obtener datos de la base de datos
        $datos = DB::table('regresion')
            ->select('ventas', 'cantidad', 'mes')
            ->where('id_repuesto', $producto_id)
            ->get();

        if ($datos->isEmpty()) {
            return response()->json(["error" => "No se encontraron datos para el producto con id $producto_id"], 404);
        }

        // Preparar datos para la regresión
        $samples = [];
        $targets = [];

        foreach ($datos as $dato) {
            $samples[] = [(float) $dato->cantidad, (float) $dato->mes];
            $targets[] = (float) $dato->ventas;
        }

        // Normalizar los datos para reducir problemas de escala
        $maxCantidad = max(array_column($samples, 0)) ?: 1;
        $maxMes = max(array_column($samples, 1)) ?: 1;

        foreach ($samples as &$sample) {
            $sample[0] /= $maxCantidad;  // Normalizar cantidad
            $sample[1] /= $maxMes;       // Normalizar mes
        }
        unset($sample);  // Rompe la referencia para evitar problemas

        // Verificar y agregar pequeña perturbación si los datos están duplicados o no tienen variación
        $samples = array_map(function($sample) {
            return [
                $sample[0] + (rand(1, 10) * 0.0001),  // Añadir perturbación a cantidad
                $sample[1] + (rand(1, 10) * 0.0001)   // Añadir perturbación a mes
            ];
        }, $samples);

        // Dividir datos en entrenamiento y prueba (80% para entrenamiento, 20% para prueba)
        $trainSize = (int)(count($samples) * 0.8);
        $trainSamples = array_slice($samples, 0, $trainSize);
        $trainTargets = array_slice($targets, 0, $trainSize);
        $testSamples = array_slice($samples, $trainSize);
        $testTargets = array_slice($targets, $trainSize);

        // Crear y entrenar el modelo de regresión
        $regression = new LeastSquares();
        try {
            $regression->train($trainSamples, $trainTargets);
        } catch (\Exception $e) {
            return response()->json(["error" => "No se pudo entrenar el modelo debido a un problema con los datos: " . $e->getMessage()], 500);
        }

        // Realizar predicciones
        $predictions = [];
        foreach ($testSamples as $sample) {
            $predictions[] = $regression->predict($sample);
        }

        // Formatear los resultados en JSON
        $resultados = [
            "ventas_reales" => $testTargets,
            "ventas_predichas" => $predictions,
            "producto_id" => $producto_id
        ];

        return response()->json($resultados);
    }
}
