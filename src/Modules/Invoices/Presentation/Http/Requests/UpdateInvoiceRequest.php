<?php

namespace Modules\Invoices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Invoices\Domain\Enums\StatusEnum;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|uuid',
            'status' => 'string|in:' . implode(',', StatusEnum::values()),
            'customer_name' => 'string|max:255',
            'customer_email' => 'string|email|max:255',
        ];
    }
}
