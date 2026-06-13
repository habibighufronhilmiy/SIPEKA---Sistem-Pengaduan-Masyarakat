<?php

namespace App\Http\Controllers;

use App\Mail\PengumumanMail;
use App\Models\AuditLog;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tipe' => 'required|in:pengumuman,jadwal,kegiatan,pembangunan',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
            'status' => 'required|in:draft,publish',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengumuman', 'public');
        }

        $pengumuman = Pengumuman::create($validated);

        if ($pengumuman->status === 'publish') {
            $this->kirimEmailPengumuman($pengumuman);
        }

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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tipe' => 'required|in:pengumuman,jadwal,kegiatan,pembangunan',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
            'status' => 'required|in:draft,publish',
        ]);

        if ($request->hasFile('foto')) {
            if ($pengumuman->foto) {
                Storage::disk('public')->delete($pengumuman->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pengumuman', 'public');
        }

        $pengumuman->update($validated);

        if ($pengumuman->status === 'publish') {
            $this->kirimEmailPengumuman($pengumuman);
        }

        AuditLog::log('Mengupdate pengumuman', 'Mengupdate pengumuman: ' . $pengumuman->judul, $pengumuman, 'pengumuman');

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    private function kirimEmailPengumuman(Pengumuman $pengumuman): void
    {
        $users = User::whereNotNull('email')->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new PengumumanMail($pengumuman));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email pengumuman ke ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->foto) {
            Storage::disk('public')->delete($pengumuman->foto);
        }
        AuditLog::log('Menghapus pengumuman', 'Menghapus pengumuman: ' . $pengumuman->judul, null, 'pengumuman');
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
