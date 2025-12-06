<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gas_bottle_moves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_bottle_id')->constrained()->onDelete('cascade');
            $table->enum('from_location', ['cocina', 'calentador'])->comment('Ubicación de origen');
            $table->enum('to_location', ['cocina', 'calentador'])->comment('Ubicación de destino');
            $table->dateTime('moved_at')->comment('Fecha y hora del movimiento');
            $table->text('reason')->nullable()->comment('Razón del movimiento');
            $table->timestamps();

            // Índices para consultas
            $table->index(['gas_bottle_id', 'moved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_bottle_moves');
    }
};
