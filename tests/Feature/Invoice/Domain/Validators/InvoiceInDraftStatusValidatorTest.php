<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Validators\InvoiceInDraftStatusValidator;
use PHPUnit\Framework\TestCase;
use Modules\Invoices\Domain\Enums\StatusEnum;

class InvoiceInDraftStatusValidatorTest extends TestCase
{
    public function testValidateReturnsTrueWhenStatusIsDraft(): void
    {
        $invoice = new Invoice();
        $invoice->status = StatusEnum::Draft->value;

        $validator = new InvoiceInDraftStatusValidator();
        $this->assertTrue($validator->validate($invoice));
    }

    public function testValidateReturnsFalseWhenStatusIsNotDraft(): void
    {
        $invoice = new Invoice();
        $invoice->status = StatusEnum::Sending->value;

        $validator = new InvoiceInDraftStatusValidator();
        $this->assertFalse($validator->validate($invoice));
    }
}
