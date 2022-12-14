<?php

use Crater\Http\Controllers\AppVersionController;
use Crater\Http\Controllers\V1\Admin\Auth\ForgotPasswordController;
use Crater\Http\Controllers\V1\Admin\Auth\ResetPasswordController;
use Crater\Http\Controllers\V1\Admin\Backup\BackupsController;
use Crater\Http\Controllers\V1\Admin\Backup\DownloadBackupController;
use Crater\Http\Controllers\V1\Admin\Company\CompaniesController;
use Crater\Http\Controllers\V1\Admin\Company\CompanyController as AdminCompanyController;
use Crater\Http\Controllers\V1\Admin\Customer\CustomersController;
use Crater\Http\Controllers\V1\Admin\Customer\CustomerStatsController;
use Crater\Http\Controllers\V1\Admin\Supplier\SuppliersController;
use Crater\Http\Controllers\V1\Admin\Supplier\SupplierStatsController;
use Crater\Http\Controllers\V1\Admin\Store\StoresController;
use Crater\Http\Controllers\V1\Admin\Store\StoreStatsController;
use Crater\Http\Controllers\V1\Admin\CustomField\CustomFieldsController;
use Crater\Http\Controllers\V1\Admin\Dashboard\DashboardController;
use Crater\Http\Controllers\V1\Admin\Estimate\ChangeEstimateStatusController;
use Crater\Http\Controllers\V1\Admin\Estimate\ConvertEstimateController;
use Crater\Http\Controllers\V1\Admin\Estimate\EstimatesController;
use Crater\Http\Controllers\V1\Admin\Estimate\EstimateTemplatesController;
use Crater\Http\Controllers\V1\Admin\Estimate\SendEstimateController;
use Crater\Http\Controllers\V1\Admin\Estimate\SendEstimatePreviewController;
use Crater\Http\Controllers\V1\Admin\Estimate\ReviseEstimateController;
use Crater\Http\Controllers\V1\Admin\ExchangeRate\ExchangeRateProviderController;
use Crater\Http\Controllers\V1\Admin\ExchangeRate\GetActiveProviderController;
use Crater\Http\Controllers\V1\Admin\ExchangeRate\GetExchangeRateController;
use Crater\Http\Controllers\V1\Admin\ExchangeRate\GetSupportedCurrenciesController;
use Crater\Http\Controllers\V1\Admin\ExchangeRate\GetUsedCurrenciesController;
use Crater\Http\Controllers\V1\Admin\Expense\ExpenseCategoriesController;
use Crater\Http\Controllers\V1\Admin\Category\CategoriesController;
use Crater\Http\Controllers\V1\Admin\Expense\ExpensesController;
use Crater\Http\Controllers\V1\Admin\Expense\ShowReceiptController;
use Crater\Http\Controllers\V1\Admin\Expense\UploadReceiptController;
use Crater\Http\Controllers\V1\Admin\General\BootstrapController;
use Crater\Http\Controllers\V1\Admin\General\LicenceController;
use Crater\Http\Controllers\V1\Admin\General\BulkExchangeRateController;
use Crater\Http\Controllers\V1\Admin\General\ConfigController;
use Crater\Http\Controllers\V1\Admin\General\CountriesController;
use Crater\Http\Controllers\V1\Admin\General\CurrenciesController;
use Crater\Http\Controllers\V1\Admin\General\DateFormatsController;
use Crater\Http\Controllers\V1\Admin\General\GetAllUsedCurrenciesController;
use Crater\Http\Controllers\V1\Admin\General\NextNumberController;
use Crater\Http\Controllers\V1\Admin\General\NextNumberPurchaseController;
use Crater\Http\Controllers\V1\Admin\General\NotesController;
use Crater\Http\Controllers\V1\Admin\General\NumberPlaceholdersController;
use Crater\Http\Controllers\V1\Admin\General\SearchController;
use Crater\Http\Controllers\V1\Admin\General\SearchUsersController;
use Crater\Http\Controllers\V1\Admin\General\TimezonesController;
use Crater\Http\Controllers\V1\Admin\Invoice\ChangeInvoiceStatusController;
use Crater\Http\Controllers\V1\Admin\Invoice\CloneInvoiceController;
use Crater\Http\Controllers\V1\Admin\Invoice\InvoicesController;
use Crater\Http\Controllers\V1\Admin\CreditNote\CreditNotesController;
use Crater\Http\Controllers\V1\Admin\CreditNote\CreditNotesTemplatesController;
use Crater\Http\Controllers\V1\Admin\CreditNote\ChangeCreditNoteStatusController;
use Crater\Http\Controllers\V1\Admin\PerformaInvoice\PerformaInvoicesController;

