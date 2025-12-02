<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GasBottle;
use App\Models\GasPurchase;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GasDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer usuario o crear uno de prueba
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Usuario Demo',
                'email' => 'demo@example.com',
            ]);
        }

        // Generar datos de los últimos 6 meses
        $startDate = Carbon::now()->subMonths(6);

        // Array de proveedores realistas
        $suppliers = ['Repsol', 'Cepsa', 'Galp', 'Alcampo', 'Carrefour'];

        // Precios realistas (entre 14€ y 18€)
        $basePrice = 15.50;

        // Generar botellas de cocina (cambian aprox cada 45-60 días)
        $this->generateBottles($user, 'cocina', $startDate, [45, 60], $suppliers, $basePrice);

        // Generar botellas de calentador (cambian aprox cada 25-40 días)
        $this->generateBottles($user, 'calentador', $startDate, [25, 40], $suppliers, $basePrice);

        $this->command->info('✓ Datos de prueba generados correctamente');
    }

    private function generateBottles(
        User $user,
        string $location,
        Carbon $startDate,
        array $durationRange,
        array $suppliers,
        float $basePrice
    ): void {
        $currentDate = $startDate->copy();
        $now = Carbon::now();

        while ($currentDate->lessThan($now)) {
            // Duración aleatoria dentro del rango
            $duration = rand($durationRange[0], $durationRange[1]);
            $finishedAt = $currentDate->copy()->addDays($duration);

            // Si la fecha de finalización es futura, esta es la botella activa
            $isActive = $finishedAt->greaterThan($now);

            // Crear botella
            $bottle = GasBottle::create([
                'user_id' => $user->id,
                'location' => $location,
                'weight_kg' => 12.5,
                'installed_at' => $currentDate,
                'finished_at' => $isActive ? null : $finishedAt,
                'duration_days' => $isActive ? null : $duration,
                'estimated_daily_usage' => $isActive ? null : round(12.5 / $duration, 3),
                'notes' => $isActive ? 'Botella actualmente en uso' : null,
            ]);

            // Crear compra asociada con variación de precio
            $priceVariation = rand(-150, 150) / 100; // Variación de ±1.50€
            $price = $basePrice + $priceVariation;

            GasPurchase::create([
                'user_id' => $user->id,
                'gas_bottle_id' => $bottle->id,
                'price' => round($price, 2),
                'purchase_date' => $currentDate,
                'supplier' => $suppliers[array_rand($suppliers)],
                'weight_kg' => 12.5,
                'bottle_type' => 'recarga',
            ]);

            // Si es la botella activa, no generar más
            if ($isActive) {
                break;
            }

            // Avanzar a la siguiente botella (fecha de instalación = fecha finalización anterior)
            $currentDate = $finishedAt->copy();
        }
    }
}
