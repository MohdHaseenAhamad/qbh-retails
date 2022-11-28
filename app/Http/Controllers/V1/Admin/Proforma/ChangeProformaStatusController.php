<?php

namespace Crater\Http\Controllers\V1\Admin\Proforma;

use Crater\Http\Controllers\Controller;
use Crater\Models\Proforma;
use Illuminate\Http\Request;

class ChangeProformaStatusController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function __invoke(Request $request, Proforma $proforma)
    {
        // $this->authorize('send proforma', $proforma);

        if ($request->status == Proforma::STATUS_SENT) {
            $proforma->status = Proforma::STATUS_SENT;
            $proforma->sent = true;
            $proforma->save();
        } elseif ($request->status == Proforma::STATUS_COMPLETED) {
            $proforma->status = Proforma::STATUS_COMPLETED;
            $proforma->paid_status = Proforma::STATUS_PAID;
            $proforma->due_amount = 0;
            $proforma->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
