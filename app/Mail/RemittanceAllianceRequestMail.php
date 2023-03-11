<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemittanceAllianceRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $form_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->form_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['form_data'] = $this->form_data;
        if (isset($data['form_data']['is_admin'])) {
            return $this->view('emails.remit_request_admin', $data);
        }
        return $this->view('emails.remit_request', $data);
    }
}
