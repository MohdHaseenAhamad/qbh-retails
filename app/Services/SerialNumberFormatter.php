<?php

namespace Crater\Services;

use Crater\Models\CompanySetting;
use Crater\Models\Company;
use Crater\Models\Customer;
use Crater\Models\Supplier;

/**
 * SerialNumberFormatter
 * @package Crater\Services;
 */

class SerialNumberFormatter
{
    public const VALID_PLACEHOLDERS = ['CUSTOMER_SERIES', 'SEQUENCE', 'DATE_FORMAT', 'SERIES', 'RANDOM_SEQUENCE', 'DELIMITER', 'CUSTOMER_SEQUENCE'];

    private $model;

    private $ob;

    private $customer;

    private $supplier;

    private $company;

    public $all_sequences;
    public $with_date = false;
    public $whichModule = 'invoice';

    /**
     * @var string
     */
    public $nextSequenceNumber;

    /**
     * @var string
     */
    public $nextCustomerSequenceNumber;

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function setModelObject($id = null)
    {
        $this->ob = $this->model::find($id);
        if ($this->ob && $this->ob->sequence_number) {
            $this->nextSequenceNumber = $this->ob->sequence_number;
        }

        if (isset($this->ob) && isset($this->ob->customer_sequence_number) && isset($this->customer) && $this->ob->customer_id == $this->customer->id) {
            // dd($this->ob->sequence_number);
            $this->nextCustomerSequenceNumber = $this->ob->customer_sequence_number;
        }

        return $this;
    }

    /**
     * @param $company
     * @return $this
     */
    public function setCompany($company, $woking_module = 'invoice')
    {
        $this->company = $company;

        // SET VALUE FOR SPECIFIC MODULE
        // AS INVOICE/ESTIMATE/CREDIT/PERFORMA AND SO..
        $this->whichModule = $woking_module;
        // GET/SET ALL SEQUENCE FOR SPECIFIC MODULE
        $this->setAllSequences();

        return $this;
    }

    /**
     * @param $customer
     * @return $this
     */
    public function setCustomer($customer = null)
    {
        $this->customer = Customer::find($customer);

        return $this;
    }
    public function setSupplier($supplier = null)
    {
        $this->supplier = Supplier::find($supplier);

        return $this;
    }

    public function setAllSequences(){
        // dd($this->whichModule);
        $this->all_sequences = $this->model::orderBy('sequence_number', 'asc')
            ->where('company_id', $this->company)
            ->where('sequence_number', '<>', null)->get([$this->whichModule.'_date', 'sequence_number']);

        return $this;
    }

    /**
     * @return string
     */
    public function getNextNumber($data = null, $forWhichModule = 'invoice')
    {
        // USED TO GET SEQUENCES WITH DATE
        // IF RECORD(s) ALREADY EXISTS FOR
        // INVOICE/ESTIMATE/PAYMENT/CREDIT/PROFORMA..
        if(count($this->all_sequences) > 0)
            $this->with_date = true;

        $modelName = strtolower(class_basename($this->model));
        $settingKey = $modelName.'_number_format';
        $companyId = $this->company;
        if (request()->has('format')) {
            $format = request()->format;
        } else {
            $format = CompanySetting::getSetting(
                $settingKey,
                $companyId
            );
        }

        // UPDATE DEFAULT SETTINGS FOR
        // LATEST IMPLEMENTED MODULES
        // INTO COMPANY SETTING
        // IF NOT CREATED
        if($format == null){
            $format = Company::setNewModulesDefaultSettings($this->company, $this->whichModule, $settingKey);
        }

        $this->setNextNumbers();

        $serialNumber = $this->generateSerialNumber(
            $format
        );
        // dd($serialNumber);
        if($this->with_date){
            return [$serialNumber, $this->{$this->whichModule."_date"}];
        }else{
            return $serialNumber;
        }
    }

    public function setNextNumbers()
    {
        $this->nextSequenceNumber ?
            $this->nextSequenceNumber : $this->setNextSequenceNumber();

        $this->nextCustomerSequenceNumber ?
            $this->nextCustomerSequenceNumber : $this->setNextCustomerSequenceNumber();

        return $this;
    }

