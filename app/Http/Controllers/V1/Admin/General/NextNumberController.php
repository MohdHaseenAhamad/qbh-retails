<?php

namespace Crater\Http\Controllers\V1\Admin\General;

use Crater\Http\Controllers\Controller;
use Crater\Models\Estimate;
use Crater\Models\Invoice;
use Crater\Models\Payment;
use Crater\Models\Credit;
use Crater\Models\Debit;
use Crater\Models\Purchase;
use Crater\Models\Proforma;
use Crater\Services\SerialNumberFormatter;
use Illuminate\Http\Request;

class NextNumberController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Invoice $invoice, Estimate $estimate, Payment $payment, Credit $credit, Proforma $proforma, Purchase $purchase, Debit $debit)
    {
        // dd($request->all());
        $key = $request->key;
        $nextNumber = null;
        try {
            
            $serial = (new SerialNumberFormatter())
                        ->setModel(${$key})
                        ->setModelObject($request->model_id)
                        ->setCompany($request->header('company'), $key)
                        ->setCustomer($request->userId);

            $nextNumber = $serial->getNextNumber();
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }

        // return response()->json(['data'=>$nextNumber]);
        return response()->json([
            'success' => true,
            'nextNumber' => $serial->with_date ? $nextNumber[0] : $nextNumber,
            'invoiceDate' => $serial->with_date ? $nextNumber[1] : date('Y-m-d h:i:s'),
        ]);
    }
}
