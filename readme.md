# 🐾 PetHotelku - Sistem Informasi Penitipan & Layanan Hewan Peliharaan

## 📝 Deskripsi Proyek

**PetHotelku** adalah aplikasi berbasis web yang dirancang untuk memudahkan pemilik hewan peliharaan dalam melakukan reservasi penitipan dan layanan perawatan seperti grooming dan vaksinasi. Platform ini menyediakan fitur registrasi pengguna, pemesanan layanan, pelacakan status pesanan, riwayat layanan, serta sistem login multi-role (admin & user).

---

## 🎯 Fitur Utama

- **Autentikasi Pengguna**: Sistem login dan registrasi terpisah untuk admin dan pengguna.
- **Pemesanan Layanan**: Pengguna dapat memilih fasilitas dan memesan layanan penitipan, grooming, dll.
- **Mencetak PDF**: Pengguna dapat mencetak struk dan admin dapat mencetak laporan keuangan.
- **Riwayat Layanan**: Menyimpan catatan lengkap semua layanan yang pernah digunakan.
- **Upload Bukti Pembayaran**: Pengguna dapat mengunggah Bukti Pembayaran (maks. 2 MB).
- **Dashboard Admin**: Admin dapat mengelola konfirmasi pembayaran, Data Pemesanan, pengguna, layanan, fasilitas, dan Laporan.

---

## 💻 Teknologi yang Digunakan

| Komponen     | Detail                         |
|--------------|--------------------------------|
| **Bahasa**   | HTML, CSS, PHP 8.1.25          |
| **Framework**| Bootstrap 5                    |
| **Database** | MySQL (`db_petservice`)        |
| **Server**   | XAMPP 8.1.25                   |
| **Browser**  | Google Chrome                  |
| **Rancangan**| Draw.io                        |

---

## ⚙️ Instalasi & Konfigurasi

### 📌 Prasyarat

Pastikan perangkat Anda telah menginstal:

- XAMPP `8.1.25` atau lebih baru
- Browser modern (Google Chrome direkomendasikan)
- Code editor (VS Code disarankan)

### 📥 Langkah Instalasi

1. **Clone repositori**
   ```bash
   git clone https://github.com/namakamu/pethotelku.git
   
2. **Pindahkan folder ke direktori XAMPP dan impor database**
- Salin folder pethotelku ke dalam direktori htdocs di XAMPP Anda:
  ```bash
   C:\xampp\htdocs\pethotelku
- Buka browser dan navigasi ke http://localhost/phpmyadmin
- Buat database baru dengan nama: db_petservice
- Impor file SQL dari: database/db_petservice.sql

3. **Atur Koneksi database**
- Buka file config/database.php menggunakan code editor
- Pastikan konfigurasi koneksi sesuai:
   ```bash
   $host = "localhost";
   $user = "root";
   $pass = "";
   $dbname = "db_petservice";

4. **Jalankan proyek**
Buka browser dan akses:
http://localhost/pethotelku/

### 🚀 Rencana Pengembangan
Fitur yang direncanakan di masa mendatang:
- Integrasi payment gateway
- Fitur notifikasi email
- Live chat support untuk komunikasi langsung
- Optimisasi tampilan mobile secara menyeluruh

### 🤝 Kontribusi
Kontribusi sangat terbuka! Anda bisa:
- Membuka issue baru untuk diskusi ide/bug
- Lakukan Pull Request setelah membuat perubahan yang sesuai
