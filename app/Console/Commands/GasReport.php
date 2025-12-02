<?php

namespace App\Console\Commands;

use App\Models\GasBottle;
use Illuminate\Console\Command;

class GasReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gas:report {user_id? : ID del usuario}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un reporte del estado de las botellas de gas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id') ?? 1;

        $this->info("📊 Reporte de Botellas de Gas - Usuario #{$userId}");
        $this->newLine();

        // Botellas activas
        $activeBottles = GasBottle::where('user_id', $userId)
            ->active()
            ->get();

        if ($activeBottles->count() > 0) {
            $this->info("🟢 BOTELLAS ACTIVAS:");
            foreach ($activeBottles as $bottle) {
                $icon = $bottle->location === 'cocina' ? '🍳' : '🚿';
                $this->line("  $icon {$bottle->location}: {$bottle->days_elapsed} días activa");

                if ($bottle->estimated_usage_percentage) {
                    $percentage = round($bottle->estimated_usage_percentage);
                    $bar = str_repeat('█', (int)($percentage / 5));
                    $this->line("     [{$bar}] {$percentage}% estimado");
                }
            }
            $this->newLine();
        } else {
            $this->warn("⚠️  No hay botellas activas");
            $this->newLine();
        }

        // Estadísticas por ubicación
        foreach (['cocina', 'calentador'] as $location) {
            $stats = $this->getLocationStats($userId, $location);

            if ($stats['total'] > 0) {
                $icon = $location === 'cocina' ? '🍳' : '🚿';
                $this->info("$icon ESTADÍSTICAS DE " . strtoupper($location) . ":");
                $this->line("  • Total de botellas: {$stats['total']}");
                $this->line("  • Duración promedio: {$stats['avg_duration']} días");
                $this->line("  • Uso diario promedio: {$stats['avg_daily']} kg/día");
                $this->line("  • Rango: {$stats['min']}-{$stats['max']} días");
                $this->newLine();
            }
        }

        return Command::SUCCESS;
    }

    private function getLocationStats(int $userId, string $location): array
    {
        $bottles = GasBottle::where('user_id', $userId)
            ->where('location', $location)
            ->finished()
            ->whereNotNull('duration_days')
            ->get();

        if ($bottles->isEmpty()) {
            return ['total' => 0];
        }

        return [
            'total' => $bottles->count(),
            'avg_duration' => round($bottles->avg('duration_days'), 1),
            'avg_daily' => round($bottles->avg('estimated_daily_usage'), 3),
            'min' => $bottles->min('duration_days'),
            'max' => $bottles->max('duration_days'),
        ];
    }
}
