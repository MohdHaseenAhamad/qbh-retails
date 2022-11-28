<?php

namespace Crater\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'logo' => $this->logo,
            'logo_path' => $this->logo_path,
            'letterhead' => $this->letterhead,
            'letterhead_path' => $this->letterhead_path,
            'unique_hash' => $this->unique_hash,
            'owner_id' => $this->owner_id,
            'cr' => $this->cr,
            'vat' => $this->vat,
            'name_ar' => $this->name_ar,
            'phone_ar' => $this->phone_ar,
            'state_ar' => $this->state_ar,
            'city_ar' => $this->city_ar,
            'zip_ar' => $this->zip_ar,
            'address_street_1_ar' => $this->address_street_1_ar,
            'address_street_2_ar' => $this->address_street_2_ar,
            'cr_ar' => $this->cr_ar,
            'vat_ar' => $this->vat_ar,
            'account_name_ar' => $this->account_name_ar,
            'bank_name_ar' => $this->bank_name_ar,
            'account_no_ar' => $this->account_no_ar,
            'iban_ar' => $this->iban_ar,
            'swift_code_ar' => $this->swift_code_ar,
            'account_name' => $this->account_name,
            'bank_name' => $this->bank_name,
            'account_no' => $this->account_no,
            'iban' => $this->iban,
            'swift_code' => $this->swift_code,
            'address' => $this->when($this->address()->exists(), function () {
                return new AddressResource($this->address);
            }),
            'roles' => RoleResource::collection($this->roles)
        ];
    }
}
