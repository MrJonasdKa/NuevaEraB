<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'pais',
        'email',
        'direccion',
        'telefono',
        'sitio_web',
        'descripcion'
    ];

    // Relación uno a muchos con la tabla 'repuestos'
    public function repuestos()
    {
        return $this->hasMany(Repuesto::class, 'id_marca', 'id');
    }
}
