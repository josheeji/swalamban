<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailCareerAdmin extends Mailable
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
        //
        $this->applicant = $applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "New Job Application";

        if ($this->applicant->career) {
            $subject = 'New Job Application for ' . $this->applicant->career->title;
        }

        return $this->view('emails.career-admin')
            ->subject($subject)
            ->with([
                'applicant' => $this->applicant,
            ])
            ->attachFromStorage($this->applicant->resume)
            ->attachFromStorage($this->applicant->cover_letter);
    }
}
