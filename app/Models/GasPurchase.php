<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $gas_bottle_id
 * @property numeric $price Precio de compra en euros
 * @property \Illuminate\Support\Carbon $purchase_date Fecha de compra
 * @property string|null $supplier Proveedor o tienda
 * @property numeric $weight_kg
 * @property string $bottle_type
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GasBottle|null $gasBottle
 * @property-read float $price_per_kg
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase recent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereBottleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereGasBottleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GasPurchase whereWeightKg($value)
 * @mixin \Eloquent
 */
class GasPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gas_bottle_id',
        'supplier_id',
        'price',
        'purchase_date',
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
     * Relación con el proveedor
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(GasSupplier::class, 'supplier_id');
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
