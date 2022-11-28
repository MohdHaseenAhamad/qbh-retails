<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Item;
use Illuminate\Http\Request;
use Auth;

class ItemFilteredPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // dd($request->all());
        $items =  Item::with('company')->whereCompanyId($request->selectedCompany)
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->applyFilters($request->all())
            ->select('items.*', 'units.name as unit_name')
            ->latest()
            ->get();
            // dd($items);
            if(count($items) > 0){
                return $items[0]->getGeneratedPDFOrStreamOfFilter($items, $request->all(), 'items');
            }else{
                return;
            }
    }
}
