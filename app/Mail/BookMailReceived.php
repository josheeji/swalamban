<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Modules\Grn\Models\Grn;
use App\Models\User;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Package;
use App\Models\Destination;

class BookMailReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $book,$country,$destination,$package;

    // protected $sender,$site_setting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        Booking $book,
        Country $country,
        Destination $destination,
        Package $package
    ){
        $this->book = $book;
        $this->country = $country;
        $this->destination = $destination;
        $this->package = $package;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.book_received')
            ->subject('Booking Details is Received.')
            ->with([
                'book' => $this->book,
                'country' =>$this->country,
                'destination' => $this->destination,
                'package' => $this->package,
                // 'receiver' => $this->grn->receivedBy,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
