<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Listeners;

use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Invoices\Domain\Models\Invoice;
use Log;

class UpdateInvoiceStatus
{
    public function handle(ResourceDeliveredEvent $event): void
    {
        Log::info('UpdateInvoiceStatus listener triggered', ['resourceId' => $event->resourceId]);

        $invoice = Invoice::where('id', $event->resourceId)->first();

        if ($invoice && $invoice->status === 'sending') {
            $invoice->status = 'sent-to-client';
            $invoice->save();
        } else {
            Log::warning('Invoice not found or status not sending', ['resourceId' => $event->resourceId]);
        }
    }
}
