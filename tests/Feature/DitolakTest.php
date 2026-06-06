<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Pengaduan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DitolakTest extends TestCase
{
    use RefreshDatabase;

    public function test_ditolak_report_shows_normally()
    {
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'masyarakat',
            'email_verified_at' => now(),
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $pengaduan = Pengaduan::create([
            'id_user' => $user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Laporan Ditolak AI',
            'isi_laporan' => 'Isi laporan yang ditolak AI',
            'lokasi' => 'Jl. Test No.1',
            'status' => 'ditolak',
            'kode_tracking' => 'SPK-DTLK01',
        ]);

        $this->actingAs($user);

        // Test index page
        $response = $this->get('/pengaduan');
        $response->assertOk();
        $response->assertSee('Laporan Ditolak AI');
        $response->assertSee('Ditolak');

        // Test show page
        $response = $this->get("/pengaduan/{$pengaduan->id}");
        $response->assertOk();
        $response->assertSee('Laporan Ditolak AI');
        $response->assertSee('Ditolak');
    }

    public function test_petugas_sees_ditolak_report()
    {
        $petugas = User::create([
            'name' => 'Petugas',
            'username' => 'petugas',
            'email' => 'petugas@test.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $pengaduan = Pengaduan::create([
            'id_user' => User::create([
                'name' => 'Masyarakat',
                'username' => 'masyarakat',
                'email' => 'masyarakat@test.com',
                'password' => bcrypt('password'),
                'role' => 'masyarakat',
                'email_verified_at' => now(),
            ])->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Laporan Ditolak',
            'isi_laporan' => 'Isi',
            'lokasi' => 'Jl. Test',
            'status' => 'ditolak',
            'kode_tracking' => 'SPK-DTLK02',
        ]);

        $this->actingAs($petugas);

        $response = $this->get('/petugas/pengaduan');
        $response->assertOk();
        $response->assertSee('Laporan Ditolak');

        $response = $this->get("/petugas/pengaduan/{$pengaduan->id}");
        $response->assertOk();
        $response->assertSee('Laporan Ditolak');
        $response->assertSee('Ditolak');
    }
}
