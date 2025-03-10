<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProductLine extends Model
{
    protected $fillable = [
        'id',
        'invoice_id',
        'name',
        'price',
        'quantity',
    ];

    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $appends = ['total_unit_price'];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function getTotalUnitPriceAttribute(): float
    {
        return $this->quantity * $this->price;
    }
}
