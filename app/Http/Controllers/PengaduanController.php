<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Kategori;
use App\Models\MediaPengaduan;
use App\Models\Notifikasi;
use App\Models\Pengaduan;
use App\Models\RiwayatPengaduan;
use App\Models\Tanggapan;
use App\Models\Rating;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\PengaduanCreatedMail;
use App\Mail\PengaduanStatusMail;
use App\Mail\TanggapanMail;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::where('id_user', Auth::id())
            ->with('kategori', 'media');

        if ($request->filled('filter')) {
            if ($request->filter === 'draft') {
                $query->where('draft', true);
            } elseif ($request->filter === 'submitted') {
                $query->where('draft', false);
            }
        }

        $pengaduans = $query->latest()->paginate(10);
        $filter = $request->filter;

        return view('masyarakat.pengaduan.index', compact('pengaduans', 'filter'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('masyarakat.pengaduan.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'draft' => 'boolean',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,avi|max:20480',
        ]);

        $validated['id_user'] = Auth::id();
        $validated['kode_tracking'] = 'SPK-' . strtoupper(\Str::random(8));
        $validated['status'] = 'menunggu';
        $validated['draft'] = $request->boolean('draft');

        $pengaduan = Pengaduan::create($validated);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('pengaduan/media', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'foto';
                MediaPengaduan::create([
                    'id_pengaduan' => $pengaduan->id,
                    'file_path' => $path,
                    'file_type' => $type,
                ]);
            }
        }

        if ($pengaduan->draft) {
            return redirect()->route('pengaduan.index')
                ->with('success', 'Draft pengaduan berhasil disimpan.');
        }

        $this->kirimEmailPengaduanDibuat($pengaduan);

        $this->prosesAiVerifikasi($pengaduan);

        AuditLog::log('Membuat pengaduan', 'Membuat pengaduan: ' . $pengaduan->judul, $pengaduan, 'pengaduan');

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dibuat.');
    }

    public function show(Pengaduan $pengaduan)
    {
        if (Auth::user()->role === 'masyarakat' && $pengaduan->id_user !== Auth::id()) {
            abort(403);
        }

        $pengaduan->load(['kategori', 'media', 'riwayats', 'tanggapans.petugas', 'tanggapans.user', 'rating', 'user']);
        return view('masyarakat.pengaduan.show', compact('pengaduan'));
    }

    public function edit(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id() || $pengaduan->status !== 'menunggu') {
            abort(403);
        }

        $kategoris = Kategori::all();
        return view('masyarakat.pengaduan.edit', compact('pengaduan', 'kategoris'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id() || $pengaduan->status !== 'menunggu') {
            abort(403);
        }

        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $pengaduan->update($validated);

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function submitDraft(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id() || !$pengaduan->draft) {
            abort(403);
        }

        $pengaduan->update(['draft' => false]);

        $pengaduan->load('user');

        $this->kirimEmailPengaduanDibuat($pengaduan);

        $this->prosesAiVerifikasi($pengaduan);

        AuditLog::log('Submit draft', 'Mengirim draft pengaduan: ' . $pengaduan->judul, $pengaduan, 'pengaduan');

        return redirect()->route('pengaduan.index')
            ->with('success', 'Draft berhasil dikirim dan sedang diproses.');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id() || $pengaduan->status !== 'menunggu') {
            abort(403);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }

    public function downloadPdf(Pengaduan $pengaduan)
    {
        $user = Auth::user();
        $isOwner = $pengaduan->id_user === $user->id;
        $isAdmin = $user->role === 'admin';
        $isAssignedPetugas = $user->role === 'petugas' && $pengaduan->id_petugas === $user->id;

        if (!$isOwner && !$isAdmin && !$isAssignedPetugas) {
            abort(403);
        }

        try {
            if (!app()->environment('local')) {
                ini_set('memory_limit', '256M');
                set_time_limit(120);
            }

            $pengaduan->load(['user', 'kategori', 'petugas', 'tanggapans.petugas', 'tanggapans.user', 'riwayats']);

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pengaduan.pdf', compact('pengaduan'));
            return $pdf->download('pengaduan-' . $pengaduan->kode_tracking . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Download Error: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
                'user_id' => $user->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->with('error', 'Gagal mengunduh PDF. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function storeTanggapan(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        $pengaduan->tanggapans()->create([
            'id_petugas' => Auth::id(),
            'tgl_tanggapan' => now(),
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        $this->sendTanggapanNotification($pengaduan, $request->isi_tanggapan);

        return back()->with('success', 'Tanggapan berhasil dikirim.');
    }

    public function storeTanggapanMasyarakat(Request $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        $pengaduan->tanggapans()->create([
            'id_user' => Auth::id(),
            'tgl_tanggapan' => now(),
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        $penerima = $pengaduan->id_petugas ?? \App\Models\User::where('role', 'petugas')->value('id');
        if ($penerima) {
            Notifikasi::create([
                'id_user' => $penerima,
                'id_pengaduan' => $pengaduan->id,
                'judul' => 'Tanggapan Baru dari Masyarakat',
                'pesan' => Auth::user()->name . ' memberikan tanggapan pada pengaduan: ' . $pengaduan->judul,
                'tipe' => 'info',
            ]);
        }

        return back()->with('success', 'Tanggapan berhasil dikirim.');
    }

    private function kirimEmailPengaduanDibuat(Pengaduan $pengaduan): void
    {
        $pengaduan->load('user', 'kategori');

        if ($pengaduan->user->email) {
            try {
                Mail::to($pengaduan->user->email)->send(new PengaduanCreatedMail($pengaduan));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email pengaduan dibuat: ' . $e->getMessage());
            }
        }
    }

    private function prosesAiVerifikasi(Pengaduan $pengaduan): void
    {
        RiwayatPengaduan::create([
            'id_pengaduan' => $pengaduan->id,
            'status' => 'menunggu',
            'keterangan' => 'Pengaduan berhasil dibuat',
        ]);

        $pengaduan->load('kategori');

        $gemini = app(GeminiService::class);
        $hasil = $gemini->verifikasiLaporan($pengaduan);

        $pengaduan->update(['status' => $hasil['status']]);

        RiwayatPengaduan::create([
            'id_pengaduan' => $pengaduan->id,
            'status' => $hasil['status'],
            'keterangan' => $hasil['alasan'],
        ]);

        $user = Auth::user();
        if ($hasil['status'] === 'diverifikasi') {
            Notifikasi::create([
                'id_user' => $user->id,
                'id_pengaduan' => $pengaduan->id,
                'judul' => 'Pengaduan Diverifikasi',
                'pesan' => 'Pengaduan Anda telah diverifikasi oleh sistem AI dan akan segera diproses petugas.',
                'tipe' => 'success',
            ]);

            if ($user->email) {
                try {
                    Mail::to($user->email)->send(new PengaduanStatusMail(
                        $pengaduan,
                        'Diverifikasi',
                        'Pengaduan Anda telah diverifikasi oleh sistem AI dan akan segera diproses petugas.'
                    ));
                } catch (\Exception $e) {
                    Log::error('Gagal kirim email verifikasi: ' . $e->getMessage());
                }
            }
        } else {
            Notifikasi::create([
                'id_user' => $user->id,
                'id_pengaduan' => $pengaduan->id,
                'judul' => 'Pengaduan Ditolak',
                'pesan' => 'Maaf, pengaduan Anda ditolak oleh sistem AI. Alasan: ' . $hasil['alasan'],
                'tipe' => 'error',
            ]);

            if ($user->email) {
                try {
                    Mail::to($user->email)->send(new PengaduanStatusMail(
                        $pengaduan,
                        'Ditolak',
                        'Maaf, pengaduan Anda ditolak. Alasan: ' . $hasil['alasan']
                    ));
                } catch (\Exception $e) {
                    Log::error('Gagal kirim email ditolak: ' . $e->getMessage());
                }
            }
        }
    }

    private function sendTanggapanNotification(Pengaduan $pengaduan, string $isiTanggapan): void
    {
        $pengaduan->load('user');

        Notifikasi::create([
            'id_user' => $pengaduan->id_user,
            'id_pengaduan' => $pengaduan->id,
            'judul' => 'Tanggapan Baru',
            'pesan' => 'Petugas memberikan tanggapan baru pada pengaduan Anda.',
            'tipe' => 'info',
        ]);

        if ($pengaduan->user->email) {
            try {
                Mail::to($pengaduan->user->email)->send(new TanggapanMail($pengaduan, $isiTanggapan));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email tanggapan: ' . $e->getMessage());
            }
        }
    }

    public function storeRating(Request $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->status !== 'selesai' || $pengaduan->id_user !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        $pengaduan->rating()->updateOrCreate(
            ['id_user' => Auth::id()],
            ['rating' => $request->rating, 'komentar' => $request->komentar]
        );

        return back()->with('success', 'Rating berhasil diberikan.');
    }
}
