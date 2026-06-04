<?php

namespace App\Services;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('app.gemini_api_key', env('GEMINI_API_KEY', ''));
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

        $prompt = <<<PROMPT
Anda adalah sistem verifikasi pengaduan masyarakat. Tugas Anda memeriksa apakah laporan ini valid atau tidak.

LAPORAN BARU:
- Judul: {$pengaduan->judul}
- Kategori: {$pengaduan->kategori->nama_kategori}
- Isi: {$pengaduan->isi_laporan}
- Lokasi: {$pengaduan->lokasi}

LAPORAN SEBELUMNYA (untuk cek duplikasi):
{$laporanTerbaru}

INSTRUKSI:
1. Periksa apakah laporan ini adalah DUPLIKAT dari laporan yang sudah ada (judul/isi/ lokasi yang sangat mirip)
2. Periksa apakah laporan ini mengandung SPAM, ujaran kebencian, konten tidak pantas, atau tidak masuk akal
3. Periksa apakah laporan ini masuk akal sebagai pengaduan masyarakat

RESPON ANDA HARUS BERUPA JSON SAJA (tanpa markdown, tanpa format lain):
{
  "status": "diverifikasi" atau "ditolak",
  "alasan": "Penjelasan singkat dalam Bahasa Indonesia mengapa laporan ini diterima atau ditolak",
  "confidence": 0.0-1.0
}
PROMPT;

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->endpoint}?key={$this->apiKey}", [
                    'contents' => [
                        [
                            'parts' => [['text' => $prompt]]
                        ]
                    ]
                ]);

            if (!$response->successful()) {
                Log::warning('Gemini API error: ' . $response->body());
                return ['status' => 'diverifikasi', 'alasan' => 'Gagal verifikasi AI, laporan diverifikasi manual.'];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = trim(str_replace(['```json', '```'], '', $text));

            $result = json_decode($text, true);

            if (!isset($result['status']) || !in_array($result['status'], ['diverifikasi', 'ditolak'])) {
                return ['status' => 'diverifikasi', 'alasan' => 'Format respons AI tidak valid, laporan diverifikasi otomatis.'];
            }

            return [
                'status' => $result['status'],
                'alasan' => $result['alasan'] ?? 'Laporan telah diverifikasi oleh sistem AI.',
            ];

        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
            return ['status' => 'diverifikasi', 'alasan' => 'Error koneksi AI, laporan diverifikasi otomatis.'];
        }
    }
}
