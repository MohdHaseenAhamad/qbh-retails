<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Invoice;
use Illuminate\Http\Request;
use Auth;

class InvoiceFilteredPdfController extends Controller
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
        $query = Invoice::query();
        $query->whereCompanyId($request->selectedCompany)
            ->join('customers', 'customers.id', '=', 'invoices.customer_id');
            if($request->sort_by == 'DATE'){
                $query->whereOrder('invoices.invoice_date', 'asc');
            }else{
                $query->whereOrder('invoices.invoice_number', 'asc');
            }
            $invoices = $query->applyFilters($request->all())
            ->select('invoices.*', 'customers.name')
            ->get();
            // dd($invoices);
            if(count($invoices) > 0){
                return $invoices[0]->getGeneratedPDFOrStreamOfFilter($invoices, $request->all(), 'invoice');
            }else{
                return;
            }
    }
}
