# Implementation Plan: POS Coffee Shop (PHP Native)

## Overview
Membangun sistem Point of Sale (POS) terpadu untuk coffee shop menggunakan PHP Native murni dengan pola MVC, TailwindCSS via CLI standalone, dan AJAX Polling untuk fitur real-time. Fase 1 difokuskan pada Autentikasi, Manajemen Produk, Manajemen Stok, Transaksi Kasir Dasar, dan Pengaturan Sistem.

## Architecture Decisions
- **Framework**: PHP Native murni, tanpa framework eksternal seperti Laravel atau CodeIgniter.
- **Pola Arsitektur**: Custom MVC (Model, View, Controller) dengan Front Controller (public/index.php) dan class Router sederhana.
- **Templating**: Menggunakan native PHP include dan equire dengan passing variabel array untuk komponen reusable (TIDAK menggunakan Twig/Blade).
- **Styling**: TailwindCSS via CLI Standalone.
- **Database**: MySQL dengan PDO Prepared Statements.
- **Real-time (Fase 1)**: AJAX Polling (interval 3-5 detik) untuk notifikasi order dan Kitchen Display System (KDS).
- **Payment Gateway**: Midtrans (ditunda eksekusinya ke Fase 2 sesuai prioritas PRD).

## Task List

### Phase 1: Foundation (Setup & Arsitektur)
- [ ] Task 1: Setup struktur direktori MVC & konfigurasi awal.
- [ ] Task 2: Implementasi Core System (Router, Database PDO wrapper, Base Controller, Session, Validator).
- [ ] Task 3: Setup TailwindCSS Standalone CLI dan integrasi 	ailwind.config.js sesuai aturan *Design Guidelines*.
- [ ] Task 4: Pembuatan Komponen UI Reusable Utama (layout.php, dialog-alert.php, orm-input.php).

### Checkpoint: Foundation
- [ ] Autoloader berfungsi.
- [ ] Routing dasar ke halaman statis berhasil.
- [ ] Tailwind CSS berhasil dikompilasi dan diaplikasikan.

### Phase 2: Modul A (Autentikasi & RBAC)
- [ ] Task 5: Pembuatan database schema & seeder untuk oles, permissions, ole_permissions, dan users.
- [ ] Task 6: Implementasi Model User dan Auth.
- [ ] Task 7: Implementasi Controller Auth (Login, Logout) dan Middleware/Pengecekan Role.
- [ ] Task 8: Implementasi UI Login dan Dashboard Ringkasan Dasar (Halaman 1-3).

### Checkpoint: Autentikasi
- [ ] User bisa login dan redirect sesuai role.
- [ ] RBAC (Role-Based Access Control) memblokir akses yang tidak berhak.
- [ ] Logout berfungsi.

### Phase 3: Komponen Pendukung Layout
- [ ] Task 9: Pembuatan UI Sidebar navigasi dinamis berdasarkan role.
- [ ] Task 10: Pembuatan UI Topbar.

### Checkpoint: Layout
- [ ] Layout utama solid dan responsif.

## Risks and Mitigations
| Risk | Impact | Mitigation |
|------|--------|------------|
| Keamanan SQL Injection | High | Wajib menggunakan PDO Prepared Statements di semua query. |
| XSS Attack | High | Gunakan fungsi htmlspecialchars() pada setiap output data dinamis di View. |
| Performa AJAX Polling | Medium | Batasi interval polling (misal 5 detik) dan optimasi query untuk meminimalisir load server. |

## Open Questions
- Apakah kita membutuhkan library eksternal untuk pembuatan PDF struk (misal: mPDF/DomPDF) atau hanya mengandalkan fitur window.print() browser?
