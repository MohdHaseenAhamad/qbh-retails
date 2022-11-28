<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Purchase;
use Illuminate\Http\Request;
use Auth;

class PurchaseFilteredPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // dd($request->all());
        $purchases = Purchase::whereCompanyId($request->selectedCompany)
            ->join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->whereOrder('purchases.purchase_date','desc')
            ->applyFilters($request->all())
            ->select('purchases.*', 'suppliers.name')
            ->latest()
            ->get();
            // dd($purchases);
            if(count($purchases) > 0){
                return $purchases[0]->getGeneratedPDFOrStreamOfFilter($purchases, $request->all(), 'purchase');
            }else{
                return;
            }
    }
}
