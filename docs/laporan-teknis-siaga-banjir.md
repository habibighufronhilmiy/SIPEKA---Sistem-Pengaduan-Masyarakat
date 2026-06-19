# LAPORAN TEKNIS SISTEM APLIKASI "SEKECAM (SIPEKA)"

**Sistem Pengaduan, Keluhan, dan Aspirasi Masyarakat**

---

## A. TEKNOLOGI DAN PLATFORM

### A.1 Backend — Laravel

| Komponen | Spesifikasi |
|----------|-------------|
| Framework | Laravel 13.x |
| Bahasa Pemrograman | PHP ^8.3 |
| Database Utama | MySQL 8.0+ |
| Database Testing | SQLite (in-memory) |
| Database Cache & Session | Database (tabel `cache`, `sessions`) |
| Queue Driver | Database (tabel `jobs`) |
| Web Server Produksi | Apache 2.4 / Nginx 1.18+ (via Laragon / Railway) |
| Platform Deployment | Railway (PaaS) |
| PHP Extensions | BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL, GD, Exif, ZIP |
| Cache Driver | Database / Array (testing) |
| Session Driver | Database / Array (testing) |
| Storage (File Upload) | Local `public/storage` |
| Authentication | Session-based, role system (Masyarakat, Petugas, Admin) |
| Social Auth | Google OAuth 2.0 via Laravel Socialite |
| Email / Mailer | Brevo (Sendinblue) API via Symfony Brevo Mailer |
| AI / Verifikasi | Groq API (`llama-3.3-70b-versatile`) untuk verifikasi teks & foto |
| Push Notification | — (email-based notification via Brevo) |
| PDF Generation | barryvdh/laravel-dompdf |
| Testing | Pest PHP 4.x + PHPUnit 12.x |
| Interactive Shell | Laravel Tinker |
| Assets / Build Tool | Vite 8.x + Tailwind CSS 4.x |
| Package Manager | Composer (PHP), npm (Node.js) |

### A.2 Frontend Web

| Komponen | Spesifikasi |
|----------|-------------|
| Template Engine | Blade (Laravel native) |
| CSS Framework | Tailwind CSS 4.x |
| Build Tool | Vite 8.x |
| JavaScript | Vanilla JS (minimal, untuk toggle & interaksi ringan) |
| Icons | SVG inline |
| Layout | Komponen `app-layout` (sidebar + header) dengan dark mode toggle |
| Responsive | Ya — mobile-first via Tailwind breakpoints |

### A.3 Fitur Aplikasi

| Fitur | Deskripsi |
|-------|-----------|
| Landing Page | Halaman publik dengan info aplikasi, login, tracking |
| Registrasi & Login | Register dengan Nama, Username, Email, No Telepon, Password, Alamat; Login dengan toggle password visibility |
| Google OAuth | Login menggunakan akun Google |
| Lupa Password | Reset password via email (Brevo) |
| Pengaduan (CRUD) | Masyarakat: buat, lihat, edit pengaduan dengan upload foto |
| Tanggapan | Petugas menanggapi pengaduan; Masyarakat bisa menambahkan tanggapan |
| Verifikasi AI | Verifikasi otomatis teks & foto pengaduan via Groq API |
| Rating | Masyarakat memberi rating setelah pengaduan selesai |
| Voting | Fitur voting/pemilihan (CRUD + hak pilih) |
| Pengumuman | Admin/Petugas membuat pengumuman, notifikasi email ke semua user |
| Kritik & Saran | Masyarakat mengirim kritik dan saran |
| Tracking Publik | Lacak status pengaduan tanpa login (via kode unik) |
| Notifikasi | In-app notification + email notifikasi (Welcome, PengaduanCreated, Pengumuman) |
| Manajemen User | Admin mengelola user (CRUD, role assignment) |
| Dashboard | Dashboard berbeda per role (Masyarakat, Petugas, Admin) |
| Laporan PDF | Generate laporan pengaduan dalam format PDF |
| Audit Trail | Catat aktivitas admin |
| Dark Mode | Toggle dark mode (localStorage + system preference) |

