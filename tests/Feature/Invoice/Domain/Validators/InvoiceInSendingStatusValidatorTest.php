<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Validators\InvoiceInSendingStatusValidator;
use PHPUnit\Framework\TestCase;
use Modules\Invoices\Domain\Enums\StatusEnum;

class InvoiceInSendingStatusValidatorTest extends TestCase
{
    public function testValidateReturnsTrueWhenStatusIsSending(): void
    {
        $invoice = new Invoice();
        $invoice->status = StatusEnum::Sending->value;

        $validator = new InvoiceInSendingStatusValidator();
        $this->assertTrue($validator->validate($invoice));
    }

    public function testValidateReturnsFalseWhenStatusIsNotSending(): void
    {
        $invoice = new Invoice();
        $invoice->status = StatusEnum::Draft->value;

        $validator = new InvoiceInSendingStatusValidator();
        $this->assertFalse($validator->validate($invoice));
    }
}
