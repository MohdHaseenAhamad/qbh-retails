<?php

namespace Crater\Http\Controllers\V1\Admin\Report;

use Carbon\Carbon;
use Crater\Http\Controllers\Controller;
use Crater\Models\Company;
use Crater\Models\CompanySetting;
use Crater\Models\Supplier;
use Crater\Models\Purchase;
use Crater\Models\Debit;
use Crater\Models\Credit;
use Crater\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDF;

class VatReturnReportController extends Controller
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

        // $this->authorize('view report', $company);

        $locale = CompanySetting::getSetting('language',  $company->id);

        App::setLocale($locale);

        $start = $request->from_date;
        $end = $request->to_date;
        // $start = $request->from_date ?? '2020-01-01';
        // $end = $request->to_date ?? '2023-01-01';
        $start_date = Carbon::createFromFormat('Y-m-d', $start);
        $end_date = Carbon::createFromFormat('Y-m-d', $end)->addDays(1);

        $suppliers = Supplier::with(['purchases' => function ($query) use ($start_date, $end_date) {
            $query->where('purchase_date', '>=', $start_date->format('Y-m-d'));
            $query->where('purchase_date', '<=', $end_date->format('Y-m-d'));
        }])
            ->where('company_id', $company->id)
            ->applyPurchaseFilters([$start_date, $end_date])
            ->get();

        // INTITIALIZE TOTALS
        $total = [
            'sales' => 0,
            'sales_vat' => 0,
            'purchase' => 0,
            'purchase_vat' => 0,
            'credit' => 0,
            'credit_vat' => 0,
            'debit' => 0,
            'debit_vat' => 0
        ];
        // GET TOTAL SALES VALUES
        $sales = Invoice::select('sub_total','total','tax','discount_val')->where('tax', '>=', 0)->invoicesBetween($start_date, $end_date)->get();
        foreach($sales as $sale){
            $total['sales'] += $sale->sub_total;
            $total['sales_vat'] += $sale->tax;
        }

        // --------------------------------------------------------------------
        // GET TOTAL PURCHASE VALUES
        $purchases = Purchase::select('sub_total','total','tax','discount_val')->where('tax', '>=', 0)->purchasesBetween($start_date, $end_date)->get();
        foreach($purchases as $purchase){
            $total['purchase'] += $purchase->sub_total;
            $total['purchase_vat'] += $purchase->tax;
        }

        // GET TOTAL CREDIT NOTE VALUES
        $credits = Credit::select('sub_total','total','tax','discount_val')->where('tax', '>=', 0)->creditsBetween($start_date, $end_date)->get();
        foreach($credits as $credit){
            $total['credit'] += $credit->sub_total;
            $total['credit_vat'] += $credit->tax;
        }

        // GET TOTAL DEBIT NOTE VALUES
        $debits = Debit::select('sub_total','total','tax','discount_val')->where('tax', '>=', 0)->debitsBetween($start_date, $end_date)->get();
        foreach($debits as $debit){
            $total['debit'] += $debit->sub_total;
            $total['debit_vat'] += $debit->tax;
        }

        // REPORT FINAL OUTCOME
        $total['final_sales'] = $total['sales'] - $total['credit'];
        // $total['final_sales_vat'] = $total['sales_vat'] - $total['credit_vat'];
        $total['final_sales_vat'] = $total['final_sales'] * 15/100;

        $total['final_purchase'] = $total['purchase'] - $total['debit'];
        // $total['final_purchase_vat'] = $total['purchase_vat'] - $total['debit_vat'];
        $total['final_purchase_vat'] = $total['final_purchase'] * 15/100;

        $total['net_vat_due'] = $total['final_sales_vat'] - $total['final_purchase_vat'];
        // --------------------------------------------------------------------

        $dateFormat = CompanySetting::getSetting('carbon_date_format', $company->id);
        $from_date = Carbon::createFromFormat('Y-m-d', $start)->format($dateFormat);
        $to_date = Carbon::createFromFormat('Y-m-d', $end)->format($dateFormat);

        $data = [
            'suppliers' => $suppliers,
            'company' => $company,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'amount' => $total
        ];

        // view()->share($data);
        // $pdf = PDF::loadView('app.pdf.reports.vat_purchase');
        // return view('app.pdf.reports.vat_return', $data);
        $pdf = PDF::loadView('app.pdf.reports.vat_return', $data, [], [
                      'title' => 'Purchase-Voucher',
                      'format' => 'A4-L',
                      'orientation' => 'L'
                    ]);

        if ($request->has('preview')) {
            return view('app.pdf.reports.vat_return');
        }

        if ($request->has('download')) {
            return $pdf->download('Vat return Report_'.$data['company']['name'].'_'.$data['from_date'].'-'.$data['to_date'].'.pdf');
        }

        return $pdf->stream('Vat return Report_'.$data['company']['name'].'_'.$data['from_date'].'-'.$data['to_date'].'.pdf');
    }
}
