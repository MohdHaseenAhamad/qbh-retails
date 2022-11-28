<?php

namespace Crater\Http\Controllers\V1\Admin\Supplier;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests;
// use Crater\Http\Requests\DeleteCustomersRequest;
use Crater\Http\Requests\DeleteSuppliersRequest;
use Crater\Http\Resources\CustomerResource;
use Crater\Models\Supplier;
use Crater\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Supplier::class);

        $limit = $request->has('limit') ? $request->limit : 10;

        $suppliers = Supplier::with('creator')
            ->whereCompany()
            ->applyFilters($request->all())
            ->groupBy('suppliers.id')
            // ->leftJoin('invoices', 'suppliers.id', '=', 'invoices.customer_id')
            ->paginateData($limit);

        return (CustomerResource::collection($suppliers))
            ->additional(['meta' => [
                'customer_total_count' => Supplier::whereCompany()->count(),
            ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\SupplierRequest $request)
    {
        // $this->authorize('create', Supplier::class);

        $supplier = Supplier::createSupplier($request);

        return new CustomerResource($supplier);
    }

    /**
     * Display the specified resource.
     *
     * @param  Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Supplier $supplier)
    {
        // $this->authorize('view', $supplier);

        return new CustomerResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Crater\Models\Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\SupplierRequest $request, Supplier $supplier)
    {
        // dd($request->billing);
        // $this->authorize('update', $supplier);

        $supplier = Supplier::updateSupplier($request, $supplier);

        if (is_string($supplier)) {
            return respondJson('you_cannot_edit_currency', 'Cannot change currency once transactions created');
        }

        return new CustomerResource($supplier);
    }

    /**
     * Remove a list of Customers along side all their resources (ie. Estimates, Invoices, Payments and Addresses)
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteSuppliersRequest $request)
    {
        // $this->authorize('delete multiple suppliers');
        
        Supplier::deleteSuppliers($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }
}
