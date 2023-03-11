<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailApplicant extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $applicant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Applicant $applicant)
    {
        $this->applicant = $applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.applicant')
            ->subject('Job application received.')
            ->with([
                'applicant' => $this->applicant,
            ]);
    }
}
