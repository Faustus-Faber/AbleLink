<?php
//F15 - Akida Lisi
namespace App\Mail\Emergency;

use App\Models\Emergency\EmergencySosEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmergencySosAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly EmergencySosEvent $event)
    {
    }

    public function envelope(): Envelope
    {
        $name = $this->event->user?->name ?? 'A user';

        return new Envelope(
            subject: "Emergency SOS: {$name} needs help",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sos-alert',
            with: [
                'event' => $this->event,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

