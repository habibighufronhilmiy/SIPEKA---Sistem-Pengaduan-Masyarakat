<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\KritikSaran;
use App\Models\Voting;
use App\Models\PilihanVoting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FullFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $petugas;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'email_verified_at' => now(),
        ]);

        $this->petugas = User::create([
            'name' => 'Test Petugas',
            'username' => 'testpetugas',
            'email' => 'petugas@test.com',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);

        $this->admin = User::create([
            'name' => 'Test Admin',
            'username' => 'testadmin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_1_public_routes_load()
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/tracking')->assertOk();
        $this->get('/forgot-password')->assertOk();
        $this->get('/faq')->assertOk();
        $this->get('/tentang')->assertOk();
        $this->get('/pengumuman-umum')->assertOk();
        $this->get('/hasil-voting')->assertOk();
    }

    public function test_2_masyarakat_dashboard()
    {
        $this->actingAs($this->user);
        $this->get('/dashboard')->assertOk();
    }

    public function test_3_create_and_view_pengaduan()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Infrastruktur',
            'slug' => 'infrastruktur',
        ]);

        $this->actingAs($this->user);

        $this->get('/pengaduan/create')->assertOk();

        $this->post('/pengaduan', [
            'id_kategori' => $kategori->id,
            'judul' => 'Test Laporan Jalan Rusak',
            'isi_laporan' => 'Isi laporan test panjang',
            'lokasi' => 'Jl. Merdeka No.1',
            'latitude' => '-6.2',
            'longitude' => '106.8',
            'draft' => '0',
        ])->assertSessionHas('success');

        $pengaduan = Pengaduan::first();
        $this->assertNotNull($pengaduan);

        $this->get('/pengaduan')->assertOk();
        $this->get("/pengaduan/{$pengaduan->id}")->assertOk();
    }

    public function test_4_edit_pengaduan_while_menunggu()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);
        $kategori2 = Kategori::create(['nama_kategori' => 'Lingkungan', 'slug' => 'lingkungan']);

        $this->actingAs($this->user);

        $pengaduan = Pengaduan::create([
            'id_user' => $this->user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Judul Awal',
            'isi_laporan' => 'Isi awal',
            'lokasi' => 'Lokasi awal',
            'status' => 'menunggu',
            'kode_tracking' => 'SPK-TEST01',
        ]);

        $this->get("/pengaduan/{$pengaduan->id}/edit")->assertOk();

        $this->put("/pengaduan/{$pengaduan->id}", [
            'id_kategori' => $kategori2->id,
            'judul' => 'Judul Diubah',
            'isi_laporan' => 'Isi diubah',
            'lokasi' => 'Lokasi baru',
        ])->assertSessionHas('success');

        $pengaduan->refresh();
        $this->assertEquals('Judul Diubah', $pengaduan->judul);
    }

    public function test_5_kritik_saran_flow()
    {
        $this->actingAs($this->user);

        $this->get('/kritik-saran')->assertOk();
        $this->get('/kritik-saran/create')->assertOk();

        $this->post('/kritik-saran', [
            'kategori' => 'kritik',
            'judul' => 'Test Kritik',
            'isi_kritik' => 'Isi kritik test',
        ])->assertSessionHas('success');

        $ks = KritikSaran::first();
        $this->assertNotNull($ks);
        $this->get("/kritik-saran/{$ks->id}")->assertOk();

        // Petugas gives response
        $this->actingAs($this->petugas);
        $this->get("/kelola-kritik-saran/{$ks->id}")->assertOk();
        $this->post("/kelola-kritik-saran/{$ks->id}/tanggapan", [
            'tanggapan' => 'Terima kasih atas masukannya',
            'status' => 'selesai',
        ])->assertSessionHas('success');
    }

    public function test_6_petugas_pengaduan_flow()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $pengaduan = Pengaduan::create([
            'id_user' => $this->user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Test Laporan',
            'isi_laporan' => 'Isi laporan',
            'lokasi' => 'Jl. Test',
            'status' => 'diverifikasi',
            'kode_tracking' => 'SPK-TEST02',
        ]);

        $this->actingAs($this->petugas);
        $this->get('/petugas/pengaduan')->assertOk();
        $this->get("/petugas/pengaduan/{$pengaduan->id}")->assertOk();

        $this->post("/petugas/pengaduan/{$pengaduan->id}/proses")
            ->assertSessionHas('success');

        $pengaduan->refresh();
        $this->assertEquals('diproses', $pengaduan->status);

        $this->post("/petugas/pengaduan/{$pengaduan->id}/selesai", [
            'isi_tanggapan' => 'Laporan telah ditangani',
        ])->assertSessionHas('success');

        $pengaduan->refresh();
        $this->assertEquals('selesai', $pengaduan->status);
    }

    public function test_7_admin_users_management()
    {
        $this->actingAs($this->admin);

        $this->get('/admin/users')->assertOk();
        $this->get('/admin/users/create')->assertOk();

        $this->post('/admin/users', [
            'name' => 'New User',
            'username' => 'newuser',
            'email' => 'new@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'masyarakat',
        ])->assertSessionHas('success');

        $user = User::where('username', 'newuser')->first();
        $this->assertNotNull($user);

        $this->get("/admin/users/{$user->id}/edit")->assertOk();
        $this->put("/admin/users/{$user->id}", [
            'name' => 'Updated Name',
            'username' => 'newuser',
            'email' => 'new@test.com',
            'role' => 'masyarakat',
        ])->assertSessionHas('success');
    }

    public function test_8_admin_pengaduan_management()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $pengaduan = Pengaduan::create([
            'id_user' => $this->user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Test Laporan',
            'isi_laporan' => 'Isi laporan',
            'lokasi' => 'Jl. Test',
            'status' => 'menunggu',
            'kode_tracking' => 'SPK-TEST03',
        ]);

        $this->actingAs($this->admin);
        $this->get('/admin/pengaduan')->assertOk();
        $this->get("/admin/pengaduan/{$pengaduan->id}")->assertOk();

        $this->delete("/admin/pengaduan/{$pengaduan->id}")->assertSessionHas('success');
        $this->assertEquals(0, Pengaduan::count());
    }

    public function test_9_admin_kategori_management()
    {
        $this->actingAs($this->admin);

        $this->get('/admin/kategoris')->assertOk();

        $this->post('/admin/kategoris', [
            'nama_kategori' => 'Test Kategori',
            'slug' => 'test-kategori',
        ])->assertSessionHas('success');

        $kategori = Kategori::first();
        $this->assertNotNull($kategori);

        $this->post("/admin/kategoris/{$kategori->id}", [
            'nama_kategori' => 'Updated Kategori',
            'slug' => 'test-kategori',
        ])->assertSessionHas('success');

        $this->delete("/admin/kategoris/{$kategori->id}")->assertSessionHas('success');
    }

    public function test_10_voting_flow()
    {
        $this->actingAs($this->admin);

        $this->get('/voting/create')->assertOk();

        $this->post('/voting', [
            'pertanyaan' => 'Test Voting?',
            'deskripsi' => 'Voting test',
            'tanggal_mulai' => now()->format('Y-m-d H:i'),
            'tanggal_selesai' => now()->addDays(7)->format('Y-m-d H:i'),
            'pilihans' => ['Opsi A', 'Opsi B', 'Opsi C'],
        ])->assertSessionHas('success');

        $voting = Voting::first();
        $this->assertNotNull($voting);

        $this->actingAs($this->user);
        $this->get("/voting/{$voting->id}")->assertOk();

        $pilihan = $voting->pilihans->first();
        $this->post("/voting/{$voting->id}/vote", [
            'id_pilihan' => $pilihan->id,
        ])->assertSessionHas('success');
    }

    public function test_11_pdf_download()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $this->actingAs($this->user);

        $pengaduan = Pengaduan::create([
            'id_user' => $this->user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Test PDF',
            'isi_laporan' => 'Isi PDF test',
            'lokasi' => 'Jl. PDF',
            'status' => 'diverifikasi',
            'kode_tracking' => 'SPK-TEST04',
        ]);

        $this->get("/pengaduan/{$pengaduan->id}/pdf")->assertOk();
    }

    public function test_12_rating()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $this->actingAs($this->user);

        $pengaduan = Pengaduan::create([
            'id_user' => $this->user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Test Rating',
            'isi_laporan' => 'Isi rating',
            'lokasi' => 'Jl. Rating',
            'status' => 'selesai',
            'kode_tracking' => 'SPK-TEST05',
        ]);

        $this->post("/pengaduan/{$pengaduan->id}/rating", [
            'rating' => 5,
            'komentar' => 'Sangat bagus!',
        ])->assertSessionHas('success');
    }

    public function test_13_tracking_public()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $pengaduan = Pengaduan::create([
            'id_user' => $this->user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Tracked Report',
            'isi_laporan' => 'Isi tracked',
            'lokasi' => 'Jl. Tracking',
            'status' => 'diverifikasi',
            'kode_tracking' => 'SPK-TEST123',
        ]);

        $this->post('/tracking', [
            'kode' => 'SPK-TEST123',
        ])->assertSee($pengaduan->judul);
    }

    public function test_14_notifikasi_page()
    {
        $this->actingAs($this->user);
        $this->get('/notifikasi')->assertOk();
    }

    public function test_15_petugas_authorization_gap()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $anotherPetugas = User::create([
            'name' => 'Another Petugas',
            'username' => 'anotherpetugas',
            'email' => 'another@test.com',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);

        $pengaduan = Pengaduan::create([
            'id_user' => $this->user->id,
            'id_kategori' => $kategori->id,
            'judul' => 'Auth Test',
            'isi_laporan' => 'Isi auth test',
            'lokasi' => 'Jl. Auth',
            'status' => 'diproses',
            'id_petugas' => $anotherPetugas->id,
            'kode_tracking' => 'SPK-TEST06',
        ]);

        $this->actingAs($this->petugas);
        $this->post("/petugas/pengaduan/{$pengaduan->id}/selesai", [
            'isi_tanggapan' => 'Mencoba selesaikan',
        ])->assertSessionHas('error');
    }

    public function test_16_draft_flow()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

        $this->actingAs($this->user);

        $this->post('/pengaduan', [
            'id_kategori' => $kategori->id,
            'judul' => 'Draft Report',
            'isi_laporan' => 'Isi draft',
            'lokasi' => 'Jl. Draft',
            'draft' => '1',
        ])->assertSessionHas('success');

        $pengaduan = Pengaduan::where('draft', true)->first();
        $this->assertNotNull($pengaduan);
        $this->assertEquals(1, $pengaduan->draft);

        $this->post("/pengaduan/{$pengaduan->id}/submit")
            ->assertSessionHas('success');

        $pengaduan->refresh();
        $this->assertEquals(0, $pengaduan->draft);
    }
}
