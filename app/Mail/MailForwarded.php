<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Modules\Grn\Models\Grn;
use App\Models\User;
use App\Models\Contact;

class MailForwarded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        Contact $contact
    ){
        $this->contact = $contact;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.forward')
            ->subject('Thank You for Contacting Us')
            ->with([
                'contact' => $this->contact,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
