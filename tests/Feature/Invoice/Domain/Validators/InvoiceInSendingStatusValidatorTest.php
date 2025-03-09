<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Validators\InvoiceInSendingStatusValidator;
use PHPUnit\Framework\TestCase;

class InvoiceInSendingStatusValidatorTest extends TestCase
{
    public function testValidateReturnsTrueWhenStatusIsSending(): void
    {
        $invoice = new Invoice();
        $invoice->status = 'sending';

        $validator = new InvoiceInSendingStatusValidator();
        $this->assertTrue($validator->validate($invoice));
    }

    public function testValidateReturnsFalseWhenStatusIsNotSending(): void
    {
        $invoice = new Invoice();
        $invoice->status = 'draft';

        $validator = new InvoiceInSendingStatusValidator();
        $this->assertFalse($validator->validate($invoice));
    }
}
