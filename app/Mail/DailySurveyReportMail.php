<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailySurveyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    // Make properties public so Blade can access them
    public $survey;
    public $responses_count;

    /**
     * Create a new message instance.
     */
    public function __construct($survey, $responses_count)
    {
        $this->survey = $survey;
        $this->responses_count = $responses_count;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Survey Report Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_survey_report',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
