# Sistem POS Terpadu Coffee Shop

Sistem Point of Sale (POS), Manajemen Stok (Inventory), dan Laporan khusus untuk Coffee Shop skala tunggal (Single Outlet).

## Stack Teknologi
- **Bahasa:** PHP Native murni (tanpa framework)
- **Arsitektur:** MVC Custom
- **Database:** MySQL (dengan PDO)
- **Tampilan:** HTML, Tailwind CSS (via CDN), Alpine.js (via CDN) untuk state interaktif.
- **Ikon:** Heroicons atau sejenisnya.

## Panduan Instalasi (Development)

1. Pastikan Anda memiliki PHP (minimal versi 7.4/8.x) dan MySQL yang sedang berjalan (menggunakan XAMPP, Laragon, dsb).
2. Clone atau salin repository ini ke direktori web server lokal Anda.
3. Konfigurasi Database:
   - Buka file `config/config.php`
   - Sesuaikan `DB_HOST`, `DB_USER`, `DB_PASS`, dan `DB_NAME` dengan pengaturan lokal Anda.
   - Setup dasar (url dasar) `BASE_URL` juga ada di file tersebut.
4. Buat database baru di MySQL dengan nama `pos_coffeeshop`.
5. Buka terminal pada root direktori project, jalankan script instalasi tabel (akan disiapkan: `php database/install.php` atau import manual dari `/database/schema.sql`).
6. Akses aplikasi melalui browser di `http://localhost/native-coffeshop/public` (atau sesuai konfigurasi vhost/path web server Anda).

## Aturan Struktur Folder

- `app/Controllers/`: Berisi logika aplikasi penghubung input dan view.
- `app/Models/`: Berisi entitas dan operasi CRUD database (PDO).
- `app/Views/`:
  - `components/`: Kumpulan elemen antarmuka yang dapat digunakan berulang (tombol, input form, alert).
  - `layouts/`: Master file (header, sidebar, container utama).
  - `pages/`: Halaman-halaman fitur spesifik.
- `app/Core/`: File-file inti framework custom (Router, Database wrapper, Base Controller).

## Desain & UI

Pengaturan warna dan layout berpedoman penuh pada `DESIGN_GUIDELINE.md`. Tampilan utama menggunakan skema **Operate** (Utilitarian, padat, responsif). Menggunakan Tailwind CDN.
