<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'detalles_venta';

    public $timestamps = false; // Deshabilita los timestamps

    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'id_venta',
        'id_repuesto',
        'cantidad',
        'monto',
        'subtotal',
        'total'
    ];

    // Relación con la tabla 'ventas'
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id');
    }

    // Relación con la tabla 'repuestos'
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'id_repuesto', 'id');
    }
}
