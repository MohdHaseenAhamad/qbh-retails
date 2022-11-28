<?php

namespace Crater\Http\Controllers\V1\Admin\Estimate;

use Carbon\Carbon;
use Crater\Http\Controllers\Controller;
use Crater\Http\Resources\EstimateResource;
use Crater\Models\CompanySetting;
use Crater\Models\Estimate;
use Crater\Services\SerialNumberFormatter;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use DB;

class ReviseEstimateController extends Controller
{
    /**
     * Mail a specific estimate to the corresponding customer's email address.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, Estimate $estimate)
    {
        $this->authorize('create', Estimate::class);

        $data['estimate_date'] = $estimate->estimate_date ?? '';
        $data['expiry_date'] = $estimate->expiry_date ?? '';
        $data['status'] = $estimate->status ?? '';
        $data['reference_number'] = $estimate->reference_number ?? '';
        $data['tax_per_item'] = $estimate->tax_per_item ?? '';
        $data['discount_per_item'] = $estimate->discount_per_item ?? '';
        $data['notes'] = $estimate->notes ?? '';
        $data['discount'] = $estimate->discount ?? '';
        $data['discount_type'] = $estimate->discount_type ?? '';
        $data['discount_val'] = $estimate->discount_val ?? '';
        $data['sub_total'] = $estimate->sub_total ?? '';
        $data['total'] = $estimate->total ?? '';
        $data['tax'] = $estimate->tax ?? '';
        $data['company_id'] = $estimate->company_id ?? '';
        $data['creator_id'] = $estimate->creator_id ?? '';
        $data['template_name'] = $estimate->template_name ?? '';
        $data['exchange_rate'] = $estimate->exchange_rate ?? '';
        $data['base_discount_val'] = $estimate->base_discount_val ?? '';
        $data['base_sub_total'] = $estimate->base_sub_total ?? '';
        $data['base_total'] = $estimate->base_total ?? '';
        $data['base_tax'] = $estimate->base_tax ?? '';
        $data['currency_id'] = $estimate->currency_id ?? '';
        $data['prepared_by_id'] = $estimate->prepared_by_id ?? '';
        $data['deduction_per_item'] = $estimate->deduction_per_item ?? '';
        $data['upper_margin'] = $estimate->upper_margin ?? '';
        $data['lower_margin'] = $estimate->lower_margin ?? '';
        $data['subject'] = $estimate->subject ?? '';
        $data['comp_name'] = $estimate->comp_name ?? '';
        $data['comp_name_ar'] = $estimate->comp_name_ar ?? '';
        $data['comp_cr'] = $estimate->comp_cr ?? '';
        $data['comp_cr_ar'] = $estimate->comp_cr_ar ?? '';
        $data['comp_vat'] = $estimate->comp_vat ?? '';
        $data['comp_vat_ar'] = $estimate->comp_vat_ar ?? '';
        $data['comp_phone_ar'] = $estimate->comp_phone_ar ?? '';
        $data['comp_state_ar'] = $estimate->comp_state_ar ?? '';
        $data['comp_city_ar'] = $estimate->comp_city_ar ?? '';
        $data['comp_zip_ar'] = $estimate->comp_zip_ar ?? '';
        $data['comp_address_street_1_ar'] = $estimate->comp_address_street_1_ar ?? '';
        $data['comp_address_street_2_ar'] = $estimate->comp_address_street_2_ar ?? '';
        $data['comp_phone'] = $estimate->comp_phone ?? '';
        $data['comp_state'] = $estimate->comp_state ?? '';
        $data['comp_city'] = $estimate->comp_city ?? '';
        $data['comp_zip'] = $estimate->comp_zip ?? '';
        $data['comp_address_street_1'] = $estimate->comp_address_street_1 ?? '';
        $data['comp_address_street_2'] = $estimate->comp_address_street_2 ?? '';
        $data['cus_prefix'] = $estimate->cus_prefix ?? '';
        $data['cus_prefix_ar'] = $estimate->cus_prefix_ar ?? '';
        $data['cus_website'] = $estimate->cus_website ?? '';
        $data['cus_website_ar'] = $estimate->cus_website_ar ?? '';
        $data['cus_state_ar'] = $estimate->cus_state_ar ?? '';
        $data['cus_city_ar'] = $estimate->cus_city_ar ?? '';
        $data['cus_address_street_1_ar'] = $estimate->cus_address_street_1_ar ?? '';
        $data['cus_address_street_2_ar'] = $estimate->cus_address_street_2_ar ?? '';
        $data['cus_phone_ar'] = $estimate->cus_phone_ar ?? '';
        $data['cus_zip_ar'] = $estimate->cus_zip_ar ?? '';
        $data['cus_state'] = $estimate->cus_state ?? '';
        $data['cus_city'] = $estimate->cus_city ?? '';
        $data['cus_address_street_1'] = $estimate->cus_address_street_1 ?? '';
        $data['cus_address_street_2'] = $estimate->cus_address_street_2 ?? '';
        $data['cus_phone'] = $estimate->cus_phone ?? '';
        $data['cus_zip'] = $estimate->cus_zip ?? '';
        $data['is_edit'] = '1';
        $data['comp_account_name_ar'] = $estimate->comp_account_name_ar ?? '';
        $data['comp_bank_name_ar'] = $estimate->comp_bank_name_ar ?? '';
        $data['comp_account_no_ar'] = $estimate->comp_account_no_ar ?? '';
        $data['comp_iban_ar'] = $estimate->comp_iban_ar ?? '';
        $data['comp_swift_code_ar'] = $estimate->comp_swift_code_ar ?? '';
        $data['comp_account_name'] = $estimate->comp_account_name ?? '';
        $data['comp_bank_name'] = $estimate->comp_bank_name ?? '';
        $data['comp_account_no'] = $estimate->comp_account_no ?? '';
        $data['comp_iban'] = $estimate->comp_iban ?? '';
        $data['comp_swift_code'] = $estimate->comp_swift_code ?? '';
        $data['bank_detail_id'] = $estimate->bank_detail_id ?? '';
        $data['is_edit'] = $estimate->is_edit ?? '';
        $data['customer_id'] = $estimate->customer_id ?? '';
        $data['sequence_number'] = $estimate->sequence_number;
        $data['customer_sequence_number'] = $estimate->customer_sequence_number;
        $revise_estimate = Estimate::create($data);
        $revise_estimate->unique_hash = Hashids::connection(Estimate::class)->encode($revise_estimate->id);
        // $revise_estimate->serial = 'er'
        $revised_estimate = strpos($estimate->estimate_number, ' (R');
        if($revised_estimate !== false){
            $exploded_serial = explode(' (R', $estimate->estimate_number)[0];
            $index = $revised_estimate + strlen(' (R');
            $number = (int) substr($estimate->estimate_number, $index);
            if($number){
                $number++;
                $revise_estimate->estimate_number = $exploded_serial. ' (R'.$number.')';
            }

        }else{
            $revise_estimate->estimate_number = $estimate->estimate_number. ' (R1)';
        }
        $exchange_rate = $estimate->exchange_rate;

        $revise_estimate->save();
        $estimate->update(['is_edit' => '0']);
        $items = DB::table('estimate_items')->where('estimate_id', $estimate->id);
        if($items){
            $items->update(['estimate_id' => $revise_estimate->id]);
        }
        $taxes = DB::table('taxes')->where('estimate_id', $estimate->id);
        if($taxes){
            $taxes->update(['estimate_id' => $revise_estimate->id]);
        }        
        $custom_fields = DB::table('custom_field_values')->where('custom_field_valuable_id', $estimate->id);
        if($custom_fields){
            $custom_fields->update(['custom_field_valuable_id' => $revise_estimate->id]);
        }        

        return new EstimateResource($revise_estimate);
    }
}
