<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\User;
use App\Mail\PetugasAssignedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        return view('admin.users.create');
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:masyarakat,petugas,admin',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        AuditLog::log('Membuat user', 'Membuat user baru: ' . $user->name . ' (' . $user->role . ')', $user, 'user');

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:masyarakat,petugas,admin',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        AuditLog::log('Mengupdate user', 'Mengupdate user: ' . $user->name, $user, 'user');

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function usersDestroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        AuditLog::log('Menghapus user', 'Menghapus user: ' . $user->name . ' (' . $user->email . ')', null, 'user');
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function kategoris()
    {
        $kategoris = Kategori::withCount('pengaduans')->latest()->paginate(10);
        return view('admin.kategoris.index', compact('kategoris'));
    }

    public function kategorisStore(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:kategoris,slug',
            'icon' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::create($validated);

        AuditLog::log('Membuat kategori', 'Membuat kategori baru: ' . $kategori->nama_kategori, $kategori, 'kategori');

        return redirect()->route('admin.kategoris')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function kategorisUpdate(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:kategoris,slug,' . $kategori->id,
            'icon' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($validated);

        AuditLog::log('Mengupdate kategori', 'Mengupdate kategori: ' . $kategori->nama_kategori, $kategori, 'kategori');

        return redirect()->route('admin.kategoris')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function kategorisDestroy(Kategori $kategori)
    {
        AuditLog::log('Menghapus kategori', 'Menghapus kategori: ' . $kategori->nama_kategori, null, 'kategori');
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function pengaduanIndex(Request $request)
    {
        $query = Pengaduan::with(['user', 'kategori', 'petugas']);

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $pengaduans = $query->latest()->paginate(10);
        $kategoris = Kategori::all();

        return view('admin.pengaduan.index', compact('pengaduans', 'kategoris'));
    }

    public function pengaduanShow(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user', 'kategori', 'petugas', 'media', 'riwayats', 'tanggapans.petugas', 'tanggapans.user', 'rating']);
        $petugases = User::where('role', 'petugas')->get();
        return view('admin.pengaduan.show', compact('pengaduan', 'petugases'));
    }

    public function assignPetugas(Request $request, Pengaduan $pengaduan)
    {
        $request->validate(['id_petugas' => 'required|exists:users,id']);

        $pengaduan->update(['id_petugas' => $request->id_petugas]);

        \App\Models\Notifikasi::create([
            'id_user' => $request->id_petugas,
            'id_pengaduan' => $pengaduan->id,
            'judul' => 'Tugas Baru',
            'pesan' => 'Anda ditugaskan untuk menangani pengaduan: ' . $pengaduan->judul,
            'tipe' => 'info',
        ]);

        $petugas = User::find($request->id_petugas);
        $pengaduan->load('kategori');
        if ($petugas && $petugas->email) {
            try {
                Mail::to($petugas->email)->send(new PetugasAssignedMail($pengaduan, $petugas->name));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email assign petugas: ' . $e->getMessage());
            }
        }

        AuditLog::log('Assign petugas', 'Menugaskan ' . ($petugas->name ?? 'petugas') . ' ke pengaduan: ' . $pengaduan->judul, $pengaduan, 'pengaduan');

        return back()->with('success', 'Petugas berhasil ditugaskan.');
    }

    public function pengaduanDestroy(Pengaduan $pengaduan)
    {
        $judul = $pengaduan->judul;

        foreach ($pengaduan->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $pengaduan->riwayats()->delete();
        $pengaduan->tanggapans()->delete();
        $pengaduan->rating()->delete();
        $pengaduan->notifikasis()->delete();
        $pengaduan->delete();

        AuditLog::log('Menghapus pengaduan', 'Menghapus pengaduan: ' . $judul, null, 'pengaduan');

        return back()->with('success', 'Pengaduan berhasil dihapus.');
    }
}