    /**
     * @return $this
     */
    public function setNextSequenceNumber()
    {
        $sequence_numbers = $this->all_sequences->pluck('sequence_number')->toArray();
        for($i=1; in_array($i, $sequence_numbers); $i++);
        /** uncomment this for missing invoice sequence */
        //$this->nextSequenceNumber = $i;

        /** get latest sequence */
        $this->nextSequenceNumber = end($sequence_numbers)+1;
        //For including date
        if($this->with_date){

            if($this->nextSequenceNumber > $sequence_numbers[count($sequence_numbers) - 1])

                ${$this->whichModule."_date"} = date('Y-m-d h:i:s A');

            else
                ${$this->whichModule."_date"} = $this->all_sequences
                                    ->where('sequence_number', '<=', $this->nextSequenceNumber)
                                    ->sortBy($this->whichModule.'_date',SORT_REGULAR,true)
                                    ->pluck($this->whichModule.'_date')
                                    ->first();
        }

        if(!$this->with_date || ${$this->whichModule."_date"} == null)

            ${$this->whichModule."_date"} = $this->all_sequences
                                        ->where('sequence_number', '>=', $this->nextSequenceNumber)
                                        ->sortBy($this->whichModule.'_date',SORT_REGULAR,false)
                                        ->pluck($this->whichModule.'_date')
                                        ->first();

        $this->{$this->whichModule."_date"} = ${$this->whichModule."_date"};
        return $this;
    }

    /**
     * @return self
     */
    public function setNextCustomerSequenceNumber()
    {
        $customer_id = ($this->customer) ? $this->customer->id : 1;
        $client = $this->whichModule == 'purchase'?'supplier_id':'customer_id';

        $all_customer_sequences = $this->model::orderBy('customer_sequence_number', 'asc')
            ->where('company_id', $this->company)
            ->where($client, $customer_id)
            ->where('customer_sequence_number', '<>', null)->get(['customer_sequence_number']);
        $customer_sequence_numbers = $all_customer_sequences->pluck('customer_sequence_number')->toArray();
        for($i=1; in_array($i, $customer_sequence_numbers); $i++);
        $this->nextCustomerSequenceNumber = $i;

        // $last = $this->model::orderBy('customer_sequence_number', 'desc')
        //     ->where('company_id', $this->company)
        //     ->where('customer_id', $customer_id)
        //     ->where('customer_sequence_number', '<>', null)
        //     ->take(1)
        //     ->first();

        // $this->nextCustomerSequenceNumber = ($last) ? $last->customer_sequence_number + 1 : 1;

        return $this;
    }

    public static function getPlaceholders(string $format)
    {
        $regex = "/{{([A-Z_]{1,})(?::)?([a-zA-Z0-9_]{1,8}|.{1})?}}/";

        preg_match_all($regex, $format, $placeholders);
        array_shift($placeholders);
        $validPlaceholders = collect();
        /** @var array */
        $mappedPlaceholders = array_map(
            null,
            current($placeholders),
            end($placeholders)
        );
        foreach ($mappedPlaceholders as $placeholder) {
            $name = current($placeholder);
            $value = end($placeholder);

            if (in_array($name, self::VALID_PLACEHOLDERS)) {
                $validPlaceholders->push([
                    "name" => $name,
                    "value" => $value
                ]);
            }
        }
        return $validPlaceholders;
    }

    /**
     * @return string
     */
    private function generateSerialNumber(string $format)
    {
        $serialNumber = '';
        $placeholders = self::getPlaceholders($format);
        foreach ($placeholders as $placeholder) {
            $name = $placeholder['name'];
            $value = $placeholder['value'];

            switch ($name) {
                    case "SEQUENCE":
                        $value = $value ? $value : 6;
                        $serialNumber .= str_pad($this->nextSequenceNumber, $value, 0, STR_PAD_LEFT);

                        break;
                    case "DATE_FORMAT":
                        $value = $value ? $value : 'Y';
                        $serialNumber .= date($value);

                        break;
                    case "RANDOM_SEQUENCE":
                        $value = $value ? $value : 6;
                        $serialNumber .= substr(bin2hex(random_bytes($value)), 0, $value);

                        break;
                    case "CUSTOMER_SERIES":
                        if (isset($this->customer)) {
                            // $serialNumber .= $this->customer->prefix ?? $value;
                            $serialNumber .= $value;
                        } else {
                            $serialNumber .= $value;
                        }
                        break;
                    case "CUSTOMER_SEQUENCE":
                        $serialNumber .= str_pad($this->nextCustomerSequenceNumber, $value, 0, STR_PAD_LEFT);

                        break;
                    default:
                        $serialNumber .= $value;
                }
        }

        return $serialNumber;
    }
}
