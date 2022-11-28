<?php

namespace Crater\Http\Controllers\V1\Admin\Purchase;

use Crater\Http\Controllers\Controller;
use Crater\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseTemplatesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // $this->authorize('viewAny', Invoice::class);

        $purchaseTemplates = Purchase::purchaseTemplates();

        return response()->json([
            'purchaseTemplates' => $purchaseTemplates,
        ]);
    }
}
