<?php

namespace Crater\Traits;

use Carbon\Carbon;
use Crater\Models\Address;
use Crater\Models\Note;
use Crater\Models\UserSetting;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Crater\Models\CompanySetting;
use Crater\Models\FileDisk;
use Illuminate\Support\Facades\App;

trait GeneratesPdfTrait
{
    public function getGeneratedPDFOrStream($collection_name)
    {
        // $pdf = $this->getGeneratedPDF($collection_name);

        // if ($pdf && file_exists($pdf['path']) && $collection_name != 'credit') {
        //     return response()->make(file_get_contents($pdf['path']), 200, [
        //         'Content-Type' => 'application/pdf',
        //         'Content-Disposition' => 'inline; filename="'.$pdf['file_name'].'.pdf"',
        //     ]);
        // }

        // $locale = UserSetting::getSetting('language',  $this->company_id);
        // App::setLocale($locale);
// dd($this);
        $pdf = $this->getPDFData($collection_name);
        if($collection_name == 'proforma'){
            $pdf_name = $this[$collection_name.'_number'].'_'.$this->cus_name ?? '';
        }elseif($collection_name == 'invoice'){
             $pdf_name = $this[$collection_name.'_number'].'_'.$this->cus_name ?? '';
        }elseif($collection_name == 'estimate'){
            $pdf_name = $this[$collection_name.'_number'].'_'.$this->cus_name ?? '';
        }elseif($collection_name == 'payment'){
            $pdf_name = $this[$collection_name.'_number'].'_'.$this->customer->name ?? '';
        }
        elseif($collection_name == 'purchase'){
            $pdf_name = $this[$collection_name.'_no'].'_'.(json_decode($this->supplier_details,true) ? json_decode($this->supplier_details,true)['name'] : '');
        }
        elseif($collection_name == 'credit'){
            $pdf_name = $this[$collection_name.'_number'].'_'.(json_decode($this->client_details,true) ? json_decode($this->client_details,true)['name'] : '');
        }
        elseif($collection_name == 'debit'){
            $pdf_name = $this[$collection_name.'_number'].'_'.(json_decode($this->supplier_details,true) ? json_decode($this->supplier_details,true)['name'] : '');
        }
            // dd($pdf_name);
        return response()->make($pdf->stream($pdf_name.'.pdf'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$pdf_name.'.pdf"',
        ]);
    }

