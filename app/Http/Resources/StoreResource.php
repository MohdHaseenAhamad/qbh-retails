<?php

namespace Crater\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'country_id' => $this->country_id,
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'phone' => $this->phone,
            'contact_name' => $this->contact_name,
            'description' => $this->description,
            "state" => $this->state,
            "city" => $this->city,
            "address_street_1" => $this->address_street_1,
            "address_street_2" => $this->address_street_2,
            "phone" => $this->phone,
            "zip" => $this->zip,
            "state_ar" => $this->state_ar,
            "city_ar" => $this->city_ar,
            "address_street_1_ar" => $this->address_street_1_ar,
            "address_street_2_ar" => $this->address_street_2_ar,
            "phone_ar" => $this->phone_ar,
            "zip_ar" => $this->zip_ar,
            "created_at" => $this->formattedCreatedAt,
            'fields' => $this->when($this->fields()->exists(), function () {
                return CustomFieldValueResource::collection($this->fields);
            }),
        ];
    }
}
