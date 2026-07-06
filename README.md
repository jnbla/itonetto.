# Badminton Court Booking

Aplikasi sederhana untuk reservasi lapangan bulutangkis menggunakan PHP MVC dan MySQL.

## Fitur Utama

- Struktur MVC dengan `controllers`, `models`, dan `views`.
- Register dan login dengan password tersimpan aman menggunakan `password_hash()`.
- Role `admin` dan `user` untuk pembatasan akses.
- CRUD lengkap untuk manajemen lapangan.
- Upload foto lapangan ke folder `uploads`.
- User bisa membuat reservasi dan melihat riwayat booking.
- Admin bisa mengelola lapangan, melihat semua booking, dan mengubah status reservasi.
- Deteksi bentrok jadwal untuk booking lapangan.
- Export data lapangan ke CSV.
- Prepared statements digunakan di query input pengguna.

## Struktur Folder

- `app/controllers`: controller aplikasi.
- `app/models`: model dan query database.
- `app/views`: tampilan halaman.
- `app/helpers`: helper untuk auth, settings, upload, dll.
- `config/database.php`: konfigurasi koneksi database.
- `database/schema.sql`: struktur database.
- `database/seed.php`: data contoh untuk awal.
- `uploads`: tempat penyimpanan gambar lapangan.

## Cara Menjalankan di XAMPP

1. Letakkan folder proyek di `c:/xampp/htdocs/IkiNet`.
2. Jalankan Apache dan MySQL di XAMPP.
3. Buka phpMyAdmin.
4. Import `database/schema.sql`.
5. Akses `http://localhost/IkiNet/public/`.
6. Daftar akun baru. Akun pertama akan menjadi admin.

## Database

- Database default: `badminton_booking`.
- Ubah nama database pada `config/database.php` jika perlu.

Tabel utama:

- `users`: data akun dan role.
- `courts`: data lapangan.
- `bookings`: data reservasi.
- `settings`: konfigurasi aplikasi.

## Peran Pengguna

- `admin`: kelola lapangan, upload foto, lihat semua reservasi, dan ubah status booking.
- `user`: melihat lapangan, membuat reservasi, dan membatalkan reservasi sendiri.

1. [ ]
