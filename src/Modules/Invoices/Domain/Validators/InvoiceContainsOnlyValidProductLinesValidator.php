<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\InvoiceProductLine;

class InvoiceContainsOnlyValidProductLinesValidator implements InvoiceValidatorInterface
{
    public function validate(Invoice $invoice): bool
    {
        foreach ($invoice->productLines as $productLine) {
            if (!$this->isValidProductLine($productLine)) {
                return false;
            }
        }
        return true;
    }

    private function isValidProductLine(InvoiceProductLine $productLine): bool
    {
        return $productLine->quantity > 0
            && $productLine->unitPrice > 0
            && is_int($productLine->quantity)
            && is_int($productLine->unitPrice);
    }

    public function getValidationError(): string
    {
        return 'validation.invoice-contains-invalid-product-lines';
    }
}
