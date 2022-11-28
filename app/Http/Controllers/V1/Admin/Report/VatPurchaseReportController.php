<?php

namespace Crater\Http\Controllers\V1\Admin\Report;

use Carbon\Carbon;
use Crater\Http\Controllers\Controller;
use Crater\Models\Company;
use Crater\Models\CompanySetting;
use Crater\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDF;

class VatPurchaseReportController extends Controller
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
        $queryy = Supplier::query();
        $queryy->join('purchases', 'suppliers.id', '=', 'purchases.supplier_id')
            ->join('purchase_items', 'purchases.id', '=', 'purchase_items.purchase_id')
            ->selectRaw('sum(quantity) as sum_quantity,purchases.*,purchase_items.*, suppliers.*, purchases.tax')
            ->where('purchases.company_id', $company->id)
            ->whereDate('purchases.purchase_date', '>=', $start_date->format('Y-m-d'))
            ->whereDate('purchases.purchase_date', '<=', $end_date->format('Y-m-d'));

            if($request->sortBy == 'DATE'){
                $queryy->orderBy('purchases.purchase_date', $request->sort_by_order);
            }else{
                $queryy->orderBy('purchases.purchase_no', $request->sort_by_order);
            }

           $suppliers = $queryy->groupBy('purchase_no')->get();

        $dateFormat = CompanySetting::getSetting('carbon_date_format', $company->id);
        $from_date = Carbon::createFromFormat('Y-m-d', $start)->format($dateFormat);
        $to_date = Carbon::createFromFormat('Y-m-d', $end)->format($dateFormat);

        $data = [
            'suppliers' => $suppliers,
            'company' => $company,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ];
        view()->share($data);
        // return view('app.pdf.reports.vat_purchase', $data);
        // $pdf = PDF::loadView('app.pdf.reports.vat_purchase');
        $pdf = PDF::loadView('app.pdf.reports.vat_purchase', $data, [], [
                      'title' => 'Purchase-Voucher',
                      'format' => 'A4-L',
                      'orientation' => 'L'
                    ]);

        if ($request->has('preview')) {
            return view('app.pdf.reports.vat_purchase');
        }

        if ($request->has('download')) {
            return $pdf->download('Vat purchase Report_'.$data['company']['name'].'_'.$data['from_date'].'-'.$data['to_date'].'.pdf');
        }

        return $pdf->stream('Vat purchase Report_'.$data['company']['name'].'_'.$data['from_date'].'-'.$data['to_date'].'.pdf');
    }
}
