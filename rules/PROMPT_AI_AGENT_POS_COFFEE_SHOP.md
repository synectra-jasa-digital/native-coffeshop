# Prompt untuk AI Agent: Pengembangan Sistem POS Coffee Shop (PHP Native + MVC + TailwindCSS)

Gunakan teks di bawah ini sebagai prompt awal untuk AI Agent (Claude Code, Cursor, atau agent coding lain). Lampirkan tiga file referensi berikut saat memberi prompt ini ke agent:

1. `PRDAplikasiPOSCoffeeShop.md`
2. `design.md`
3. `DAFTAR_HALAMAN_FITUR.md` (daftar detail 46 halaman dan fitur, ada di bagian akhir dokumen ini)

---

## PROMPT

Kamu bertindak sebagai senior full-stack PHP developer. Tugasmu membangun Sistem POS Terpadu Coffee Shop menggunakan **PHP Native (tanpa framework)** dengan struktur **MVC custom**, **komponen layout reusable**, dan **TailwindCSS** untuk seluruh tampilan.

Baca dulu dokumen referensi berikut sebelum mulai coding:

- `PRDAplikasiPOSCoffeeShop.md` berisi kebutuhan fungsional, alur pengguna, dan model data.
- `design.md` berisi aturan desain (warna, tipografi, spacing) untuk mode Persuade dan Operate.
- `DAFTAR_HALAMAN_FITUR.md` berisi rincian fitur di tiap 46 halaman sistem. Ini acuan wajib. Bangun semua fitur yang tercantum di dalamnya, jangan ada yang terlewat atau disederhanakan tanpa konfirmasi.

### Catatan penting soal stack

PRD menyebutkan stack Laravel + Livewire + MySQL. Abaikan bagian itu. Bangun seluruh sistem dengan PHP Native murni, PDO untuk akses database, MySQL sebagai database, dan TailwindCSS untuk styling. Semua kebutuhan fungsional, alur pengguna, dan model data di PRD tetap berlaku. Hanya stack teknisnya yang berubah.

---

### 1. Struktur Folder MVC

```
project-root/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   │   ├── layouts/          (master layout, sidebar, topbar, footer)
│   │   ├── components/       (semua komponen reusable)
│   │   └── pages/            (view per modul: pos, produk, stok, laporan, dll)
│   └── Core/
│       ├── Router.php
│       ├── Controller.php
│       ├── Model.php
│       ├── Database.php      (wrapper PDO)
│       ├── Session.php
│       └── Validator.php
├── config/
│   └── config.php
├── public/
│   ├── index.php             (front controller / single entry point)
│   ├── assets/css/           (input.css dan output hasil build Tailwind)
│   ├── assets/js/
│   └── assets/img/
├── routes/
│   └── web.php
├── database/
│   ├── migrations/
│   └── seeders/
├── storage/
│   └── logs/
├── tailwind.config.js
├── package.json (jika build Tailwind pakai npm) atau tailwindcss binary standalone
└── PROGRESS.md
```

Semua request masuk lewat `public/index.php`. Router membaca `routes/web.php` lalu memanggil controller dan method yang sesuai. Jangan taruh logic bisnis di dalam view.

### 2. Aturan Coding

- Ikuti PSR-12 untuk penulisan kode.
- Semua query database wajib pakai PDO prepared statement. Jangan concatenate input user langsung ke query SQL.
- Hash password dengan `password_hash()`, verifikasi dengan `password_verify()`.
- Tambahkan CSRF token di setiap form.
- Escape semua output ke HTML dengan `htmlspecialchars()`.
- Penamaan class pakai PascalCase, method dan variabel pakai camelCase, nama kolom database pakai snake_case.
- Satu file class hanya berisi satu class.

### 3. Komponen Layout Reusable

Bangun komponen berikut satu kali di `app/Views/components/`, lalu pakai ulang di semua halaman:

