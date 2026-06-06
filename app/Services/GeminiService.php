<?php

namespace App\Services;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        $pengaduan->load('media');

        $fotos = $pengaduan->media->where('file_type', 'foto');
        $hasFotos = $fotos->count() > 0;

        $laporanTerbaru = Pengaduan::where('id', '!=', $pengaduan->id)
            ->where('id_kategori', $pengaduan->id_kategori)
            ->whereIn('status', ['menunggu', 'diverifikasi', 'diproses', 'selesai'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($p) => "- Judul: {$p->judul}\n  Isi: {$p->isi_laporan}\n  Status: {$p->status}")
            ->implode("\n\n");

        $instruksiFoto = $hasFotos
            ? "\n5. Periksa apakah FOTO yang dilampirkan SELARAS dengan judul dan isi laporan. TOLAK jika foto tidak relevan (foto random, foto tempat lain, screenshot chat, selfi, dll)."
            : '';

        $systemPrompt = <<<SYSTEM
Anda adalah sistem verifikasi pengaduan masyarakat. Tugas Anda memeriksa apakah laporan ini valid atau tidak.

INSTRUKSI:
1. Periksa apakah laporan ini adalah DUPLIKAT dari laporan SEKATEGORI yang sudah ada (judul/isi/lokasi yang sangat mirip). TOLAK jika duplikat.
2. Periksa apakah laporan ini mengandung SPAM, ujaran kebencian, konten tidak pantas, atau tidak masuk akal. TOLAK jika ya.
3. Periksa apakah isi laporan adalah teks yang masuk akal sebagai pengaduan masyarakat. TOLAK jika isinya hanya path file (misal: C:\\Users\\...), URL, karakter acak, atau konten yang tidak relevan dengan pengaduan.
4. Periksa apakah laporan ini masuk akal sebagai pengaduan masyarakat{$instruksiFoto}

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

LAPORAN SEBELUMNYA (hanya dari kategori yang sama, untuk cek duplikasi):
{$laporanTerbaru}
USER;

        try {
            $model = $hasFotos ? 'llama-3.2-11b-vision-preview' : 'llama-3.3-70b-versatile';

            if ($hasFotos) {
                $content = [['type' => 'text', 'text' => $userPrompt]];
                foreach ($fotos as $media) {
                    $path = $media->file_path;
                    if (!Storage::disk('public')->exists($path)) {
                        continue;
                    }
                    $imageData = Storage::disk('public')->get($path);
                    $mime = $this->getImageMime($path);
                    $base64 = base64_encode($imageData);
                    $content[] = [
                        'type' => 'image_url',
                        'image_url' => ['url' => "data:{$mime};base64,{$base64}"],
                    ];
                }

                $body = [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $content],
                    ],
                    'temperature' => 0.1,
                ];
            } else {
                $body = [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.1,
                    'response_format' => ['type' => 'json_object'],
                ];
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->post($this->endpoint, $body);

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

    private function getImageMime(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };
    }
}
