<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Simple welcome / account-created email sent when an admin creates a user
 * account from the backend. No approval wording — that is a separate email.
 */
class AccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Tap Review Cards account is ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account_created',
            with: ['user' => $this->user],
        );
    }
}
