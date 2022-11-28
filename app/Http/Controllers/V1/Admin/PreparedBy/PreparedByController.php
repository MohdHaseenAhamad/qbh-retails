<?php

namespace Crater\Http\Controllers\V1\Admin\PreparedBy;

use Crater\Http\Controllers\Controller;
use Crater\Models\PreparedBy;
use Illuminate\Http\Request;
use Crater\Http\Resources\PreparedByResource;

class PreparedByController extends Controller
{
      /**
     * Retrieve a list of existing Items.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', PreparedBy::class);

        $limit = $request->has('limit') ? $request->limit : 10;
        $prepared_by = PreparedBy::whereCompany()
        ->applyFilters($request->all())
            ->latest()
            ->paginateData($limit);

        return (PreparedByResource::collection($prepared_by));
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

        $prepared_by = PreparedBy::createPreparedBy($request);

        return new PreparedByResource($prepared_by);
    }

    /**
     * get an existing Item.
     *
     * @param  Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PreparedBy $prepared_by)
    {
        // $this->authorize('view', $item);

        return new PreparedByResource($prepared_by);
    }

    /**
     * Update an existing PreparedBy User.
     *
     * @param  Crater\Http\Requests $request
     * @param  \Crater\Models\Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, PreparedBy $prepared_by)
    {
        // dd($request->all());
        // $this->authorize('update', $item);

        $prepared_by = $prepared_by->updatePreparedBy($request);

        return new PreparedByResource($prepared_by);
    }

    /**
     * Delete a list of existing PreparedBy User.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PreparedBy $prepared_by)
    {
        // $this->authorize('delete multiple items');

        $prepared_by->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
