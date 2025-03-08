<?php

namespace Modules\Invoices\Presentation\Http\Controllers;

use Modules\Invoices\Presentation\Http\Requests\CreateInvoiceRequest;
use Modules\Invoices\Presentation\Http\Requests\GetInvoiceRequest;
use Modules\Invoices\Presentation\Http\Requests\AddInvoiceProductLineRequest;
use Modules\Invoices\Presentation\Http\Requests\DeleteInvoiceProductLineRequest;
use Modules\Invoices\Presentation\Http\Requests\UpdateInvoiceRequest;
use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\InvoiceProductLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class InvoiceController extends Controller
{
    public function get(GetInvoiceRequest $request, $id): JsonResponse
    {
        $invoice = Invoice::with('productLines')->findOrFail($id);

        return response()->json($invoice);
    }

    public function create(CreateInvoiceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::create(array_merge(['id' => \Str::uuid()], $validated));
        $invoice->save();
        return response()->json($invoice, 201);
    }

    public function update(UpdateInvoiceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['id']);
        $invoice->update($validated);

        return response()->json($invoice);
    }

    public function addItem(AddInvoiceProductLineRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['invoice_id']);

        if ($invoice->status !== 'draft') {
            return response()->json(['message' => 'error.not-allowed'], 400);
        }

        $productLine = InvoiceProductLine::create(array_merge(['id' => \Str::uuid()], $validated));
        $invoice->productLines()->save($productLine);

        return response()->json($productLine, 201);
    }

    public function deleteItem(DeleteInvoiceProductLineRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['invoice_id']);

        if ($invoice->status !== 'draft') {
            return response()->json(['message' => 'error.not-allowed'], 400);
        }

        $productLine = $invoice->productLines()->findOrFail($validated['id']);
        $productLine->delete();

        return response()->json(null, 204);
    }
}
