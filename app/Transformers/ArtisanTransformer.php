<?php
namespace App\Transformers;

use App\Transformers\BaseTransformer;

class ArtisanTransformer extends BaseTransformer{

    public function __construct(){
        parent::__construct();
    }
    public function transform($result){

        $parsed_result = [
            'First Name' => $result->first_name,
            'Last Name' => $result->last_name,
            'Store Name' => $result->store_name,
            'Store URL' => $result->store_url,
            'Phone Number' => $result->phone_number,
            'Email' => $result->email,
            'Craft Practiced' => $result->craft_category,
            'Street 1' => $result->street1,
            'Street 2' => $result->street2,
            'Zip' => $result->zip,
            'City' => $result->city,
            'State' => $result->state,
            'Country' => $result->country,
            'Account Name' => $result->account_name,
            'Account Number' => $result->account_number,
            'Bank Address' => $result->bank_address,
            'Routing Number' => $result->routing_number,
            'IBAN' => $result->iban,
            'Swift' => $result->swift,
            'PayPal Email' => $result->email_paypal,
        ];

        return $parsed_result;
    }
}
