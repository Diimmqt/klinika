<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $roleLabel,
        public readonly string $initialPassword,
        public readonly string $activationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[KLINIKA] Aktivasi Akun Anda — {$this->roleLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-activation',
        );
    }
}
