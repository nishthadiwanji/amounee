<?php

namespace App\Adapter\Notification;
use Log;

class SmsApi
{
    private $api_key;
    private $api_endpoint;
    private $sender_id;
    // private $entity_id;
    private $payload;

    public function __construct()
    {
        $this->sender_id = env('SENDER_ID', 'AMOUNE');
        $this->api_endpoint = "https://www.smsalert.co.in/api/push.json";
        $this->api_key = env('SMS_API_KEY', '#');
        // $this->entity_id = env('SMS_API_ENTITY_ID', '#');
        $this->payload = [];
    }

    private function initCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT , 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION  , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->payload));
        $result = curl_exec($ch);
        curl_close($ch);
        Log::info('SMS End Result', [$result]);
        return $result;
    }

    private function preparePostFields($receiver, $message, $templateId=NULL)
    {
        $this->payload = [
            'apikey'       => $this->api_key,
            'sender'  => $this->sender_id,
            'mobileno' => $receiver,
            'text' => $message
            // 'entity'    => $this->entity_id,
            // 'routeid'   => '418',
            // 'type'      => 'text',
            // 'tempid'    => $templateId,
            // 'contacts'  => $receiver,
            // 'msg'       => $message
        ];
    }

    public function sendSms($contact_number = [], $message)
    {
        //TODO::implement chunck if needed
        $receiver = implode(',',$contact_number);
        $this->preparePostFields($receiver, $message);
        return $this->initCurl();
    }

}
