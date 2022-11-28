<?php

namespace Crater\Traits;

use Illuminate\Http\Request;
use Crater\Models\Licence as LicenceModel;
use Carbon\Carbon;

trait Licence
{

    private $licence;
    private $options = 0;
    private $ciphering = "AES-128-CTR";
    private $is_it_trial = true;

    public function authenticateLicence(Request $request)
    {
        $this->validateLicence($request);
        return $this->isLicenceAuthorized($request);
    }

    private function isLicenceAuthorized(Request $request){
        // GET DECRYPTED LICENCE
        $licence = $this->decryptLicence();
        // dd($licence);

        // CHECK FOR PAID VERSION
        if(!is_null($licence['subscription_id']) && is_null($licence['trial_version']))
            $this->is_it_trial = false;

        return ['status'=>true, 'is_trial'=>$this->is_it_trial, 'licence_id' => $licence['id'], 'endpoint' => config('licence.endpoint'), 'data'=>$licence];
    }

    private function decryptLicence(){
        $licence = $this->licence;
        $licence['company_id'] = $this->decryptData($this->licence['company_id']);
        $licence['trial_version'] = $this->decryptData($this->licence['trial_version']);
        $licence['client_id'] = $this->licence['client_id'] ?? $this->decryptData($this->licence['client_id']);
        $licence['plan_id'] = $this->licence['plan_id'] ?? $this->decryptData($this->licence['plan_id']);
        $licence['expire_date'] = $this->licence['expire_at'] ?? $this->decryptData($this->licence['expire_at']);
        $licence['description'] = $this->licence['description'] ?? $this->decryptData($this->licence['description']);
        
        return $licence;
    }

    private function validateLicence(Request $request){
        $licence = $this->getLicence($request);

        if($licence)
            $this->licence = $licence;
        else
            $this->licence = $this->createLicence($request);
    }

    private function createLicence(Request $request){
        $data['company_id'] = $this->encryptData($request->header('company'));
        $data['trial_version'] = bin2hex(random_bytes(20));
        $data['expire_at'] = Carbon::now()->addDays(config('licence.trial_days'));
        // $data['client_id'] = $this->encryptData('licence_type');
        // $data['plan_id'] = $this->encryptData('expire_date');
        // $data['description'] = $this->encryptData('trial_version');

        return LicenceModel::create($data);
    }

    private function getLicence(Request $request){
        return LicenceModel::where('company_id', $this->encryptData($request->header('company')))->first();
    }

    protected function encryptData($value){
        return $value;
        // Use OpenSSl Encryption method 
        $iv_length = openssl_cipher_iv_length($this->ciphering); 

        $iv = rand(1000000000000000, 9999999999999999);
        $key = env('APP_KEY');

        // Use openssl_encrypt() function to encrypt the data 
        $encryption = openssl_encrypt($value, $this->ciphering, $key, $this->options, $iv); 

        // Return the encrypted string
        return $encryption;
    }

    protected function decryptData($value){
        return $value;
        // Use OpenSSl Encryption method 
        $iv_length = openssl_cipher_iv_length($this->ciphering); 

        $iv = rand(1000000000000000, 9999999999999999);
        $key = env('APP_KEY');

        // Use openssl_decrypt() function to decrypt the data 
        $decryption=openssl_decrypt ($value, $this->ciphering, $key, $this->options, $iv); 
        
        // Return the decrypted string 
        return $decryption;
    }
}