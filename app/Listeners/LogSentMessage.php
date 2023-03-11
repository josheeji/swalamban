<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Repositories\EmailLogRepository;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @param EmailLogRepository  $emailLogs
     * @return void
     */
    public function __construct(
        EmailLogRepository $emailLogs
    ){
        $this->emailLogs = $emailLogs;
    }

    /**
     * Handle the event.
     *
     *@param MessageSent $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $data = [
            'user_id'=>auth()->id() ?? null,
            'subject'=>json_encode($event->message->getSubject()),
            'email_content'=>json_encode($event->message->getBody()),
            'from_email'=>json_encode($event->message->getFrom()),
            'to_email'=>json_encode($event->message->getTo()),
            'cc_email'=>json_encode($event->message->getCc()),
            'type'=>2
        ];
        return $this->emailLogs->create($data);
    }
}
