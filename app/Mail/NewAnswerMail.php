<?php

namespace App\Mail;

use App\Models\SurveyAnswer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAnswerMail extends Mailable
{
    use Queueable, SerializesModels;

    public SurveyAnswer $answer;


    public function __construct(SurveyAnswer $answer){$this->answer = $answer;}



    public function envelope(): Envelope{
        return new Envelope(
            subject: 'Nouvelle réponse à votre sondage!',);}



    public function content(): Content{
        return new Content(
            view: 'emails.new_answer_mail',);}



    public function attachment(): array{
        return [];}
}
