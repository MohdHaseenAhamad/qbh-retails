<?php

namespace Crater\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'purchase_no' => $this->purchase_no,
            'purchase_date' => $this->purchase_date,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'order_no' => $this->order_no,
            'order_date' => $this->order_date,
            'material_receipt' => $this->material_receipt,
            'supply_date' => $this->supply_date,
            'reference_number' => $this->reference_number,
            'status' => $this->status,
            'paid_status' => $this->paid_status,
            'tax_per_item' => $this->tax_per_item,
            'discount_per_item' => $this->discount_per_item,
            'notes' => $this->notes,
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
            'supplier_id' => $this->customer_id,
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
            'purchase_pdf_url' => $this->invoicePdfUrl,
            'formatted_invoice_date' => $this->formattedInvoiceDate,
            'formatted_purchase_date' => $this->formattedPurchaseDate,
            'formatted_due_date' => $this->formattedDueDate,
            'allow_edit' => $this->allow_edit,
            'upper_margin' => $this->upper_margin,
            'company_details' => json_decode($this->company_details),
            'supplier_details' => json_decode($this->supplier_details),
            'prepared_by_id' => $this->when($this->preparedby()->exists(), function () {
                return new PreparedByResource($this->preparedby);
            }),
            'items' => $this->when($this->items()->exists(), function () {
                return PurchaseItemResource::collection($this->items);
            }),
            'supplier' => $this->when($this->supplier()->exists(), function () {
                return new SupplierResource($this->supplier);
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
