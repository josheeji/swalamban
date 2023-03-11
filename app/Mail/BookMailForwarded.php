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

class BookMailForwarded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $book,$country,$destination,$package;

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
        return $this->view('emails.book_forward')
            ->subject('Booking Message is forwarded.')
            ->with([
                'book' => $this->book,
                'country' =>$this->country,
                'destination' => $this->destination,
                'package' => $this->package,
                'logoPath' => public_path('images/logo.png'),
            ]);
    }
}
