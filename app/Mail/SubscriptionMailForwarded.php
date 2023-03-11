<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionMailForwarded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $email_address;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
         $email_address
    ){
        $this->email_address = $email_address;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.subscription_forward')
            ->subject('Subscription message is received.')
            ->with([
                'email_address' => $this->email_address,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
