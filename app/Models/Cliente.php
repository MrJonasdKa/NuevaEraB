<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    // Nombre de la tabla en la base de datos
    protected $table = 'clientes';

    public $timestamps = false; // Deshabilita los timestamps
    
    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'ci',
        'nombres',
        'apellidos',
        'telefono',
        'email',
        'direccion'
    ];

    // Relación con la tabla 'ventas'
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_cliente', 'id');
    }
}
