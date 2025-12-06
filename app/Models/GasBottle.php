<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $location Ubicación de la botella
 * @property numeric $weight_kg Peso en kg de la botella
 * @property \Illuminate\Support\Carbon $installed_at Fecha y hora de instalación
 * @property \Illuminate\Support\Carbon|null $finished_at Fecha y hora cuando se agotó
 * @property int|null $duration_days Días que duró la botella
 * @property numeric|null $estimated_daily_usage Uso diario estimado en kg
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $days_elapsed
 * @property-read float|null $estimated_usage_percentage
 * @property-read string $status
 * @property-read \App\Models\GasPurchase|null $purchase
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle byLocation(string $location)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle finished()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereEstimatedDailyUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereInstalledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasBottle whereWeightKg($value)
 * @mixin \Eloquent
 */
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
     * Relación con los movimientos de la botella
     */
    public function moves(): HasMany
    {
        return $this->hasMany(GasBottleMove::class);
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
    public function markAsFinished(?Carbon $finishedAt = null): void
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
        return (int) $this->installed_at->diffInDays($endDate);
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

    /**
     * Mover botella a otra ubicación
     */
    public function moveTo(string $newLocation, ?string $reason = null, ?Carbon $movedAt = null): void
    {
        if ($this->location === $newLocation) {
            throw new \InvalidArgumentException('La botella ya está en la ubicación ' . $newLocation);
        }

        if (!in_array($newLocation, ['cocina', 'calentador'])) {
            throw new \InvalidArgumentException('Ubicación no válida: ' . $newLocation);
        }

        $movedAt = $movedAt ?? now();
        $oldLocation = $this->location;

        // Registrar el movimiento
        $this->moves()->create([
            'from_location' => $oldLocation,
            'to_location' => $newLocation,
            'moved_at' => $movedAt,
            'reason' => $reason,
        ]);

        // Actualizar la ubicación
        $this->location = $newLocation;
        $this->save();
    }
}
