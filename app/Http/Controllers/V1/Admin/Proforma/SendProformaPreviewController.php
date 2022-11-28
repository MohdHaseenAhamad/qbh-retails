<?php

namespace Crater\Http\Controllers\V1\Admin\Proforma;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests\SendProformaRequest;
use Crater\Models\Proforma;
use Illuminate\Mail\Markdown;

class SendProformaPreviewController extends Controller
{
    /**
     * Mail a specific proforma to the corresponding customer's email address.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SendProformaRequest $request, Proforma $proforma)
    {
        // $this->authorize('send proforma', $proforma);

        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.send.proforma', ['data' => $proforma->sendProformaData($request->all())]);
    }
}
