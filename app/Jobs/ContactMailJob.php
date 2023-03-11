<?php

namespace App\Jobs;

use App\Mail\MailForwarded;
use App\Mail\MailReceived;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ContactMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $contact;
    public $contact_email;
    public $amin_email;
    public function __construct($contact,$contact_email,$admin_email)
    {
        $this->contact = $contact;
        $this->contact_email = $contact_email;
        $this->admin_email = $admin_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->contact_email) {
            Mail::to($this->contact_email)
                ->send(new MailForwarded($this->contact));
        }
        if (!empty($this->admin_email)) {
            Mail::to($this->admin_email)
                ->send(new MailReceived($this->contact));
        }
    }
}
