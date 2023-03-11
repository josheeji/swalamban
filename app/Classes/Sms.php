<?php namespace App\Classes;

use App\Models\Setting;
use App\SmsLog;
use Input;
class Sms
{
    public static function sendAdminMessage($reservation)

    { //dd($reservation->roomType->name);
        $message = "Inquiry of,$reservation->first_name $reservation->last_name,$reservation->address,$reservation->contact,Email:$reservation->mail_address,\nCheck-in:$reservation->check_in_date,
      Check-out:$reservation->check_out_date,\n$reservation->number_of_rooms-{$reservation->roomType->name}.";


        $senderEmail = Setting::where('name','mobile')->first();


        $args = http_build_query(array(
            'api_key' => 'T0RJTmI9ZnFKckRnSEpsSHNBcmM=',
            'to' =>  $senderEmail->value,
            'from' => 'PNDC',
            'sms' => $message));

        $url = "https://yaadayo.com/sms/api?action=send-sms";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        SmsLog::create(['mobile_no' => $reservation->contact,'message'=>$message,'api_response'=>$status_code]);
    }
    public static function sendConfirmation($reservation)
    {

        $message = "Dear $reservation->first_name $reservation->last_name,\nYour booking  has been confirmed. \nThank You\n";

        $args = http_build_query(array(
            'api_key' => 'T0RJTmI9ZnFKckRnSEpsSHNBcmM=',
            'to' => $reservation->contact,
            'from' => 'PNDC',
            'sms' => $message));


        $url = "https://yaadayo.com/sms/api?action=send-sms";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        SmsLog::create(['mobile_no' => $reservation->contact,'message'=>$message,'api_response'=>$status_code]);

    }



}
