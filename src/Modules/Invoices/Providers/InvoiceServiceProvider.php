<?php

namespace Modules\Invoices\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Invoices\Domain\Services\InvoiceValidatorService;

class InvoiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            InvoiceValidatorService::class,
            static fn($app): InvoiceValidatorService => new InvoiceValidatorService()
        );
    }
}
