<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Debit;
use Illuminate\Http\Request;
use Auth;

class DebitFilteredPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $debits = Debit::with('purchase')->whereCompanyId($request->selectedCompany)
            ->join('suppliers', 'suppliers.id', '=', 'debits.customer_id')
            ->whereOrder('debits.debit_date','desc')
            ->applyFilters($request->all())
            ->select('debits.*', 'suppliers.name')
            ->latest()
            ->get();
        // dd($debits);
        if(count($debits) > 0){
            return $debits[0]->getGeneratedPDFOrStreamOfFilter($debits, $request->all(), 'debit');
        }else{
            return;
        }
    }
}
