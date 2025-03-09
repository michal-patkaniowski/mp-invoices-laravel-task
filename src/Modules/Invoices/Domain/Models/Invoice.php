<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function productLines(): HasMany
    {
        return $this->hasMany(InvoiceProductLine::class, 'invoice_id');
    }
}
