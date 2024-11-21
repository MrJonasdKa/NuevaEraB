<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'categorias';

    public $timestamps = false; // Deshabilita los timestamps

    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    // Relación uno a uno con la tabla 'repuestos'
    public function repuesto()
    {
        return $this->hasOne(Repuesto::class, 'id_categoria', 'id');
    }
}
