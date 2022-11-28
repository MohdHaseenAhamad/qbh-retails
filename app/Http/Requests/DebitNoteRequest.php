<?php

namespace Crater\Http\Requests;

use Crater\Models\CompanySetting;
use Crater\Models\Supplier;
use Crater\Models\Debit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DebitNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.s
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'debit_date' => [
                'required',
            ],
            'customer_id' => [
                'required',
            ],
            'purchase_id' => [
                'required',
            ],
            'debit_number' => [
                'required',
                Rule::unique('debits')->where('company_id', $this->header('company'))
            ],
            'exchange_rate' => [
                'nullable'
            ],
            'discount' => [
                'required',
            ],
            'discount_val' => [
                'required',
            ],
            'sub_total' => [
                'required',
            ],
            'total' => [
                'required',
            ],
            'tax' => [
                'required',
            ],
            'template_name' => [
                'required'
            ],
            'items' => [
                'required',
                'array',
            ],
            'items.*' => [
                'required',
                'max:255',
            ],
            'items.*.description' => [
                'nullable',
            ],
            'items.*.name' => [
                'required',
            ],
            'items.*.quantity' => [
                'required',
            ],
            'items.*.price' => [
                'required',
            ],
        ];

        $companyCurrency = CompanySetting::getSetting('currency', $this->header('company'));

        $supplier = Supplier::find($this->customer_id);

        if ($supplier && $companyCurrency) {
            if ((string)$supplier->currency_id !== $companyCurrency) {
                $rules['exchange_rate'] = [
                    'required',
                ];
            };
        }

        if ($this->isMethod('PUT')) {
            $rules['debit_number'] = [
                'required',
                Rule::unique('debits')
                    ->ignore($this->route('debit')->id)
                    ->where('company_id', $this->header('company')),
            ];
        }

        return $rules;
    }

    public function getDebitPayload()
    {
        $company_currency = CompanySetting::getSetting('currency', $this->header('company'));
        $current_currency = $this->currency_id;
        $exchange_rate = $company_currency != $current_currency ? $this->exchange_rate : 1;
        $currency = Supplier::find($this->customer_id)->currency_id;

        return collect($this->except('items', 'taxes', 'sequence_number'))
            ->merge([
                'creator_id' => $this->user()->id ?? null,
                'status' => $this->has('debitSend') ? Debit::STATUS_SENT : Debit::STATUS_DRAFT,
                'company_id' => $this->header('company'),
                'tax_per_item' => CompanySetting::getSetting('tax_per_item', $this->header('company')) ?? 'NO ',
                'discount_per_item' => CompanySetting::getSetting('discount_per_item', $this->header('company')) ?? 'NO',
                'exchange_rate' => $exchange_rate,
                'base_total' => $this->total * $exchange_rate,
                'base_discount_val' => $this->discount_val * $exchange_rate,
                'base_sub_total' => $this->sub_total * $exchange_rate,
                'base_tax' => $this->tax * $exchange_rate,
                'base_due_amount' => $this->total * $exchange_rate,
                'currency_id' => $currency,
                'supplier_id' => $this->customer_id,
                'notes' => json_encode(['heading'=>$this->notes_heading, 'description'=>$this->notes])
            ])
            ->toArray();
    }
}
