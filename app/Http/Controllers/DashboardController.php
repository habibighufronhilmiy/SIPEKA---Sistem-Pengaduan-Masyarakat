<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Pengumuman;
use App\Models\Voting;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'petugas') {
            return $this->petugasDashboard();
        }

        return $this->masyarakatDashboard();
    }

    protected function masyarakatDashboard()
    {
        $pengaduans = Pengaduan::where('id_user', Auth::id())
            ->with('kategori')
            ->latest()
            ->take(5)
            ->get();

        $total = Pengaduan::where('id_user', Auth::id())->count();
        $diproses = Pengaduan::where('id_user', Auth::id())->whereIn('status', ['diverifikasi', 'diproses'])->count();
        $selesai = Pengaduan::where('id_user', Auth::id())->where('status', 'selesai')->count();
        $menunggu = Pengaduan::where('id_user', Auth::id())->where('status', 'menunggu')->count();

        $pengumumen = Pengumuman::where('status', 'publish')->latest()->take(3)->get();
        $votings = Voting::where('status', 'aktif')->with('pilihans')->get();

        return view('masyarakat.dashboard', compact(
            'pengaduans', 'total', 'diproses', 'selesai', 'menunggu', 'pengumumen', 'votings'
        ));
    }

    protected function petugasDashboard()
    {
        $userId = Auth::id();

        $totalTugas = Pengaduan::where('id_petugas', $userId)->count();
        $menunggu = Pengaduan::where('id_petugas', $userId)->where('status', 'menunggu')->count();
        $diproses = Pengaduan::where('id_petugas', $userId)->whereIn('status', ['diverifikasi', 'diproses'])->count();
        $selesai = Pengaduan::where('id_petugas', $userId)->where('status', 'selesai')->count();

        $pengaduans = Pengaduan::with(['user', 'kategori'])
            ->where('id_petugas', $userId)
            ->latest()
            ->take(10)
            ->get();

        $semuaPengaduan = Pengaduan::with(['user', 'kategori'])
            ->whereIn('status', ['menunggu', 'diverifikasi'])
            ->latest()
            ->take(10)
            ->get();

        return view('petugas.dashboard', compact(
            'totalTugas', 'menunggu', 'diproses', 'selesai', 'pengaduans', 'semuaPengaduan'
        ));
    }

    protected function adminDashboard()
    {
        $totalPengaduan = Pengaduan::count();
        $pengaduanHariIni = Pengaduan::whereDate('created_at', today())->count();
        $pengaduanSelesai = Pengaduan::where('status', 'selesai')->count();
        $pengaduanDiproses = Pengaduan::whereIn('status', ['diverifikasi', 'diproses'])->count();
        $pengaduanDitolak = Pengaduan::where('status', 'ditolak')->count();
        $totalUser = \App\Models\User::count();
        $totalPetugas = \App\Models\User::where('role', 'petugas')->count();

        $pengaduans = Pengaduan::with(['user', 'kategori', 'petugas'])
            ->latest()
            ->take(10)
            ->get();

        $statistikBulanan = Pengaduan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $statistikKategori = Kategori::withCount('pengaduans')->get();

        $pengaduanMap = Pengaduan::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('draft', false)
            ->with('kategori')
            ->latest()
            ->get()
            ->map(fn($p) => [
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                'judul' => $p->judul,
                'status' => $p->status,
                'kategori' => $p->kategori->nama_kategori ?? '-',
            ]);

        return view('admin.dashboard', compact(
            'totalPengaduan', 'pengaduanHariIni', 'pengaduanSelesai',
            'pengaduanDiproses', 'pengaduanDitolak', 'totalUser', 'totalPetugas',
            'pengaduans', 'statistikBulanan', 'statistikKategori', 'pengaduanMap'
        ));
    }
}
