<?php

namespace Crater\Http\Controllers\V1\Admin\Category;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests\CategoryRequest;
use Crater\Http\Resources\CategoryResource;
use Crater\Models\Category;
use Illuminate\Http\Request;
use DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $limit = $request->has('limit') ? $request->limit : 5;
        // dd($request->all());

        if($request->has('name_with_code')){
            $categories = Category::select('*', DB::raw("CONCAT(categories.name,' (',categories.code,')') AS name"))->applyFilters($request->all())
            ->whereCompany()->get();
        }
        else{
            $categories = Category::applyFilters($request->all())
            ->whereCompany()->latest()->paginateData($limit);
        }

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        // dd($request->all());
        // dd($request->getCategoryPayload());
        $category = Category::create($request->getCategoryPayload());
        // dd($category);
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\ExpenseCategory $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        // dd($category);
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Crater\Models\ExpenseCategory $ExpenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $category->update($request->getCategoryPayload());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Crater\ExpensesCategory $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        if (($category->items() && $category->items()->count() > 0) && ($category->clients() && $category->clients()->count() > 0) && ($category->suppliers() && $category->suppliers()->count() > 0)) {
            return respondJson('expense_attached', 'Expense Attached');
        }

        $category->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function codeAvailability(Request $request){
        $exist = Category::whereCompany()->whereCode($request->code)->whereType($request->type)->where('id', '!=', $request->id)->first();

        return response()->json([
            'status' => $exist ? false : true,
            // 'request' => $request->all(),
            // 'response' => $exist
        ]);
    }
}
