<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_repuesto_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('repuesto_id')->constrained('repuestos')->onDelete('cascade');
            $table->date('fecha');
            $table->integer('cantidad');
            $table->text('descripcion')->nullable();
            $table->string('estado');
            $table->decimal('costo_adquisicion', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_repuesto_proveedor');
    }
};
