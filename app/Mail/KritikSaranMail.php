<?php

namespace App\Mail;

use App\Models\KritikSaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KritikSaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public KritikSaran $kritikSaran,
        public string $tipe, // 'baru' or 'tanggapan'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->tipe === 'baru'
            ? 'Kritik & Saran Baru dari ' . $this->kritikSaran->user->name
            : 'Tanggapan untuk ' . ucfirst($this->kritikSaran->kategori) . ' Anda';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kritik-saran',
        );
    }
}
