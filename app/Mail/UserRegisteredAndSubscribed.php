<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\SubscriptionPlan;
use App\Models\User;

class UserRegisteredAndSubscribed extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $plan;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, SubscriptionPlan $plan)
    {
        $this->user = $user;
        $this->plan = $plan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New User Registered & Subscribed'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       return new Content(
            view: 'emails.user_registered',
            with: [
                'user' => $this->user,
                'plan' => $this->plan,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