    public function getGeneratedPDFOrStreamOfFilter($collections, $query_data, $collection_name)
    {
        if($collection_name == 'payment'){
            $date_heading = 'For the period starting from ';
            $filename_prefix = 'Payment Summary';
            if($collections){
                $filename_prefix = $filename_prefix.'_'.$collections->min('payment_date').'_'.$collections->max('payment_date');
                $date_heading = $date_heading.$collections->min('payment_date').' to '.$collections->max('payment_date');
            }
        }elseif( $collection_name == 'quotation'){
            $date_heading = 'For the period starting from ';
            $filename_prefix = 'Quotation Summary';
            if($query_data['from_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['from_date'];
                $date_heading = $date_heading.$query_data['from_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.$collections->min('estimate_date');
                 $date_heading = $date_heading.$collections->min('estimate_date');
            }
            $date_heading = $date_heading.' to ';
            if($query_data['to_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['to_date'];
                $date_heading = $date_heading.$query_data['to_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.$collections->max('estimate_date').($query_data['status'] != null ? '_'.$query_data['status'] : '');
                 $date_heading = $date_heading.$collections->max('estimate_date');
            }
        }elseif( $collection_name == 'proforma'){
            $date_heading = 'For the period starting from ';
            $filename_prefix = 'Proforma Summary';
            if($query_data['from_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['from_date'];
                $date_heading = $date_heading.$query_data['from_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.$collections->min('proforma_date');
                 $date_heading = $date_heading.$collections->min('proforma_date');
            }
            $date_heading = $date_heading.' to ';
            if($query_data['to_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['to_date'];
                $date_heading = $date_heading.$query_data['to_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.$collections->max('proforma_date').($query_data['status'] != null ? '_'.$query_data['status'] : '');
                 $date_heading = $date_heading.$collections->max('proforma_date');
            }
        }elseif( $collection_name == 'purchase'){
            $date_heading = 'For the period starting from ';
            $filename_prefix = 'Vat Purchase Register Summary';
            if($query_data['from_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['from_date'];
                $date_heading = $date_heading.$query_data['from_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.strtok($collections->min('purchase_date'),' ');
                 $date_heading = $date_heading.strtok($collections->min('purchase_date'),' ');
            }
            $date_heading = $date_heading.' to ';
            if($query_data['to_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['to_date'];
                $date_heading = $date_heading.$query_data['to_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.strtok($collections->max('purchase_date'),' ');
                 $date_heading = $date_heading.strtok($collections->max('purchase_date'),' ');
            }
        }elseif( $collection_name == 'credit'){
            $date_heading = 'For the period starting from ';
            $filename_prefix = 'Credit Note Summary';
            if($query_data['from_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['from_date'];
                $date_heading = $date_heading.$query_data['from_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.strtok($collections->min('credit_date'),' ');
                 $date_heading = $date_heading.strtok($collections->min('credit_date'),' ');
            }
            $date_heading = $date_heading.' to ';
            if($query_data['to_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['to_date'];
                $date_heading = $date_heading.$query_data['to_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.strtok($collections->max('credit_date'),' ');
                 $date_heading = $date_heading.strtok($collections->max('credit_date'),' ');
            }
        }elseif( $collection_name == 'debit'){
            $date_heading = 'For the period starting from ';
            $filename_prefix = 'Debit Note Summary';
            if($query_data['from_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['from_date'];
                $date_heading = $date_heading.date('Y-m-d',strtotime($query_data['from_date']));
            }else{
                 $filename_prefix = $filename_prefix.'_'.$collections->min('debit_date');
                 $date_heading = $date_heading.date('Y-m-d',strtotime($collections->min('debit_date')));
            }
            $date_heading = $date_heading.' to ';
            if($query_data['to_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['to_date'];
                $date_heading = $date_heading.date('Y-m-d',strtotime($query_data['to_date']));
            }else{
                 $filename_prefix = $filename_prefix.'_'.$collections->max('debit_date');
                 $date_heading = $date_heading.date('Y-m-d',strtotime($collections->max('debit_date')));
            }
        }elseif( $collection_name == 'user'){
            $date_heading = '';
            $filename_prefix = 'User Summary';
            
            if($query_data['display_name'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['display_name'];
            }
        }elseif($collection_name == 'invoice'){
            $date_heading = 'For the period starting from ';
            $filename_prefix = 'Vat Sales Register Summary';
            if($query_data['from_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['from_date'];
                $date_heading = $date_heading.$query_data['from_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.strtok($collections->min('invoice_date'),' ');
                 $date_heading = $date_heading.strtok($collections->min('invoice_date'),' ');
            }
            $date_heading = $date_heading.' to ';
            if($query_data['to_date'] != null){
                $filename_prefix = $filename_prefix.'_'.$query_data['to_date'];
                $date_heading = $date_heading.$query_data['to_date'];
            }else{
                 $filename_prefix = $filename_prefix.'_'.strtok($collections->max('invoice_date'),' ').($query_data['status'] != null ? '_'.$query_data['status'] : '');
                 $date_heading = $date_heading.strtok($collections->max('invoice_date'),' ');
            }
        }elseif($collection_name == 'customer'){
            $date_heading = '';
            $filename_prefix = 'Client Summary';
            
            if($query_data['display_name'] != null){
                 $filename_prefix = $filename_prefix.'_'.$query_data['display_name'];
            }
        }elseif($collection_name == 'supplier'){
            $date_heading = '';
            $filename_prefix = 'Supplier Summary';
            
            if($query_data['display_name'] != null){
                 $filename_prefix = $filename_prefix.'_'.$query_data['display_name'];
            }
        }elseif($collection_name == 'items'){
            $date_heading = '';
            $filename_prefix = 'Item Summary';
            
            if($query_data['name'] != null){
                 $filename_prefix = $filename_prefix.'_'.$query_data['name'];
            }
        }
        $locale = UserSetting::getSetting('language',  $this->company_id);
        App::setLocale($locale);

        $pdf = $this->getFilterPDFData($collections, $query_data, $date_heading);
        return response()->make($pdf->stream($filename_prefix.'.pdf'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename_prefix.'.pdf',
        ]);
    }

    public function getGeneratedPDF($collection_name)
    {
        try {
            $media = $this->getMedia($collection_name)->first();

            if ($media) {
                $file_disk = FileDisk::find($media->custom_properties['file_disk_id']);

                if (! $file_disk) {
                    return false;
                }

                $file_disk->setConfig();

                $path = null;

                if ($file_disk->driver == 'local') {
                    $path = $media->getPath();
                } else {
                    $path = $media->getTemporaryUrl(Carbon::now()->addMinutes(5));
                }

                return collect([
                    'path' => $path,
                    'file_name' => $media->file_name,
                ]);
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    public function generatePDF($collection_name, $file_name, $deleteExistingFile = false)
    {
        $save_pdf_to_disk = CompanySetting::getSetting('save_pdf_to_disk',  $this->company_id);

        if ($save_pdf_to_disk == 'NO') {
            return 0;
        }

        $locale = CompanySetting::getSetting('language',  $this->company_id);

        App::setLocale($locale);

        $pdf = $this->getPDFData();

        \Storage::disk('local')->put('temp/'.$collection_name.'/'.$this->id.'/temp.pdf', $pdf->output());

        if ($deleteExistingFile) {
            $this->clearMediaCollection($this->id);
        }

        $file_disk = FileDisk::whereSetAsDefault(true)->first();

        if ($file_disk) {
            $file_disk->setConfig();
        }

        $media = \Storage::disk('local')->path('temp/'.$collection_name.'/'.$this->id.'/temp.pdf');

        try {
            $this->addMedia($media)
                ->withCustomProperties(['file_disk_id' => $file_disk->id])
                ->usingFileName($file_name.'.pdf')
                ->toMediaCollection($collection_name, config('filesystems.default'));

            \Storage::disk('local')->deleteDirectory('temp/'.$collection_name.'/'.$this->id);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getFieldsArray()
    {
        $customer = $this->customer;
        $shippingAddress = $customer->shippingAddress ?? new Address();
        $billingAddress = $customer->billingAddress ?? new Address();
        $companyAddress = $this->company->address ?? new Address();

        $fields = [
            '{SHIPPING_ADDRESS_NAME}' => $this->dynamicDataTranslate($shippingAddress->name),
            '{SHIPPING_COUNTRY}' => $this->dynamicDataTranslate($shippingAddress->country_name),
            '{SHIPPING_STATE}' => $this->dynamicDataTranslate($shippingAddress->state),
            '{SHIPPING_CITY}' => $this->dynamicDataTranslate($shippingAddress->city),
            '{SHIPPING_ADDRESS_STREET_1}' => $shippingAddress->address_street_1,
            '{SHIPPING_ADDRESS_STREET_2}' => $shippingAddress->address_street_2,
            '{SHIPPING_PHONE}' => $shippingAddress->phone,
            '{SHIPPING_ZIP_CODE}' => $shippingAddress->zip,
            '{BILLING_ADDRESS_NAME}' => $this->dynamicDataTranslate($billingAddress->name),
            '{BILLING_COUNTRY}' => $this->dynamicDataTranslate($billingAddress->country_name),
            '{BILLING_STATE}' => $this->dynamicDataTranslate($billingAddress->state),
            '{BILLING_CITY}' => $this->dynamicDataTranslate($billingAddress->city),
            '{BILLING_ADDRESS_STREET_1}' => $billingAddress->address_street_1,
            '{BILLING_ADDRESS_STREET_2}' => $billingAddress->address_street_2,
            '{BILLING_PHONE}' => $billingAddress->phone,
            '{BILLING_ZIP_CODE}' => $billingAddress->zip,
            '{COMPANY_NAME}' => $this->dynamicDataTranslate($this->company->name),
            '{COMPANY_COUNTRY}' => $this->dynamicDataTranslate($companyAddress->country_name),
            '{COMPANY_STATE}' => $this->dynamicDataTranslate($companyAddress->state),
            '{COMPANY_CITY}' => $this->dynamicDataTranslate($companyAddress->city),
            '{COMPANY_ADDRESS_STREET_1}' => $companyAddress->address_street_1,
            '{COMPANY_ADDRESS_STREET_2}' => $companyAddress->address_street_2,
            '{COMPANY_PHONE}' => $companyAddress->phone,
            '{COMPANY_ZIP_CODE}' => $companyAddress->zip,
            '{CONTACT_DISPLAY_NAME}' => $this->dynamicDataTranslate($customer->name),
            '{PRIMARY_CONTACT_NAME}' => $this->dynamicDataTranslate($customer->contact_name),
            '{CONTACT_EMAIL}' => $customer->email,
            '{CONTACT_PHONE}' => $customer->phone,
            '{CONTACT_WEBSITE}' => $customer->website,
        ];

        $customFields = $this->fields;
        $customerCustomFields = $this->customer->fields;

        foreach ($customFields as $customField) {
            $fields['{'.$customField->customField->slug.'}'] = $customField->defaultAnswer;
        }

        foreach ($customerCustomFields as $customField) {
            $fields['{'.$customField->customField->slug.'}'] = $customField->defaultAnswer;
        }

        return $fields;
    }
    public function dynamicDataTranslate($value){
        if($value){
            return $value;
            //return GoogleTranslate::trans($value, 'ar', 'en');
        }
    }

    public function getFormattedString($format, $model='')
    {
        // $values = array_merge($this->getFieldsArray(), $this->getExtraFields());
        // dd($model->cus_name);
        if($model != ''){
            $values = array_merge($this->getFieldsArray(), $this->getExtraFields());
            $values['{BILLING_ADDRESS_NAME}'] = $model->cus_name;
            $values['{BILLING_STATE}'] = $model->cus_state;
            $values['{BILLING_CITY}'] = $model->cus_city;
            $values['{BILLING_ADDRESS_STREET_1}'] = $model->cus_address_street_1;
            $values['{BILLING_ADDRESS_STREET_2}'] = $model->cus_address_street_2;
            $values['{BILLING_PHONE}'] = $model->cus_phone;
            $values['{BILLING_ZIP_CODE}'] = $model->cus_zip;
            $str = nl2br(strtr($format, $values));

            $str = preg_replace('/{(.*?)}/', '', $str);

            $str = preg_replace("/<[^\/>]*>([\s]?)*<\/[^>]*>/", '', $str);

            $str = str_replace("<p>", "", $str);

            $str = str_replace("</p>", "<br>", $str);
        }else{
            $values = array_merge($this->getFieldsArray(), $this->getExtraFields());

            $str = nl2br(strtr($format, $values));

            $str = preg_replace('/{(.*?)}/', '', $str);

            $str = preg_replace("/<[^\/>]*>([\s]?)*<\/[^>]*>/", '', $str);

            $str = str_replace("<p>", "", $str);

            $str = str_replace("</p>", "<br>", $str);
        }
        
        return $str;
    }

    public function getNoteHeading($description){
        return ucwords(Note::whereNotes($description)->value('name'));
    }
}