- `layout.php`: wrapper utama halaman (head, body, slot konten).
- `sidebar.php`: navigasi sesuai role yang login, collapsible di layar kecil.
- `topbar.php`: judul halaman, info user, tombol logout.
- `table.php`: tabel data dengan header, sorting sederhana, pagination, dan scroll horizontal otomatis di layar sempit.
- `pagination.php`: navigasi halaman untuk data panjang.
- `form-input.php`: input text, select, textarea dengan label dan pesan error konsisten.
- `button.php`: varian tombol (primary, secondary, danger) dengan ukuran konsisten, termasuk state loading.
- `badge.php`: status berwarna (status order, status stok, status transaksi).
- `modal.php`: struktur dasar modal untuk form tambah/edit dan konfirmasi.
- `dialog-alert.php`: komponen dialog untuk semua notifikasi (lihat bagian 5).
- `empty-state.php`: tampilan saat data kosong.
- `skeleton-loader.php`: placeholder loading saat data sedang diambil lewat AJAX.

Setiap komponen menerima data lewat parameter array, bukan variabel global. Komponen tidak boleh mengandung logic query database.

### 4. Panduan Desain (turunan dari design.md)

Sistem internal (POS, Admin, KDS, Stok) memakai aturan mode **Operate**. Halaman menu digital dan QR order pelanggan memakai gaya campuran, tetap cepat dan legible seperti Operate, tapi sedikit lebih ramah pelanggan dan mobile-first.

Buat file `DESIGN_GUIDELINE.md` di root proyek yang merangkum aturan berikut, lalu terapkan konsisten:

**Warna**
- Primary/Action: `#398263`
- Warning: `#D97706`
- Danger: `#DC2626`
- Background aplikasi: `#F3F4F6`
- Surface: `#FFFFFF`
- Border: `#E5E7EB`
- Text primary: `#1A1A1A`, text secondary: `#6B7280`

**Tipografi**
- Font sans-serif (Inter) untuk semua halaman sistem.
- Tabel dan data angka pakai ukuran 0.875rem.
- Label data kecil, abu-abu, huruf kapital. Value data hitam, medium atau bold.

**Spacing**
- Skala 4px, 8px, 16px, 24px untuk padding dan margin di semua komponen.
- Padding card standar: 16px atau 24px.
- Padding sel tabel: 8px vertikal, 12px horizontal.
- Jarak antar section dalam satu halaman: 24px.

**Border, Radius, Shadow**
- Border 1px solid `#E5E7EB` untuk card dan input.
- Radius kecil dan konsisten.
- Shadow maksimal `shadow-sm`.

**Aturan Wajib**
- Angka dan mata uang di tabel rata kanan.
- Kontras teks tinggi, terutama di layar POS dan KDS.
- Tidak ada gradient di background.
- Input wajib punya border terlihat.
- KDS harus terbaca dari jarak jauh: card besar, warna beda per status.

### 5. Tampilan Interaktif, Dinamis, dan Responsif (TailwindCSS wajib)

Seluruh styling sistem wajib pakai TailwindCSS, bukan CSS custom manual. Petakan token warna dan spacing dari `DESIGN_GUIDELINE.md` ke `tailwind.config.js` lewat `theme.extend` (colors, spacing, fontFamily), supaya konsisten dan gampang diubah dari satu tempat.

**Responsif (mobile dan web view)**

- Halaman publik untuk pelanggan (menu digital, keranjang QR order) wajib mobile-first. Sebagian besar pelanggan mengakses lewat HP saat scan QR di meja.
- Halaman POS, Admin, Laporan, dan KDS dioptimalkan untuk layar desktop/tablet, tapi tetap harus tetap bisa dipakai dan terbaca di layar lebih kecil (minimal breakpoint tablet). Jangan bikin layout yang rusak total di layar sempit.
- Pakai breakpoint Tailwind standar (`sm`, `md`, `lg`, `xl`) untuk mengatur perubahan layout, bukan media query custom.
- Tabel panjang di layar sempit wajib bisa di-scroll horizontal atau berubah jadi tampilan card, jangan terpotong tanpa cara melihat sisanya.

**Interaktif dan dinamis**

