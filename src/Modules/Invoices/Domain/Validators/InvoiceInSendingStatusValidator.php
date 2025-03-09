<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;

class InvoiceInSendingStatusValidator implements InvoiceValidatorInterface
{
    public function validate(Invoice $invoice): bool
    {
        return $invoice->status === StatusEnum::Sending->value;
    }

    public function getValidationError(): string
    {
        return 'validation.invoice-not-in-sending-status';
    }
}
