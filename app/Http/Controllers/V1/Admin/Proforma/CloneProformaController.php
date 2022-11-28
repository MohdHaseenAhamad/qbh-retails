<?php

namespace Crater\Http\Controllers\V1\Admin\Proforma;

use Carbon\Carbon;
use Crater\Http\Controllers\Controller;
use Crater\Http\Resources\ProformaResource;
use Crater\Models\CompanySetting;
use Crater\Models\Proforma;
use Crater\Services\SerialNumberFormatter;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class CloneProformaController extends Controller
{
    /**
     * Mail a specific proforma to the corresponding customer's email address.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, Proforma $proforma)
    {
        // $this->authorize('create', Proforma::class);

        $date = Carbon::now();

        $serial = (new SerialNumberFormatter())
            ->setModel($proforma)
            ->setCompany($proforma->company_id, 'proforma')
            ->setCustomer($proforma->customer_id)
            ->setNextNumbers();

        $due_date = null;
        $dueDateEnabled = CompanySetting::getSetting(
            'proforma_set_due_date_automatically',
            $request->header('company')
        );
        if ($dueDateEnabled === 'YES') {
            $dueDateDays = CompanySetting::getSetting(
                'proforma_due_date_days',
                $request->header('company')
            );
            $due_date = Carbon::now()->addDays($dueDateDays)->format('Y-m-d');
        }

        $exchange_rate = $proforma->exchange_rate;
// dd($serial->getNextNumber());
        $newproforma = Proforma::create([
            'proforma_date' => $date->format('Y-m-d'),
            'due_date' => $due_date,
            'proforma_number' => $serial->getNextNumber()[0],
            'sequence_number' => $serial->nextSequenceNumber,
            'customer_sequence_number' => $serial->nextCustomerSequenceNumber,
            'reference_number' => $proforma->reference_number,
            'customer_id' => $proforma->customer_id,
            'prepared_by_id' => $proforma->prepared_by_id,
            'company_id' => $request->header('company'),
            'template_name' => $proforma->template_name,
            'status' => Proforma::STATUS_DRAFT,
            'paid_status' => Proforma::STATUS_UNPAID,
            'sub_total' => $proforma->sub_total,
            'discount' => $proforma->discount,
            'discount_type' => $proforma->discount_type,
            'discount_val' => $proforma->discount_val,
            'total' => $proforma->total,
            'due_amount' => $proforma->total,
            'tax_per_item' => $proforma->tax_per_item,
            'discount_per_item' => $proforma->discount_per_item,
            'tax' => $proforma->tax,
            'notes' => $proforma->notes,
            'exchange_rate' => $exchange_rate,
            'base_total' => $proforma->total * $exchange_rate,
            'base_discount_val' => $proforma->discount_val * $exchange_rate,
            'base_sub_total' => $proforma->sub_total * $exchange_rate,
            'base_tax' => $proforma->tax * $exchange_rate,
            'base_due_amount' => $proforma->total * $exchange_rate,
            'currency_id' => $proforma->currency_id,
        ]);

        $newproforma->unique_hash = Hashids::connection(Proforma::class)->encode($newproforma->id);
        $newproforma->save();
        $proforma->load('items.taxes');

        $proformaItems = $proforma->items->toArray();

        foreach ($proformaItems as $proformaItem) {
            $proformaItem['company_id'] = $request->header('company');
            $proformaItem['name'] = $proformaItem['name'];
            $proformaItem['exchange_rate'] = $exchange_rate;
            $proformaItem['base_price'] = $proformaItem['price'] * $exchange_rate;
            $proformaItem['base_discount_val'] = $proformaItem['discount_val'] * $exchange_rate;
            $proformaItem['base_tax'] = $proformaItem['tax'] * $exchange_rate;
            $proformaItem['base_total'] = $proformaItem['total'] * $exchange_rate;

            $item = $newproforma->items()->create($proformaItem);

            if (array_key_exists('taxes', $proformaItem) && $proformaItem['taxes']) {
                foreach ($proformaItem['taxes'] as $tax) {
                    $tax['company_id'] = $request->header('company');

                    if ($tax['amount']) {
                        $item->taxes()->create($tax);
                    }
                }
            }
        }

        if ($proforma->taxes) {
            foreach ($proforma->taxes->toArray() as $tax) {
                $tax['company_id'] = $request->header('company');
                $newproforma->taxes()->create($tax);
            }
        }

        if ($proforma->fields()->exists()) {
            $customFields = [];

            foreach ($proforma->fields as $data) {
                $customFields[] = [
                    'id' => $data->custom_field_id,
                    'value' => $data->defaultAnswer
                ];
            }

            $newproforma->addCustomFields($customFields);
        }

        return new ProformaResource($newproforma);
    }
}
