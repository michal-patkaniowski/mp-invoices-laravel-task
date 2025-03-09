<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\InvoiceProductLine;
use Modules\Invoices\Domain\Validators\InvoiceContainsAtLeastOneProductLineValidator;
use PHPUnit\Framework\TestCase;

class InvoiceContainsAtLeastOneProductLineValidatorTest extends TestCase
{
    public function testValidateReturnsTrueWhenThereIsAtLeastOneProductLine(): void
    {
        $invoice = new Invoice();
        $invoice->productLines = [new InvoiceProductLine()];

        $validator = new InvoiceContainsAtLeastOneProductLineValidator();
        $this->assertTrue($validator->validate($invoice));
    }

    public function testValidateReturnsFalseWhenThereAreNoProductLines(): void
    {
        $invoice = new Invoice();
        $invoice->productLines = [];

        $validator = new InvoiceContainsAtLeastOneProductLineValidator();
        $this->assertFalse($validator->validate($invoice));
    }
}
