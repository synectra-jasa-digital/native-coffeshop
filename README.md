# Sistem POS Terpadu Coffee Shop

Sistem Point of Sale (POS), Manajemen Stok (Inventory), dan Laporan khusus untuk Coffee Shop skala tunggal (Single Outlet).

![Dashboard Utama](screenshot/02_dashboard.png)

## Tangkapan Layar (Screenshots)

Berikut adalah beberapa tampilan utama aplikasi yang tersimpan di direktori [`/screenshot`](screenshot/):

### 1. Antarmuka Utama & Kasir (POS)
| Halaman Login | Point of Sale (POS) |
|---|---|
| ![Login](screenshot/01_login.png) | ![POS](screenshot/03_pos.png) |

| Buka Shift Kasir | Tutup Shift Kasir |
|---|---|
| ![Buka Shift](screenshot/04_shift_open.png) | ![Tutup Shift](screenshot/05_shift_close.png) |

### 2. Pesanan Meja & Layar Dapur (KDS)
| Kitchen Display System (KDS) | Digital Menu Pelanggan |
|---|---|
| ![KDS](screenshot/14_kds.png) | ![Digital Menu](screenshot/22_customer_menu.png) |

### 3. Manajemen Stok & Resep (HPP)
| Bahan Baku (Inventory) | Manajemen Resep |
|---|---|
| ![Bahan Baku](screenshot/10_ingredients.png) | ![Resep](screenshot/11_recipes.png) |

| Pergerakan Stok | Stock Opname |
|---|---|
| ![Pergerakan Stok](screenshot/12_movements.png) | ![Stock Opname](screenshot/13_opname.png) |

### 4. Pelaporan & Administrasi
| Laporan Penjualan Harian | Laporan Stok |
|---|---|
| ![Laporan Harian](screenshot/15_reports_daily.png) | ![Laporan Stok](screenshot/16_reports_stock.png) |

| Manajemen Meja | Manajemen Produk |
|---|---|
| ![Meja](screenshot/07_tables.png) | ![Produk](screenshot/08_products.png) |

> *Daftar seluruh tangkapan layar lengkap (22 file) tersedia secara utuh di folder [`/screenshot`](screenshot/).*

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
5. Buka terminal pada root direktori project, jalankan script instalasi tabel (`php database/install.php` atau import manual dari `/database/schema.sql`).
6. Akses aplikasi melalui browser di `http://localhost/native-coffeshop/public` (atau sesuai konfigurasi vhost/path web server Anda).

## Aturan Struktur Folder

- `app/Controllers/`: Berisi logika aplikasi penghubung input dan view.
- `app/Models/`: Berisi entitas dan operasi CRUD database (PDO).
- `app/Views/`:
  - `components/`: Kumpulan elemen antarmuka yang dapat digunakan berulang (tombol, input form, alert).
  - `layouts/`: Master file (header, sidebar, container utama).
  - `pages/`: Halaman-halaman fitur spesifik.
- `app/Core/`: File-file inti framework custom (Router, Database wrapper, Base Controller).

## Fitur Utama

- **Point of Sale (POS):** Antarmuka kasir cepat dengan interaksi AJAX (Alpine.js) dan fitur buka/tutup Shift. Mendukung cetak struk Thermal 58mm.
- **KDS & Manajemen Meja:** QR Code Menu digital (*responsive* web/mobile) terintegrasi langsung dengan Kitchen Display System (KDS) yang mengurangi stok bahan otomatis secara akurat.
- **Role-Based Access Control (RBAC):** Dashboard terpisah dengan widget unik dan sidebar khusus berdasarkan akses (Admin, Manager, Kasir, Dapur). Perekaman Log Aktivitas lengkap.
- **Manajemen Inventori & Produk:** Variasi produk, resep HPP dinamis, dan *Stock Opname* sinkron langsung ke database dan log pergerakan stok tanpa hambatan *pending approval*.
- **Pelaporan & Ekspor PDF:** Laporan Keuangan (Harian, Bulanan, Tahunan) format Akuntansi resmi (font Times New Roman, pengkategorian tunai/non-tunai, kolom pengesahan).

## Desain & UI

Pengaturan warna dan layout berpedoman penuh pada `DESIGN_GUIDELINE.md` dan panduan desain internal. Tampilan utama menggunakan skema **Operate Mode** (Utilitarian, padat, responsif Inter) pada dashboard internal dan **Persuade Mode** (Ruang bernafas lega, font Playfair Display) pada area eksternal pelanggan (Digital Menu). Menggunakan Tailwind CDN.
