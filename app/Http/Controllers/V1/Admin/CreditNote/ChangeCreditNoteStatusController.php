<?php

namespace Crater\Http\Controllers\V1\Admin\CreditNote;

use Crater\Http\Controllers\Controller;
use Crater\Models\Credit;
use Illuminate\Http\Request;

class ChangeCreditNoteStatusController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function __invoke(Request $request, Credit $credit)
    {
        // $this->authorize('send credit', $credit);

        if ($request->status == Credit::STATUS_SENT) {
            $credit->status = Credit::STATUS_SENT;
            $credit->sent = true;
            $credit->is_edit_credit = '0';
            $credit->save();
        } elseif ($request->status == Credit::STATUS_COMPLETED) {
            $credit->is_edit_credit = '0';
            $credit->status = Credit::STATUS_COMPLETED;
            $credit->paid_status = Credit::STATUS_PAID;
            $credit->due_amount = 0;
            $credit->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
