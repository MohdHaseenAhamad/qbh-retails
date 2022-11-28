<?php

namespace Crater\Http\Controllers\V1\Admin\General;

use Crater\Http\Controllers\Controller;
use Crater\Models\Company;
use Crater\Models\User;
use Crater\Models\Licence;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade;
use Crater\Traits\Licence as LicenceTrait;
use Carbon\Carbon;

class LicenceController extends Controller
{

    use LicenceTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Invoice $invoice)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLicenceClient(Request $request)
    {
        // dd($request->all());

        $licence = Licence::find($request->licence_id);

        if($request->has('subscriptions.clientId'))
            $licence->client_id = $request->subscriptions['clientId'];

        if($request->has('subscriptions.planId'))
            $licence->plan_id = $request->subscriptions['planId'];

        if($request->has('subscriptions.id'))
            $licence->subscription_id = $request->subscriptions['id'];

        // if($request->has('subscriptions.expireAt'))
        //     $licence->expire_at = $request->subscriptions['expireAt'];

        if($request->has('subscriptions.licenseKey'))
            $licence->licence_key = $request->subscriptions['licenseKey'];

        if($request->has('description'))
            $licence->description = $request->description;

        if($request->has('subscriptions.status') && $request->subscriptions['status']){
            $licence->trial_version = null;
            $licence->status = $request->subscriptions['status'];
            $licence->expire_at = $request->subscriptions['expireAt'];
        }

        // dd($licence);
        $result = $licence->save();

        if($result){
            return response()->json(['status' => true]);
        }
        
        return response()->json(['status' => false]);
    }

    public function purchaseLicencePlan(Request $request){
        $expiry_date = Carbon::now()->addMonths($request->duration);

        $licence = Licence::find($request->licence_id);
        $licence->plan_id = $request->plan_id;
        $licence->expire_date = $expiry_date->toDateString();

        $result = $licence->save();

        if($result){
            return response()->json(['status' => true]);
        }
        
        return response()->json(['status' => false]);   
    }

    /**
     * delete the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteInvoiceRequest $request)
    {
        
    }

}
