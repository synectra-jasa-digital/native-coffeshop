|| No | Modul/Fitur | Status | Tanggal | Catatan |
||---|---|---|---|---|
|| 1 | Setup struktur MVC, dokumentasi, dan Tailwind CDN | Selesai | 04 Sep 2026 | Berhasil menyiapkan `DESIGN_GUIDELINE.md`, layout, dan CDN |
|| 2 | Persiapan Skema Database | Selesai | 04 Sep 2026 | Skema SQL untuk semua entitas (PRD bagian 11) dan seeder |
|| 3 | Autentikasi dan Dashboard (Modul A) | Selesai | 04 Sep 2026 | Implementasi form login, MVC Session, komponen UI, & Dashboard awal |
|| 4 | Manajemen Produk & Menu (Modul C) | Selesai | 04 Sep 2026 | Manajemen Kategori (AJAX Modal) dan Produk (CRUD & Varian) |
|| 5 | Stok dan Resep (Modul D) | Selesai | 04 Sep 2026 | CRUD Bahan Baku, Resep Produk (BoM), Pergerakan Stok, & Stock Opname (AJAX + Dialog Component) |
|| 6 | Point of Sale (Modul B) | Selesai | 04 Sep 2026 | Model Shift, Transaction, Table, Setting. Buka/Tutup Shift, POS Interface (Alpine.js Cart), Checkout, Riwayat Transaksi, Detail Transaksi, Void Transaksi. |
|| 7 | Laporan Dasar (Modul F) | Selesai | 04 Sep 2026 | Laporan Penjualan Harian, Laporan Stok & Nilai Stok, Laporan Kinerja Kasir per Shift. |
|| 8 | Manajemen Pengguna (Modul G) | Selesai | 04 Sep 2026 | CRUD Pengguna, Role Management, Log Aktivitas (Audit Trail). |
|| 9 | Pengaturan Sistem (Modul H) | Selesai | 04 Sep 2026 | Pengaturan Info Toko, Pajak & Service Charge, Metode Pembayaran. |

---

### Catatan Rilis Terakhir
- **Fase 1 (MVP) Sebagian Besar Sudah Selesai.**
- Telah diperbaiki masalah URL routing pada `index.php` dan `Router.php` sehingga dapat diakses via subdirektori (localhost/native-coffeshop).
- Model `Category` dan `Product` ditambahkan beserta method CRUD.
- Tampilan list produk dan kelola kategori interaktif dengan modal Alpine.js dan Fetch API.
- **Modul B (Point of Sale) Selesai:** Model Shift, Transaction, Table, Setting; Buka/Tutup Shift; POS Interface (Alpine.js Cart); Checkout; Riwayat Transaksi; Detail Transaksi; Void Transaksi.
- **Modul F (Laporan Dasar) Selesai:** Laporan Penjualan Harian; Laporan Stok & Nilai Stok; Laporan Kinerja Kasir per Shift.
- **Modul G (Manajemen Pengguna) Selesai:** CRUD Pengguna; Role Management; Log Aktivitas (Audit Trail).
- **Modul H (Pengaturan Sistem) Selesai:** Pengaturan Info Toko; Pajak & Service Charge; Metode Pembayaran.

### File yang Dibuat atau Diubah
- `app/Models/Shift.php` - Model untuk shift kasir
- `app/Models/Transaction.php` - Model untuk transaksi
- `app/Models/Table.php` - Model untuk meja
- `app/Models/Setting.php` - Model untuk pengaturan sistem
- `app/Models/ActivityLog.php` - Model untuk log aktivitas
- `app/Controllers/ShiftController.php` - Controller untuk shift (buka/tutup)
- `app/Controllers/TransactionController.php` - Controller untuk riwayat transaksi
- `app/Controllers/ReportController.php` - Controller untuk laporan
- `app/Controllers/UserController.php` - Controller untuk manajemen pengguna
- `app/Controllers/SettingController.php` - Controller untuk pengaturan sistem
- `app/Views/pages/shift/form_open.php` - View form buka shift
- `app/Views/pages/shift/form_close.php` - View form tutup shift
- `app/Views/pages/shift/history.php` - View riwayat shift
- `app/Views/pages/transactions/index.php` - View riwayat transaksi
- `app/Views/pages/transactions/detail.php` - View detail transaksi
- `app/Views/pages/reports/harian.php` - View laporan penjualan harian
- `app/Views/pages/reports/stok.php` - View laporan stok & nilai stok
- `app/Views/pages/reports/kasir_shift.php` - View laporan kinerja kasir per shift
- `app/Views/pages/users/index.php` - View daftar pengguna
- `app/Views/pages/users/form.php` - View form tambah/edit pengguna
- `app/Views/pages/users/activity_logs.php` - View log aktivitas
- `app/Views/pages/settings/index.php` - View pengaturan sistem
- `app/Controllers/PosController.php` - Diupdate untuk integrasi dengan Shift dan Setting
- `app/Models/User.php` - Diupdate dengan method getAll, create, update, delete, getRoles
- `app/Models/Ingredient.php` - Diupdate dengan method getConnection, getLowStock
- `routes/web.php` - Ditambahkan route untuk shift, transaksi, laporan, pengguna, dan pengaturan
- `TODO.md` - File daftar tugas yang selalu diupdate
- `hermes_discord_bot.py` - Script Discord Bot untuk notifikasi
- `hermes_discord_bot_universal.py` - Script Discord Bot Universal