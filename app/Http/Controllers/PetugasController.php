<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Notifikasi;
use App\Models\Pengaduan;
use App\Models\RiwayatPengaduan;
use App\Models\Tanggapan;
use App\Mail\PengaduanStatusMail;
use App\Mail\TanggapanMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with(['user', 'kategori']);

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $pengaduans = $query->latest()->paginate(10);
        $kategoris = \App\Models\Kategori::all();

        return view('petugas.pengaduan.index', compact('pengaduans', 'kategoris'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user', 'kategori', 'media', 'riwayats', 'tanggapans.petugas', 'tanggapans.user']);
        return view('petugas.pengaduan.show', compact('pengaduan'));
    }

    public function verifikasi(Request $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->status !== 'menunggu') {
            return back()->with('error', 'Pengaduan sudah diverifikasi oleh AI dan tidak bisa diubah manual.');
        }

        $request->validate([
            'status' => 'required|in:diverifikasi,ditolak',
            'alasan' => 'required_if:status,ditolak|string',
        ]);

        $pengaduan->update([
            'status' => $request->status,
            'id_petugas' => Auth::id(),
        ]);

        RiwayatPengaduan::create([
            'id_pengaduan' => $pengaduan->id,
            'status' => $request->status,
            'keterangan' => $request->alasan ?? 'Pengaduan diverifikasi',
        ]);

        $judulNotif = $request->status === 'diverifikasi' ? 'Pengaduan Diverifikasi' : 'Pengaduan Ditolak';
        $pesanNotif = $request->status === 'diverifikasi'
            ? 'Pengaduan Anda telah diverifikasi dan akan segera diproses.'
            : 'Pengaduan Anda ditolak. Alasan: ' . $request->alasan;
        $tipeNotif = $request->status === 'diverifikasi' ? 'success' : 'error';

        Notifikasi::create([
            'id_user' => $pengaduan->id_user,
            'id_pengaduan' => $pengaduan->id,
            'judul' => $judulNotif,
            'pesan' => $pesanNotif,
            'tipe' => $tipeNotif,
        ]);

        $pengaduan->load('user');
        if ($pengaduan->user->email) {
            try {
                Mail::to($pengaduan->user->email)->send(new PengaduanStatusMail(
                    $pengaduan,
                    $request->status === 'diverifikasi' ? 'Diverifikasi' : 'Ditolak',
                    $pesanNotif
                ));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email verifikasi petugas: ' . $e->getMessage());
            }
        }

        AuditLog::log('Verifikasi pengaduan', $request->status === 'diverifikasi'
            ? 'Menerima pengaduan: ' . $pengaduan->judul
            : 'Menolak pengaduan: ' . $pengaduan->judul . ' (Alasan: ' . $request->alasan . ')',
            $pengaduan, 'pengaduan');

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function proses(Pengaduan $pengaduan)
    {
        if ($pengaduan->status !== 'diverifikasi') {
            return back()->with('error', 'Pengaduan harus diverifikasi terlebih dahulu.');
        }

        if ($pengaduan->id_petugas && (int) $pengaduan->id_petugas !== (int) Auth::id()) {
            return back()->with('error', 'Pengaduan ini sedang ditangani oleh petugas lain.');
        }

        $pengaduan->update([
            'status' => 'diproses',
            'id_petugas' => Auth::id(),
        ]);

        RiwayatPengaduan::create([
            'id_pengaduan' => $pengaduan->id,
            'status' => 'diproses',
            'keterangan' => 'Pengaduan sedang diproses',
        ]);

        Notifikasi::create([
            'id_user' => $pengaduan->id_user,
            'id_pengaduan' => $pengaduan->id,
            'judul' => 'Pengaduan Diproses',
            'pesan' => 'Pengaduan Anda sedang dalam proses penanganan.',
            'tipe' => 'info',
        ]);

        $pengaduan->load('user');
        if ($pengaduan->user->email) {
            try {
                Mail::to($pengaduan->user->email)->send(new PengaduanStatusMail(
                    $pengaduan,
                    'Diproses',
                    'Pengaduan Anda sedang dalam proses penanganan oleh petugas.'
                ));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email diproses: ' . $e->getMessage());
            }
        }

        AuditLog::log('Proses pengaduan', 'Memproses pengaduan: ' . $pengaduan->judul, $pengaduan, 'pengaduan');

        return back()->with('success', 'Pengaduan sedang diproses.');
    }

    public function selesai(Request $request, Pengaduan $pengaduan)
    {
        if ((int) $pengaduan->id_petugas !== (int) Auth::id()) {
            return back()->with('error', 'Anda tidak ditugaskan untuk menangani pengaduan ini.');
        }

        if ($pengaduan->status !== 'diproses') {
            return back()->with('error', 'Pengaduan harus dalam proses terlebih dahulu.');
        }

        $request->validate([
            'isi_tanggapan' => 'required|string',
            'bukti_foto' => 'nullable|image|max:5120',
        ]);

        $pengaduan->update(['status' => 'selesai']);

        $buktiPath = null;
        if ($request->hasFile('bukti_foto')) {
            $buktiPath = $request->file('bukti_foto')->store('pengaduan/bukti', 'public');
        }

        Tanggapan::create([
            'id_pengaduan' => $pengaduan->id,
            'id_petugas' => Auth::id(),
            'tgl_tanggapan' => now(),
            'isi_tanggapan' => $request->isi_tanggapan,
            'bukti_foto' => $buktiPath,
        ]);

        RiwayatPengaduan::create([
            'id_pengaduan' => $pengaduan->id,
            'status' => 'selesai',
            'keterangan' => 'Pengaduan selesai ditangani',
        ]);

        Notifikasi::create([
            'id_user' => $pengaduan->id_user,
            'id_pengaduan' => $pengaduan->id,
            'judul' => 'Pengaduan Selesai',
            'pesan' => 'Pengaduan Anda telah selesai ditangani. Silakan berikan rating.',
            'tipe' => 'success',
        ]);

        $pengaduan->load('user');
        if ($pengaduan->user->email) {
            try {
                Mail::to($pengaduan->user->email)->send(new PengaduanStatusMail(
                    $pengaduan,
                    'Selesai',
                    'Pengaduan Anda telah selesai ditangani. Silakan berikan rating melalui aplikasi.'
                ));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email selesai: ' . $e->getMessage());
            }

            try {
                Mail::to($pengaduan->user->email)->send(new TanggapanMail($pengaduan, $request->isi_tanggapan));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email tanggapan: ' . $e->getMessage());
            }
        }

        AuditLog::log('Selesaikan pengaduan', 'Menyelesaikan pengaduan: ' . $pengaduan->judul, $pengaduan, 'pengaduan');

        return back()->with('success', 'Pengaduan selesai ditangani.');
    }
}
