<?php

namespace Crater\Http\Controllers\V1\Customer;

use Crater\Http\Controllers\Controller;
use Crater\Mail\ProformaViewedMail;
use Crater\Models\CompanySetting;
use Crater\Models\Customer;
use Crater\Models\Proforma;

class ProformaPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Proforma $proforma)
    {
        if ($proforma && ($proforma->status == Proforma::STATUS_SENT || $proforma->status == proforma::STATUS_DRAFT)) {
            $proforma->status = Proforma::STATUS_VIEWED;
            $proforma->viewed = true;
            $proforma->save();
            $notifyproformaViewed = CompanySetting::getSetting(
                'notify_proforma_viewed',
                $proforma->company_id
            );

            if ($notifyproformaViewed == 'YES') {
                $data['proforma'] = Proforma::findOrFail($proforma->id)->toArray();
                $data['user'] = Customer::find($proforma->customer_id)->toArray();
                $notificationEmail = CompanySetting::getSetting(
                    'notification_email',
                    $proforma->company_id
                );

                \Mail::to($notificationEmail)->send(new ProformaViewedMail($data));
            }
        }

        return $proforma->getGeneratedPDFOrStream('proforma');
    }
}
