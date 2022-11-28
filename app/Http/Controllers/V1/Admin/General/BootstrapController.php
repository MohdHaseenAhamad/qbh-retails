<?php

namespace Crater\Http\Controllers\V1\Admin\General;

use Crater\Http\Controllers\Controller;
use Crater\Http\Resources\CompanyResource;
use Crater\Http\Resources\UserResource;
use Crater\Models\Company;
use Crater\Models\CompanySetting;
use Crater\Models\Currency;
use Crater\Traits\GeneratesMenuTrait;
use Crater\Traits\Licence;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade;

class BootstrapController extends Controller
{
    use GeneratesMenuTrait, Licence;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $current_user = $request->user();
        $current_user_settings = $current_user->getAllSettings();
        
        // CHECK AND UPDATE FOR NEW MODULE ABILITIES FOR SUPER USER
        Company::checkUpdateForNewAbilities($request->header('company'));

        $main_menu = $this->generateMenu('main_menu', $current_user);

        $setting_menu = $this->generateMenu('setting_menu', $current_user);

        $companies = $current_user->companies;

        $current_company = Company::find($request->header('company'));

        if ((! $current_company) || ($current_company && ! $current_user->hasCompany($current_company->id))) {
            $current_company = $current_user->companies()->first();
        }

        $current_company_settings = CompanySetting::getAllSettings($current_company->id);

        $current_company_currency = $current_company_settings->has('currency')
            ? Currency::find($current_company_settings->get('currency'))
            : Currency::first();

        BouncerFacade::refreshFor($current_user);

        $current_user_abilities = $current_user->getAbilities();

        return response()->json([
            'current_user' => new UserResource($current_user),
            'current_user_settings' => $current_user_settings,
            'current_user_abilities' => $current_user_abilities,
            'current_user_template_abilities' => $this->getTemplateAbilities($current_user_abilities),
            'companies' => CompanyResource::collection($companies),
            'current_company' => new CompanyResource($current_company),
            'current_company_settings' => $current_company_settings,
            'current_company_currency' => $current_company_currency,
            'config' => config('crater'),
            'main_menu' => $main_menu,
            'setting_menu' => $setting_menu,
            'licence' => $this->authenticateLicence($request)
        ]);
    }

    public function getTemplateAbilities($current_user_abilities){
        // dd($current_user_abilities->toArray());
        $allowed_templates = [];
        foreach($current_user_abilities as $ability){
            if (str_contains($ability['title'], 'templates')) { 
                $allowed_templates[] = $ability['name'];
            }
        }
        // dd($allowed_templates);
        return $allowed_templates;
    }
}
