<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GasSupplierCall extends Model
{
    protected $fillable = [
        'user_id',
        'supplier_id',
        'called_at',
    ];

    protected $casts = [
        'called_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(GasSupplier::class, 'supplier_id');
    }
}
