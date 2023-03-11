<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//use Unisharp\Laravelfilemanager\Events\ImageIsDeleting;
//use Unisharp\Laravelfilemanager\Events\ImageIsRenaming;
//use Unisharp\Laravelfilemanager\Events\ImageIsUploading;
//use Unisharp\Laravelfilemanager\Events\ImageWasUploaded;
//
//use App\Listeners\DeleteImageListener;
//use App\Listeners\RenameImageListener;
//use App\Listeners\IsUploadingImageListener;
//use App\Listeners\HasUploadedImageListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'Unisharp\Laravelfilemanager\Events\ImageIsDeleting' => [
            'App\Listeners\DeleteImageListener'
        ],
        'Unisharp\Laravelfilemanager\Events\ImageIsRenaming' => [
            'App\Listeners\RenameImageListener'
        ],
        'Unisharp\Laravelfilemanager\Events\ImageIsUploading' => [
            'App\Listeners\IsUploadingImageListener'
        ],
        'Unisharp\Laravelfilemanager\Events\ImageWasUploaded' => [
            'App\Listeners\HasUploadedImageListener'
        ],
        'Illuminate\Mail\Events\MessageSending' => [
            'App\Listeners\LogSendingMessage',
        ],
        'Illuminate\Mail\Events\MessageSent' => [
            'App\Listeners\LogSentMessage',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
