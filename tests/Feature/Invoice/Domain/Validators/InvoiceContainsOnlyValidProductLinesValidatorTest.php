<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\InvoiceProductLine;
use Modules\Invoices\Domain\Validators\InvoiceContainsOnlyValidProductLinesValidator;
use PHPUnit\Framework\TestCase;

class InvoiceContainsOnlyValidProductLinesValidatorTest extends TestCase
{
    public function testValidateReturnsTrueWhenAllProductLinesAreValid(): void
    {
        $invoice = new Invoice();
        $invoice->productLines = [
            new InvoiceProductLine(['quantity' => 1, 'price' => 100]),
            new InvoiceProductLine(['quantity' => 2, 'price' => 200]),
        ];

        $validator = new InvoiceContainsOnlyValidProductLinesValidator();
        $this->assertTrue($validator->validate($invoice));
    }

    public function testValidateReturnsFalseWhenAnyProductLineIsInvalid(): void
    {
        $invoice = new Invoice();
        $invoice->productLines = [
            new InvoiceProductLine(['quantity' => 1, 'price' => 100]),
            new InvoiceProductLine(['quantity' => 0, 'price' => 200]),
        ];

        $validator = new InvoiceContainsOnlyValidProductLinesValidator();
        $this->assertFalse($validator->validate($invoice));
    }
}
