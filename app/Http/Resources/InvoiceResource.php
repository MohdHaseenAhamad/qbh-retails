<?php

namespace Crater\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'invoice_number' => $this->invoice_number,
            'reference_number' => $this->reference_number,
            'status' => $this->status,
            'paid_status' => $this->paid_status,
            'tax_per_item' => $this->tax_per_item,
            'discount_per_item' => $this->discount_per_item,
            'notes' => $this->notes,
            'note_name' => $this->note_name,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'discount_val' => $this->discount_val,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'tax' => $this->tax,
            'due_amount' => $this->due_amount,
            'sent' => $this->sent,
            'viewed' => $this->viewed,
            'unique_hash' => $this->unique_hash,
            'template_name' => $this->template_name,
            'customer_id' => $this->customer_id,
            'recurring_invoice_id' => $this->recurring_invoice_id,
            'sequence_number' => $this->sequence_number,
            'exchange_rate' => $this->exchange_rate,
            'base_discount_val' => $this->base_discount_val,
            'base_sub_total' => $this->base_sub_total,
            'base_total' => $this->base_total,
            'creator_id' => $this->creator_id,
            'base_tax' => $this->base_tax,
            'base_due_amount' => $this->base_due_amount,
            'currency_id' => $this->currency_id,
            'formatted_created_at' => $this->formattedCreatedAt,
            'invoice_pdf_url' => $this->invoicePdfUrl,
            'formatted_invoice_date' => $this->formattedInvoiceDate,
            'formatted_due_date' => $this->formattedDueDate,
            'allow_edit' => $this->allow_edit,
            'upper_margin' => $this->upper_margin,
            'lower_margin' => $this->lower_margin,
            'comp_name' => $this->comp_name,
            'comp_name_ar' => $this->comp_name_ar,
            'comp_cr' => $this->comp_cr,
            'comp_cr_ar' => $this->comp_cr_ar,
            'comp_vat' => $this->comp_vat,
            'comp_vat_ar' => $this->comp_vat_ar,
            'comp_phone_ar' => $this->comp_phone_ar,
            'comp_state_ar' => $this->comp_state_ar,
            'comp_city_ar' => $this->comp_city_ar,
            'comp_zip_ar' => $this->comp_zip_ar,
            'comp_address_street_1_ar' => $this->comp_address_street_1_ar,
            'comp_address_street_2_ar' => $this->comp_address_street_2_ar,
            'comp_phone' => $this->comp_phone,
            'comp_state' => $this->comp_state,
            'comp_city' => $this->comp_city,
            'comp_zip' => $this->comp_zip,
            'comp_address_street_1' => $this->comp_address_street_1,
            'comp_address_street_2' => $this->comp_address_street_2,
            'comp_account_name_ar' => $this->comp_account_name_ar,
            'comp_bank_name_ar' => $this->comp_bank_name_ar,
            'comp_account_no_ar' => $this->comp_account_no_ar,
            'comp_iban_ar' => $this->comp_iban_ar,
            'comp_swift_code_ar' => $this->comp_swift_code_ar,
            'comp_account_name' => $this->comp_account_name,
            'comp_bank_name' => $this->comp_bank_name,
            'comp_account_no' => $this->comp_account_no,
            'comp_iban' => $this->comp_iban,
            'comp_swift_code' => $this->comp_swift_code,
            'cus_prefix' => $this->cus_prefix,
            'cus_prefix_ar' => $this->cus_prefix_ar,
            'cus_website' => $this->cus_website,
            'cus_website_ar' => $this->cus_website_ar,
            'cus_state_ar' => $this->cus_state_ar,
            'cus_city_ar' => $this->cus_city_ar,
            'cus_address_street_1_ar' => $this->cus_address_street_1_ar,
            'cus_address_street_2_ar' => $this->cus_address_street_2_ar,
            'cus_phone_ar' => $this->cus_phone_ar,
            'cus_zip_ar' => $this->cus_zip_ar,
            'cus_state' => $this->cus_state,
            'cus_city' => $this->cus_city,
            'cus_address_street_1' => $this->cus_address_street_1,
            'cus_address_street_2' => $this->cus_address_street_2,
            'cus_phone' => $this->cus_phone,
            'cus_zip' => $this->cus_zip,
            'is_edit' => $this->is_edit,
            'from_quotation' => $this->from_quotation,
            'prepared_by_id' => $this->when($this->preparedby()->exists(), function () {
                return new PreparedByResource($this->preparedby);
            }),
            'bank_detail_id' => $this->when($this->bankdetail()->exists(), function () {
                return new BankDetailResource($this->bankdetail);
            }),
            'items' => $this->when($this->items()->exists(), function () {
                return InvoiceItemResource::collection($this->items);
            }),
            'customer' => $this->when($this->customer()->exists(), function () {
                return new CustomerResource($this->customer);
            }),
            'creator' => $this->when($this->creator()->exists(), function () {
                return new UserResource($this->creator);
            }),
            'taxes' => $this->when($this->taxes()->exists(), function () {
                return TaxResource::collection($this->taxes);
            }),
            'fields' => $this->when($this->fields()->exists(), function () {
                return CustomFieldValueResource::collection($this->fields);
            }),
            'company' => $this->when($this->company()->exists(), function () {
                return new CompanyResource($this->company);
            }),
            'currency' => $this->when($this->currency()->exists(), function () {
                return new CurrencyResource($this->currency);
            }),
        ];
    }
}
