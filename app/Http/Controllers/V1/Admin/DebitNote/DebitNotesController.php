<?php

namespace Crater\Http\Controllers\V1\Admin\DebitNote;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests;
use Crater\Http\Requests\DeleteDebitNoteRequest;
use Crater\Http\Resources\DebitResource;
use Crater\Jobs\GenerateDebitNotePdfJob;
use Crater\Models\Debit;
use Illuminate\Http\Request;

class DebitNotesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Debit::class);
        
        $limit = $request->has('limit') ? $request->limit : 10;

        $debits = Debit::whereCompany()
                            ->join('suppliers', 'suppliers.id', '=', 'debits.customer_id')
                            ->whereOrder('debits.debit_date','desc')
                            ->applyFilters($request->all())
                            ->select('debits.*', 'suppliers.name')
                            ->latest()
                            ->paginateData($limit);

        return (DebitResource::collection($debits))
                ->additional(['meta' => [
                    'debit_total_count' => Debit::whereCompany()->count(),
                ]]);
        return new DebitResource($debits);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\DebitNoteRequest $request)
    {
        $this->authorize('create', Debit::class);

        $debitNote = Debit::createDebitNote($request);

        if ($request->has('debitSend')) {
            $debitNote->send($request->subject, $request->body);
        }

        GenerateDebitNotePdfJob::dispatch($debitNote);

        return new DebitResource($debitNote);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\Debit $debit
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Debit $debit)
    {
        $this->authorize('view', $debit);

        return new DebitResource($debit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Debit $debit
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\DebitNoteRequest $request, Debit $debit)
    {
        $this->authorize('update', $debit);

        $debit = $debit->updateDebitNote($request);

        if (is_string($debit)) {
            return respondJson($debit, $debit);
        }

        GenerateDebitNotePdfJob::dispatch($debit, true);

        return new DebitResource($debit);
    }

    /**
     * delete the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteDebitNoteRequest $request)
    {
        $this->authorize('delete multiple debit notes');

        Debit::destroy($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }
}
