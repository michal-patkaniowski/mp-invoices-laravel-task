<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Listeners;

use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Services\InvoiceValidatorService;
use Modules\Invoices\Domain\Validators\InvoiceInSendingStatusValidator;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Invoices\Domain\Models\Invoice;

class UpdateInvoiceStatus
{
    public function __construct(private InvoiceValidatorService $invoiceValidatorService)
    {
    }
    public function handle(ResourceDeliveredEvent $event): void
    {
        $invoice = Invoice::findOrFail($event->resourceId);

        $this->invoiceValidatorService->validateOrFail([InvoiceInSendingStatusValidator::class], $invoice);

        $invoice->status = StatusEnum::SentToClient;
        $invoice->save();
    }
}
