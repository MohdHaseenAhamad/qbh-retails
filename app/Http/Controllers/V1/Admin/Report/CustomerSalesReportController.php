<?php

namespace Crater\Http\Controllers\V1\Admin\Report;

use Carbon\Carbon;
use Crater\Http\Controllers\Controller;
use Crater\Models\Company;
use Crater\Models\CompanySetting;
use Crater\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDF;

class CustomerSalesReportController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string  $hash
    * @return \Illuminate\Http\JsonResponse
    */
    public function __invoke(Request $request, $hash)
    {
        $company = Company::with('address')->where('unique_hash', $hash)->first();

        $this->authorize('view report', $company);

        $locale = CompanySetting::getSetting('language',  $company->id);

        App::setLocale($locale);

        $start = $request->from_date;
        $end = $request->to_date;
        // $start = $request->from_date ?? '2020-01-01';
        // $end = $request->to_date ?? '2023-01-01';

        $start_date = Carbon::createFromFormat('Y-m-d', $start);
        $end_date = Carbon::createFromFormat('Y-m-d', $end);

        $queryy = Customer::query();
        $queryy->join('invoices', 'customers.id', '=', 'invoices.customer_id')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->selectRaw('sum(quantity) as sum_quantity,invoices.*,invoice_items.*, customers.*,  invoices.tax')
            ->where('invoices.company_id', $company->id)
            ->whereDate('invoices.invoice_date', '>=', $start_date->format('Y-m-d'))
            ->whereDate('invoices.invoice_date', '<=', $end_date->format('Y-m-d'));

        if($request->sortBy == 'DATE'){
            $queryy->orderBy('invoices.invoice_date', $request->sort_by_order);
        }else{
            $queryy->orderBy('invoices.invoice_number', $request->sort_by_order);
        }

        $customers = $queryy->groupBy('invoice_number')->get();
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $company->id);
        $from_date = Carbon::createFromFormat('Y-m-d', $start)->format($dateFormat);
        $to_date = Carbon::createFromFormat('Y-m-d', $end)->format($dateFormat);

        $data = [
                'customers' => $customers,
                'company' => $company,
                'from_date' => $from_date,
                'to_date' => $to_date,
            ];
        view()->share($data);

        $pdf = PDF::loadView('app.pdf.reports.sales-customers', $data, [], [
                      'title' => 'Purchase-Voucher',
                      'format' => 'A4-L',
                      'orientation' => 'L',
                      'filename'=> 'Vat sales Report_'.$data['company']['name'].'_'.$data['from_date'].'-'.$data['to_date']
                    ])->stream('Vat sales Report_'.$data['company']['name'].'_'.$data['from_date'].'-'.$data['to_date'].'.pdf');

        if ($request->has('preview')) {
            return view('app.pdf.reports.sales-customers');
        }

        if ($request->has('download')) {
            return $pdf->download('Vat sales Report_'.$data['company']['name'].'_'.$data['from_date'].'-'.$data['to_date'].'.pdf');
        }

        return $pdf->stream();
    }
}