- PHP Native tidak punya reaktivitas otomatis seperti Livewire. Pakai Alpine.js sebagai lapisan interaktivitas ringan di sisi client untuk hal seperti: buka/tutup modal, toggle sidebar, filter tanpa reload, dan state keranjang order di POS maupun QR order.
- Pakai Fetch API untuk komunikasi ke server tanpa reload halaman penuh pada aksi seperti: tambah/kurang item ke keranjang, submit form CRUD, ubah status order di KDS, cek status pembayaran.
- Setiap aksi yang manggil server lewat AJAX wajib menampilkan state loading (pakai `skeleton-loader.php` atau spinner di tombol) supaya user tahu proses sedang berjalan.
- Untuk update yang butuh terasa real-time (order baru masuk ke KDS, notifikasi order dari QR ke kasir, status pembayaran gateway), pakai polling AJAX dengan interval singkat (3 sampai 5 detik) sebagai pendekatan default. Tanyakan dulu ke saya kalau mau upgrade ke pendekatan lain seperti WebSocket atau Server-Sent Events (lihat bagian 6).
- Transisi antar state (buka modal, ganti tab, hover tombol) pakai transition class bawaan Tailwind, jangan animasi berat yang bikin lambat di HP.

### 6. Titik yang Wajib Dikonfirmasi ke Saya

Berhenti dan tanya saya dulu sebelum lanjut di titik-titik berikut:

1. Sebelum finalisasi skema database untuk modul yang sedang dikerjakan.
2. Sebelum memutuskan cara templating: pure PHP include dengan output buffering, atau template engine kecil sendiri.
3. Sebelum menentukan cara build TailwindCSS: pakai Tailwind CLI standalone tanpa Node.js, atau lewat npm dengan proses build biasa.
4. Sebelum menentukan mekanisme update real-time untuk KDS dan notifikasi order: tetap polling AJAX, atau upgrade ke WebSocket/SSE.
5. Sebelum mulai integrasi payment gateway. PRD masih membuka pilihan antara Midtrans dan Xendit.
6. Sebelum menambah library eksternal apapun (contoh: Alpine.js versi tertentu, SweetAlert2, DataTables, Chart.js).
7. Sebelum menghapus atau menulis ulang file/komponen yang sudah pernah dibuat di sesi sebelumnya.
8. Sebelum pindah ke fase berikutnya. Tunggu konfirmasi bahwa fase sebelumnya sudah diterima.
9. Kalau ada fitur di `DAFTAR_HALAMAN_FITUR.md` yang bertentangan atau tidak jelas dibanding PRD.

### 7. Aturan Alert dan Dialog

Jangan pernah pakai `alert()`, `confirm()`, atau `prompt()` bawaan browser. Semua notifikasi dan konfirmasi wajib tampil lewat komponen dialog/modal custom (`dialog-alert.php` plus JavaScript pendukung).

Buat dialog untuk kondisi berikut:

1. **CRUD berhasil**: tambah, ubah, hapus data sukses. Dialog warna hijau/primary, boleh auto-close beberapa detik.
2. **CRUD gagal**: tambah, ubah, hapus data gagal. Dialog warna merah, tampilkan pesan error yang jelas.
3. **Login berhasil**: dialog singkat sebelum redirect ke halaman utama sesuai role.
4. **Login gagal**: dialog merah dengan alasan (email/password salah, akun nonaktif).
5. **Logout berhasil**: dialog konfirmasi ("Yakin ingin logout?") sebelum proses, lalu dialog sukses setelah logout selesai.
6. **Dialog konfirmasi umum**: dipakai ulang untuk aksi berisiko seperti hapus data, void transaksi, batalkan order. Wajib ada tombol Ya dan Batal.

### 8. Urutan Pengerjaan

Kerjakan per modul sesuai urutan berikut, mengikuti prioritas Fase 1 di PRD:

1. Modul A (Autentikasi dan Dashboard): halaman 1 sampai 3.
2. Modul C (Manajemen Produk dan Menu): halaman 14 sampai 19.
3. Modul D (Stok dan Resep): halaman 20 sampai 27.
4. Modul B (Point of Sale): halaman 4 sampai 13.
5. Modul F (Laporan dasar): halaman 33, 37, 38.
6. Modul G (Manajemen Pengguna): halaman 39 sampai 41.
7. Modul H (Pengaturan Sistem): halaman 42 sampai 46.

