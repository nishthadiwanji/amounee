<?php
namespace App\Transformers;

use App\Transformers\BaseTransformer;

class PaymentTransformer extends BaseTransformer{

    public function __construct(){
        parent::__construct();
    }
    public function transform($result){

        $parsed_result = [
            'Artisan Name' => $result->artisan_name,
            'Payment Amount' => $result->payment_amount,
            'Payment Type' => $result->payment_type,
            'Paid Amount' => $result->paid_amount,
            'Date of Payment' => $result->date_payment,
            'Note' => $result->note,
        ];

        return $parsed_result;
    }
}
