<?php

namespace Modules\Invoices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Invoices\Domain\Enums\StatusEnum;

class CreateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:' . implode(',', StatusEnum::values()),
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|string|email|max:255',
        ];
    }
}