Modul E (Meja, KDS, QR Order, halaman 28 sampai 32) dan sisa Modul F dikerjakan di Fase 2, setelah Fase 1 dikonfirmasi selesai dan diterima.

### 9. Laporan Progress

Setiap kali menyelesaikan satu unit kerja, update file `PROGRESS.md` di root proyek. Jangan tunggu sampai semua selesai baru dilaporkan.

Format tabel `PROGRESS.md`:

| No | Modul/Fitur | Status | Tanggal | Catatan |
|---|---|---|---|---|
| 1 | Setup struktur MVC, router, dan Tailwind | Selesai | ... | ... |
| 2 | Autentikasi dan role | Proses | ... | ... |

Setiap update wajib mencantumkan:
- Daftar file yang dibuat atau diubah.
- Fitur yang sudah selesai dan bisa dites, dicocokkan dengan nomor halaman di `DAFTAR_HALAMAN_FITUR.md`.
- Fitur yang belum selesai atau masih pending konfirmasi.
- Masalah atau blocker yang ditemukan.

### 10. Output yang Diharapkan

- Kode berjalan sesuai struktur MVC di atas, dengan styling TailwindCSS penuh, responsif, dan interaktif sesuai bagian 5.
- File `PROGRESS.md` yang selalu update.
- File `DESIGN_GUIDELINE.md` sesuai bagian 4.
- File `tailwind.config.js` dengan token warna dan spacing sesuai design guideline.
- File `README.md` berisi cara instalasi, konfigurasi database, cara build Tailwind, dan cara menjalankan proyek secara lokal.

---

## Lampiran: DAFTAR_HALAMAN_FITUR.md

Simpan bagian ini sebagai file terpisah bernama `DAFTAR_HALAMAN_FITUR.md`, lalu lampirkan bersama PRD dan design guideline ke AI Agent.

# Daftar Halaman dan Fitur
## Aplikasi POS Terpadu Coffee Shop (turunan dari PRD v1.0)

Dokumen ini merinci fitur di tiap halaman yang tercantum di PRD bagian 12. Dipakai sebagai acuan sebelum masuk tahap wireframe dan development.

---

## A. Autentikasi & Umum

### 1. Login
- Input username/email dan password.
- Pesan error jelas saat kredensial salah.
- Redirect otomatis ke dashboard sesuai role setelah login berhasil.

### 2. Lupa Password / Reset Password
- Form input email atau username untuk request reset.
- Link reset password (atau reset manual oleh Admin sesuai PRD 7.7).
- Form input password baru dengan konfirmasi.

### 3. Dashboard Ringkasan
- Kartu ringkasan hari ini: total penjualan, jumlah transaksi, stok kritis.
- Grafik tren penjualan singkat.
- Daftar isi berbeda sesuai role: Kasir lihat status shift, Owner/Manager lihat ringkasan bisnis.
- Notifikasi stok bahan baku yang mendekati batas minimum.

---

## B. Point of Sale (Kasir)

### 4. Buka Shift
- Input nominal modal kas awal.
- Validasi shift sebelumnya sudah ditutup sebelum bisa buka shift baru.
- Catat waktu dan kasir yang membuka shift.

### 5. Layar POS Utama
- Grid menu dengan gambar, nama, dan harga per kategori.
- Pencarian dan filter menu per kategori.
- Keranjang order yang update real-time di sisi layar.
- Tombol pilih tipe order: dine-in (dengan nomor meja), take away, atau bungkus.
- Indikator menu berstatus habis sementara tidak bisa dipilih.

### 6. Pilih Varian & Catatan Item
- Pilihan varian per produk (ukuran, level gula, level es, topping).
- Penyesuaian harga otomatis sesuai varian yang dipilih.
- Kolom catatan bebas per item (contoh: less sugar, extra shot).

### 7. Ringkasan Order & Pilih Tipe Order
- Rincian item, qty, subtotal per item.
- Perhitungan otomatis pajak dan service charge.
- Input diskon manual dengan approval Manager jika melebihi batas.
- Tombol edit atau hapus item sebelum bayar.

