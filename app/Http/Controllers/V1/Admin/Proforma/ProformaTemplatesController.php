<?php

namespace Crater\Http\Controllers\V1\Admin\Proforma;

use Crater\Http\Controllers\Controller;
use Crater\Models\Proforma;
use Illuminate\Http\Request;

class ProformaTemplatesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // $this->authorize('viewAny', Proforma::class);

        $proformaTemplates = Proforma::proformaTemplates();

        return response()->json([
            'proformaTemplates' => $proformaTemplates,
        ]);
    }
}