// DEBIT NOTE
use Crater\Http\Controllers\V1\Admin\DebitNote\DebitNotesController;
use Crater\Http\Controllers\V1\Admin\DebitNote\DebitNotesTemplatesController;
use Crater\Http\Controllers\V1\Admin\DebitNote\ChangeDebitNoteStatusController;

//purchase...

use Crater\Http\Controllers\V1\Admin\Purchase\SendPurchasePreviewController;
use Crater\Http\Controllers\V1\Admin\Purchase\SendPurchaseController;
use Crater\Http\Controllers\V1\Admin\Purchase\ClonePurchaseController;
use Crater\Http\Controllers\V1\Admin\Purchase\ChangePurchaseStatusController;
use Crater\Http\Controllers\V1\Admin\Purchase\PurchasesController;
use Crater\Http\Controllers\V1\Admin\Purchase\PurchaseTemplatesController;

use Crater\Http\Controllers\V1\Admin\Invoice\InvoiceTemplatesController;
use Crater\Http\Controllers\V1\Admin\Invoice\SendInvoiceController;
use Crater\Http\Controllers\V1\Admin\Invoice\SendInvoicePreviewController;

use Crater\Http\Controllers\V1\Admin\Proforma\ChangeProformaStatusController;
use Crater\Http\Controllers\V1\Admin\Proforma\CloneProformaController;
use Crater\Http\Controllers\V1\Admin\Proforma\ProformasController;
use Crater\Http\Controllers\V1\Admin\Proforma\ProformaTemplatesController;
use Crater\Http\Controllers\V1\Admin\Proforma\SendProformaController;
use Crater\Http\Controllers\V1\Admin\Proforma\SendProformaPreviewController;

use Crater\Http\Controllers\V1\Admin\Item\ItemsController;
use Crater\Http\Controllers\V1\Admin\PreparedBy\PreparedByController;
use Crater\Http\Controllers\V1\Admin\BankDetail\BankDetailController;
use Crater\Http\Controllers\V1\Admin\Item\UnitsController;
use Crater\Http\Controllers\V1\Admin\Mobile\AuthController;
use Crater\Http\Controllers\V1\Admin\Payment\PaymentMethodsController;
use Crater\Http\Controllers\V1\Admin\Payment\PaymentsController;
use Crater\Http\Controllers\V1\Admin\Payment\SendPaymentController;
use Crater\Http\Controllers\V1\Admin\Payment\SendPaymentPreviewController;
use Crater\Http\Controllers\V1\Admin\RecurringInvoice\RecurringInvoiceController;
use Crater\Http\Controllers\V1\Admin\RecurringInvoice\RecurringInvoiceFrequencyController;
use Crater\Http\Controllers\V1\Admin\Role\AbilitiesController;
use Crater\Http\Controllers\V1\Admin\Role\RolesController;
use Crater\Http\Controllers\V1\Admin\Settings\CompanyController;
use Crater\Http\Controllers\V1\Admin\Settings\DiskController;
use Crater\Http\Controllers\V1\Admin\Settings\GetCompanyMailConfigurationController;
use Crater\Http\Controllers\V1\Admin\Settings\GetCompanySettingsController;
use Crater\Http\Controllers\V1\Admin\Settings\GetUserSettingsController;
use Crater\Http\Controllers\V1\Admin\Settings\MailConfigurationController;
use Crater\Http\Controllers\V1\Admin\Settings\TaxTypesController;
use Crater\Http\Controllers\V1\Admin\Settings\UpdateCompanySettingsController;
use Crater\Http\Controllers\V1\Admin\Settings\UpdateUserSettingsController;
use Crater\Http\Controllers\V1\Admin\Update\CheckVersionController;
use Crater\Http\Controllers\V1\Admin\Update\CheckUpdateDownloadController;
use Crater\Http\Controllers\V1\Admin\Update\CopyFilesController;
use Crater\Http\Controllers\V1\Admin\Update\DeleteFilesController;
use Crater\Http\Controllers\V1\Admin\Update\DownloadUpdateController;
use Crater\Http\Controllers\V1\Admin\Update\FinishUpdateController;
use Crater\Http\Controllers\V1\Admin\Update\MigrateUpdateController;
use Crater\Http\Controllers\V1\Admin\Update\UnzipUpdateController;
use Crater\Http\Controllers\V1\Admin\Users\UsersController;
use Crater\Http\Controllers\V1\Installation\AppDomainController;
use Crater\Http\Controllers\V1\Installation\DatabaseConfigurationController;
use Crater\Http\Controllers\V1\Installation\FilePermissionsController;
use Crater\Http\Controllers\V1\Installation\FinishController;
use Crater\Http\Controllers\V1\Installation\LoginController;
use Crater\Http\Controllers\V1\Installation\OnboardingWizardController;
use Crater\Http\Controllers\V1\Installation\RequirementsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// ping
//----------------------------------

