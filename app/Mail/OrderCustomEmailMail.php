<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCustomEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $subjectText;
    public string $messageText;
    public bool $includeSummary;

    public function __construct(Order $order, string $subjectText, string $messageText, bool $includeSummary = true)
    {
        $this->order = $order;
        $this->subjectText = $subjectText;
        $this->messageText = $messageText;
        $this->includeSummary = $includeSummary;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'support@reviewbooster.co.uk'),
                config('mail.from.name', 'Tap Review Cards')
            ),
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_custom_email',
            with: [
                'order' => $this->order,
                'subjectText' => $this->subjectText,
                'messageText' => $this->messageText,
                'includeSummary' => $this->includeSummary,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
