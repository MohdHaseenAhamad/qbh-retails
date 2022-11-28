<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\Proforma;

class DownloadProformaPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Proforma $proforma)
    {
        $path = storage_path('app/temp/proforma/'.$proforma->id.'.pdf');

        return response()->download($path);
    }
}
