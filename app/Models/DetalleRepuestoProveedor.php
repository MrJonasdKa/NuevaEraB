<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRepuestoProveedor extends Model
{
    use HasFactory;

    protected $table = 'detalle_repuesto_proveedor';

    protected $fillable = ['proveedor_id', 'repuesto_id', 'fecha', 'cantidad', 'descripcion', 'estado', 'costo_adquisicion'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }
}
