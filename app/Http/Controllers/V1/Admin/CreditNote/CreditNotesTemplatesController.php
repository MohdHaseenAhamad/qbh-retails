<?php

namespace Crater\Http\Controllers\V1\Admin\CreditNote;

use Crater\Http\Controllers\Controller;
use Crater\Models\Credit;
use Illuminate\Http\Request;

class CreditNotesTemplatesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', Credit::class);

        $invoiceTemplates = Credit::creditNoteTemplates();

        return response()->json([
            'invoiceTemplates' => $invoiceTemplates,
        ]);
    }
}
