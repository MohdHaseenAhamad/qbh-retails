<?php

namespace Crater\Http\Controllers\V1\Admin\Store;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests;
use Crater\Http\Requests\DeleteStoresRequest;
use Crater\Http\Resources\StoreResource;
use Crater\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Store::class);

        $limit = $request->has('limit') ? $request->limit : 10;

        $stores = Store::with('creator')
            ->whereCompany()
            ->applyFilters($request->all())
            ->groupBy('stores.id')
            // ->leftJoin('invoices', 'suppliers.id', '=', 'invoices.customer_id')
            ->paginateData($limit);

        return (StoreResource::collection($stores))
            ->additional(['meta' => [
                'customer_total_count' => Store::whereCompany()->count(),
            ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\StoreRequest $request)
    {
        $this->authorize('create', Store::class);

        $store = Store::createStore($request);

        return new StoreResource($store);
    }

    /**
     * Display the specified resource.
     *
     * @param  Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Store $store)
    {
        $this->authorize('view', $store);

        return new StoreResource($store);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Crater\Models\Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\StoreRequest $request, Store $store)
    {
        $this->authorize('update', $store);

        $store = Store::updateStore($request, $store);

        if (is_string($store)) {
            return respondJson('you_cannot_edit_currency', 'Cannot change currency once transactions created');
        }

        return new StoreResource($store);
    }

    /**
     * Remove a list of Customers along side all their resources (ie. Estimates, Invoices, Payments and Addresses)
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteStoresRequest $request)
    {
        $this->authorize('delete multiple stores');

        Store::deleteStores($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }

    public function codeAvailability(Request $request){
        $exist = Store::whereCompany()->whereCode($request->code)->where('id', '!=', $request->id)->first();

        return response()->json([
            'status' => $exist ? false : true,
            'request' => $request->all(),
            'response' => $exist
        ]);
    }
}
