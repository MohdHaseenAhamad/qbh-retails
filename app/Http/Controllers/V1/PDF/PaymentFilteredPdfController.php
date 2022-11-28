<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Payment;
use Illuminate\Http\Request;
use Auth;

class PaymentFilteredPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $payments = Payment::whereCompanyId($request->selectedCompany)
           ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'payments.payment_method_id')
            ->whereOrder('payments.payment_date','desc')
            ->applyFilters($request->all())
            ->get();
            // dd($payments);
            if(count($payments) > 0){
                return $payments[0]->getGeneratedPDFOrStreamOfFilter($payments, $request->all(), 'payment');
            }else{
                return;
            }
    }
}
