<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'ventas';

    public $timestamps = false; // Deshabilita los timestamps
    
    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'id_cliente',
        'empleados_id',
        'fecha_venta'
    ];

    // Relación con la tabla 'clientes'
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    // Relación con la tabla 'empleados'
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleados_id', 'id');
    }

    // Relación con la tabla 'detalles_venta'
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id');
    }
}
