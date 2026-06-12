<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengaduanStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pengaduan $pengaduan,
        public string $statusLabel,
        public string $pesan,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembaruan Status Pengaduan - ' . $this->statusLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengaduan-status',
        );
    }
}