Route::get('ping', function () {
    return response()->json([
        'success' => 'crater-self-hosted',
    ]);
})->name('ping');


// Version 1 endpoints
// --------------------------------------
Route::prefix('/v1')->group(function () {


    // App version
    // ----------------------------------

    Route::get('/app/version', AppVersionController::class);

    Route::get('/update/download', CheckUpdateDownloadController::class);

    // Authentication & Password Reset
    //----------------------------------

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

        // Send reset password mail
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware("throttle:10,2");

        // handle reset password form process
        Route::post('reset/password', [ResetPasswordController::class, 'reset']);
    });


    // Countries
    //----------------------------------

    Route::get('/countries', CountriesController::class);


    // Onboarding
    //----------------------------------

    Route::middleware(['redirect-if-installed'])->prefix('installation')->group(function () {
        Route::get('/wizard-step', [OnboardingWizardController::class, 'getStep']);

        Route::post('/wizard-step', [OnboardingWizardController::class, 'updateStep']);

        Route::get('/requirements', [RequirementsController::class, 'requirements']);

        Route::get('/permissions', [FilePermissionsController::class, 'permissions']);

        Route::post('/database/config', [DatabaseConfigurationController::class, 'saveDatabaseEnvironment']);

        Route::get('/database/config', [DatabaseConfigurationController::class, 'getDatabaseEnvironment']);

        Route::put('/set-domain', AppDomainController::class);

        Route::post('/login', LoginController::class);

        Route::post('/finish', FinishController::class);
    });




    Route::middleware(['auth:sanctum', 'company'])->group(function () {
        Route::middleware(['bouncer'])->group(function () {

            // Bootstrap
            //----------------------------------

            Route::get('/bootstrap', BootstrapController::class);

            // Licence
            //----------------------------------
            Route::post('set_licence_client', [LicenceController::class, 'setLicenceClient']);
            Route::post('subscribe', [LicenceController::class, 'purchaseLicencePlan']);

            // Currencies
            //----------------------------------

            Route::prefix('/currencies')->group(function () {
                Route::get('/used', GetAllUsedCurrenciesController::class);

                Route::post('/bulk-update-exchange-rate', BulkExchangeRateController::class);
            });


            // Dashboard
            //----------------------------------

            Route::get('/dashboard', DashboardController::class);


            // Auth check
            //----------------------------------

            Route::get('/auth/check', [AuthController::class, 'check']);


            // Search users
            //----------------------------------

            Route::get('/search', SearchController::class);

            Route::get('/search/user', SearchUsersController::class);


            // MISC
            //----------------------------------

            Route::get('/config', ConfigController::class);

            Route::get('/currencies', CurrenciesController::class);

            Route::get('/timezones', TimezonesController::class);

            Route::get('/date/formats', DateFormatsController::class);

            Route::get('/next-number', NextNumberController::class);

            Route::get('/number-placeholders', NumberPlaceholdersController::class);

            Route::get('/current-company', AdminCompanyController::class);


            // Customers
            //----------------------------------

            Route::post('/customers/delete', [CustomersController::class, 'delete']);

            Route::get('customers/{customer}/stats', CustomerStatsController::class);

            Route::resource('customers', CustomersController::class);


            // Suppliers
            //--------------------------------------
            Route::post('/suppliers/delete', [SuppliersController::class, 'delete']);

            Route::get('suppliers/{supplier}/stats', SupplierStatsController::class);

            Route::resource('suppliers', SuppliersController::class);

            // Stores
            //--------------------------------------
            Route::post('/stores/code_availability', [StoresController::class, 'codeAvailability']);

            Route::post('/stores/delete', [StoresController::class, 'delete']);

            Route::get('stores/{store}/stats', StoreStatsController::class);

            Route::resource('stores', StoresController::class);


            // Items
            //----------------------------------

            Route::post('/items/delete', [ItemsController::class, 'delete']);

            Route::resource('items', ItemsController::class);

            Route::resource('units', UnitsController::class);


            // Prpared By
            //----------------------------------

            // Route::post('/prepared-by/delete', [PreparedByController::class, 'delete']);

            Route::resource('prepared-by', PreparedByController::class);

            // Bank Detail
            //----------------------------------

            // Route::post('/bank-detail/delete', [BankDetailController::class, 'delete']);

            Route::resource('bank-detail', BankDetailController::class);


            // Invoices
            //-------------------------------------------------

            Route::get('/invoices/{invoice}/send/preview', SendInvoicePreviewController::class);

            Route::post('/invoices/{invoice}/send', SendInvoiceController::class);

            Route::post('/invoices/{invoice}/clone', CloneInvoiceController::class);

            Route::post('/invoices/{invoice}/status', ChangeInvoiceStatusController::class);

            Route::post('/invoices/delete', [InvoicesController::class, 'delete']);

            Route::get('/invoices/templates', InvoiceTemplatesController::class);

            Route::get('/invoices/sent_invoices_not_in_credit_note', [InvoicesController::class, 'sentInvoicesNotInCredits']);

            Route::apiResource('invoices', InvoicesController::class);

            // purchases
            //-------------------------------------------------

            Route::get('/purchases/{purchase}/send/preview', SendPurchasePreviewController::class);

            Route::post('/purchases/{purchase}/send', SendPurchaseController::class);

            Route::post('/purchases/{purchase}/clone', ClonePurchaseController::class);

            Route::post('/purchases/{purchase}/status', ChangePurchaseStatusController::class);

            Route::post('/purchases/delete', [PurchasesController::class, 'delete']);

            Route::get('/purchases/templates', PurchaseTemplatesController::class);

            Route::get('/purchases/for_debitnotes', [PurchasesController::class, 'forDebitNotes']);

            Route::apiResource('purchases', PurchasesController::class);


            // Proforma
            //-------------------------------------------------

            Route::get('/proformas/{proforma}/send/preview', SendProformaPreviewController::class);

            Route::post('/proformas/{proforma}/send', SendProformaController::class);

            Route::post('/proformas/{proforma}/clone', CloneProformaController::class);

            Route::post('/proformas/{proforma}/status', ChangeProformaStatusController::class);

            Route::post('/proformas/delete', [ProformasController::class, 'delete']);

            Route::get('/proformas/templates', ProformaTemplatesController::class);

            Route::apiResource('proformas', ProformasController::class);

            // CREDIT NOTE
            //-------------------------------------------------

            // Route::get('/invoices/{invoice}/send/preview', SendInvoicePreviewController::class);

            // Route::post('/invoices/{invoice}/send', SendInvoiceController::class);

            // Route::post('/invoices/{invoice}/clone', CloneInvoiceController::class);

            // Route::post('/invoices/{invoice}/status', ChangeInvoiceStatusController::class);

            Route::post('/credits/delete', [CreditNotesController::class, 'delete']);

            Route::get('/credits/templates', CreditNotesTemplatesController::class);

            Route::apiResource('credits', CreditNotesController::class);
            Route::post('/credits/{credit}/status', ChangeCreditNoteStatusController::class);


            // DEBIT NOTE
            //--------------------------------------------

            Route::post('/debits/delete', [DebitNotesController::class, 'delete']);

            Route::get('/debits/templates', DebitNotesTemplatesController::class);

            Route::apiResource('debits', DebitNotesController::class);
            Route::post('/debits/{debit}/status', ChangeDebitNoteStatusController::class);


            // ITEM/CLIENT/SUPPLIER CATEGORIES
            //---------------------------------

            Route::prefix('item_client')->group(function () {
                Route::post('categories/code_availability', [CategoriesController::class, 'codeAvailability']);
                Route::apiResource('categories', CategoriesController::class);
            });


            // Recurring Invoice
            //-------------------------------------------------

            Route::get('/recurring-invoice-frequency', RecurringInvoiceFrequencyController::class);

            Route::post('/recurring-invoices/delete', [RecurringInvoiceController::class, 'delete']);

            Route::apiResource('recurring-invoices', RecurringInvoiceController::class);


            // Estimates
            //-------------------------------------------------

            Route::get('/estimates/{estimate}/send/preview', SendEstimatePreviewController::class);

            Route::post('/estimates/{estimate}/send', SendEstimateController::class);

            Route::post('/estimates/{estimate}/status', ChangeEstimateStatusController::class);

            Route::post('/estimates/{estimate}/convert-to-invoice', ConvertEstimateController::class);

            Route::get('/estimates/templates', EstimateTemplatesController::class);
             Route::post('/estimates/{estimate}/clone', ReviseEstimateController::class);

            Route::post('/estimates/delete', [EstimatesController::class, 'delete']);

            Route::apiResource('estimates', EstimatesController::class);


            // Expenses
            //----------------------------------

            Route::get('/expenses/{expense}/show/receipt', ShowReceiptController::class);

            Route::post('/expenses/{expense}/upload/receipts', UploadReceiptController::class);

            Route::post('/expenses/delete', [ExpensesController::class, 'delete']);

            Route::apiResource('expenses', ExpensesController::class);

            Route::apiResource('categories', ExpenseCategoriesController::class);


            // Payments
            //----------------------------------

            Route::get('/payments/{payment}/send/preview', SendPaymentPreviewController::class);

            Route::post('/payments/{payment}/send', SendPaymentController::class);

            Route::post('/payments/delete', [PaymentsController::class, 'delete']);

            Route::apiResource('payments', PaymentsController::class);

            Route::apiResource('payment-methods', PaymentMethodsController::class);


            // Custom fields
            //----------------------------------

            Route::resource('custom-fields', CustomFieldsController::class);


            // Backup & Disk
            //----------------------------------

            Route::apiResource('backups', BackupsController::class);

            Route::apiResource('/disks', DiskController::class);

            Route::get('download-backup', DownloadBackupController::class);

            Route::get('/disk/drivers', [DiskController::class, 'getDiskDrivers']);


            // Exchange Rate
            //----------------------------------

            Route::get('/currencies/{currency}/exchange-rate', GetExchangeRateController::class);

            Route::get('/currencies/{currency}/active-provider', GetActiveProviderController::class);

            Route::get('/used-currencies', GetUsedCurrenciesController::class);

            Route::get('/supported-currencies', GetSupportedCurrenciesController::class);

            Route::apiResource('exchange-rate-providers', ExchangeRateProviderController::class);


            // Settings
            //----------------------------------


            Route::get('/me', [CompanyController::class, 'getUser']);

            Route::put('/me', [CompanyController::class, 'updateProfile']);

            Route::get('/me/settings', GetUserSettingsController::class);

            Route::put('/me/settings', UpdateUserSettingsController::class);

            Route::post('/me/upload-avatar', [CompanyController::class, 'uploadAvatar']);


            Route::put('/company', [CompanyController::class, 'updateCompany']);

            Route::post('/company/upload-logo', [CompanyController::class, 'uploadCompanyLogo']);
            Route::post('/company/upload-letterhead', [CompanyController::class, 'uploadCompanyLetterHead']);

            Route::get('/company/settings', GetCompanySettingsController::class);

            Route::post('/company/settings', UpdateCompanySettingsController::class);


            // Mails
            //----------------------------------

            Route::get('/mail/drivers', [MailConfigurationController::class, 'getMailDrivers']);

            Route::get('/mail/config', [MailConfigurationController::class, 'getMailEnvironment']);

            Route::post('/mail/config', [MailConfigurationController::class, 'saveMailEnvironment']);

            Route::post('/mail/test', [MailConfigurationController::class, 'testEmailConfig']);

            Route::get('/company/mail/config', GetCompanyMailConfigurationController::class);

            Route::apiResource('notes', NotesController::class);


            // Tax Types
            //----------------------------------

            Route::apiResource('tax-types', TaxTypesController::class);


            // Roles
            //----------------------------------

            Route::get('abilities', AbilitiesController::class);

            Route::apiResource('roles', RolesController::class);
        });


        // Self Update
        //----------------------------------

        Route::get('/check/update', CheckVersionController::class);
        //Route::get('/check/update/downlad', CheckUpdateDownload::class);

        Route::post('/update/download', DownloadUpdateController::class);

        Route::post('/update/unzip', UnzipUpdateController::class);

        Route::post('/update/copy', CopyFilesController::class);

        Route::post('/update/delete', DeleteFilesController::class);

        Route::post('/update/migrate', MigrateUpdateController::class);

        Route::post('/update/finish', FinishUpdateController::class);

        // Companies
        //-------------------------------------------------

        Route::post('companies', [CompaniesController::class, 'store']);

        Route::post('/transfer/ownership/{user}', [CompaniesController::class, 'transferOwnership']);

        Route::post('companies/delete', [CompaniesController::class, 'destroy']);

        Route::get('companies', [CompaniesController::class, 'getUserCompanies']);


        // Users
        //----------------------------------

        Route::post('/users/delete', [UsersController::class, 'delete']);

        Route::apiResource('/users', UsersController::class);
    });
});
