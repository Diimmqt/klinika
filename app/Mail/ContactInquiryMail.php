<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public const TOPIC_LABELS = [
        'demo' => 'Jadwalkan Demo Produk',
        'fitur' => 'Pertanyaan Fitur & Alur Pelayanan',
        'satusehat' => 'Kesiapan Integrasi SATUSEHAT',
        'akses' => 'Bantuan Akses & Akun Klinik',
        'lainnya' => 'Lainnya',
    ];

    /**
     * @param  array{name: string, clinic_name: string, email: string, phone: ?string, topic: string, message: string, submitted_at: string}  $inquiry
     */
    public function __construct(
        public readonly array $inquiry,
    ) {}

    public function envelope(): Envelope
    {
        $topicLabel = self::TOPIC_LABELS[$this->inquiry['topic']] ?? $this->inquiry['topic'];

        return new Envelope(
            subject: "[KLINIKA] {$topicLabel} — {$this->inquiry['clinic_name']}",
            replyTo: [
                new Address($this->inquiry['email'], $this->inquiry['name']),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-inquiry',
            with: [
                'inquiry' => $this->inquiry,
                'topicLabel' => self::TOPIC_LABELS[$this->inquiry['topic']] ?? $this->inquiry['topic'],
            ],
        );
    }
}
