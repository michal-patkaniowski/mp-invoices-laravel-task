<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Validators;

use Modules\Invoices\Domain\Models\Invoice;

interface InvoiceValidatorInterface
{
    public function validate(Invoice $invoice): bool;

    public function getValidationError(): string;
}
