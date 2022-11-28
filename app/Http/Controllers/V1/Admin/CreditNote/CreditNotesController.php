<?php

namespace Crater\Http\Controllers\V1\Admin\CreditNote;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests;
use Crater\Http\Requests\DeleteCreditNoteRequest;
use Crater\Http\Resources\CreditResource;
use Crater\Jobs\GenerateCreditNotePdfJob;
use Crater\Models\Credit;
use Illuminate\Http\Request;

class CreditNotesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Credit::class);
        
        $limit = $request->has('limit') ? $request->limit : 10;

        $credits = Credit::whereCompany()
                            ->join('customers', 'customers.id', '=', 'credits.customer_id')
                            ->whereOrder('credits.credit_date','desc')
                            ->applyFilters($request->all())
                            ->select('credits.*', 'customers.name')
                            ->latest()
                            ->paginateData($limit);

        return (CreditResource::collection($credits))
                ->additional(['meta' => [
                    'credit_total_count' => Credit::whereCompany()->count(),
                ]]);
        return new CreditResource($credits);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\CreditNoteRequest $request)
    {
        $this->authorize('create', Credit::class);

        $creditNote = Credit::createCreditNote($request);

        if ($request->has('creditSend')) {
            $creditNote->send($request->subject, $request->body);
        }

        GenerateCreditNotePdfJob::dispatch($creditNote);

        return new CreditResource($creditNote);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\Credit $credit
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Credit $credit)
    {
        $this->authorize('view', $credit);

        return new CreditResource($credit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Credit $credit
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\CreditNoteRequest $request, Credit $credit)
    {
        $this->authorize('update', $credit);

        $credit = $credit->updateCreditNote($request);

        if (is_string($credit)) {
            return respondJson($credit, $credit);
        }

        GenerateCreditNotePdfJob::dispatch($credit, true);

        return new CreditResource($credit);
    }

    /**
     * delete the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteCreditNoteRequest $request)
    {
        $this->authorize('delete multiple credit notes');

        Credit::destroy($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }
}
