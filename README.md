# IMPLEMENTASI BASIS DATA RELASIONAL DAN DATA WAREHOUSE MENGGUNAKAN STAR SCHEMA PADA PLATFORM MARKETPLACE 'DATAMATE
# Identitas Kelompok
Nomor Kelompok : C(3)
Anggota Kelompok :
1. JOSHUA GIOVANNI MULYANTO (164231046)
2. MANISA (164231050)
3. CHELSEA DHEIRRANAYA SITINJAK (164231051)
4. VENEDICT GRINALDY PRASETYO (164231063)
5. AFLAH ZAIN JAPAMEL (164231085)

# DataMate

DataMate adalah platform web yang menghubungkan Klien dengan Analis Data profesional. Aplikasi ini dibangun menggunakan framework Laravel 12, Tailwind CSS, Javascript, dan MySQL.

## Prasyarat Sistem

Sebelum menjalankan aplikasi ini, pastikan komputer Anda telah terinstal:

- **PHP** >= 8.2
- **Composer** (Manajer dependensi PHP)
- **Node.js** & **NPM** (Untuk manajemen aset frontend)
- **Database Server** (MySQL via XAMPP, atau SQLite)

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek di komputer lokal:

1.  **Ekstrak File Proyek**
    Ekstrak file proyek yang telah diunduh ke dalam folder yang diinginkan.

2.  **Install Dependensi PHP**
    ```bash
    composer install
    ```

3.  **Install Dependensi Frontend**
    ```bash
    npm install
    ```

4.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Atau pada Windows (Command Prompt):
    ```cmd
    copy .env.example .env
    ```

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi Database**
    Buka file `.env` dan sesuaikan pengaturan database Anda (DB_DATABASE, DB_USERNAME, DB_PASSWORD). Jika menggunakan XAMPP/MySQL:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    *Pastikan Anda telah membuat database kosong dengan nama yang sesuai di phpMyAdmin.*

7.  **Migrasi Database**
    Jalankan migrasi untuk membuat tabel-tabel yang diperlukan:
    ```bash
    php artisan migrate
    ```

## Cara Menjalankan Aplikasi

Untuk menjalankan aplikasi dalam mode development:

1.  **Jalankan Server Laravel**
    Buka terminal baru dan jalankan:
    ```bash
    php artisan serve
    ```
    Server akan berjalan di `http://localhost:8000`.

2.  **Akses Aplikasi**
    Buka browser dan kunjungi alamat `http://localhost:8000`.

## Fitur Utama

- **Autentikasi**: Registrasi dan Login untuk peran Client dan Analyst.
- **Dashboard**: Halaman dashboard khusus untuk setiap peran.
- **Pencarian Analis**: Klien dapat mencari dan melihat profil analis.
- **Manajemen Order**: Sistem pemesanan jasa analisis.
- **Analytics**: Visualisasi data performa dan tren yang dapat diakses oleh admin.
- **Dark/Light Mode**: Tampilan antarmuka yang dapat disesuaikan (Switch di header).

## Cara Mengakses Halaman Admin

Untuk mengakses halaman admin, Anda perlu login sebagai admin dengan kredensial:

- **Username**: admin@example.com
- **Password**: password

Lalu pergi ke halaman admin dengan mengakses `http://localhost:8000/admin`.

## Struktur Folder Penting

- `app/Http/Controllers`: Logika backend.
- `app/Models`: Model database (Eloquent).
- `resources/views`: Tampilan antarmuka (Blade templates).
- `routes/web.php`: Definisi rute aplikasi.
- `public/`: Aset publik (CSS, JS, Images).
- `database/migrations`: File skema database.