### 8. Pilih Metode Pembayaran
- Pilihan metode: cash, QRIS statis manual, QRIS dinamis, e-wallet, kartu.
- Dukungan split payment (kombinasi cash dan non-tunai).
- Kalkulator kembalian otomatis untuk pembayaran cash.

### 9. Pembayaran Gateway
- Tampilan QR code pembayaran sesuai nominal transaksi.
- Status pembayaran real-time (menunggu, berhasil, gagal) lewat notifikasi webhook.
- Timer batas waktu pembayaran, transaksi otomatis batal jika lewat batas.
- Tombol cek ulang status pembayaran manual sebagai cadangan.

### 10. Preview & Cetak Struk
- Preview struk sebelum cetak.
- Cetak otomatis ke thermal printer atau unduh PDF.
- Info toko, nomor struk, rincian item, dan metode pembayaran di struk.

### 11. Riwayat Transaksi
- Daftar transaksi dengan filter tanggal, kasir, dan nomor struk.
- Pencarian cepat transaksi tertentu.
- Status transaksi: lunas, tertunda, dibatalkan.

### 12. Detail Transaksi
- Rincian lengkap satu transaksi.
- Tombol void/batalkan dengan kolom alasan wajib diisi.
- Alur approval Manager sebelum void diproses.

### 13. Tutup Shift & Rekonsiliasi Kas
- Ringkasan total transaksi selama shift berjalan.
- Input jumlah kas fisik akhir untuk dibandingkan dengan kas sistem.
- Sistem menghitung dan menampilkan selisih kas otomatis.

---

## C. Manajemen Produk & Menu

### 14. Daftar Kategori
- Tabel kategori dengan jumlah produk per kategori.
- Tombol aktif/nonaktifkan kategori.

### 15. Form Tambah/Edit Kategori
- Input nama kategori dan urutan tampil di POS.

### 16. Daftar Produk/Menu
- Tabel/grid produk dengan status aktif/nonaktif dan indikator resep sudah/belum lengkap.
- Filter per kategori.

### 17. Form Tambah/Edit Produk
- Input nama, deskripsi, harga jual, gambar, dan kategori.
- Toggle status aktif/nonaktif dan tandai habis sementara.
- Validasi produk tidak bisa dipublish sebelum resep diisi lengkap.

### 18. Manajemen Varian Produk
- Tambah varian (ukuran, level gula, level es) per produk.
- Penyesuaian harga tambahan per varian.

### 19. Manajemen Promosi/Harga Khusus
- Input harga promosi dengan tanggal mulai dan berakhir.
- Daftar promosi aktif dan yang sudah berakhir.

---

## D. Stok & Resep (BOM)

### 20. Daftar Bahan Baku
- Tabel bahan baku dengan satuan, stok saat ini, dan status (aman/menipis/habis).
- Filter dan pencarian bahan baku.

### 21. Form Tambah/Edit Bahan Baku
- Input nama, satuan (gram, ml, pcs), dan stok minimum.

### 22. Manajemen Resep per Produk (BOM)
- Pilih bahan baku dan takaran untuk tiap produk atau varian.
- Preview estimasi harga pokok produk berdasarkan resep.

### 23. Form Penerimaan Barang/Stok Masuk
- Input supplier, daftar bahan baku, jumlah, dan harga beli.
- Input tanggal kedaluwarsa jika relevan.
- Stok otomatis bertambah setelah disimpan.

### 24. Riwayat Pergerakan Stok (Kartu Stok)
- Riwayat stok masuk, keluar, dan penyesuaian per bahan baku.
- Filter berdasarkan tanggal dan jenis pergerakan.

### 25. Stok Opname
- Daftar bahan baku dengan stok sistem saat ini.
- Input stok fisik hasil hitung manual.
- Sistem menghitung selisih otomatis.

### 26. Approval Stok Opname
- Daftar hasil stok opname menunggu approval Manager.
- Detail selisih per bahan baku sebelum disetujui.
- Stok sistem update otomatis setelah disetujui.

### 27. Manajemen Supplier
- Data supplier: nama, kontak, alamat.
- Riwayat pembelian per supplier.

---

## E. Meja, Kitchen Display & QR Order

