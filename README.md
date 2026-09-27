# Booking Lapangan

Aplikasi booking lapangan olahraga (futsal, badminton) — mulai dari validasi
jadwal bentrok, panel admin, REST API dengan dokumentasi otomatis, notifikasi
antrean, sampai jadwal yang update *realtime* tanpa reload. Dibuat sebagai
eksplorasi menyeluruh ekosistem Laravel modern.

![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20)
![Filament](https://img.shields.io/badge/Filament-5-F59E0B)
![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6)

## Daftar isi

- [Fitur](#fitur)
- [Stack](#stack)
- [Arsitektur singkat](#arsitektur-singkat)
- [Instalasi](#instalasi)
- [Menjalankan (development)](#menjalankan-development)
- [Akun demo](#akun-demo)
- [Environment variables](#environment-variables)
- [Testing](#testing)
- [Struktur proyek](#struktur-proyek)
- [Catatan environment Windows](#catatan-environment-windows)
- [Branch eksplorasi](#branch-eksplorasi)
- [Roadmap](#roadmap)

## Fitur

**Booking & jadwal**
- Validasi anti-bentrok jadwal (dua booking di lapangan & jam yang sama ditolak)
- Aman dari race condition — pengecekan dan penyimpanan dibungkus row locking (`lockForUpdate`) dalam satu transaksi DB
- Jadwal per lapangan menampilkan slot per jam, update **realtime** ke semua orang yang sedang membuka halaman yang sama (Laravel Reverb + Livewire), tanpa reload

**Autentikasi & akses**
- Login email/password (Laravel Breeze, stack Livewire)
- Login dengan Google (Laravel Socialite)
- Role & permission: `admin`, `staff`, `customer` (Spatie Permission)

**Panel admin** (Filament, `/admin`)
- Kelola lapangan (`Field`) dan booking (`Booking`)
- Booking yang dibuat lewat panel admin tetap melewati validasi anti-bentrok yang sama seperti booking pelanggan
- Widget statistik: booking hari ini, pendapatan bulan ini, booking pending

**API**
- REST API dengan autentikasi token (Laravel Sanctum), prefix `/api/v1`
- Dokumentasi API dibuat otomatis dari route + Form Request (Dedoc Scramble), lihat di `/docs/api`

**Antrean & notifikasi**
- Email konfirmasi booking dikirim lewat queue job (`SendBookingConfirmation`)
- Booking yang belum dibayar otomatis dibatalkan setelah 30 menit (`CancelUnpaidBooking`, job dengan delay)
- Queue driver: `database` (tanpa dependensi Redis untuk development)

**Monitoring**
- Laravel Telescope (`/telescope`) — request, query, dan job, khusus development
- Laravel Pulse (`/pulse`) — agregat performa aplikasi

## Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Panel admin | Filament 5 |
| Frontend interaktif | Livewire 4 |
| Autentikasi | Laravel Breeze, Laravel Socialite |
| Otorisasi | Spatie Laravel Permission |
| API | Laravel Sanctum, Dedoc Scramble |
| Realtime | Laravel Reverb |
| Queue | Database driver (production: siap dipakai dengan Redis) |
| Monitoring | Laravel Telescope, Laravel Pulse |
| Performa (production) | Laravel Octane (FrankenPHP) |

## Arsitektur singkat

Semua aturan booking (validasi jam, harga, anti-bentrok, dispatch job dan
event) hidup di satu tempat: `app/Services/BookingService.php`. Baik
controller API, halaman Livewire pelanggan, maupun resource Filament untuk
admin, semuanya memanggil service yang sama — jadi aturan bisnisnya konsisten
di semua jalur masuk, tidak ditulis ulang di tiap tempat.

```
Pelanggan (Livewire)  ─┐
Panel Admin (Filament) ─┼─▶ BookingService::create() ─▶ Booking tersimpan
API (Sanctum)          ─┘        │
                                  ├─▶ dispatch SendBookingConfirmation (email)
                                  ├─▶ dispatch CancelUnpaidBooking (delay 30 menit)
                                  └─▶ broadcast BookingCreated (Reverb → Livewire lain ikut update)
```

## Instalasi

Prasyarat: PHP 8.3+, Composer, Node.js, dan database (MySQL/PostgreSQL — atau
SQLite untuk percobaan cepat).

```bash
git clone https://github.com/SulthanAfif/booking-lapangan.git
cd booking-lapangan

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Atur koneksi database di `.env`, lalu:

```bash
php artisan migrate --seed
npm run build
```

Seeder akan membuat data lapangan contoh, role & permission, serta satu akun
admin (lihat [Akun demo](#akun-demo)).

## Menjalankan (development)

Jalankan tiga proses berikut secara bersamaan (masing-masing di terminal terpisah, biarkan tetap terbuka):

```bash
php artisan serve        # aplikasi web, http://localhost:8000
php artisan queue:work   # proses email & auto-cancel booking
php artisan reverb:start # server realtime untuk update jadwal
```

Buka:

| Halaman | URL |
|---|---|
| Aplikasi (pelanggan) | http://localhost:8000 |
| Daftar lapangan | http://localhost:8000/booking |
| Panel admin | http://localhost:8000/admin |
| Dokumentasi API | http://localhost:8000/docs/api |
| Telescope | http://localhost:8000/telescope |
| Pulse | http://localhost:8000/pulse |

## Akun demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@booking.test` | `password` |

Akun `customer` dibuat sendiri lewat halaman **Register**, atau lewat **Login dengan Google**.

## Environment variables

Selain variabel bawaan Laravel, tambahkan ini di `.env`:

```env
# Login Google (Socialite)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Realtime (Reverb) — nilai default dari `php artisan install:broadcasting`
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Queue — driver database, tidak butuh Redis di lokal
QUEUE_CONNECTION=database

# Email dicek lewat log saat development
MAIL_MAILER=log
```

Kredensial Google OAuth didapat dari [Google Cloud Console](https://console.cloud.google.com) — buat OAuth Client ID tipe "Web application" dengan redirect URI seperti di atas.

## Testing

```bash
php artisan test
```

Cakupan test saat ini:
- `BookingServiceTest` — booking berhasil, jadwal bentrok ditolak, jadwal yang bersambung (tidak overlap) diperbolehkan
- `RoleAccessTest` — customer tidak bisa akses route khusus admin
- `FilamentBookingTest` — akses panel `/admin` dibatasi sesuai role

> **Catatan:** test yang melibatkan job berdelay (`CancelUnpaidBooking`) butuh
> `QUEUE_CONNECTION=database` di `phpunit.xml`, bukan `sync` — dengan `sync`,
> delay diabaikan dan job langsung jalan di request yang sama, yang bisa
> membuat assert jadi salah.

## Struktur proyek

Bagian yang spesifik untuk domain aplikasi ini (di luar struktur standar Laravel):

```
app/
├── Enums/BookingStatus.php          # pending | paid | cancelled
├── Services/BookingService.php      # satu-satunya jalur membuat booking
├── Jobs/
│   ├── SendBookingConfirmation.php
│   └── CancelUnpaidBooking.php
├── Events/BookingCreated.php        # broadcast ke channel fields.{id}
├── Livewire/FieldSchedule.php       # grid slot jam + form booking pelanggan
└── Filament/Resources/
    ├── Fields/                      # CRUD lapangan
    └── Bookings/                    # CRUD booking (lewat BookingService)

resources/views/
├── booking/                         # halaman pelanggan (index & show)
└── livewire/field-schedule.blade.php
```

## Catatan environment Windows

Development dilakukan di Windows, yang punya beberapa keterbatasan dibanding
Linux untuk tooling Laravel tertentu:

- **Laravel Horizon tidak dipakai.** Horizon butuh ekstensi `pcntl`/`posix`
  untuk proses supervisor-nya, yang tidak tersedia di PHP native Windows.
  Sebagai gantinya dipakai `php artisan queue:work` biasa — job yang sama
  persis, cuma tanpa dashboard.
- **Laravel Octane tidak dijalankan di lokal**, karena alasan yang sama
  (`pcntl` untuk signal handling). Octane tetap terpasang di `composer.json`
  dan siap dipakai saat deploy ke server Linux:
  ```bash
  php artisan octane:start --server=frankenphp
  ```
- Keduanya bukan batasan aplikasi — hanya batasan menjalankan development
  di Windows. Di server production (Linux), keduanya bisa dipakai penuh.

## Branch eksplorasi

Selain `main`, ada tiga branch untuk membandingkan pendekatan lain (tidak di-merge, disimpan sebagai eksplorasi):

| Branch | Isi |
|---|---|
| `exp/jetstream` | Jetstream sebagai alternatif Breeze untuk autentikasi (fitur teams & 2FA) |
| `exp/passport` | Passport OAuth2 sebagai alternatif Sanctum, dipisah lewat model `Partner` |
| `exp/inertia` | Inertia + Vue sebagai alternatif Livewire untuk halaman jadwal |

## Roadmap

- [ ] GitHub Actions untuk menjalankan test otomatis tiap push
- [ ] Integrasi payment gateway untuk status `paid`
- [ ] Riwayat booking pelanggan di halaman profil
- [ ] Notifikasi WhatsApp/SMS selain email

---

Dibuat oleh [Muhammad Sulthan Muqsith Afif](https://github.com/SulthanAfif) sebagai proyek portofolio.
