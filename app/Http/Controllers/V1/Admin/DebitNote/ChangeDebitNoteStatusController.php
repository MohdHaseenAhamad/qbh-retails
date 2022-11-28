<?php

namespace Crater\Http\Controllers\V1\Admin\DebitNote;

use Crater\Http\Controllers\Controller;
use Crater\Models\Debit;
use Illuminate\Http\Request;

class ChangeDebitNoteStatusController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function __invoke(Request $request, Debit $debit)
    {
        // $this->authorize('send debit', $debit);

        if ($request->status == Debit::STATUS_SENT) {
            $debit->status = Debit::STATUS_SENT;
            $debit->sent = true;
            $debit->is_edit = '0';
            $debit->save();
        } elseif ($request->status == Debit::STATUS_COMPLETED) {
            $debit->is_edit = '0';
            $debit->status = Debit::STATUS_COMPLETED;
            $debit->paid_status = Debit::STATUS_PAID;
            $debit->due_amount = 0;
            $debit->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
