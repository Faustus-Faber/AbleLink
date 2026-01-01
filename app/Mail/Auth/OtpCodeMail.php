<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class OtpCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly string $context,
        public readonly int $expiresInMinutes
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Ablelink OTP Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.otp',
            with: [
                'code' => $this->code,
                'context' => $this->context,
                'expiresInMinutes' => $this->expiresInMinutes,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
