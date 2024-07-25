<?php
namespace App\Repositories;

use \SendGrid\Mail\From as From;
use \SendGrid\Mail\Mail as Mail;
use \SendGrid\Mail\To as To;
use \SendGrid\Mail\Cc as Cc;
use \SendGrid\Mail\Bcc as Bcc;
use Exception;
use Log;

class EmailRepository
{
    public static function sendTo($template_id, $send_to, $data, $subject = '', $send_cc = [], $send_bcc = [])
    {
        try {
            $from = new From("info@Amounee.com");

            $tos = array();
            $ccs = array();
            $bccs = array();
            foreach($send_to as $to){
                $tos[] = new To($to['email'], $to['name'], $data);
            }

            foreach($send_cc as $cc){
                $ccs[] = new Cc($cc['email'], $cc['name']);
            }

            foreach($send_bcc as $bcc){
                $bccs[] = new Bcc($bcc['email'], $bcc['name']);
            }

            $email = new Mail($from, $tos);

            if(trim($subject) != ''){
                $email->setSubject($subject);
            }

            if(count($ccs)){
                $email->addCcs($ccs);
            }

            if(count($bccs)){
                $email->addBccs($bccs);
            }
            $email->setTemplateId($template_id);
            $sendgrid = new \SendGrid(config('app.sendgrid_key'));

            $response = $sendgrid->send($email);

            return $response;

        } catch (Exception $e) {

            Log::error('Error while sending email via Sendgrid - Email Repository => ',[$e->getMessage(), $e->getTraceAsString()]);
            return false;
        }

    }

    public static function forgotPasswordEmail($user_email, $attributes){
        return self::sendTo('d-b5a82cea7d0a461dbe20929397c7502d', [['email' => $user_email, 'name' => $attributes['name']]], [
            'admin_name' => $attributes['name'],
            'reset_url'  => $attributes['site_url']
        ]);
    }

    public static function passwordChanged($user_email, $attributes){
        //TODO::need to integrate change password template id from sendgrid
        return self::sendTo('', [['email' => $user_email, 'name' => $attributes['name']]], [
            'name' => $attributes['name'],
            'login_url' => route('home')
        ]);
    }
    
}
