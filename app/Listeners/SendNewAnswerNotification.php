<?php

namespace App\Listeners;

use App\Events\SurveyAnswerSubmitted;
use App\Mail\NewAnswerMail;
use App\Models\Survey;
use App\Models\User;
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
        $survey = Survey::find($event->answer->survey_id);
        $mail_user = User::find($survey->user_id)->email;
        Mail::to($mail_user)->send(new NewAnswerMail($event->answer));
    }
}
