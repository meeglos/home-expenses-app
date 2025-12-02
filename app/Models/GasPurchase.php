<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GasPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gas_bottle_id',
        'price',
        'purchase_date',
        'supplier',
        'weight_kg',
        'bottle_type',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
        'weight_kg' => 'decimal:2',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la botella
     */
    public function gasBottle(): BelongsTo
    {
        return $this->belongsTo(GasBottle::class);
    }

    /**
     * Scope para ordenar por fecha más reciente
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('purchase_date', 'desc');
    }

    /**
     * Precio por kg
     */
    public function getPricePerKgAttribute(): float
    {
        return $this->price / $this->weight_kg;
    }
}