### 28. Manajemen Meja & Generate QR Code
- Daftar meja dengan nomor dan status (kosong/terisi).
- Generate dan unduh QR code unik per meja untuk dicetak dan ditempel.

### 29. Menu Digital untuk Pelanggan (Publik)
- Tampilan menu per kategori tanpa perlu login.
- Info menu habis sementara ditampilkan jelas.
- Otomatis terhubung ke nomor meja sesuai QR yang dipindai.

### 30. Keranjang & Konfirmasi Pesanan (Pelanggan)
- Tambah/kurang item dan catatan khusus per item.
- Ringkasan pesanan sebelum kirim ke kasir.
- Notifikasi status setelah pesanan terkirim (menunggu konfirmasi kasir).

### 31. Notifikasi Order Masuk (Kasir)
- Notifikasi real-time saat ada order baru dari QR order.
- Tombol verifikasi dan proses pembayaran langsung dari notifikasi.

### 32. Kitchen Display System
- Antrian order real-time terurut berdasarkan waktu masuk.
- Tombol ubah status: diterima, sedang diproses, selesai.
- Indikator waktu tunggu tiap order untuk prioritas pengerjaan.
- Order selesai otomatis pindah ke riwayat, hilang dari antrian aktif.

---

## F. Laporan & Analitik

### 33. Laporan Penjualan Harian
- Total transaksi, total pendapatan, dan rincian metode pembayaran per hari.
- Tombol export ke Excel dan PDF.

### 34. Laporan Penjualan per Periode
- Filter rentang tanggal custom (mingguan, bulanan, bebas).
- Grafik tren penjualan.

### 35. Laporan Menu Terlaris
- Ranking menu terlaris dan paling sedikit terjual.
- Filter per periode dan kategori.

### 36. Laporan Margin Keuntungan per Menu
- Perbandingan harga jual vs harga pokok bahan baku per menu.
- Persentase margin per menu.

### 37. Laporan Stok & Nilai Stok
- Nilai stok saat ini berdasarkan harga beli bahan baku.
- Daftar bahan baku mendekati atau di bawah stok minimum.

### 38. Laporan Kinerja Kasir per Shift
- Jumlah transaksi dan total penjualan per kasir per shift.
- Selisih kas per shift.

---

## G. Manajemen Pengguna

### 39. Daftar Pengguna
- Tabel pengguna dengan role dan status aktif/nonaktif.
- Pencarian dan filter per role.

### 40. Form Tambah/Edit Pengguna & Role
- Input data pengguna dan penetapan role.
- Reset password oleh Admin.

### 41. Log Aktivitas (Audit Trail)
- Catatan siapa mengubah harga, void transaksi, atau ubah stok.
- Filter berdasarkan pengguna, tanggal, dan jenis aktivitas.

---

## H. Pengaturan Sistem

### 42. Pengaturan Info Toko
- Nama toko, alamat, nomor telepon, dan logo untuk struk.

### 43. Pengaturan Pajak & Service Charge
- Toggle aktif/nonaktif PPN dan service charge.
- Aturan berbeda per tipe order jika diperlukan.

### 44. Pengaturan Metode Pembayaran
- Aktifkan/nonaktifkan metode pembayaran yang tersedia di kasir.
- Konfigurasi kredensial payment gateway.

### 45. Pengaturan Template Struk
- Format nomor struk otomatis.
- Editor sederhana untuk susunan informasi di struk cetak.

### 46. Pengaturan Notifikasi Stok Minimum
- Atur batas stok minimum default untuk semua bahan baku.
- Atur penerima notifikasi saat stok menipis.

---

*Dokumen ini melengkapi PRD-Aplikasi-POS-Coffee-Shop.md bagian 7 dan 12.*

---

## Cara Pakai Prompt Ini

Salin seluruh bagian "PROMPT" di atas ke AI Agent kamu. Pisahkan bagian "Lampiran: DAFTAR_HALAMAN_FITUR.md" jadi file sendiri kalau agent kamu punya batas panjang pesan. Lampirkan `PRDAplikasiPOSCoffeeShop.md`, `design.md`, dan `DAFTAR_HALAMAN_FITUR.md` di percakapan yang sama supaya agent punya konteks penuh sebelum mulai kerja.
