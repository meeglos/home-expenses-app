<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GasSupplier extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(GasPurchase::class, 'supplier_id');
    }

    public function calls(): HasMany
    {
        return $this->hasMany(GasSupplierCall::class, 'supplier_id');
    }
}
