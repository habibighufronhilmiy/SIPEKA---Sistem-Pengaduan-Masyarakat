<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengaduanCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pengaduan $pengaduan,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengaduan Diterima - ' . $this->pengaduan->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengaduan-created',
        );
    }
}
