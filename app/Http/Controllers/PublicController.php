<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pengumuman;
use App\Models\Voting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function tracking()
    {
        return view('public.tracking');
    }

    public function trackingCek(Request $request)
    {
        $request->validate(['kode' => 'required|string|max:20']);

        $pengaduan = Pengaduan::with(['kategori', 'media', 'riwayats', 'tanggapans.petugas', 'rating'])
            ->where('kode_tracking', $request->kode)
            ->first();

        if (!$pengaduan) {
            return back()->with('error', 'Kode tracking tidak ditemukan.')->withInput();
        }

        return view('public.tracking', compact('pengaduan'));
    }

    public function pengumuman()
    {
        $pengumumen = Pengumuman::where('status', 'publish')->latest()->paginate(12);
        return view('public.pengumuman', compact('pengumumen'));
    }

    public function voting()
    {
        $votings = Voting::with(['pilihans.users'])
            ->withCount('pilihans')
            ->latest()
            ->paginate(10);

        return view('public.voting', compact('votings'));
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function tentang()
    {
        $totalPengaduan = Pengaduan::count();
        $selesai = Pengaduan::where('status', 'selesai')->count();
        $totalUser = \App\Models\User::count();
        $totalKritik = \App\Models\KritikSaran::count();
        $totalPetugas = \App\Models\User::where('role', 'petugas')->count();

        return view('public.tentang', compact('totalPengaduan', 'selesai', 'totalUser', 'totalKritik', 'totalPetugas'));
    }
}
