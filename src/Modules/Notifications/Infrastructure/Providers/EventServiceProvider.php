<?php

namespace Modules\Notifications\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Invoices\Domain\Listeners\UpdateInvoiceStatus;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ResourceDeliveredEvent::class => [
            UpdateInvoiceStatus::class,
        ],
    ];
}
