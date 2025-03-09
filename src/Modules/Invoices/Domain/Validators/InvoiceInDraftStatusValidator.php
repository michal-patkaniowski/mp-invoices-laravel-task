<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;

class InvoiceInDraftStatusValidator implements InvoiceValidatorInterface
{
    public function validate(Invoice $invoice): bool
    {
        return $invoice->status === StatusEnum::Draft->value;
    }

    public function getValidationError(): string
    {
        return 'validation.invoice-not-in-draft-status';
    }
}
