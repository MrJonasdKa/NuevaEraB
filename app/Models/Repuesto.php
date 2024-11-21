<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Importar la clase Storage

class Repuesto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nombre', 
        'descripcion', 
        'cantidad_stock', 
        'imagen', 
        'id_marca', 
        'id_categoria', 
        'costo_unitario', 
        'precio_unitario', 
        'codigo_oem', 
        'numero_serie'
    ];

    // Relación con la tabla 'marcas' (un repuesto pertenece a una marca)
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id');
    }

    // Relación con la tabla 'categorias'
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id');
    }

    // Método para obtener la URL de la imagen
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : null;
    }
    
}
