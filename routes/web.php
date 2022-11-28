<?php

use Crater\Http\Controllers\V1\Admin\Auth\LoginController;
use Crater\Http\Controllers\V1\Admin\Expense\ShowReceiptController;
use Crater\Http\Controllers\V1\Admin\Report\CustomerSalesReportController;
use Crater\Http\Controllers\V1\Admin\Report\VatPurchaseReportController;
use Crater\Http\Controllers\V1\Admin\Report\VatReturnReportController;
use Crater\Http\Controllers\V1\Admin\Report\ExpensesReportController;
use Crater\Http\Controllers\V1\Admin\Report\ItemSalesReportController;
use Crater\Http\Controllers\V1\Admin\Report\ProfitLossReportController;
use Crater\Http\Controllers\V1\Admin\Report\TaxSummaryReportController;
use Crater\Http\Controllers\V1\Customer\EstimatePdfController as CustomerEstimatePdfController;
use Crater\Http\Controllers\V1\Customer\InvoicePdfController as CustomerInvoicePdfController;
use Crater\Http\Controllers\V1\Customer\ProformaPdfController as CustomerProformaPdfController;
use Crater\Http\Controllers\V1\Customer\CreditPdfController as CustomerCreditNotePdfController;
use Crater\Http\Controllers\V1\PDF\DownloadInvoicePdfController;
use Crater\Http\Controllers\V1\PDF\DownloadProformaPdfController;
use Crater\Http\Controllers\V1\PDF\DownloadPaymentPdfController;
use Crater\Http\Controllers\V1\PDF\PaymentFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\DownloadReceiptController;
use Crater\Http\Controllers\V1\PDF\EstimatePdfController;
use Crater\Http\Controllers\V1\PDF\InvoicePdfController;
use Crater\Http\Controllers\V1\PDF\InvoiceFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\CreditFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\DebitFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\PurchasePdfController;
use Crater\Http\Controllers\V1\PDF\PurchaseFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\ProformaPdfController;
use Crater\Http\Controllers\V1\PDF\ProformaFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\QuotationFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\CustomerFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\SupplierFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\ItemFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\UserFilteredPdfController;
use Crater\Http\Controllers\V1\PDF\CreditsPdfController;
use Crater\Http\Controllers\V1\PDF\DebitsPdfController;
use Crater\Http\Controllers\V1\PDF\PaymentPdfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::get('licence_expired', function(Request $request){
    return view('licence_expired');
});
Route::post('login', [LoginController::class, 'login']);


Route::middleware('auth:sanctum')->prefix('reports')->group(function () {

    // sales report by customer
    //----------------------------------
    Route::get('/sales/customers/{hash}', CustomerSalesReportController::class);

    // sales report by items
    //----------------------------------
    Route::get('/sales/items/{hash}', ItemSalesReportController::class);

    // vat register report
    //----------------------------------
    Route::get('/purchase/suppliers/{hash}', VatPurchaseReportController::class);
    Route::get('/sale_purchase/return/{hash}', VatReturnReportController::class);

    // report for expenses
    //----------------------------------
    Route::get('/expenses/{hash}', ExpensesReportController::class);

    // report for tax summary
    //----------------------------------
    Route::get('/tax-summary/{hash}', TaxSummaryReportController::class);

    // report for profit and loss
    //----------------------------------
    Route::get('/profit-loss/{hash}', ProfitLossReportController::class);
});


    Route::get('/filtered-customers-list', CustomerFilteredPdfController::class);
    Route::get('/filtered-items-list', ItemFilteredPdfController::class);
    Route::get('/filtered-suppliers-list', SupplierFilteredPdfController::class);
// view invoice pdf
// -------------------------------------------------

Route::get('/invoices/pdf/{invoice:unique_hash}', InvoicePdfController::class);
Route::get('/filtered-invoices-list', InvoiceFilteredPdfController::class);

// VIEW PURCHASE INVOICES
Route::get('/purchases/pdf/{purchase:unique_hash}', PurchasePdfController::class);
Route::get('/filtered-purchases-list', PurchaseFilteredPdfController::class);

// view proforma pdf
// -------------------------------------------------
Route::get('/proformas/pdf/{proforma:unique_hash}', ProformaPdfController::class);
Route::get('/filtered-proformas-list', ProformaFilteredPdfController::class);

// view credit note pdf
// -------------------------------------------------
Route::get('/credits/pdf/{credit:unique_hash}', CreditsPdfController::class);
Route::get('/filtered-credits-list', CreditFilteredPdfController::class);

// view user pdf
// -------------------------------------------------
Route::get('/filtered-users-list', UserFilteredPdfController::class);

// view debit note pdf
// -------------------------------------------------
Route::get('/debits/pdf/{debit:unique_hash}', DebitsPdfController::class);
Route::get('/filtered-debits-list', DebitFilteredPdfController::class);

// download invoice pdf
// -------------------------------------------------

Route::get('/invoices/pdf/download/{invoice:unique_hash}', DownloadInvoicePdfController::class);

// download proforma pdf
// -------------------------------------------------

Route::get('/proformas/pdf/download/{proforma:unique_hash}', DownloadProformaPdfController::class);




// view estimate pdf
// -------------------------------------------------

Route::get('/estimates/pdf/{estimate:unique_hash}', EstimatePdfController::class);
Route::get('/filtered-quotations-list', QuotationFilteredPdfController::class);

// view payment pdf
// -------------------------------------------------

Route::get('/payments/pdf/{payment:unique_hash}', PaymentPdfController::class);
Route::get('/filtered-payments-list', PaymentFilteredPdfController::class);


// download payment pdf
// -------------------------------------------------

Route::get('/payments/pdf/download/{payment:unique_hash}', DownloadPaymentPdfController::class);


// download expense receipt
// -------------------------------------------------

Route::get('/expenses/{expense}/download-receipt', DownloadReceiptController::class);
Route::get('/expenses/{expense}/receipt', ShowReceiptController::class);


// customer pdf endpoints for invoice, creditnote, proforma and estimate
// -----------------------------------------------------------

Route::get('/customer/invoices/pdf/{invoice:unique_hash}', CustomerInvoicePdfController::class);

Route::get('/customer/credits/pdf/{credit:unique_hash}', CustomerCreditNotePdfController::class);

//Route::get('/customer/pro-forma-invoices/pdf/{performa:unique_hash}', CustomerPerformaPdfController::class);
Route::get('/customer/proformas/pdf/{proforma:unique_hash}', CustomerProformaPdfController::class);

Route::get('/customer/estimates/pdf/{estimate:unique_hash}', CustomerEstimatePdfController::class);


Route::get('auth/logout', function () {
    Auth::guard('web')->logout();
});


// Setup for installation of app
// ----------------------------------------------

Route::get('/installation', function () {
    return view('app');
})->name('install')->middleware('redirect-if-installed');


// Move other http requests to the Vue App
// -------------------------------------------------

Route::get('/admin/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('admin')->middleware(['install', 'redirect-if-unauthenticated']);


// Move other http requests to the Vue App
// -------------------------------------------------

Route::get('/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('login')->middleware(['install']);
