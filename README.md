# Booking Lapangan

Aplikasi booking lapangan olahraga (futsal, badminton) dengan panel admin,
API, notifikasi antrean, dan jadwal realtime. Dibuat untuk mengeksplorasi
ekosistem Laravel secara menyeluruh.

## Fitur

- Booking dengan validasi anti-bentrok jadwal (dengan row locking untuk race condition)
- Login email/password (Breeze + Livewire) dan Google (Socialite)
- Role & permission: admin, staff, customer (Spatie Permission)
- Panel admin (Filament): kelola lapangan, kelola booking, widget statistik
- REST API dengan token (Sanctum) + dokumentasi otomatis (Scramble di `/docs/api`)
- Notifikasi email & auto-cancel booking belum dibayar via queue job
- Jadwal lapangan update realtime tanpa reload (Reverb + Livewire)
- Monitoring: Telescope (debug request/query) dan Pulse (performa)

## Stack

Laravel 13 · Filament 5 · Livewire 4 · Sanctum · Reverb · Spatie Permission · Scramble

## Instalasi

\`\`\`bash
git clone https://github.com/SulthanAfif/booking-lapangan.git
cd booking-lapangan
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
\`\`\`

### Menjalankan (development, 3 terminal)

\`\`\`bash
php artisan serve
php artisan queue:work
php artisan reverb:start
\`\`\`

### Akun demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@booking.test | password |

## Catatan environment

- Development dilakukan di Windows: **Horizon dan Octane tidak dijalankan di lokal**
  karena keduanya butuh ekstensi `pcntl` yang tidak tersedia di PHP native Windows.
  Sebagai gantinya dipakai `queue:work` biasa. Keduanya tetap terpasang di `composer.json`
  dan siap dipakai saat deploy ke server Linux.
- Queue driver: `database` (bukan Redis), supaya development tidak butuh dependensi tambahan.

## Branch eksplorasi

Selain `main`, ada tiga branch pembanding (tidak di-merge, untuk eksplorasi):
- `exp/jetstream` — **tidak jadi diimplementasikan.** Jetstream (per Sept 2026) masih
  mewajibkan Livewire ^3.6.4, sedangkan Filament v5 di project ini mewajibkan Livewire ^4.1.
  Keduanya belum bisa dipasang bersamaan sampai Jetstream merilis dukungan Livewire v4.
- `exp/passport` — Passport OAuth2 vs Sanctum, dipisah lewat model `Partner`
- `exp/inertia` — Inertia+Vue vs Livewire untuk komponen jadwal

## Screenshot

(tambahkan screenshot: panel admin, jadwal realtime, halaman dokumentasi API Scramble)