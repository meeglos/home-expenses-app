<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $gas_bottle_id
 * @property string $from_location
 * @property string $to_location
 * @property \Illuminate\Support\Carbon $moved_at
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GasBottle $gasBottle
 */
class GasBottleMove extends Model
{
    protected $fillable = [
        'gas_bottle_id',
        'from_location',
        'to_location',
        'moved_at',
        'reason',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    /**
     * Relación con la botella
     */
    public function gasBottle(): BelongsTo
    {
        return $this->belongsTo(GasBottle::class);
    }

    /**
     * Alias para la relación gasBottle
     */
    public function bottle(): BelongsTo
    {
        return $this->gasBottle();
    }
}
