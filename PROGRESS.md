| No | Modul/Fitur | Status | Tanggal | Catatan |
|---|---|---|---|---|
| 1 | Setup struktur MVC, dokumentasi, dan Tailwind CDN | Selesai | 04 Sep 2026 | Berhasil menyiapkan `DESIGN_GUIDELINE.md`, layout, dan CDN |
| 2 | Persiapan Skema Database | Selesai | 04 Sep 2026 | Skema SQL untuk semua entitas (PRD bagian 11) dan seeder |
| 3 | Autentikasi dan Dashboard (Modul A) | Selesai | 04 Sep 2026 | Implementasi form login, MVC Session, komponen UI, & Dashboard awal |
| 4 | Manajemen Produk & Menu (Modul C) | Selesai | 04 Sep 2026 | Manajemen Kategori (AJAX Modal) dan Produk (CRUD & Varian) |
| 5 | Stok dan Resep (Modul D) | Selesai | 04 Sep 2026 | CRUD Bahan Baku, Resep Produk (BoM), Pergerakan Stok, & Stock Opname (AJAX + Dialog Component) |
| 6 | Point of Sale (Modul B) | Pending | | |
| 7 | Laporan Dasar (Modul F) | Pending | | |
| 8 | Manajemen Pengguna (Modul G) | Pending | | |
| 9 | Pengaturan Sistem (Modul H) | Pending | | |

---

### Catatan Rilis Terakhir
- **Modul C (Manajemen Produk dan Menu) Selesai.**
- Telah diperbaiki masalah URL routing pada `index.php` dan `Router.php` sehingga dapat diakses via subdirektori (localhost/native-coffeshop).
- Model `Category` dan `Product` ditambahkan beserta method CRUD.
- Tampilan list produk dan kelola kategori interaktif dengan modal Alpine.js dan Fetch API.
