<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $partner;
    public string $status;

    public function __construct(User $partner, string $status)
    {
        $this->partner = $partner;
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved' 
            ? 'Congratulations! Your B2B ' . $this->partner->partner_type_label . ' Account is Approved'
            : 'Update regarding your B2B Partner Application';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner_status_updated',
        );
    }
}
