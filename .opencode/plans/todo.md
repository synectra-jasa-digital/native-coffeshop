# TODO List: POS Coffee Shop (PHP Native)

## Phase 1: Foundation (Setup & Arsitektur)
- [ ] Task 1: Setup struktur direktori MVC & konfigurasi awal.
  - Acceptance: Folder app/, public/, config/ terbentuk. public/index.php merespons.
  - Verify: Jalankan php -S localhost:8000 -t public/ dan buka di browser.
- [ ] Task 2: Implementasi Core System.
  - Acceptance: Router, Database, Controller, Session, Validator class tersedia dan berfungsi.
  - Verify: Test routing dinamis ke method controller dummy.
- [ ] Task 3: Setup TailwindCSS Standalone CLI.
  - Acceptance: Binary tailwindcss tersedia, 	ailwind.config.js diatur, build script berjalan.
  - Verify: Jalankan perintah build tailwind dan pastikan file css tergenerate di public/assets/css/.
- [ ] Task 4: Pembuatan Komponen UI Reusable Utama.
  - Acceptance: layout.php, dialog-alert.php, dan orm-input.php selesai dibuat.
  - Verify: Panggil komponen di view dummy dan pastikan render HTML benar dengan gaya Tailwind.

## Checkpoint: Foundation
- [ ] Autoloader berfungsi.
- [ ] Routing dasar ke halaman statis berhasil.
- [ ] Tailwind CSS berhasil dikompilasi dan diaplikasikan.

## Phase 2: Modul A (Autentikasi & RBAC)
- [ ] Task 5: Skema & Seeder Database Auth.
  - Acceptance: File migrasi/setup db PHP murni (database/setup.php) dibuat dan dieksekusi menghasilkan tabel user, roles, permissions.
  - Verify: Cek database di MySQL.
- [ ] Task 6: Implementasi Model Auth.
  - Acceptance: Method untuk verifikasi login, ambil data user & permission selesai.
  - Verify: Eksekusi unit test sederhana via PHP CLI.
- [ ] Task 7: Implementasi Auth Controller & Middleware.
  - Acceptance: Logic validasi input login, pengecekan hash password, set session, pembatasan akses.
  - Verify: Coba login dengan data benar dan salah di form login.
- [ ] Task 8: UI Login & Dashboard.
  - Acceptance: Halaman login interaktif, menampilkan alert error dengan custom alert dialog.
  - Verify: Manual flow test di browser.

## Checkpoint: Autentikasi
- [ ] User bisa login dan redirect sesuai role.
- [ ] RBAC memblokir akses yang tidak berhak.
- [ ] Logout berfungsi.

## Phase 3: Komponen Pendukung Layout
- [ ] Task 9: UI Sidebar.
  - Acceptance: Menu Sidebar dinamis sesuai hak akses (session).
  - Verify: Login dengan role berbeda, pastikan menu berbeda.
- [ ] Task 10: UI Topbar.
  - Acceptance: Topbar memuat informasi user dan tombol logout.
  - Verify: Klik logout memicu dialog konfirmasi.
