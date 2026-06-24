# Website Tes Ujian Penerimaan Mahasiswa KIP-K

Starter Laravel 13 untuk seleksi penerimaan mahasiswa KIP-K dengan dua role: `admin` dan `user`. Backend memakai Laravel, database siap MySQL, tampilan Blade, Bootstrap, JavaScript vanilla/AJAX, migration, seeder, middleware role, dan service perhitungan SAW.

## Alur Sistem

1. Pendaftar register, login, melengkapi profil, dan mengunggah dokumen.
2. Admin melihat data pendaftar, memverifikasi atau menolak pendaftaran.
3. Admin mengelola kriteria, sub-kriteria, bobot, dan jenis benefit/cost.
4. Admin input nilai alternatif untuk setiap pendaftar terverifikasi.
5. Sistem memproses SAW: normalisasi, nilai terbobot, nilai akhir, ranking, dan status penerima sesuai kuota.
6. Admin mengumumkan hasil, pendaftar melihat hasil setelah diumumkan.

## Struktur Penting

- `app/Models`: User, StudentProfile, Criteria, SubCriteria, Alternative, AlternativeScore, SawResult, Document, Notification.
- `app/Http/Controllers`: controller auth, user, admin, SAW, laporan, notifikasi.
- `app/Http/Middleware/RoleMiddleware.php`: proteksi role admin/user.
- `app/Services/SawCalculationService.php`: logika normalisasi, nilai akhir, ranking, dan kuota.
- `database/migrations`: semua tabel utama.
- `database/seeders/DatabaseSeeder.php`: admin default, kriteria, dan sub-kriteria.
- `resources/views`: layout, auth, halaman user, halaman admin, SAW, laporan.
- `public/js/app.js`: sidebar, password toggle, preview file, AJAX status, AJAX proses SAW, Chart.js.

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Buat database MySQL:

```sql
CREATE DATABASE kipk_saw;
```

Pastikan `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kipk_saw
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Menjalankan untuk User Berbeda Jaringan

Untuk perangkat yang masih satu WiFi/LAN, jalankan:

```powershell
.\serve-lan.ps1
```

Lalu buka alamat LAN yang muncul di terminal, misalnya `http://192.168.1.97:8002`.

Untuk user dari jaringan berbeda/internet, jalankan:

```powershell
.\serve-public.ps1
```

Skrip ini akan:

- menjalankan Laravel di `http://127.0.0.1:8000`
- membuat Cloudflare Tunnel publik
- menampilkan URL `https://...trycloudflare.com` yang bisa dibuka dari jaringan lain

Biarkan terminal tetap terbuka selama website dipakai. Jika terminal ditutup, URL publik ikut mati. Untuk pemakaian serius/produksi, pindahkan aplikasi ke hosting atau gunakan Cloudflare Tunnel bernama dengan domain tetap.

Login admin default:

- Email: `admin@kipk.test`
- Password: `password`

## Contoh Perhitungan SAW Manual

Misal bobot: C1 nilai ujian `0.35` benefit, C2 penghasilan `0.25` cost, C3 tanggungan `0.20` benefit.

Data:

| Alternatif | C1 | C2 | C3 |
| --- | ---: | ---: | ---: |
| A1 | 80 | 2000000 | 3 |
| A2 | 90 | 3000000 | 4 |

Normalisasi:

- C1 benefit: A1 `80/90 = 0.8889`, A2 `90/90 = 1`
- C2 cost: A1 `2000000/2000000 = 1`, A2 `2000000/3000000 = 0.6667`
- C3 benefit: A1 `3/4 = 0.75`, A2 `4/4 = 1`

Nilai akhir:

- A1: `(0.35*0.8889) + (0.25*1) + (0.20*0.75) = 0.7111`
- A2: `(0.35*1) + (0.25*0.6667) + (0.20*1) = 0.7167`

Ranking: A2 peringkat 1, A1 peringkat 2.

## Testing

```bash
php artisan route:list
php artisan test
```

## Export/Cetak Laporan

Buka menu Admin `Laporan`, lalu klik `Cetak`. Halaman print memakai route `/admin/reports/print` dan otomatis membuka dialog cetak browser.

## Pengembangan Lanjutan

- Tambahkan Laravel Breeze/Fortify bila ingin email verification dan reset password lengkap.
- Tambahkan policy per model untuk aturan akses yang lebih granular.
- Tambahkan export Excel/PDF memakai `maatwebsite/excel` atau DomPDF.
- Tambahkan audit log verifikasi admin dan riwayat perubahan bobot kriteria.
- Tambahkan queue untuk notifikasi massal dan upload dokumen besar.
