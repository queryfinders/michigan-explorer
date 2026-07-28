<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifySubscription extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Subscriber $subscriber;
    public string $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->verificationUrl = route('newsletter.verify', ['token' => $subscriber->verification_token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Content|Envelope
    {
        return new Envelope(
            subject: 'Confirm your Michigan Explorer Newsletter Subscription',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.verify',
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
