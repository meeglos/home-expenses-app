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
        Schema::create('gas_bottles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('location', ['cocina', 'calentador'])->comment('Ubicación de la botella');
            $table->decimal('weight_kg', 5, 2)->default(12.5)->comment('Peso en kg de la botella');
            $table->dateTime('installed_at')->comment('Fecha y hora de instalación');
            $table->dateTime('finished_at')->nullable()->comment('Fecha y hora cuando se agotó');
            $table->integer('duration_days')->nullable()->comment('Días que duró la botella');
            $table->decimal('estimated_daily_usage', 8, 3)->nullable()->comment('Uso diario estimado en kg');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Índices para consultas rápidas
            $table->index(['user_id', 'location', 'installed_at']);
            $table->index('finished_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_bottles');
    }
};
