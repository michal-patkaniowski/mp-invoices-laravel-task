<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Validators;

use Modules\Invoices\Domain\Models\Invoice;

class InvoiceContainsAtLeastOneProductLineValidator implements InvoiceValidatorInterface
{
    public function validate(Invoice $invoice): bool
    {
        return count($invoice->productLines) > 0;
    }

    public function getValidationError(): string
    {
        return 'validation.invoice-contains-no-product-lines';
    }
}
