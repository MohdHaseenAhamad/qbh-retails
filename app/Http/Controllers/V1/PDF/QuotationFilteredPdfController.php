<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Estimate;
use Illuminate\Http\Request;
use Auth;

class QuotationFilteredPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $estimates = Estimate::whereCompanyId($request->selectedCompany)
            ->join('customers', 'customers.id', '=', 'estimates.customer_id')
            ->whereOrder('estimates.estimate_date','desc')
            ->applyFilters($request->all())
            ->select('estimates.*', 'customers.name')
            ->latest()
            ->get();
            if(count($estimates) > 0){
                return $estimates[0]->getGeneratedPDFOrStreamOfFilter($estimates, $request->all(), 'quotation');
            }else{
                return;
            }
    }
}
