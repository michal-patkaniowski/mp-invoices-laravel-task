<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Models\Invoice;
use Modules\Invoices\Domain\Models\InvoiceProductLine;
use Modules\Invoices\Domain\Services\InvoiceValidatorService;
use Modules\Invoices\Domain\Validators\InvoiceContainsAtLeastOneProductLineValidator;
use Modules\Invoices\Domain\Validators\InvoiceContainsOnlyValidProductLinesValidator;
use Modules\Invoices\Domain\Validators\InvoiceInDraftStatusValidator;
use Modules\Invoices\Presentation\Http\Requests\AddInvoiceProductLineRequest;
use Modules\Invoices\Presentation\Http\Requests\CreateInvoiceRequest;
use Modules\Invoices\Presentation\Http\Requests\DeleteInvoiceProductLineRequest;
use Modules\Invoices\Presentation\Http\Requests\GetInvoiceRequest;
use Modules\Invoices\Presentation\Http\Requests\UpdateInvoiceRequest;
use Modules\Notifications\Api\NotificationFacadeInterface;
use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Invoices\Presentation\Http\Requests\SendInvoiceRequest;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceValidatorService $invoiceValidatorService)
    {
        $this->invoiceValidatorService = $invoiceValidatorService;
    }

    public function get(GetInvoiceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::with('productLines')->findOrFail($validated['id']);

        return response()->json($invoice);
    }

    public function create(CreateInvoiceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::create(array_merge(['id' => \Str::uuid()], $validated));

        $this->invoiceValidatorService->validateOrFail([InvoiceInDraftStatusValidator::class], $invoice);

        $invoice->save();
        return response()->json($invoice, 201);
    }

    public function update(UpdateInvoiceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['id']);

        $this->invoiceValidatorService->validateOrFail([InvoiceInDraftStatusValidator::class], $invoice);

        $invoice->update($validated);

        return response()->json($invoice);
    }

    public function addItem(AddInvoiceProductLineRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['invoice_id']);

        $this->invoiceValidatorService->validateOrFail([InvoiceInDraftStatusValidator::class], $invoice);

        $productLine = InvoiceProductLine::create(array_merge(['id' => \Str::uuid()], $validated));
        $invoice->productLines()->save($productLine);

        return response()->json($productLine, 201);
    }

    public function deleteItem(DeleteInvoiceProductLineRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['invoice_id']);

        $this->invoiceValidatorService->validateOrFail([InvoiceInDraftStatusValidator::class], $invoice);

        $productLine = $invoice->productLines()->findOrFail($validated['id']);
        $productLine->delete();

        return response()->json(null, 204);
    }

    public function send(SendInvoiceRequest $request, NotificationFacadeInterface $notificationFacade): JsonResponse
    {
        $validated = $request->validated();
        $invoice = Invoice::findOrFail($validated['id']);

        $this->invoiceValidatorService->validateOrFail([
            InvoiceInDraftStatusValidator::class,
            InvoiceContainsAtLeastOneProductLineValidator::class,
            InvoiceContainsOnlyValidProductLinesValidator::class
        ], $invoice);

        $notificationFacade->notify(new NotifyData(
            resourceId: \Str::uuid(),
            toEmail: $invoice->customer_email,
            subject: 'Your Invoice',
            message: 'Your invoice has been sent.'
        ));

        $invoice->status = StatusEnum::Sending;
        $invoice->save();

        return response()->json($invoice);
    }
}
