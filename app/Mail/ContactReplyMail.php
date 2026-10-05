<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\ContactSubmission;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;
    public $replyMessage;
    public $replySubject;

    public function __construct(ContactSubmission $submission, string $replyMessage, string $replySubject)
    {
        $this->submission = $submission;
        $this->replyMessage = $replyMessage;
        $this->replySubject = $replySubject;
    }

    public function envelope(): Envelope
    {
        $from = new Address(
            config('mail.from.address'),
            config('mail.from.name', 'Tap Review Cards')
        );

        return new Envelope(
            subject: $this->replySubject,
            from: $from,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
            with: [
                'submission' => $this->submission,
                'replyMessage' => $this->replyMessage,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
