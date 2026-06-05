<?php

namespace App\Services;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $endpoint = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY', '');
    }

    public function verifikasiLaporan(Pengaduan $pengaduan): array
    {
        if (empty($this->apiKey)) {
            return ['status' => 'diverifikasi', 'alasan' => 'AI tidak dikonfigurasi, laporan diverifikasi otomatis.'];
        }

        $laporanTerbaru = Pengaduan::where('id', '!=', $pengaduan->id)
            ->whereIn('status', ['menunggu', 'diverifikasi', 'diproses', 'selesai'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($p) => "- Judul: {$p->judul}\n  Isi: {$p->isi_laporan}\n  Status: {$p->status}")
            ->implode("\n\n");

        $systemPrompt = <<<SYSTEM
Anda adalah sistem verifikasi pengaduan masyarakat. Tugas Anda memeriksa apakah laporan ini valid atau tidak.

INSTRUKSI:
1. Periksa apakah laporan ini adalah DUPLIKAT dari laporan yang sudah ada (judul/isi/lokasi yang sangat mirip). TOLAK jika duplikat.
2. Periksa apakah laporan ini mengandung SPAM, ujaran kebencian, konten tidak pantas, atau tidak masuk akal. TOLAK jika ya.
3. Periksa apakah isi laporan adalah teks yang masuk akal sebagai pengaduan masyarakat. TOLAK jika isinya hanya path file (misal: C:\Users\...), URL, karakter acak, atau konten yang tidak relevan dengan pengaduan.
4. Periksa apakah laporan ini masuk akal sebagai pengaduan masyarakat

RESPON ANDA HARUS BERUPA JSON SAJA (tanpa markdown, tanpa format lain):
{
  "status": "diverifikasi" atau "ditolak",
  "alasan": "Penjelasan singkat dalam Bahasa Indonesia mengapa laporan ini diterima atau ditolak",
  "confidence": 0.0-1.0
}
SYSTEM;

        $userPrompt = <<<USER
LAPORAN BARU:
- Judul: {$pengaduan->judul}
- Kategori: {$pengaduan->kategori->nama_kategori}
- Isi: {$pengaduan->isi_laporan}
- Lokasi: {$pengaduan->lokasi}

LAPORAN SEBELUMNYA (untuk cek duplikasi):
{$laporanTerbaru}
USER;

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->post($this->endpoint, [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.1,
                    'response_format' => ['type' => 'json_object'],
                ]);

            if (!$response->successful()) {
                Log::warning('Groq API error: ' . $response->body());
                return ['status' => 'menunggu', 'alasan' => 'Gagal verifikasi AI, laporan perlu diverifikasi manual oleh petugas.'];
            }

            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '{}';
            $text = trim(str_replace(['```json', '```'], '', $text));

            $result = json_decode($text, true);

            if (!isset($result['status']) || !in_array($result['status'], ['diverifikasi', 'ditolak'])) {
                return ['status' => 'menunggu', 'alasan' => 'Format respons AI tidak valid, laporan perlu diverifikasi manual oleh petugas.'];
            }

            return [
                'status' => $result['status'],
                'alasan' => $result['alasan'] ?? 'Laporan telah diverifikasi oleh sistem AI.',
            ];

        } catch (\Exception $e) {
            Log::error('Groq API exception: ' . $e->getMessage());
            return ['status' => 'menunggu', 'alasan' => 'Error koneksi AI, laporan perlu diverifikasi manual oleh petugas.'];
        }
    }
}
