<?php

namespace App\Listeners;

use App\Events\SurveyAnswerSubmitted;
use App\Mail\NewAnswerMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewAnswerNotification implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SurveyAnswerSubmitted $event): void
    {
        Mail::to('liam.deparfouru@gmail.com')->send(new NewAnswerMail($event->answer));
    }
}
