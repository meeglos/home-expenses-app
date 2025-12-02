<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class GasBottle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'weight_kg',
        'installed_at',
        'finished_at',
        'duration_days',
        'estimated_daily_usage',
        'notes',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
        'finished_at' => 'datetime',
        'weight_kg' => 'decimal:2',
        'estimated_daily_usage' => 'decimal:3',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la compra asociada
     */
    public function purchase(): HasOne
    {
        return $this->hasOne(GasPurchase::class);
    }

    /**
     * Scope para botellas activas (no finalizadas)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('finished_at');
    }

    /**
     * Scope para botellas finalizadas
     */
    public function scopeFinished($query)
    {
        return $query->whereNotNull('finished_at');
    }

    /**
     * Scope por ubicación
     */
    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Marcar botella como terminada y calcular duración
     */
    public function markAsFinished(Carbon $finishedAt = null): void
    {
        $finishedAt = $finishedAt ?? now();

        $this->finished_at = $finishedAt;
        $this->duration_days = $this->installed_at->diffInDays($finishedAt);

        // Calcular uso diario estimado
        if ($this->duration_days > 0) {
            $this->estimated_daily_usage = $this->weight_kg / $this->duration_days;
        }

        $this->save();
    }

    /**
     * Días transcurridos desde la instalación
     */
    public function getDaysElapsedAttribute(): int
    {
        $endDate = $this->finished_at ?? now();
        return $this->installed_at->diffInDays($endDate);
    }

    /**
     * Estado de la botella (activa/terminada)
     */
    public function getStatusAttribute(): string
    {
        return $this->finished_at ? 'terminada' : 'activa';
    }

    /**
     * Porcentaje estimado de uso (si está activa)
     */
    public function getEstimatedUsagePercentageAttribute(): ?float
    {
        if ($this->finished_at) {
            return 100;
        }

        // Buscar promedio de duración de botellas anteriores en la misma ubicación
        $avgDuration = static::query()
            ->where('user_id', $this->user_id)
            ->where('location', $this->location)
            ->finished()
            ->whereNotNull('duration_days')
            ->avg('duration_days');

        if (!$avgDuration) {
            return null;
        }

        $daysElapsed = $this->days_elapsed;
        $percentage = ($daysElapsed / $avgDuration) * 100;

        return min($percentage, 100);
    }
}
