<?php

namespace Crater\Http\Requests;

use Crater\Models\Address;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => [
                'required',
            ],
            'email' => [
                'email',
                'nullable',
                Rule::unique('stores')->where('company_id', $this->header('company'))
            ],
            'password' => [
                'nullable',
            ],
            'phone' => [
                'nullable',
            ],
            'description' => [
                'nullable',
            ],
            'company_name' => [
                'nullable',
            ],
            'contact_name' => [
                'nullable',
            ],
            'website' => [
                'nullable',
            ],
            'prefix' => [
                'nullable',
            ],
            'enable_portal' => [
                'nullable',
            ],
            'currency_id' => [
                'nullable',
            ],
            'address_street_1' => [
                'nullable',
            ],
            'address_street_2' => [
                'nullable',
            ],
            'city' => [
                'nullable',
            ],
            'state' => [
                'nullable',
            ],
            'country_id' => [
                'nullable',
            ],
            'zip' => [
                'nullable',
            ],
            'state_ar' => [
                'nullable',
            ],
            'city_ar' => [
                'nullable',
            ],
            'address_street_1_ar' => [
                'nullable',
            ],
            'address_street_2_ar' => [
                'nullable',
            ],
            'zip_ar' => [
                'nullable',
            ],
            'code' => [
                'required',
                Rule::unique('stores'),
            ]
        ];

        // if ($this->isMethod('PUT') && $this->email != null) {
        if ($this->isMethod('PUT')) {
            $rules['email'] = [
                'email',
                'nullable',
                Rule::unique('stores')->ignore($this->route('store')->id),
            ];
            $rules['code'] = [
                'required',
                Rule::unique('stores')->ignore($this->route('store')->id),
            ];
        };

        return $rules;
    }

    public function getStorePayload()
    {
        $collect = collect($this->validated())
            ->only([
                'name',
                'code',
                'contact_name',
                'email',
                'phone',
                'description',
                'state',
                'city',
                'address_street_1',
                'address_street_2',
                'phone',
                'zip',
                'state_ar',
                'city_ar',
                'address_street_1_ar',
                'address_street_2_ar',
                'zip_ar',
                'country_id',
            ])
            ->merge([
                'creator_id' => $this->user()->id,
                'company_id' => $this->header('company'),
            ]);
        
        // if($this->isMethod('PUT'))
        //     $collect->forget('code');

        return $collect->toArray();
    }
}
