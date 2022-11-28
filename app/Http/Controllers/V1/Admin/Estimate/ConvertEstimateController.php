<?php

namespace Crater\Http\Controllers\V1\Admin\Estimate;

use Carbon\Carbon;
use Crater\Http\Controllers\Controller;
use Crater\Http\Resources\InvoiceResource;
use Crater\Models\CompanySetting;
use Crater\Models\Estimate;
use Crater\Models\Invoice;
use Crater\Models\CustomField;
use Crater\Models\CustomFieldValue;
use Crater\Services\SerialNumberFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class ConvertEstimateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Crater\Models\Estimate $estimate
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Estimate $estimate, Invoice $invoice)
    {
        
        $this->authorize('create', Invoice::class);

        $estimate->load(['items', 'items.taxes', 'customer', 'taxes']);

        $invoice_date = Carbon::now();
        $due_date = null;

        $dueDateEnabled = CompanySetting::getSetting(
            'invoice_set_due_date_automatically',
            $request->header('company')
        );

        if ($dueDateEnabled === 'YES') {
            $dueDateDays = CompanySetting::getSetting(
                'invoice_due_date_days',
                $request->header('company')
            );
            $due_date = Carbon::now()->addDays($dueDateDays)->format('Y-m-d');
        }

        $serial = (new SerialNumberFormatter())
            ->setModel($invoice)
            ->setCompany($estimate->company_id)
            ->setCustomer($estimate->customer_id);
        $serials = $serial->getNextNumber();

        $templateName = $estimate->getInvoiceTemplateName();

        $exchange_rate = $estimate->exchange_rate;
        
        $invoice = Invoice::create([
            'creator_id' => Auth::id(),
            'invoice_date' => $invoice_date->format('Y-m-d h:i:s'),
            'due_date' => $due_date,
            'invoice_number' => $serial->with_date ? $serials[0] : $serials,
            'sequence_number' => $serial->nextSequenceNumber,
            'customer_sequence_number' => $serial->nextCustomerSequenceNumber,
            'reference_number' => $serial->nextSequenceNumber,
            'customer_id' => $estimate->customer_id,
            'company_id' => $request->header('company'),
            'template_name' => $templateName,
            'status' => Invoice::STATUS_DRAFT,
            'paid_status' => Invoice::STATUS_UNPAID,
            'sub_total' => $estimate->sub_total,
            'discount' => $estimate->discount,
            'discount_type' => $estimate->discount_type,
            'discount_val' => $estimate->discount_val,
            'total' => $estimate->total,
            'due_amount' => $estimate->total,
            'tax_per_item' => $estimate->tax_per_item,
            'discount_per_item' => $estimate->discount_per_item,
            'tax' => $estimate->tax,
            'notes' => $estimate->notes,
            'exchange_rate' => $exchange_rate,
            'base_discount_val' => $estimate->discount_val * $exchange_rate,
            'base_sub_total' => $estimate->sub_total * $exchange_rate,
            'base_total' => $estimate->total * $exchange_rate,
            'base_tax' => $estimate->tax * $exchange_rate,
            'currency_id' => $estimate->currency_id,
            'prepared_by_id' => $estimate->prepared_by_id,
            'deduction_per_item' => $estimate->deduction_per_item,
            'upper_margin' => $estimate->upper_margin,
            'lower_margin' => $estimate->lower_margin,
            'comp_name' => $estimate->comp_name,
            'comp_name_ar' => $estimate->comp_name_ar,
            'comp_cr' => $estimate->comp_cr,
            'comp_cr_ar' => $estimate->comp_cr_ar,
            'comp_vat' => $estimate->comp_vat,
            'comp_vat_ar' => $estimate->comp_vat_ar,
            'comp_phone_ar' => $estimate->comp_phone_ar,
            'comp_state_ar' => $estimate->comp_state_ar,
            'comp_city_ar' => $estimate->comp_city_ar,
            'comp_zip_ar' => $estimate->comp_zip_ar,
            'comp_address_street_1_ar' => $estimate->comp_address_street_1_ar,
            'comp_address_street_2_ar' => $estimate->comp_address_street_2_ar,
            'comp_phone' => $estimate->comp_phone,
            'comp_state' => $estimate->comp_state,
            'comp_city' => $estimate->comp_city,
            'comp_zip' => $estimate->comp_zip,
            'comp_address_street_1' => $estimate->comp_address_street_1,
            'comp_address_street_2' => $estimate->comp_address_street_2,
            'comp_account_name_ar' => $estimate->comp_account_name_ar,
            'comp_bank_name_ar' => $estimate->comp_bank_name_ar,
            'comp_account_no_ar' => $estimate->comp_account_no_ar,
            'comp_iban_ar' => $estimate->comp_iban_ar,
            'comp_swift_code_ar' => $estimate->comp_swift_code_ar,
            'comp_account_name' => $estimate->comp_account_name,
            'comp_bank_name' => $estimate->comp_bank_name,
            'comp_account_no' => $estimate->comp_account_no,
            'comp_iban' => $estimate->comp_iban,
            'comp_swift_code' => $estimate->comp_swift_code,
            'cus_prefix' => $estimate->cus_prefix,
            'cus_prefix_ar' => $estimate->cus_prefix_ar,
            'cus_website_ar' => $estimate->cus_website_ar,
            'cus_website' => $estimate->cus_website,
            'cus_state_ar' => $estimate->cus_state_ar,
            'cus_city_ar' => $estimate->cus_city_ar,
            'cus_address_street_1_ar' => $estimate->cus_address_street_1_ar,
            'cus_address_street_2_ar' => $estimate->cus_address_street_2_ar,
            'cus_phone_ar' => $estimate->cus_phone_ar,
            'cus_zip_ar' => $estimate->cus_zip_ar,
            'cus_state' => $estimate->cus_state,
            'cus_city' => $estimate->cus_city,
            'cus_address_street_1' => $estimate->cus_address_street_1,
            'cus_address_street_2' => $estimate->cus_address_street_2,
            'cus_phone' => $estimate->cus_phone,
            'cus_zip' => $estimate->cus_zip,
            'bank_detail_id' => $estimate->bank_detail_id,
            'is_edit' => $estimate->is_edit,
            'cus_name' => $estimate->cus_name,
            'cus_name_ar' => $estimate->cus_name_ar,
            'from_quotation' => '1',
        ]);

        $invoice->unique_hash = Hashids::connection(Invoice::class)->encode($invoice->id);
        $invoice->save();
        $invoiceItems = $estimate->items->toArray();

        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItem['company_id'] = $request->header('company');
            $invoiceItem['name'] = $invoiceItem['name'];
            $estimateItem['exchange_rate'] = $exchange_rate;
            $estimateItem['base_price'] = $invoiceItem['price'] * $exchange_rate;
            $estimateItem['base_discount_val'] = $invoiceItem['discount_val'] * $exchange_rate;
            $estimateItem['base_tax'] = $invoiceItem['tax'] * $exchange_rate;
            $estimateItem['base_total'] = $invoiceItem['total'] * $exchange_rate;

            $item = $invoice->items()->create($invoiceItem);

            if (array_key_exists('taxes', $invoiceItem) && $invoiceItem['taxes']) {
                foreach ($invoiceItem['taxes'] as $tax) {
                    $tax['company_id'] = $request->header('company');
                    unset($tax['estimate_item_id']);
                    if ($tax['amount']) {
                        $item->taxes()->create($tax);
                    }
                }
            }
        }

        // dd($estimate);
        $estimatecustomFields = CustomField::with(['customFieldValues' => function ($query) use($estimate) {
            $query->where('custom_field_valuable_id', $estimate->id);
            }])->where([['model_type', 'Estimate'], ['company_id', $estimate->company_id]])->get();
        // dd($estimatecustomFields);
        $invoicecustomFields = CustomField::whereIn('name', $estimatecustomFields->pluck('name'))->where([['model_type', 'Invoice'], ['company_id', $estimate->company_id]])->get();
        // dd($invoicecustomFields);
        foreach($invoicecustomFields as $invoicecustomField){
            $estimate_field = $estimatecustomFields->where('name', $invoicecustomField->name)->pluck('customFieldValues');
                // $estimatefield = $estimatecustomFields->where('name', $invoicecustomField->name)->first();
            $invoice_field = $estimate_field->toArray()[0][0];
            // dd($invoice_field[0][0]);
            $invoice_field['custom_field_valuable_type'] = 'Crater\Models\Invoice';
            $invoice_field['custom_field_valuable_id'] = $invoice->id;
            $invoice_field['custom_field_id'] = $invoicecustomField->id;

// dd($invoice_field);
            CustomFieldValue::create($invoice_field);
        }
        if ($estimate->taxes) {
            foreach ($estimate->taxes->toArray() as $tax) {
                $tax['company_id'] = $request->header('company');
                $tax['exchange_rate'] = $exchange_rate;
                $tax['base_amount'] = $tax['amount'] * $exchange_rate;
                $tax['currency_id'] = $estimate->currency_id;
                unset($tax['estimate_id']);

                $invoice->taxes()->create($tax);
            }
        }

        $estimate->checkForEstimateConvertAction();

        $invoice = Invoice::find($invoice->id);

        return new InvoiceResource($invoice);
    }
}
