<?php

namespace App\Mail;

use App\Models\SendEmailSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailSendToSubscriberForwarded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        SendEmailSubscription $mail
    ){
        $this->mail = $mail;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emailSubscribe.susciptionmail_forward')
            ->subject('Mail Received As Suscription message.')
            ->with([
                'mail' => $this->mail,
                // 'receiver' => $this->grn->receivedBy,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
