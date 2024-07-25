<?php
if (!function_exists('number_formatter')) {
    function number_formatter($number, $format = 'en_IN', $fraction_digit = 2)
    {
        //$numberFormat = new \NumberFormatter($locale = $format, \NumberFormatter::DECIMAL);
        //$numberFormat->setAttribute($numberFormat::FRACTION_DIGITS, $fraction_digit);
        //return $numberFormat->format($number);
        return number_format($number, $fraction_digit);
    }
}
if (!function_exists('currency_formatter')) {
    function currency_formatter($number, $fraction_digit = 2)
    {
        $currency_value = number_formatter($number,'en_US', $fraction_digit);
        if($fraction_digit == 3){
            if(substr($currency_value, -1) == '0'){
                return '$ '.number_formatter($number);
            }
        }
        return '$ '.$currency_value;
    }
}
if (!function_exists('name_to_slug')) {
    function name_to_slug($name) {
        $name = strtolower($name);
        return preg_replace('/\s+/', '_', $name);
    }
}
if (!function_exists('base64_encode_image')) {
    function base64_encode_image($filename=string, $filetype=string) {
	    if ($filename) {
	        $imgbinary = file_get_contents($filename);
	        return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
	    }
	}
}
if (!function_exists('prepareFrontendUrl')) {
    function prepareFrontendUrl($route, $attributes = []) {
	    return '';
	}
}
if (!function_exists('round_number')) {
    function round_number($number) {
        return round($number, config('constant.fractional_digit', 4));
    }
}
if (!function_exists('encrypt_id_info')) {
    function encrypt_id_info($id){
        return hashid_encode($id);
    }
}
if (!function_exists('decrypt_id_info')) {
    function decrypt_id_info($code){
        if (is_numeric($code)) {
            return hashid_decode($code);
        }
    }
}
if (!function_exists('log_activity_by_user')){
    function log_activity_by_user($log_name, $performed_on, $activity_details, $additional_parameters = []){
        $user = \Sentinel::getUser();
        $activity = activity($log_name)->performedOn($performed_on);
        if(isset($user->id)){
            $activity = $activity->causedBy($user);
        }
        $activity->withProperties(array_merge(['attributes' => $performed_on->toArray()],$additional_parameters))->log($activity_details);
    }
}

if (!function_exists('generateUrl')) {
    function generateUrl($disk, $filePath) {

        if($disk == 'local'){
            return env('APP_URL').\Storage::disk($disk)->url($filePath);
        }

        return \Storage::disk($disk)->url($filePath);
    }
}
if (!function_exists('getStorageDisk')){
    function getStorageDisk(){
        return config('constant.storage_disk');
    }
}
if (!function_exists('updateEnvDuns')){
    function updateEnvDuns($token){

        $path = base_path('.env');
        \Log::info(env('DUNS_TOKEN'));
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'DUNS_TOKEN='.config('app.duns_token'), 'DUNS_TOKEN='.$token, file_get_contents($path)
            ));
        }
    }
}