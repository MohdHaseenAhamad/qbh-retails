<?php

namespace Crater\Http\Controllers\V1\Admin\BankDetail;

use Crater\Http\Controllers\Controller;
use Crater\Models\BankDetail;
use Illuminate\Http\Request;
use Crater\Models\Company;
use Crater\Models\Invoice;
use Crater\Models\Estimate;
use Auth;
use Crater\Http\Resources\BankDetailResource;

class BankDetailController extends Controller
{
      /**
     * Retrieve a list of existing Items.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $company = Company::where('id', $request->header('company'))->first();
        if($company->account_no && $company->account_no != ''){

            $bank_exist = BankDetail::whereCompany()->where('account_no', $company->account_no)->first();
        }else{
            $bank_exist = true;
        }
        if(!$bank_exist)
        {
            $new_bank = BankDetail::create([
                "account_name" => $company->account_name,
                "account_name_ar" => $company->account_name_ar,
                "bank_name" => $company->bank_name,
                "bank_name_ar" => $company->bank_name_ar,
                "account_no" => $company->account_no,
                "account_no_ar" => $company->account_no_ar,
                "iban" => $company->iban,
                "iban_ar" => $company->iban_ar,
                "swift_code" => $company->swift_code,
                "swift_code_ar" => $company->swift_code_ar,
                "company_id" => $request->header('company'),
                "creator_id" => Auth::id()
            ]);
            $invoices_with_no_bank = Invoice::whereCompany()->whereNull('bank_detail_id')->get();
            if($invoices_with_no_bank && $new_bank){
                foreach($invoices_with_no_bank as $invoice){
                    // dd($invoice);
                    $invoice->update(['bank_detail_id' => $new_bank->id]);
                }
            }
            $quotations_with_no_bank = Estimate::whereCompany()->whereNull('bank_detail_id')->get();
            if($quotations_with_no_bank && $new_bank){
                foreach($quotations_with_no_bank as $quotation){
                    $quotation->update(['bank_detail_id' => $new_bank->id]);
                }
            }
        }
        // $this->authorize('viewAny', PreparedBy::class);

        $limit = $request->has('limit') ? $request->limit : 10;
        $items = BankDetail::whereCompany()
        ->applyFilters($request->all())
            ->latest()
            ->paginateData($limit);
        return (BankDetailResource::collection($items));
    }

    /**
     * Create PreparedBy User.
     *
     * @param  Crater\Http\Requests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // $this->authorize('create', PreparedBy::class);

        $item = BankDetail::createBankDetail($request);

        return new BankDetailResource($item);
    }

    /**
     * get an existing Item.
     *
     * @param  Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BankDetail $bank_detail)
    {
        // $this->authorize('view', $item);

        return new BankDetailResource($bank_detail);
    }

    /**
     * Update an existing PreparedBy User.
     *
     * @param  Crater\Http\Requests $request
     * @param  \Crater\Models\Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, BankDetail $bank_detail)
    {
        // $this->authorize('update', $bank_detail);

        $bank_detail = $bank_detail->updateBankDetail($request);

        return new BankDetailResource($bank_detail);
    }

    /**
     * Delete a list of existing PreparedBy User.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, BankDetail $bank_detail)
    {
        // $this->authorize('delete multiple items');

        $bank_exist_in_company = Company::where('id', $request->header('company'))->where('account_no', $bank_detail->account_no)->first();
        if($bank_exist_in_company){
            $bank_exist_in_company->update([
                "account_name_ar" => '',
                "bank_name_ar" => '',
                "account_no_ar" => '',
                "iban_ar" => '',
                "swift_code_ar" => '',
                "account_name" => '',
                "bank_name" => '',
                "account_no" => '',
                "iban" => '',
                "swift_code" => ''
            ]);
        }
        $bank_detail->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
