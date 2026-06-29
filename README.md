# Badminton Court Booking

Aplikasi booking/reservasi penggunaan lapangan bulutangkis berbasis PHP MVC sederhana dan MySQL lokal.

## Fitur Utama

- Arsitektur MVC dengan folder `controllers`, `models`, dan `views`.
- Autentikasi register/login dengan `password_hash()` dan `password_verify()`.
- Otorisasi role `admin` dan `user`.
- CRUD penuh untuk modul utama manajemen lapangan.
- Upload foto lapangan ke folder `uploads`.
- Reservasi lapangan oleh user biasa.
- Admin dapat menyetujui, membatalkan, dan menyelesaikan reservasi.
- Deteksi bentrok jadwal booking pada lapangan dan tanggal yang sama.
- Laporan lapangan dan export CSV.
- Prepared statements digunakan pada query input pengguna.

## Struktur Folder

- `app/controllers` berisi controller aplikasi.
- `app/models` berisi model dan query database.
- `app/views` berisi tampilan halaman.
- `app/helpers` berisi helper auth dan settings.
- `config/database.php` berisi koneksi MySQL lokal.
- `database/schema.sql` berisi struktur database dari nol.
- `database/seed.php` berisi data contoh lapangan.
- `uploads` berisi foto lapangan yang diunggah.

## Cara Menjalankan di XAMPP

1. Letakkan folder proyek di `c:/xampp/htdocs/airport_inventory`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka phpMyAdmin.
4. Import `database/schema.sql`.
5. Buka `http://localhost/IkiNet/public/`.
6. Register akun pertama. Akun pertama otomatis mendapat role `admin`.

## Database

Database default: `badminton_booking`.

Jika ingin memakai nama database lain, ubah nilai `$db` di `config/database.php`.

Tabel utama:

- `users`: akun pengguna dan role.
- `courts`: data lapangan untuk CRUD utama.
- `bookings`: data reservasi lapangan.
- `settings`: konfigurasi nama aplikasi.

## Role

- `admin`: kelola lapangan, upload foto, lihat semua reservasi, dan ubah status booking.
- `user`: melihat lapangan, membuat reservasi, dan membatalkan booking miliknya sendiri.
