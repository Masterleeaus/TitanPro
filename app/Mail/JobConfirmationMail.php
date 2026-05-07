<?php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $queue   = 'mail';
    public int    $tries   = 3;
    public int    $timeout = 60;
    public array  $backoff = [30, 60, 120];

    public function __construct(public readonly Job $job) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Job Confirmation: {$this->job->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.job-confirmation',
        );
    }
}
