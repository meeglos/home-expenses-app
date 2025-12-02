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
        Schema::create('gas_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('gas_bottle_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('price', 8, 2)->comment('Precio de compra en euros');
            $table->date('purchase_date')->comment('Fecha de compra');
            $table->string('supplier')->nullable()->comment('Proveedor o tienda');
            $table->decimal('weight_kg', 5, 2)->default(12.5);
            $table->enum('bottle_type', ['nueva', 'recarga'])->default('recarga');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['user_id', 'purchase_date']);
            $table->index('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_purchases');
    }
};
