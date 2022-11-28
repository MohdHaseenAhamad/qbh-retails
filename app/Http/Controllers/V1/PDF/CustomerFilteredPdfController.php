<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Customer;
use Illuminate\Http\Request;
use Auth;

class CustomerFilteredPdfController extends Controller
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
        $customers = Customer::with('creator')
            // ->whereCompany()
            ->applyFilters($request->all())
            ->groupBy('customers.id')
            ->latest()
            ->get();
            // dd($customers);
            if(count($customers) > 0){
                return $customers[0]->getGeneratedPDFOrStreamOfFilter($customers, $request->all(), 'customer');
            }else{
                return;
            }
    }
}
