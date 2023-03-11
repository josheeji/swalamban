<?php namespace App\Classes;

use App\Models\Setting;
use Mail;

class Email
{

    public static function sendEmail($receiverEmail, $subject, $content, $replyTo = "", $ccBCC = [])
    {
        $senderEmail =  Setting::where('name','From Email')->first();
        $receiver = Setting::where('name','Web Master Email')->first();
        $senderName = Setting::where('name','Site Name')->first();
        $logo = Setting::where('name','logo')->first();
        $sitePath = url('/');

        $sender =  $senderEmail->value;
        $recev =  $receiver->value;
        $sendname = $senderName->value;
        $data = array(
            'logopath' => asset('images/logo.png'),
            'content' => $content,
            'footer' => ' Copyright ' . date('Y') . ' ',
            'sitepath' => $sitePath,
            'sitename' => $sendname,
        );



        try {
            Mail::send('emails.emails', $data, function ($message)
            use ($sender, $sendname, $recev, $subject) {

                $message->from($sender, $sendname);
                $message->to($recev)->subject($subject);
            });
            return count(Mail::failures()) > 0 ? false : true;

        } catch (Swift_RfcComplianceException $e) {
            return false;
        }

    }
    public static function sendBarcodeEmail($receiverEmail, $subject, $content,$attachment)
    {
        $senderEmail = 'no-reply@simon.org.np';
        $senderName = 'Society of internal Medicine of Nepal (SIMON)';
        $sitePath = url('http://simon.org.np/');
        $siteName = 'Society of internal Medicine of Nepal (SIMON) ';

        $data = array(
            'logopath' => asset('images/logo_small.png'),
            'content' => $content,
            'footer' => ' Copyright ' . date('Y') . ' ',
            'sitepath' => $sitePath,
            'sitename' => $siteName,
        );

        try {
            Mail::send('emails.email', $data, function ($message)
            use ($senderEmail, $senderName, $receiverEmail, $subject,$attachment) {
                $message->from($senderEmail, $senderName);
                $message->to($receiverEmail)->subject($subject);
                if($attachment)
                    $message->attachData($attachment->output(), "ticket.pdf");
            });
            return count(Mail::failures()) > 0 ? false : true;

        } catch (Swift_RfcComplianceException $e) {
            return false;
        }

    }

}
