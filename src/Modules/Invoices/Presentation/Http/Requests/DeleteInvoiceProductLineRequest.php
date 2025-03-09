<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteInvoiceProductLineRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'invoice_id' => 'required|uuid|exists:invoices,id',
            'id' => 'required|uuid|exists:invoice_product_lines,id',
        ];
    }
}
