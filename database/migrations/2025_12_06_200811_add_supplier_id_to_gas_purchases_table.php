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
        Schema::table('gas_purchases', function (Blueprint $table) {
            // Primero eliminar la columna supplier si existe
            $table->dropColumn('supplier');
            // Agregar la relación con gas_suppliers
            $table->foreignId('supplier_id')->nullable()->after('user_id')->constrained('gas_suppliers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_purchases', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
            // Restaurar la columna supplier
            $table->string('supplier')->nullable();
        });
    }
};
