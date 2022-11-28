<?php

namespace Crater\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DebitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $notes = json_decode($this->notes);
        $notes_description = $notes_heading = '';
        if($notes){
            $notes_description = $notes->description;
            $notes_heading = $notes->heading;
        }

        return [
            'id' => $this->id,
            'debit_date' => $this->debit_date,
            'debit_number' => $this->debit_number,
            'purchase' => $this->when($this->purchase()->exists(), function () {
                return new PurchaseResource($this->purchase);
            }),
            'reference_number' => $this->reference_number,
            'status' => $this->status,
            'tax_per_item' => $this->tax_per_item,
            'discount_per_item' => $this->discount_per_item,
            'notes' => $notes_description,
            'notes_heading' => $notes_heading,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'discount_val' => $this->discount_val,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'tax' => $this->tax,
            'sent' => $this->sent,
            'viewed' => $this->viewed,
            'unique_hash' => $this->unique_hash,
            'template_name' => $this->template_name,
            'customer_id' => $this->customer_id,
            'sequence_number' => $this->sequence_number,
            'exchange_rate' => $this->exchange_rate,
            'base_discount_val' => $this->base_discount_val,
            'base_sub_total' => $this->base_sub_total,
            'base_total' => $this->base_total,
            'base_tax' => $this->base_tax,
            'creator_id' => $this->creator_id,
            'currency_id' => $this->currency_id,
            'formatted_created_at' => $this->formattedCreatedAt,
            'debit_pdf_url' => $this->debitPdfUrl,
            'formatted_debit_date' => $this->formattedDebitDate,
            'allow_edit' => $this->allow_edit,
            'upper_margin' => $this->upper_margin,
            'lower_margin' => $this->lower_margin,
            'reason' => $this->reason,
            'company_details' => json_decode($this->company_details),
            'supplier_details' => json_decode($this->supplier_details),
            'is_edit' => $this->is_edit,
            'prepared_by_id' => $this->when($this->preparedby()->exists(), function () {
                return new PreparedByResource($this->preparedby);
            }),
            'items' => $this->when($this->items()->exists(), function () {
                return InvoiceItemResource::collection($this->items);
            }),
            'customer' => $this->when($this->customer()->exists(), function () {
                return new SupplierResource($this->customer);
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
