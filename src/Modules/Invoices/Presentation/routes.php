<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Invoices\Presentation\Http\Controllers\InvoiceController;

Route::prefix('invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'get'])->name('invoices.get');
    Route::post('/', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::put('/', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::post('/item', [InvoiceController::class, 'addItem'])->name('invoices.addItem');
    Route::delete('/item', [InvoiceController::class, 'deleteItem'])->name('invoices.deleteItem');
    Route::post('/send', [InvoiceController::class, 'send'])->name('invoices.send');
});
