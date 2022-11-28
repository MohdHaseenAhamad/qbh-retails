<?php

namespace Crater\Http\Controllers\V1\Admin\Purchase;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests;
use Crater\Http\Requests\DeletePurchaseRequest;
use Crater\Http\Resources\PurchaseResource;
use Crater\Jobs\GeneratePurchasePdfJob;
use Crater\Models\Invoice;
use Crater\Models\Purchase;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Purchase::class);
        $limit = $request->has('limit') ? $request->limit : 10;
        $purchases = Purchase::whereCompany()
            ->join('suppliers', 'suppliers.id', 'purchases.supplier_id')
            ->whereOrder('purchases.purchase_date','desc')
            ->applyFilters($request->all())
            ->select('purchases.*', 'suppliers.name')
            ->latest()
            ->paginateData($limit);

        return (PurchaseResource::collection($purchases))
            ->additional(['meta' => [
                'purchase_total_count' => Purchase::whereCompany()->count(),
            ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\PurchasesRequest $request)
    {
        $this->authorize('create', Purchase::class);
        // dd($request->all());
        $purchase = Purchase::createPurchase($request);

        if ($request->has('purchaseSend')) {
            $purchase->send($request->subject, $request->body);
        }

        GeneratePurchasePdfJob::dispatch($purchase);

        return new PurchaseResource($purchase);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Purchase $purchase)
    {
        $this->authorize('view', $purchase);

        return new PurchaseResource($purchase);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\PurchasesRequest $request, Purchase $purchase)
    {
        $this->authorize('update', $purchase);
        // dd($request->all());
        $purchase = $purchase->updatePurchase($request);

        if (is_string($purchase)) {
            return respondJson($purchase, $purchase);
        }

        GeneratePurchasePdfJob::dispatch($purchase, true);

        return new PurchaseResource($purchase);
    }

    /**
     * delete the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeletePurchaseRequest $request)
    {
        $this->authorize('delete multiple purchases');

        Purchase::destroy($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }

    public function forDebitNotes(Request $request)
    {
        $this->authorize('viewAny', Purchase::class);

        $purchases = Purchase::whereCompany()
            ->leftJoin('debits', function($join) {
              $join->on('debits.purchase_id', '=', 'purchases.id');
            })
            ->whereNull('debits.purchase_id')
            ->whereOrder('purchases.purchase_date','desc')
            ->applyFilters($request->all())
            ->select('purchases.*')
            ->get();

        return (PurchaseResource::collection($purchases))
            ->additional(['meta' => [
                'purchase_total_count' => Purchase::whereCompany()->count(),
            ]]);
    }
}
