<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PetugasAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pengaduan $pengaduan,
        public string $namaPetugas,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penugasan Baru - ' . $this->pengaduan->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.petugas-assigned',
        );
    }
}
