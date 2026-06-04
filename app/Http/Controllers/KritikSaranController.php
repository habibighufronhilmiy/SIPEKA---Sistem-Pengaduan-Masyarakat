<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\KritikSaran;
use App\Models\Notifikasi;
use App\Mail\KritikSaranMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class KritikSaranController extends Controller
{
    public function index()
    {
        $kritikSarans = KritikSaran::where('id_user', Auth::id())
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('masyarakat.kritik_saran.index', compact('kritikSarans'));
    }

    public function create()
    {
        return view('masyarakat.kritik_saran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_kritik' => 'required|string',
            'kategori' => 'required|in:kritik,saran,aspirasi',
        ]);

        $kritikSaran = KritikSaran::create([
            'id_user' => Auth::id(),
            'judul' => $validated['judul'],
            'isi_kritik' => $validated['isi_kritik'],
            'kategori' => $validated['kategori'],
        ]);

        $petugases = \App\Models\User::where('role', 'petugas')->get();
        foreach ($petugases as $petugas) {
            Notifikasi::create([
                'id_user' => $petugas->id,
                'judul' => 'Kritik & Saran Baru',
                'pesan' => 'Ada ' . $validated['kategori'] . ' baru dari ' . Auth::user()->name . ': ' . $validated['judul'],
                'tipe' => 'info',
            ]);

            if ($petugas->email) {
                Mail::to($petugas->email)->send(new KritikSaranMail($kritikSaran, 'baru'));
            }
        }

        Notifikasi::create([
            'id_user' => Auth::id(),
            'judul' => 'Kritik & Saran Terkirim',
            'pesan' => '' . ucfirst($validated['kategori']) . ' Anda berhasil dikirim dan akan ditanggapi oleh petugas.',
            'tipe' => 'success',
        ]);

        $user = Auth::user();
        if ($user->email) {
            Mail::to($user->email)->send(new KritikSaranMail($kritikSaran, 'baru'));
        }

        return redirect()->route('kritik-saran.index')
            ->with('success', ucfirst($validated['kategori']) . ' berhasil dikirim. Terima kasih atas partisipasi Anda!');
    }

    public function show(KritikSaran $kritikSaran)
    {
        if ($kritikSaran->id_user !== Auth::id()) {
            abort(403);
        }

        $kritikSaran->load(['user', 'petugas']);
        return view('masyarakat.kritik_saran.show', compact('kritikSaran'));
    }

    public function kelolaIndex()
    {
        $kritikSarans = KritikSaran::with(['user', 'petugas'])
            ->latest()
            ->paginate(15);

        $menunggu = KritikSaran::where('status', 'menunggu')->count();
        $ditanggapi = KritikSaran::where('status', 'ditanggapi')->count();
        $selesai = KritikSaran::where('status', 'selesai')->count();

        return view('kritik_saran.index', compact('kritikSarans', 'menunggu', 'ditanggapi', 'selesai'));
    }

    public function kelolaShow(KritikSaran $kritikSaran)
    {
        $kritikSaran->load(['user', 'petugas']);
        return view('kritik_saran.show', compact('kritikSaran'));
    }

    public function tanggapan(Request $request, KritikSaran $kritikSaran)
    {
        $validated = $request->validate([
            'tanggapan' => 'required|string',
            'status' => 'required|in:ditanggapi,selesai',
        ]);

        $kritikSaran->update([
            'tanggapan' => $validated['tanggapan'],
            'status' => $validated['status'],
            'id_petugas' => Auth::id(),
            'tanggapan_at' => now(),
        ]);

        Notifikasi::create([
            'id_user' => $kritikSaran->id_user,
            'judul' => 'Tanggapan ' . ucfirst($kritikSaran->kategori),
            'pesan' => Auth::user()->name . ' telah memberikan tanggapan untuk ' . $kritikSaran->kategori . ' Anda: "' . $kritikSaran->judul . '"',
            'tipe' => 'info',
        ]);

        $kritikSaran->load('user');
        if ($kritikSaran->user->email) {
            Mail::to($kritikSaran->user->email)->send(new KritikSaranMail($kritikSaran, 'tanggapan'));
        }

        AuditLog::log('Menanggapi kritik/saran', 'Memberi tanggapan untuk ' . $kritikSaran->kategori . ': ' . $kritikSaran->judul, $kritikSaran, 'kritik_saran');

        return redirect()->route('kelola-kritik-saran.show', $kritikSaran)
            ->with('success', 'Tanggapan berhasil diberikan.');
    }
}
