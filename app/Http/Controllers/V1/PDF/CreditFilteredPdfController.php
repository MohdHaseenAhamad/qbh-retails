<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Credit;
use Illuminate\Http\Request;
use Auth;

class CreditFilteredPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $credits = Credit::with('invoice')->whereCompanyId($request->selectedCompany)
            ->join('customers', 'customers.id', '=', 'credits.customer_id')
            ->whereOrder('credits.credit_date','desc')
            ->applyFilters($request->all())
            ->select('credits.*', 'customers.name')
            ->latest()
            ->get();

        if(count($credits) > 0){
            return $credits[0]->getGeneratedPDFOrStreamOfFilter($credits, $request->all(), 'credit');
        }else{
            return;
        }
    }
}
