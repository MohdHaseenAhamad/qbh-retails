<?php

namespace Crater\Http\Controllers\V1\Customer;

use Crater\Http\Controllers\Controller;
use Crater\Mail\InvoiceViewedMail;
use Crater\Models\CompanySetting;
use Crater\Models\Customer;
use Crater\Models\Invoice;
use Crater\Models\Credit;

class CreditPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Credit $credit)
    {
        if ($credit && ($credit->status == Credit::STATUS_SENT || $credit->status == Credit::STATUS_DRAFT)) {
            $credit->status = Credit::STATUS_VIEWED;
            $credit->viewed = true;
            $credit->save();
            $notifyCreditViewed = CompanySetting::getSetting(
                'notify_invoice_viewed',
                $invoice->company_id
            );

            if ($notifyCreditViewed == 'YES') {
                $data['invoice'] = Invoice::findOrFail($invoice->id)->toArray();
                $data['user'] = Customer::find($invoice->customer_id)->toArray();
                $notificationEmail = CompanySetting::getSetting(
                    'notification_email',
                    $invoice->company_id
                );

                \Mail::to($notificationEmail)->send(new InvoiceViewedMail($data));
            }
        }

        return $invoice->getGeneratedPDFOrStream('invoice');
    }
}