---

## FASE 7: DEPLOYMENT AND MAINTENANCE (PENERAPAN DAN PEMELIHARAAN)

---

### 7.1 Deployment

#### 7.1.1 Deployment Backend Laravel

##### A. Lingkungan Deployment

Aplikasi dideploy ke **Railway** (PaaS — Platform as a Service). Railway menyediakan infrastruktur server, database, dan domain secara otomatis. Deployment dilakukan dengan git push ke branch `master` di GitHub — Railway otomatis mendeteksi perubahan dan melakukan deploy ulang.

| Sumber Daya | Development (Laragon) | Production (Railway) |
|-------------|----------------------|---------------------|
| OS | Windows (Laragon) | Linux (Railway Container) |
| Web Server | Apache / Nginx (Laragon) | Nginx (built-in Railway) |
| PHP | PHP 8.3+ | PHP 8.3+ (otomatis) |
| Database | MySQL 8.0 (Laragon) | MySQL 8.0 (Railway MySQL plugin) |
| Node.js | 20.x LTS | 20.x LTS (untuk build asset) |
| Composer | 2.x | 2.x (otomatis saat build) |
| Cache | Database | Database |
| Session | Database | Database |
| Queue | Database | Database |

##### B. Langkah-langkah Instalasi (Local Development)

**1. Clone Repository**

```bash
git clone https://github.com/habibighufronhilmiy/SIPEKA---Sistem-Pengaduan-Masyarakat.git
cd SIPEKA---Sistem-Pengaduan-Masyarakat
```

**2. Install Dependencies PHP dengan Composer**

```bash
composer install
```

**3. Install Dependencies Frontend (Vite + Tailwind)**

```bash
npm install
npm run build
```

**4. Konfigurasi Environment**

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan isi file `.env` untuk environment lokal:

```env
APP_NAME=SEKECAM
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pengaduan
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

MAIL_MAILER=brevo
MAIL_FROM_ADDRESS="ghufronhabibi4@gmail.com"
MAIL_FROM_NAME="SEKECAM"
BREVO_API_KEY=xkeysib-...

GROQ_API_KEY=gsk_...

FILESYSTEM_DISK=local
```

**5. Migrasi Database & Seeding**

```bash
php artisan migrate
php artisan db:seed
```

**6. Konfigurasi Storage Link**

```bash
php artisan storage:link
```

##### C. Deployment ke Railway

Railway terintegrasi dengan GitHub. Setiap push ke branch `master` akan otomatis memicu build dan deploy.

**1. Setup project di Railway:**

```bash
# Install Railway CLI (jika diperlukan)
npm install -g @railway/cli

# Login (web-based)
railway login

# Init project di Railway
railway init

# Hubungkan dengan project yang sudah ada
railway link 1cf7b93b-1853-4cbf-935d-3b688540ae5d
```

**2. Konfigurasi Environment Variables di Railway:**

Set variabel berikut melalui Railway Dashboard atau CLI:

```bash
railway variable set APP_NAME=SEKECAM
railway variable set APP_ENV=production
railway variable set APP_DEBUG=false
railway variable set APP_KEY=base64:...
railway variable set APP_URL=https://sipeka-sistem-pengaduan-masyarakat-production.up.railway.app

railway variable set DB_CONNECTION=mysql
railway variable set DB_HOST=...
railway variable set DB_PORT=3306
railway variable set DB_DATABASE=...
railway variable set DB_USERNAME=...
railway variable set DB_PASSWORD=...

railway variable set CACHE_STORE=database
railway variable set SESSION_DRIVER=database
railway variable set QUEUE_CONNECTION=database

railway variable set MAIL_MAILER=brevo
railway variable set MAIL_FROM_ADDRESS=ghufronhabibi4@gmail.com
railway variable set MAIL_FROM_NAME=SEKECAM
railway variable set BREVO_API_KEY=xkeysib-...

railway variable set GROQ_API_KEY=gsk_...

railway variable set FILESYSTEM_DISK=local
```

