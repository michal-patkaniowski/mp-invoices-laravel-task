<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    protected $fillable = [
        'id',
        'customer_name',
        'customer_email',
        'status',
    ];

    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $appends = ['total_price'];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function productLines(): HasMany
    {
        return $this->hasMany(InvoiceProductLine::class, 'invoice_id');
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->productLines()->sum(DB::raw('price * quantity'));
    }
}
