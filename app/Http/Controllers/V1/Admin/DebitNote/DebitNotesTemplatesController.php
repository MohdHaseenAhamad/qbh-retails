<?php

namespace Crater\Http\Controllers\V1\Admin\DebitNote;

use Crater\Http\Controllers\Controller;
use Crater\Models\Debit;
use Illuminate\Http\Request;

class DebitNotesTemplatesController extends Controller
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

        $invoiceTemplates = Debit::debitNoteTemplates();

        return response()->json([
            'invoiceTemplates' => $invoiceTemplates,
        ]);
    }
}