**3. Push ke GitHub untuk trigger deploy:**

```bash
git add .
git commit -m "update fitur ..."
git push origin master
```

Railway akan otomatis:
- Mendeteksi push baru
- Menjalankan `composer install --no-dev --optimize-autoloader`
- Menjalankan `npm install && npm run build`
- Menjalankan `php artisan migrate --force`
- Menjalankan `php artisan storage:link`
- Menjalankan `php artisan config:cache`
- Menjalankan `php artisan route:cache`
- Menjalankan `php artisan view:cache`
- Menjalankan `php artisan event:cache`
- Men-deploy aplikasi ke domain Railway

**4. Konfigurasi Nginx Production (jika self-hosted):**

```nginx
server {
    listen 80;
    server_name sipeka.domain.com;
    root /var/www/pengaduan/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

##### D. Checklist Produksi

| Item | Keterangan | Perintah / Tindakan |
|------|-----------|---------------------|
| APP_ENV | Pastikan bernilai `production` | `grep APP_ENV .env` |
| APP_DEBUG | Pastikan bernilai `false` | `grep APP_DEBUG .env` |
| HTTPS/SSL | Railway otomatis SSL. Self-hosted: pasang Let's Encrypt | `sudo certbot --nginx -d sipeka.domain.com` |
| File Permissions | Direktori `storage/` dan `bootstrap/cache/` writable | `sudo chown -R www-data:www-data storage bootstrap/cache` |
| Cache Optimization | Config, route, view, event cache | `php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache` |
| Storage Link | Symlink public/storage ke storage/app/public | `php artisan storage:link` |
| Queue Worker | Untuk email & notifikasi async | `php artisan queue:work` (atau Supervisor) |
| Email Sending | Verifikasi Brevo API dapat mengirim email | `php artisan tinker` → `Mail::raw('Test', fn($m) => $m->to('user@test.com')->subject('Test'))` |

##### E. Queue Worker (untuk Notifikasi Email)

Di lingkungan production, queue worker perlu berjalan di background untuk memproses pengiriman email dan notifikasi. Di Railway, gunakan **Railway Cron / Worker** atau jalankan manual via Supervisor.

**Menggunakan Supervisor (self-hosted):**

```bash
sudo nano /etc/supervisor/conf.d/sekeam-worker.conf
```

```ini
[program:sekeam-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/pengaduan/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/pengaduan/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sekeam-worker:*
```

**Catatan untuk Railway:** Queue worker belum diaktifkan di Railway. Email saat ini dikirim secara sync via method `->send()` (bukan `->queue()`).

---

### 7.2 Maintenance

#### 7.2.1 Monitoring Sistem

##### Monitoring Log Backend Laravel

Laravel menyimpan log di direktori `storage/logs/`. File log utama adalah `storage/logs/laravel.log`.

**Konfigurasi Log (config/logging.php):**

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'daily'],
        'ignore_exceptions' => false,
    ],

    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],

    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
],
```

**Level Log:**

| Level | Deskripsi | Contoh Penggunaan |
|-------|-----------|-------------------|
| DEBUG | Informasi detail untuk debugging | `Log::debug('Query executed', ['sql' => $query])` |
| INFO | Informasi umum operasi aplikasi | `Log::info('User registered', ['user_id' => $id])` |
| NOTICE | Kejadian normal tapi signifikan | `Log::notice('High traffic detected', ['requests' => $count])` |
| WARNING | Kejadian tidak biasa namun bukan error | `Log::warning('Failed login attempt', ['email' => $email])` |
| ERROR | Error yang perlu ditindaklanjuti | `Log::error('Failed to send email', ['error' => $e->getMessage()])` |
| CRITICAL | Kegagalan kritis | `Log::critical('Database connection failed')` |
| ALERT | Tindakan harus segera dilakukan | `Log::alert('Storage disk nearly full')` |
| EMERGENCY | Sistem tidak berfungsi | `Log::emergency('Application is down')` |

**Perintah Monitoring Real-time:**

```bash
# Melihat log secara real-time
tail -f storage/logs/laravel.log

# Melihat 100 baris terakhir
tail -100 storage/logs/laravel.log

# Mencari error dalam log
grep -i "error" storage/logs/laravel.log

# Mencari exception stack trace
grep -A 10 "exception" storage/logs/laravel.log

# Menghitung jumlah error per hari
grep -c "$(date +%Y-%m-%d).*ERROR" storage/logs/laravel.log

# Filter log untuk channel tertentu
tail -f storage/logs/laravel.log | grep --line-buffered "production.ERROR"
```

##### Pelacakan Error Aplikasi

**Kategori Error yang Dipantau:**

| Kategori | Contoh | Sumber Deteksi |
|----------|--------|----------------|
| API/Route Error 4xx | 401 Unauthorized, 403 Forbidden, 404 Not Found, 422 Validation Error | Log Laravel, Browser Console |
| API/Route Error 5xx | 500 Internal Server Error, 503 Service Unavailable | Log Laravel, Server Log |
| Database Error | Query timeouts, deadlocks, connection refused | Log Laravel, MySQL Error Log |
| Email Error | Gagal kirim via Brevo API, invalid API key | Log Laravel (`MailServiceProvider`) |
| AI/Groq Error | Rate limit exceeded, API key invalid, model unavailable | Log Laravel |
| File Upload Error | Storage full, invalid file type, ukuran melebihi batas | Log Laravel, Validasi Request |
| Queue Error | Job gagal diproses, max attempts tercapai | Tabel `failed_jobs` di database |

**Perintah Monitoring Error:**

```bash
# Melihat failed jobs di database
php artisan queue:failed

# Menampilkan daftar failed jobs
php artisan queue:failed-table

# Retry semua job yang gagal
php artisan queue:retry all

# Hapus semua failed jobs
php artisan queue:flush
```

##### Monitoring Performa

**Response Time API (Endpoint Kunci):**

```bash
# Mengukur response time endpoint kesehatan
curl -o /dev/null -s -w "HTTP %{http_code}, Time: %{time_total}s\n" \
  https://sipeka-sistem-pengaduan-masyarakat-production.up.railway.app/login

# Mengukur beberapa endpoint
for endpoint in / /login /tracking; do
  echo "Endpoint $endpoint:"
  curl -o /dev/null -s -w "  %{http_code} - %{time_total}s\n" \
    "https://sipeka-sistem-pengaduan-masyarakat-production.up.railway.app$endpoint"
done
```

**Server Resource Monitoring (jika self-hosted):**

```bash
# CPU dan Memory usage
top -bn1 | head -20

# Disk usage
df -h

# Monitor proses PHP
ps aux | grep php

# Cek koneksi database
mysqladmin -u root -p status

# Monitor koneksi database aktif
mysql -u root -p -e "SHOW PROCESSLIST;"
```

---

#### 7.2.2 Pemeliharaan Database

##### Backup Database

Database aplikasi menggunakan MySQL. Lakukan backup secara rutin.

**Backup Manual:**

```bash
# Backup database ke file SQL
mysqldump -u root -p pengaduan > backup-pengaduan-$(date +%Y%m%d).sql

# Backup dengan kompresi
mysqldump -u root -p pengaduan | gzip > backup-pengaduan-$(date +%Y%m%d).sql.gz

# Restore dari backup
mysql -u root -p pengaduan < backup-pengaduan-20260613.sql

# Restore dari backup terkompresi
gunzip -c backup-pengaduan-20260613.sql.gz | mysql -u root -p pengaduan
```

**Script Backup Otomatis (Linux):**

Buat file `/usr/local/bin/backup-pengaduan.sh`:

```bash
#!/bin/bash

DB_NAME="pengaduan"
DB_USER="root"
DB_PASS="password"
BACKUP_DIR="/var/backups/pengaduan"
DATE=$(date +%Y-%m-%d_%H-%M-%S)
RETENTION_DAYS=7

mkdir -p "$BACKUP_DIR/daily"
mkdir -p "$BACKUP_DIR/weekly"
mkdir -p "$BACKUP_DIR/monthly"

# Backup harian
mysqldump --opt --routines --events --triggers \
  -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" \
  | gzip > "$BACKUP_DIR/daily/pengaduan_$DATE.sql.gz"

# Backup mingguan (hari Minggu)
if [ $(date +%u) -eq 7 ]; then
  cp "$BACKUP_DIR/daily/pengaduan_$DATE.sql.gz" \
     "$BACKUP_DIR/weekly/pengaduan_week_$(date +%Y-%V).sql.gz"
fi

# Backup bulanan (tanggal 1)
if [ $(date +%d) -eq 1 ]; then
  cp "$BACKUP_DIR/daily/pengaduan_$DATE.sql.gz" \
     "$BACKUP_DIR/monthly/pengaduan_$(date +%Y-%m).sql.gz"
fi

# Hapus backup lama
find "$BACKUP_DIR/daily" -type f -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR/weekly" -type f -mtime +28 -delete
find "$BACKUP_DIR/monthly" -type f -mtime +365 -delete

echo "[$(date)] Backup completed"
```

Jadwalkan dengan cron:

```bash
chmod +x /usr/local/bin/backup-pengaduan.sh
crontab -e

# Backup setiap hari jam 02:00
0 2 * * * /usr/local/bin/backup-pengaduan.sh >> /var/log/pengaduan-backup.log 2>&1
```

**Catatan untuk Railway:** Railway menyediakan MySQL plugin dengan backup otomatis. Untuk backup tambahan, gunakan Railway Dashboard atau export manual via `railway mysql dump`.

##### Manajemen Migrasi

```bash
# Melihat status migrasi
php artisan migrate:status

# Output:
# +------+-------------------------------------------------+-------+
# | Ran? | Migration                                       | Batch |
# +------+-------------------------------------------------+-------+
# | Yes  | 0001_01_01_000000_create_users_table             | 1     |
# | Yes  | 0001_01_01_000001_create_cache_table             | 1     |
# | Yes  | 0001_01_01_000002_create_jobs_table              | 1     |
# | Yes  | 2025_12_01_000001_create_pengaduan_table         | 2     |
# | No   | 2026_06_10_000002_add_ai_verification_to_pengaduan | -     |
# +------+-------------------------------------------------+-------+

# Menjalankan migrasi baru
php artisan migrate

# Rollback batch migrasi terakhir
php artisan migrate:rollback

# Rollback sejumlah langkah
php artisan migrate:rollback --step=3

# Rollback semua dan migrasi ulang dengan seeding
php artisan migrate:fresh --seed

# Membuat migration baru
php artisan make:migration add_verified_at_to_pengaduan_table
```

##### Pemeriksaan Integritas Data

```sql
-- Cari pengaduan tanpa user (orphaned records)
SELECT p.* FROM pengaduan p
LEFT JOIN users u ON p.user_id = u.id
WHERE u.id IS NULL;

-- Cari tanggapan tanpa pengaduan
SELECT t.* FROM tanggapans t
LEFT JOIN pengaduan p ON t.pengaduan_id = p.id
WHERE p.id IS NULL;

-- Cek duplikasi email user
SELECT email, COUNT(*) as total
FROM users
GROUP BY email
HAVING COUNT(*) > 1;

-- Cek pengaduan tanpa kategori
SELECT * FROM pengaduan WHERE kategori_id IS NULL;

-- Cek voting ganda
SELECT user_id, voting_id, COUNT(*) as total
FROM votes
GROUP BY user_id, voting_id
HAVING COUNT(*) > 1;
```

---

#### 7.2.3 Prosedur Update

##### Update Backend Laravel

**1. Update Dependencies:**

```bash
# Cek versi terbaru yang tersedia
composer outdated

# Update composer.lock dengan versi terbaru (sesuai constraint)
composer update

# Update package spesifik
composer update laravel/framework
composer update barryvdh/laravel-dompdf

# Update package keamanan
composer audit
composer update --only-packages-with-audit
```

**2. Update Frontend Assets:**

```bash
# Cek update npm
npm outdated

# Update dependencies
npm update

# Build ulang asset
npm run build
```

**3. Perintah Pasca-update:**

```bash
# Backup dulu
php artisan down --secret="maintenance-token"

# Jalankan migrasi baru (jika ada)
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Optimasi ulang
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue
php artisan queue:restart

# Keluar maintenance
php artisan up
```

##### Update Frontend Web

Karena menggunakan Blade + Tailwind CSS + Vite, update frontend dilakukan dengan:

```bash
# Update Tailwind CSS
npm install tailwindcss@latest

# Update Vite
npm install vite@latest

# Build ulang asset
npm run build

# Commit perubahan
git add package.json package-lock.json
git commit -m "chore: update tailwindcss dan vite ke versi terbaru"
git push origin master
```

##### Manajemen Versi

Setiap update dicatat di `CHANGELOG.md`:

```markdown
# Changelog

## [1.2.0] — 2026-06-01

### Added
- Fitur verifikasi AI untuk foto pengaduan (Groq Vision API)
- Halaman tracking publik dengan kode unik
- Export laporan PDF dengan filter tanggal

### Changed
- Upgrade Laravel 13.12 → 13.15
- Update Tailwind CSS 4.0 → 4.5
- Optimasi query dashboard (load time turun 35%)

### Fixed
- Bug toggle password di form registrasi
- Crash saat upload foto >5MB
- Email notifikasi tidak terkirim jika queue worker mati

### Security
- Update composer audit (CVE-2026-xxxx)
- Rotasi API key Brevo

## [1.1.0] — 2026-04-15
...
```

---

#### 7.2.4 Pemeliharaan Keamanan

##### Rotasi Kredensial

| Kredensial | Frekuensi Rotasi | Tindakan |
|------------|-----------------|----------|
| Password Database MySQL | Setiap 90 hari | `ALTER USER 'root'@'localhost' IDENTIFIED BY 'password_baru';` |
| BREVO_API_KEY | Setiap 6 bulan | Generate key baru dari dashboard Brevo |
| GROQ_API_KEY | Setiap 6 bulan | Generate key baru dari console Groq |
| APP_KEY Laravel | Setiap 12 bulan | `php artisan key:generate` (hati-hati: invalidate enkripsi) |
| Google OAuth Client ID/Secret | Setiap 12 bulan | Rotasi dari Google Cloud Console |
| SSL Certificate | Setiap 90 hari (Let's Encrypt) | `sudo certbot renew` |

##### Patch Keamanan

```bash
# Audit keamanan package Composer
composer audit

# Output:
# Package: laravel/framework
# Severity: high
# CVE: CVE-2026-xxxxx
# Title: SQL injection in query builder
# Affected: <13.15.0

# Update package yang terkena advisory
composer update laravel/framework

# Audit npm
npm audit
npm audit fix
```

**Kebijakan Patch:**

| Tingkat Keparahan | Waktu Respon | Tindakan |
|-------------------|-------------|----------|
| Critical | 24 jam | Terapkan patch segera, nonaktifkan fitur jika perlu |
| High | 48 jam | Terapkan patch dalam 2 hari kerja |
| Medium | 7 hari | Jadwalkan dalam siklus update berikutnya |
| Low | 30 hari | Jadwalkan pada maintenance berikutnya |

##### Kontrol Akses

Aplikasi menggunakan sistem role dengan 3 level akses:

| Role | Akses |
|------|-------|
| **Masyarakat** | Buat & kelola pengaduan sendiri, kirim kritik & saran, ikut voting, lihat notifikasi |
| **Petugas** | Verifikasi & proses pengaduan, beri tanggapan, kelola pengumuman |
| **Admin** | Semua akses, kelola user, kelola kategori, generate laporan, lihat audit trail |

**Pemeriksaan Berkala:**

```bash
# Daftar semua user dengan rolenya
php artisan tinker
```

```php
// Di tinker
$users = User::with('roles')->get();
foreach ($users as $user) {
    echo "{$user->name} ({$user->email}) - Role: {$user->getRoleNames()->implode(', ')}\n";
}
```

```sql
-- Aktivitas admin dalam 7 hari terakhir (via audit trail)
SELECT u.name as admin, a.action, a.target_type, a.created_at
FROM audit_logs a
JOIN users u ON a.user_id = u.id
WHERE a.created_at >= NOW() - INTERVAL 7 DAY
ORDER BY a.created_at DESC;

-- Deteksi gagal login
SELECT u.name, u.email, COUNT(*) as failed_attempts
FROM login_attempts la
JOIN users u ON la.user_id = u.id
WHERE la.success = false
  AND la.created_at >= NOW() - INTERVAL 24 HOUR
GROUP BY u.id
HAVING failed_attempts > 5;
```

---

#### 7.2.5 Perbaikan Bug & Resolusi Masalah

##### Alur Pelacakan Bug

**Tahap 1 — Identifikasi Masalah:**

| Sumber | Metode |
|--------|--------|
| Laporan pengguna | WhatsApp, email, atau form kontak |
| Monitoring sistem | Error log, failed jobs, alert performa |
| Testing | Pest test suite gagal di lokal |
| Code review | Potensi bug terdeteksi saat review PR |

**Tahap 2 — Klasifikasi Prioritas:**

| Prioritas | Definisi | Contoh | Waktu Respon |
|-----------|----------|--------|-------------|
| **Kritis (P0)** | Sistem down, data hilang, keamanan bocor | 500 error di semua halaman, database corrupt | Segera (1-4 jam) |
| **Tinggi (P1)** | Fitur utama tidak berfungsi | Registrasi gagal, email tidak terkirim | 1 hari |
| **Sedang (P2)** | Fitur non-kritis terganggu | Filter tidak akurat, UI tidak rapi | 3-7 hari |
| **Rendah (P3)** | Bug kosmetik | Typo, warna tidak sesuai | 14-30 hari |

**Tahap 3 — Perbaikan:**

```bash
# Buat branch baru
git checkout -b fix/P1-email-not-sending

# Identifikasi masalah dari log
tail -100 storage/logs/laravel.log | grep -A 5 "Brevo\|mail\|Mail"

# Perbaiki kode

# Tulis test
php artisan make:test Mail/BrevoMailTest
```

**Tahap 4 — Pengujian:**

```bash
# Jalankan test spesifik
php artisan test --filter=BrevoMailTest

# Jalankan semua test
php artisan test

# Pest: jalankan test dengan output verbose
./vendor/bin/pest --verbose
```

**Tahap 5 — Deployment:**

```bash
git add .
git commit -m "fix: perbaiki pengiriman email Brevo timeout"
git push origin fix/P1-email-not-sending
# Buat PR, review, merge ke master
git checkout master
git pull origin master
# Railway auto-deploy
git push origin master
```

**Tahap 6 — Update Dokumentasi:**

```bash
# Update CHANGELOG.md
echo "## [1.2.1] - $(date +%Y-%m-%d)" >> CHANGELOG.md
echo "### Fixed" >> CHANGELOG.md
echo "- Perbaiki timeout koneksi Brevo API saat pengiriman email" >> CHANGELOG.md
```

##### Prosedur Pengujian

```bash
# =====================
# Backend Testing (Pest)
# =====================

# Jalankan semua test
php artisan test

# Jalankan test dengan output detail
php artisan test --verbose

# Jalankan test spesifik (filter)
php artisan test --filter="UserRegistrationTest"

# Jalankan file test tertentu
php artisan test tests/Feature/Auth/LoginTest.php

# Jalankan test dengan coverage (butuh Xdebug/PCOV)
php artisan test --coverage

# =====================
# Code Quality
# =====================

# Laravel Pint: perbaiki style coding
./vendor/bin/pint

# Laravel Pint: dry run (cek tanpa mengubah)
./vendor/bin/pint --test
```

---

#### 7.2.6 Pemeriksaan Kesehatan Sistem

##### Checklist Pemeriksaan Mingguan

| # | Item Pemeriksaan | Metode | Kriteria Sukses |
|---|-----------------|--------|-----------------|
| 1 | Verifikasi halaman landing | `curl -I https://sipeka-sistem-pengaduan-masyarakat-production.up.railway.app` | HTTP 200 OK |
| 2 | Verifikasi halaman login | Buka halaman login di browser | Tampil normal, tidak error |
| 3 | Cek login & register | Buat akun test | Registrasi sukses, welcome email terkirim |
| 4 | Verifikasi pengaduan | Buat pengaduan test | Tersimpan, email konfirmasi terkirim |
| 5 | Cek AI verifikasi | Submit pengaduan dengan foto | Status terverifikasi/diverifikasi otomatis |
| 6 | Cek email Brevo | Periksa email masuk | Email sampai <5 detik |
| 7 | Disk space (Railway) | Cek dashboard Railway | Storage tidak penuh |
| 8 | Failed jobs | `php artisan queue:failed` | Tidak ada failed jobs |
| 9 | Log error | `tail -50 storage/logs/laravel.log` | Tidak ada error aneh |
| 10 | Database connection | Buka hapan yang pakai database | Semua halaman loading normal |

##### Checklist Pemeriksaan Bulanan

| # | Item Pemeriksaan | Detail |
|---|-----------------|--------|
| 1 | Review log pattern | Analisis tren error, identifikasi pola berulang |
| 2 | Analisis performa | Evaluasi response time, query berat, optimasi |
| 3 | Cek SSL certificate | Pastikan masih valid (Railway otomatis) |
| 4 | Test backup restore | Simulasi restore database |
| 5 | Update dependencies | `composer audit` + `npm audit` |
| 6 | Rotasi kredensial | Ganti API key Brevo, Groq, Google OAuth |
| 7 | Review user aktif | Nonaktifkan akun yang tidak aktif >90 hari |
| 8 | Optimasi database | `OPTIMIZE TABLE` untuk tabel besar |
| 9 | Review storage | Hapus file upload yang tidak terpakai |
| 10 | Backup data | Verifikasi semua backup berjalan sukses |

##### Mode Maintenance

```bash
# Aktifkan mode maintenance
php artisan down --secret="sekeam-maintenance"

# Akses aplikasi dengan token: https://domain/sekeam-maintenance
# Atau:
php artisan down --redirect=/maintenance-page

# Dengan pesan kustom
php artisan down \
  --message="Sistem sedang dalam pemeliharaan terjadwal. Akan kembali dalam 30 menit." \
  --retry=60

# Nonaktifkan mode maintenance
php artisan up
```

---

**Catatan:** Dokumen ini bersifat dinamis dan akan diperbarui seiring dengan perkembangan aplikasi. Setiap perubahan pada prosedur deployment, konfigurasi, atau alur maintenance harus dicatat dan direview secara berkala oleh tim pengembang.
