#  Dashboard Pusat Kemasan 📦

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)

Sistem Manajemen Pesanan, Produksi, dan Inventori Terintegrasi yang dirancang untuk mengoptimalkan alur kerja bisnis percetakan dan kemasan.

---

### Tampilan Aplikasi

https://github.com/user-attachments/assets/6e2d1a3c-5353-4811-96d5-a6e5b2259b36



*(Saran: Anda bisa merekam layar aplikasi Anda menggunakan aplikasi seperti **ScreenToGif** atau **LICEcap**, lalu unggah sebagai GIF ke repositori GitHub ini untuk membuat README lebih hidup)*

---

## 🚀 Tentang Proyek Ini

**Dashboard Pusat Kemasan** adalah aplikasi web internal yang dibangun untuk mengelola seluruh siklus bisnis, mulai dari pencatatan pesanan oleh kasir, proses desain oleh desainer, manajemen produksi oleh operator, hingga pemantauan laporan oleh manajer. Aplikasi ini dirancang dengan antarmuka yang bersih, responsif, dan interaktif untuk memastikan kemudahan penggunaan bagi setiap role.

## ✨ Fitur Utama

Aplikasi ini memiliki sistem otentikasi dan otorisasi berbasis peran (*role-based*). Setiap peran memiliki akses dan tugas yang spesifik:

### 👤 **Admin**
- **Manajemen User:** Kemampuan penuh (CRUD - Create, Read, Update, Delete) untuk mengelola semua akun pengguna dan peran mereka.

### 💰 **Kasir**
- **Manajemen Pesanan:** Form input pesanan yang dinamis dan detail.
- **Daftar Pesanan Aktif:** Melihat semua pesanan yang sedang dalam proses.
- **Pembayaran & Invoice:** Proses pembayaran dengan pop-up invoice interaktif yang bisa dicetak.
- **Riwayat Pesanan:** Melihat arsip semua pesanan yang telah selesai dan lunas.
- **Buku Kas:** Mencatat semua transaksi uang masuk dan uang keluar secara manual. Pembayaran pesanan akan otomatis tercatat sebagai uang masuk.

### 🎨 **Designer**
- **Dashboard Tugas:** Halaman khusus yang menampilkan antrian pesanan, terbagi menjadi "Perlu Didesain" dan "Perlu Diverifikasi".
- **Detail & Konfirmasi:** Melihat detail lengkap pesanan dan memberikan konfirmasi bahwa desain siap untuk dilanjutkan ke produksi.

### ⚙️ **Operator**
- **Antrian Produksi:** Dashboard untuk melihat pesanan yang siap diproduksi dan yang sedang dalam proses produksi.
- **Update Status Produksi:** Mengubah status pesanan dari "Siap Produksi" -> "Sedang Diproduksi" -> "Produksi Selesai".
- **Manajemen Inventory:**
  - Mengelola produk dengan varian (tipe, ukuran, dll).
  - Form dinamis untuk menambah produk beserta variannya.
  - Fitur *Stock Opname* interaktif dengan tombol `+` dan `-` melalui pop-up untuk menyesuaikan stok, lengkap dengan pencatatan riwayat perubahan stok.
  - Mengelola stok spare part (fitur dasar sudah siap).

### 📊 **Manajer**
- **Laporan Keuangan:** Dashboard interaktif dengan filter rentang waktu.
- **Visualisasi Data:** Grafik garis dinamis yang menampilkan tren pemasukan vs. pengeluaran harian.
- **Ringkasan Statistik:** Kartu statistik untuk total pemasukan, pengeluaran, dan laba/rugi bersih.
- **Ekspor & Cetak:** Kemampuan untuk mengunduh laporan ke dalam format Excel dan mencetak laporan dengan tampilan yang rapi.

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP 8.x, Laravel 12.x
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Database:** MySQL
- **Lainnya:** Chart.js, Laravel Excel

---

## ⚙️ Panduan Instalasi & Setup Lokal

1.  **Clone repositori ini:**
    ```bash
    git clone [https://github.com/USERNAME-ANDA/dashboard-kemasan.git](https://github.com/USERNAME-ANDA/dashboard-kemasan.git)
    cd dashboard-kemasan
    ```

2.  **Install dependensi PHP (Composer):**
    ```bash
    composer install
    ```

3.  **Install dependensi JavaScript (NPM):**
    ```bash
    npm install
    ```

4.  **Konfigurasi Lingkungan:**
    - Salin file `.env.example` menjadi `.env`.
      ```bash
      cp .env.example .env
      ```
    - Buat kunci aplikasi baru:
      ```bash
      php artisan key:generate
      ```
    - Atur koneksi database Anda di file `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

5.  **Setup Database:**
    - Buat database baru di MySQL dengan nama yang Anda atur di `.env`.
    - Jalankan migrasi dan seeder untuk membuat semua tabel dan data awal (termasuk roles):
      ```bash
      php artisan migrate:fresh --seed
      ```

6.  **Buat Storage Link:**
    ```bash
    php artisan storage:link
    ```

7.  **Jalankan Aplikasi:**
    - Buka dua terminal.
    - Di terminal pertama, jalankan Vite untuk kompilasi aset:
      ```bash
      npm run dev
      ```
    - Di terminal kedua, jalankan server development Laravel:
      ```bash
      php artisan serve
      ```

8.  **Buat Akun Admin Pertama:**
    - Buka aplikasi di browser (`http://127.0.0.1:8000`).
    - Klik **Register** dan buat akun baru.
    - Buka database Anda (misal: dengan phpMyAdmin), masuk ke tabel `role_user`, dan tambahkan baris baru untuk menghubungkan `user_id` Anda dengan `role_id` untuk "admin" (biasanya ID `1`).

---

## 👨‍💻 Dibuat Oleh

* **Vi Abdillah**
* GitHub: `@viabdillah(https://github.com/viabdillah)`
