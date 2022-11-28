<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Proforma;
use Illuminate\Http\Request;
use Auth;

class ProformaFilteredPdfController extends Controller
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
        $proformas = Proforma::whereCompanyId($request->selectedCompany)
            ->join('customers', 'customers.id', '=', 'proformas.customer_id')
            ->whereOrder('proformas.proforma_date','desc')
            ->applyFilters($request->all())
            ->select('proformas.*', 'customers.name')
            ->latest()
            ->get();
            // dd($proformas);
            if(count($proformas) > 0){
                return $proformas[0]->getGeneratedPDFOrStreamOfFilter($proformas, $request->all(), 'proforma');
            }else{
                return;
            }
    }
}
