# Pembagian Tugas dan Kontribusi

Anggota:

- Nama: Jordan Rangga
- NIM : A12.2022.06929

## Rincian Tugas dan Kontribusi

1. Analisis Kebutuhan & Desain

   - Menentukan scope fitur booking online, profil pengguna, dan integrasi QR code.
   - Menyusun ERD dan dokumentasi awal.
   - File terkait: `database/schema.sql`, `PANDUAN-INSTALASI-DAN-PENGGUNAAN.md`.
2. Implementasi Backend (PHP)

   - Membuat controller untuk booking (`BookingController.php`) dan profil (`ProfileController.php`).
   - Menambahkan model `User.php` untuk pengelolaan profil pengguna.
   - Memastikan validasi input, autentikasi, dan kontrol akses.
   - File terkait: `app/controllers/BookingController.php`, `app/controllers/ProfileController.php`, `app/models/User.php`.
3. Implementasi Frontend (Views & CSS)

   - Menata ulang tampilan halaman booking (`app/views/booking/create.php`, `app/views/booking/user.php`).
   - Menyelaraskan gaya PixelactUI di `app/views/layout/style.php`.
   - Membuat halaman profil di `app/views/profile/edit.php`.
4. Integrasi Pihak Ketiga

   - Integrasi pembuatan QR code (`app/helpers/QRCode.php`) menggunakan `https://api.qrserver.com/v1/create-qr-code/`.
   - Integrasi notifikasi WhatsApp (opsional) melalui `app/helpers/WhatsAppNotifier.php`.
5. Testing & Deployment

   - Menjalankan UAT sederhana di lingkungan lokal (XAMPP).
   - Memperbaiki bug tampilan dan logika selama testing.
6. Dokumentasi

   - Menulis `README.md` dan `PANDUAN-INSTALASI-DAN-PENGGUNAAN.md`.
   - Menulis `CONTRIBUTIONS.md` (file ini).

---

Jika ada perubahan tugas atau tambahan anggota, beri tahu agar saya perbarui dokumen ini.
