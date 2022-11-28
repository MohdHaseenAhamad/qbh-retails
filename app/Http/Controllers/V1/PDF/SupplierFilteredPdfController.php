<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Supplier;
use Illuminate\Http\Request;
use Auth;

class SupplierFilteredPdfController extends Controller
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
        $suppliers = Supplier::with('creator')
            // ->whereCompany()
            ->applyFilters($request->all())
            ->groupBy('suppliers.id')
            ->latest()
            ->get();
            // dd($customers);
            if(count($suppliers) > 0){
                return $suppliers[0]->getGeneratedPDFOrStreamOfFilter($suppliers, $request->all(), 'supplier');
            }else{
                return;
            }
    }
}
