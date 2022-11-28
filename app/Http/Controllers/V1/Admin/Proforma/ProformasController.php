<?php

namespace Crater\Http\Controllers\V1\Admin\Proforma;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests;
use Crater\Http\Requests\DeleteProformaRequest;
use Crater\Http\Resources\ProformaResource;
use Crater\Jobs\GenerateProformaPdfJob;
use Crater\Models\Proforma;
use Illuminate\Http\Request;
// use Crater\Traits\ProformaTrait;

class ProformasController extends Controller
{
    // use ProformaTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Proforma::class);
        $limit = $request->has('limit') ? $request->limit : 10;
        $proformas = Proforma::whereCompany()
            ->join('customers', 'customers.id', '=', 'proformas.customer_id')
            ->whereOrder('proformas.proforma_date','desc')
            ->applyFilters($request->all())
            ->select('proformas.*', 'customers.name')
            ->latest()
            ->paginateData($limit);

        return (ProformaResource::collection($proformas))
            ->additional(['meta' => [
                'proforma_total_count' => Proforma::whereCompany()->count(),
            ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\ProformasRequest $request)
    {
        $this->authorize('create', Proforma::class);

        $proforma = Proforma::createProforma($request);

        if ($request->has('proformaSend')) {
            $proforma->send($request->subject, $request->body);
        }

        GenerateProformaPdfJob::dispatch($proforma);

        return new ProformaResource($proforma);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\Proforma $proforma
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Proforma $proforma)
    {
        $this->authorize('view', $proforma);

        return new ProformaResource($proforma);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Proforma $proforma
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\ProformasRequest $request, Proforma $proforma)
    {
        $this->authorize('update', $proforma);

        $proforma = $proforma->updateProforma($request);

        if (is_string($proforma)) {
            return respondJson($proforma, $proforma);
        }

        GenerateProformaPdfJob::dispatch($proforma, true);

        return new ProformaResource($proforma);
    }

    /**
     * delete the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteProformaRequest $request)
    {
        $this->authorize('delete multiple proformas');

        Proforma::destroy($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }
}
