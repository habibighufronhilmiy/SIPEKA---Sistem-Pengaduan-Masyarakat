<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');



// Public routes (no login needed)
Route::post('/tracking', [PublicController::class, 'trackingCek'])->name('public.tracking.cek');
Route::get('/tracking', [PublicController::class, 'tracking'])->name('public.tracking');
Route::get('/pengumuman-umum', [PublicController::class, 'pengumuman'])->name('public.pengumuman');
Route::get('/hasil-voting', [PublicController::class, 'voting'])->name('public.voting');
Route::get('/faq', [PublicController::class, 'faq'])->name('public.faq');

Route::get('/tentang', [PublicController::class, 'tentang'])->name('public.tentang');

// Password reset routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    Route::get('/notifikasi', function () {
        $notifikasis = \App\Models\Notifikasi::where('id_user', auth()->id())->latest()->paginate(20);
        return view('notifikasi.index', compact('notifikasis'));
    })->name('notifikasi.index');

    Route::post('/notifikasi/read/{notifikasi}', function (\App\Models\Notifikasi $notifikasi) {
        $notifikasi->update(['is_read' => true]);
        return back();
    })->name('notifikasi.read');

    Route::post('/notifikasi/read-all', function () {
        \App\Models\Notifikasi::where('id_user', auth()->id())->update(['is_read' => true]);
        return back();
    })->name('notifikasi.readAll');

    // Masyarakat routes
    Route::middleware('role:masyarakat')->group(function () {
        Route::resource('pengaduan', PengaduanController::class);
        Route::post('/pengaduan/{pengaduan}/submit', [PengaduanController::class, 'submitDraft'])->name('pengaduan.submit');
        Route::post('/pengaduan/{pengaduan}/rating', [PengaduanController::class, 'storeRating'])->name('pengaduan.rating');
        Route::post('/pengaduan/{pengaduan}/tanggapan', [PengaduanController::class, 'storeTanggapanMasyarakat'])->name('pengaduan.tanggapan.masyarakat');
    });

    Route::get('/pengaduan/{pengaduan}/pdf', [PengaduanController::class, 'downloadPdf'])->name('pengaduan.pdf')->middleware('role:masyarakat,admin,petugas');

    // Petugas routes
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/pengaduan', [PetugasController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/{pengaduan}', [PetugasController::class, 'show'])->name('pengaduan.show');
        Route::post('/pengaduan/{pengaduan}/verifikasi', [PetugasController::class, 'verifikasi'])->name('pengaduan.verifikasi');
        Route::post('/pengaduan/{pengaduan}/proses', [PetugasController::class, 'proses'])->name('pengaduan.proses');
        Route::post('/pengaduan/{pengaduan}/selesai', [PetugasController::class, 'selesai'])->name('pengaduan.selesai');
        Route::post('/pengaduan/{pengaduan}/tanggapan', [PengaduanController::class, 'storeTanggapan'])->name('pengaduan.tanggapan');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('users.create');
        Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'usersEdit'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');

        Route::get('/kategoris', [AdminController::class, 'kategoris'])->name('kategoris');
        Route::post('/kategoris', [AdminController::class, 'kategorisStore'])->name('kategoris.store');
        Route::post('/kategoris/{kategori}', [AdminController::class, 'kategorisUpdate'])->name('kategoris.update');
        Route::delete('/kategoris/{kategori}', [AdminController::class, 'kategorisDestroy'])->name('kategoris.destroy');

        Route::get('/pengaduan', [AdminController::class, 'pengaduanIndex'])->name('pengaduan');
        Route::get('/pengaduan/{pengaduan}', [AdminController::class, 'pengaduanShow'])->name('pengaduan.show');
        Route::post('/pengaduan/{pengaduan}/assign', [AdminController::class, 'assignPetugas'])->name('pengaduan.assign');
        Route::delete('/pengaduan/{pengaduan}', [AdminController::class, 'pengaduanDestroy'])->name('pengaduan.destroy');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit');
    });

    // Voting routes
    Route::resource('voting', VotingController::class)->except(['index', 'show'])->middleware('role:admin,petugas');
    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::get('/voting/{voting}', [VotingController::class, 'show'])->name('voting.show');
    Route::post('/voting/{voting}/vote', [VotingController::class, 'vote'])->name('voting.vote')->middleware('role:masyarakat,petugas,admin');

    // Pengumuman routes (accessible by admin & petugas)
    Route::resource('pengumuman', PengumumanController::class)->except(['show'])->middleware('role:admin,petugas');

    // Kritik & Saran routes
    Route::middleware('role:masyarakat')->prefix('kritik-saran')->name('kritik-saran.')->group(function () {
        Route::get('/', [KritikSaranController::class, 'index'])->name('index');
        Route::get('/create', [KritikSaranController::class, 'create'])->name('create');
        Route::post('/', [KritikSaranController::class, 'store'])->name('store');
        Route::get('/{kritikSaran}', [KritikSaranController::class, 'show'])->name('show');
    });

    Route::middleware('role:admin,petugas')->prefix('kelola-kritik-saran')->name('kelola-kritik-saran.')->group(function () {
        Route::get('/', [KritikSaranController::class, 'kelolaIndex'])->name('index');
        Route::get('/{kritikSaran}', [KritikSaranController::class, 'kelolaShow'])->name('show');
        Route::post('/{kritikSaran}/tanggapan', [KritikSaranController::class, 'tanggapan'])->name('tanggapan');
    });
});


