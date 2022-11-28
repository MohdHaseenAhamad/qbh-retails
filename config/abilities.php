<?php

use Crater\Models\Customer;
use Crater\Models\CustomField;
use Crater\Models\Estimate;
use Crater\Models\ExchangeRateProvider;
use Crater\Models\Expense;
use Crater\Models\Invoice;
use Crater\Models\Proforma;
use Crater\Models\Credit;
use Crater\Models\Debit;
use Crater\Models\Purchase;
use Crater\Models\Supplier;
use Crater\Models\Item;
use Crater\Models\Note;
use Crater\Models\Payment;
use Crater\Models\RecurringInvoice;
use Crater\Models\RecurringProforma;
use Crater\Models\TaxType;
use Crater\Models\Store;
use Crater\Models\Category;
use Crater\Models\Template;

return [
    'abilities' => [

        // Customer
        [
            "name" => "view customer",
            "ability" => "view-customer",
            "model" => Customer::class,
        ],
        [
            "name" => "create customer",
            "ability" => "create-customer",
            "model" => Customer::class,
            "depends_on" => [
                'view-customer',
                'view-custom-field',
            ]
        ],
        [
            "name" => "edit customer",
            "ability" => "edit-customer",
            "model" => Customer::class,
            "depends_on" => [
                'view-customer',
                'view-custom-field',
            ]
        ],
        [
            "name" => "delete customer",
            "ability" => "delete-customer",
            "model" => Customer::class,
            "depends_on" => [
                'view-customer',
            ]
        ],

        // Supplier
        [
            "name" => "view supplier",
            "ability" => "view-supplier",
            "model" => Supplier::class,
        ],
        [
            "name" => "create supplier",
            "ability" => "create-supplier",
            "model" => Supplier::class,
            "depends_on" => [
                'view-supplier',
                'view-custom-field',
            ]
        ],
        [
            "name" => "edit supplier",
            "ability" => "edit-supplier",
            "model" => Supplier::class,
            "depends_on" => [
                'view-supplier',
                'view-custom-field',
            ]
        ],
        [
            "name" => "delete supplier",
            "ability" => "delete-supplier",
            "model" => Supplier::class,
            "depends_on" => [
                'view-supplier',
            ]
        ],

        // Stores
        [
            "name" => "view store",
            "ability" => "view-store",
            "model" => Store::class,
        ],
        [
            "name" => "create store",
            "ability" => "create-store",
            "model" => Store::class,
            "depends_on" => [
                'view-store',
                'view-custom-field',
            ]
        ],
        [
            "name" => "edit store",
            "ability" => "edit-store",
            "model" => Store::class,
            "depends_on" => [
                'view-store',
                'view-custom-field',
            ]
        ],
        [
            "name" => "delete store",
            "ability" => "delete-store",
            "model" => Store::class,
            "depends_on" => [
                'view-store',
            ]
        ],

        // Item
        [
            "name" => "view item",
            "ability" => "view-item",
            "model" => Item::class,
        ],
        [
            "name" => "create item",
            "ability" => "create-item",
            "model" => Item::class,
            "depends_on" => [
                'view-item',
                'view-tax-type'
            ]
        ],
        [
            "name" => "edit item",
            "ability" => "edit-item",
            "model" => Item::class,
            "depends_on" => [
                'view-item',
            ]
        ],
        [
            "name" => "delete item",
            "ability" => "delete-item",
            "model" => Item::class,
            "depends_on" => [
                'view-item',
            ]
        ],

        // Tax Type
        [
            "name" => "view tax type",
            "ability" => "view-tax-type",
            "model" => TaxType::class,
        ],
        [
            "name" => "create tax type",
            "ability" => "create-tax-type",
            "model" => TaxType::class,
            "depends_on" => [
                'view-tax-type',
            ]
        ],
        [
            "name" => "edit tax type",
            "ability" => "edit-tax-type",
            "model" => TaxType::class,
            "depends_on" => [
                'view-tax-type',
            ]
        ],
        [
            "name" => "delete tax type",
            "ability" => "delete-tax-type",
            "model" => TaxType::class,
            "depends_on" => [
                'view-tax-type',
            ]
        ],

        // Estimate
        [
            "name" => "view quotation",
            "ability" => "view-estimate",
            "model" => Estimate::class,
        ],
        [
            "name" => "create quotation",
            "ability" => "create-estimate",
            "model" => Estimate::class,
            "depends_on" => [
                'view-estimate',
                'view-item',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "edit quotation",
            "ability" => "edit-estimate",
            "model" => Estimate::class,
            "depends_on" => [
                'view-item',
                'view-estimate',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "delete quotation",
            "ability" => "delete-estimate",
            "model" => Estimate::class,
            "depends_on" => [
                'view-estimate',
            ]
        ],
        [
            "name" => "send quotation",
            "ability" => "send-estimate",
            "model" => Estimate::class,
        ],

        // Invoice
        [
            "name" => "view invoice",
            "ability" => "view-invoice",
            "model" => Invoice::class,
        ],
        [
            "name" => "create invoice",
            "ability" => "create-invoice",
            "model" => Invoice::class,
            'owner_only' => false,
            "depends_on" => [
                'view-item',
                'view-invoice',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "edit invoice",
            "ability" => "edit-invoice",
            "model" => Invoice::class,
            "depends_on" => [
                'view-item',
                'view-invoice',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "delete invoice",
            "ability" => "delete-invoice",
            "model" => Invoice::class,
            "depends_on" => [
                'view-invoice'
            ]
        ],
        [
            "name" => "send invoice",
            "ability" => "send-invoice",
            "model" => Invoice::class,
        ],
        // Proforma
        [
            "name" => "view proforma",
            "ability" => "view-proforma",
            "model" => Proforma::class,
        ],
        [
            "name" => "create proforma",
            "ability" => "create-proforma",
            "model" => Proforma::class,
            'owner_only' => false,
            "depends_on" => [
                'view-item',
                'view-proforma',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "edit proforma",
            "ability" => "edit-proforma",
            "model" => Proforma::class,
            "depends_on" => [
                'view-item',
                'view-proforma',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "delete proforma",
            "ability" => "delete-proforma",
            "model" => Proforma::class,
            "depends_on" => [
                'view-proforma'
            ]
        ],
        [
            "name" => "send profmora",
            "ability" => "send-proforma",
            "model" => Proforma::class,
        ],

        // Credit Note
        [
            "name" => "view credit note",
            "ability" => "view-credit-note",
            "model" => Credit::class,
        ],
        [
            "name" => "create credit note",
            "ability" => "create-credit-note",
            "model" => Credit::class,
            'owner_only' => false,
            "depends_on" => [
                'view-item',
                'view-credit-note',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "edit credit note",
            "ability" => "edit-credit-note",
            "model" => Credit::class,
            "depends_on" => [
                'view-item',
                'view-credit-note',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "delete credit note",
            "ability" => "delete-credit-note",
            "model" => Credit::class,
            "depends_on" => [
                'view-credit-note'
            ]
        ],
        [
            "name" => "send credit note",
            "ability" => "send-credit-note",
            "model" => Credit::class,
        ],

        // Purchase
        [
            "name" => "view purchase",
            "ability" => "view-purchase",
            "model" => Purchase::class,
        ],
        [
            "name" => "create purchase",
            "ability" => "create-purchase",
            "model" => Purchase::class,
            'owner_only' => false,
            "depends_on" => [
                'view-item',
                'view-purchase',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "edit purchase",
            "ability" => "edit-purchase",
            "model" => Purchase::class,
            "depends_on" => [
                'view-item',
                'view-purchase',
                'view-tax-type',
                'view-customer',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "delete purchase",
            "ability" => "delete-purchase",
            "model" => Purchase::class,
            "depends_on" => [
                'view-purchase'
            ]
        ],
        [
            "name" => "send purchase",
            "ability" => "send-purchase",
            "model" => Purchase::class,
        ],

        // Debit Note
        [
            "name" => "view debit note",
            "ability" => "view-debit-note",
            "model" => Debit::class,
        ],
        [
            "name" => "create debit note",
            "ability" => "create-debit-note",
            "model" => Debit::class,
            'owner_only' => false,
            "depends_on" => [
                'view-item',
                'view-debit-note',
                'view-tax-type',
                'view-supplier',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "edit debit note",
            "ability" => "edit-debit-note",
            "model" => Debit::class,
            "depends_on" => [
                'view-item',
                'view-purchase',
                'view-tax-type',
                'view-supplier',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "delete debit note",
            "ability" => "delete-debit-note",
            "model" => Debit::class,
            "depends_on" => [
                'view-purchase'
            ]
        ],
        [
            "name" => "send debit note",
            "ability" => "send-debit-note",
            "model" => Debit::class,
        ],

        // Recurring Invoice
        [
            "name" => "view recurring invoice",
            "ability" => "view-recurring-invoice",
            "model" => RecurringInvoice::class,
        ],
        [
            "name" => "create recurring invoice",
            "ability" => "create-recurring-invoice",
            "model" => RecurringInvoice::class,
            "depends_on" => [
                'view-item',
                'view-recurring-invoice',
                'view-tax-type',
                'view-customer',
                'view-all-notes',
                'send-invoice'
            ]
        ],
        [
            "name" => "edit recurring invoice",
            "ability" => "edit-recurring-invoice",
            "model" => RecurringInvoice::class,
            "depends_on" => [
                'view-item',
                'view-recurring-invoice',
                'view-tax-type',
                'view-customer',
                'view-all-notes',
                'send-invoice'
            ]
        ],
        [
            "name" => "delete recurring invoice",
            "ability" => "delete-recurring-invoice",
            "model" => RecurringInvoice::class,
            "depends_on" => [
                'view-recurring-invoice',
            ]
        ],

        // Payment
        [
            "name" => "view payment",
            "ability" => "view-payment",
            "model" => Payment::class,
        ],
        [
            "name" => "create payment",
            "ability" => "create-payment",
            "model" => Payment::class,
            "depends_on" => [
                'view-customer',
                'view-payment',
                'view-invoice',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "edit payment",
            "ability" => "edit-payment",
            "model" => Payment::class,
            "depends_on" => [
                'view-customer',
                'view-payment',
                'view-invoice',
                'view-custom-field',
                'view-all-notes'
            ]
        ],
        [
            "name" => "delete payment",
            "ability" => "delete-payment",
            "model" => Payment::class,
            "depends_on" => [
                'view-payment',
            ]
        ],
        [
            "name" => "send payment",
            "ability" => "send-payment",
            "model" => Payment::class,
        ],

        // Expense
        [
            "name" => "view expense",
            "ability" => "view-expense",
            "model" => Expense::class,
        ],
        [
            "name" => "create expense",
            "ability" => "create-expense",
            "model" => Expense::class,
            "depends_on" => [
                'view-customer',
                'view-expense',
                'view-custom-field',
            ]
        ],
        [
            "name" => "edit expense",
            "ability" => "edit-expense",
            "model" => Expense::class,
            "depends_on" => [
                'view-customer',
                'view-expense',
                'view-custom-field',
            ]
        ],
        [
            "name" => "delete expense",
            "ability" => "delete-expense",
            "model" => Expense::class,
            "depends_on" => [
                'view-expense',
            ]
        ],

        // Custom Field
        [
            "name" => "view custom field",
            "ability" => "view-custom-field",
            "model" => CustomField::class,
        ],
        [
            "name" => "create custom field",
            "ability" => "create-custom-field",
            "model" => CustomField::class,
            "depends_on" => [
                'view-custom-field',
            ]
        ],
        [
            "name" => "edit custom field",
            "ability" => "edit-custom-field",
            "model" => CustomField::class,
            "depends_on" => [
                'view-custom-field',
            ]
        ],
        [
            "name" => "delete custom field",
            "ability" => "delete-custom-field",
            "model" => CustomField::class,
            "depends_on" => [
                'view-custom-field',
            ]
        ],

        // Financial Reports
        [
            "name" => "view financial reports",
            "ability" => "view-financial-reports",
            "model" => null,
        ],

        // Exchange Rate Provider
        [
            "name" => "view exchange rate provider",
            "ability" => "view-exchange-rate-provider",
            "model" => ExchangeRateProvider::class,
            'owner_only' => false,
        ],
        [
            "name" => "create exchange rate provider",
            "ability" => "create-exchange-rate-provider",
            "model" => ExchangeRateProvider::class,
            'owner_only' => false,
            "depends_on" => [
                'view-exchange-rate-provider',
            ]
        ],
        [
            "name" => "edit exchange rate provider",
            "ability" => "edit-exchange-rate-provider",
            "model" => ExchangeRateProvider::class,
            'owner_only' => false,
            "depends_on" => [
                'view-exchange-rate-provider',
            ]
        ],
        [
            "name" => "delete exchange rate provider",
            "ability" => "delete-exchange-rate-provider",
            "model" => ExchangeRateProvider::class,
            'owner_only' => false,
            "depends_on" => [
                'view-exchange-rate-provider',
            ]
        ],

        // Settings
        [
            "name" => "view company dashboard",
            "ability" => "dashboard",
            "model" => null,
        ],
        [
            "name" => "manage company",
            "ability" => "manage-company",
            "model" => null,
        ],
        [
            "name" => "manage bank details",
            "ability" => "manage-bank",
            "model" => null,
        ],
        [
            "name" => "manage preference",
            "ability" => "manage-preference",
            "model" => null,
        ],
        [
            "name" => "manage customization",
            "ability" => "manage-customization",
            "model" => null,
        ],
        [
            "name" => "view all notes",
            "ability" => "view-all-notes",
            "model" => Note::class,
        ],
        [
            "name" => "manage notes",
            "ability" => "manage-all-notes",
            "model" => Note::class,
            "depends_on" => [
                'view-all-notes'
            ]
        ],
        [
            "name" => "view all categories",
            "ability" => "view-category",
            "model" => Category::class,
        ],
        [
            "name" => "create categories",
            "ability" => "create-category",
            "model" => Category::class,
            "depends_on" => [
                'view-category'
            ]
        ],
        [
            "name" => "delete categories",
            "ability" => "delete-category",
            "model" => Category::class,
            "depends_on" => [
                'view-category'
            ]
        ],
        [
            "name" => "edit categories",
            "ability" => "edit-category",
            "model" => Category::class,
            "depends_on" => [
                'view-category'
            ]
        ],


        // INVOICE TEMPLATES
        [
            "name" => "invoice template 1",
            "ability" => "invoice1",
            "model" => Template::class
        ],
        [
            "name" => "invoice template 2",
            "ability" => "invoice2",
            "model" => Template::class
        ],
        [
            "name" => "invoice template 3",
            "ability" => "invoice3",
            "model" => Template::class
        ],
        [
            "name" => "invoice template 4",
            "ability" => "invoice4",
            "model" => Template::class
        ],
        [
            "name" => "invoice template 5",
            "ability" => "invoice5",
            "model" => Template::class
        ],
        // CREDIT NOTE TEMPLATES
        [
            "name" => "credit template 1",
            "ability" => "credit_note1",
            "model" => Template::class
        ],
        [
            "name" => "credit template 2",
            "ability" => "credit_note2",
            "model" => Template::class
        ],
        [
            "name" => "credit template 3",
            "ability" => "credit_note3",
            "model" => Template::class
        ],
        // DEBIT NOTE TEMPLATES
        [
            "name" => "debit template 1",
            "ability" => "debit_note1",
            "model" => Template::class
        ],
        [
            "name" => "debit template 2",
            "ability" => "debit_note2",
            "model" => Template::class
        ],
        [
            "name" => "debit template 3",
            "ability" => "debit_note3",
            "model" => Template::class
        ],
        // PROFORMA TEMPLATES
        [
            "name" => "proforma template 1",
            "ability" => "proforma1",
            "model" => Template::class
        ],
        [
            "name" => "proforma template 2",
            "ability" => "proforma2",
            "model" => Template::class
        ],
        [
            "name" => "proforma template 3",
            "ability" => "proforma3",
            "model" => Template::class
        ],
        // QUOTATION TEMPLATES
        [
            "name" => "quotation template 1",
            "ability" => "quotation1",
            "model" => Template::class
        ],
        [
            "name" => "quotation template 2",
            "ability" => "quotation2",
            "model" => Template::class
        ],
        [
            "name" => "quotation template 3",
            "ability" => "quotation3",
            "model" => Template::class
        ],
        [
            "name" => "quotation template 4",
            "ability" => "quotation4",
            "model" => Template::class
        ],
        [
            "name" => "quotation template 5",
            "ability" => "quotation5",
            "model" => Template::class
        ]
        
    ]
];
