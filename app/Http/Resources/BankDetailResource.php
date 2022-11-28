<?php

namespace Crater\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankDetailResource extends JsonResource
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
            'account_name' => $this->account_name,
            'account_name_ar' => $this->account_name_ar,
            'bank_name' => $this->bank_name,
            'bank_name_ar' => $this->bank_name_ar,
            'account_no' => $this->account_no,
            'account_no_ar' => $this->account_no_ar,
            'iban' => $this->iban,
            'iban_ar' => $this->iban_ar,
            'swift_code' => $this->swift_code,
            'swift_code_ar' => $this->swift_code_ar,
            'company_id' => $this->company_id,
            'creator_id' => $this->creator_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
