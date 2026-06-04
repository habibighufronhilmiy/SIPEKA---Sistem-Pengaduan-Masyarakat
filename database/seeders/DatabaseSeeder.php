<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin SIPEKA',
            'username' => 'admin',
            'email' => 'admin@sipeka.test',
            'password' => Hash::make('password'),
            'telepon' => '081234567890',
            'alamat' => 'Kantor Desa',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas 1',
            'username' => 'petugas1',
            'email' => 'petugas1@sipeka.test',
            'password' => Hash::make('password'),
            'telepon' => '081234567891',
            'alamat' => 'Kantor Desa',
            'role' => 'petugas',
        ]);

        User::create([
            'name' => 'Masyarakat 1',
            'username' => 'masyarakat1',
            'email' => 'masyarakat1@sipeka.test',
            'password' => Hash::make('password'),
            'telepon' => '081234567892',
            'alamat' => 'RT 01 RW 01',
            'role' => 'masyarakat',
        ]);

        $kategoris = [
            ['nama_kategori' => 'Jalan Rusak', 'slug' => 'jalan-rusak', 'icon' => 'road', 'deskripsi' => 'Laporan kerusakan jalan umum'],
            ['nama_kategori' => 'Sampah', 'slug' => 'sampah', 'icon' => 'trash', 'deskripsi' => 'Laporan masalah persampahan'],
            ['nama_kategori' => 'Lampu Jalan', 'slug' => 'lampu-jalan', 'icon' => 'lightbulb', 'deskripsi' => 'Laporan kerusakan lampu jalan'],
            ['nama_kategori' => 'Banjir', 'slug' => 'banjir', 'icon' => 'water', 'deskripsi' => 'Laporan banjir dan drainase'],
            ['nama_kategori' => 'Keamanan', 'slug' => 'keamanan', 'icon' => 'shield', 'deskripsi' => 'Laporan gangguan keamanan'],
            ['nama_kategori' => 'Fasilitas Umum', 'slug' => 'fasilitas-umum', 'icon' => 'building', 'deskripsi' => 'Laporan kerusakan fasilitas umum'],
        ];

        foreach ($kategoris as $k) {
            Kategori::create($k);
        }
    }
}
