<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $applicant;

    public function __construct(User $applicant)
    {
        $this->applicant = $applicant;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New B2B Partner Application: ' . $this->applicant->partner_type_label . ' - ' . $this->applicant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner_application_submitted',
        );
    }
}
