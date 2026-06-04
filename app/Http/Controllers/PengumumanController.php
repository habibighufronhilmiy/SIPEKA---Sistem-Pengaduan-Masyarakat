<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumen = Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumumen'))->with('title', 'Pengumuman');
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:pengumuman,jadwal,kegiatan,pembangunan',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
            'status' => 'required|in:draft,publish',
        ]);

        $pengumuman = Pengumuman::create($validated);

        AuditLog::log('Membuat pengumuman', 'Membuat pengumuman: ' . $pengumuman->judul, $pengumuman, 'pengumuman');

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:pengumuman,jadwal,kegiatan,pembangunan',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
            'status' => 'required|in:draft,publish',
        ]);

        $pengumuman->update($validated);

        AuditLog::log('Mengupdate pengumuman', 'Mengupdate pengumuman: ' . $pengumuman->judul, $pengumuman, 'pengumuman');

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        AuditLog::log('Menghapus pengumuman', 'Menghapus pengumuman: ' . $pengumuman->judul, null, 'pengumuman');
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
