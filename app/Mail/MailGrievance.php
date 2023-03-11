<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Grievance;

class MailGrievance extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $grievance, $viewType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Grievance $grievance, $viewType)
    {
        $this->grievance = $grievance;
        $this->viewType = $viewType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        switch ($this->viewType) {
            case '1':
                $subject = 'Grievance is being submitted.';
                $view = 'emails.grievance-department';
                break;
            default:
                $subject = 'Grievance is submitted.';
                $view = 'emails.grievance-user';
                break;
        }
        return $this->view($view)
            ->subject($subject)
            ->with([
                'grievance' => $this->grievance,
            ]);
    }
}