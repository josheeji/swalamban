<?php

namespace App\Mail;

use App\Http\Controllers\PackageMailToFriendController;
use App\Models\Itinerary;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Modules\Grn\Models\Grn;
use App\Models\User;
use App\Models\Package;

class SendMailToFriend extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $package,$sender_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
    Package $package,
    $sender_email
    ){
        $this->package = $package;
        $this->sender_email = $sender_email;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('package.send_email_to_friend')
            ->subject('Package Requested By Friend.')
            ->with([
                'package' => $this->package,
                'sender_email'=>$this->sender_email,
                // 'receiver' => $this->grn->receivedBy,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
