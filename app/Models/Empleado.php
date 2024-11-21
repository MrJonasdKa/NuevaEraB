<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    // Nombre de la tabla en la base de datos
    protected $table = 'empleados';

    public $timestamps = false; // Deshabilita los timestamps
    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'ci',
        'nombres',
        'apellidos',
        'id_cargo',
        'telefono',
        'email',
        'direccion',
        'fecha_contratacion',
        'salario'
    ];

    // Relación con la tabla 'cargos'
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id_empleado', 'id');
    }
}
