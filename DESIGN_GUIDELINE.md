# Panduan Desain Sistem POS Coffee Shop

Sistem ini menggunakan gaya **Operate** yang menitikberatkan pada kepadatan, operasi cepat, dan keterbacaan tinggi. 

**Catatan Teknis:** 
Proyek ini menggunakan **Tailwind CSS melalui CDN**. Kita tidak menggunakan `tailwind.config.js` untuk build, tetapi melakukan kustomisasi di dalam tag `<script>` pada layout menggunakan objek `tailwind.config`.

## 1. Warna (Color Palette)

Digunakan untuk status dan hirarki visual:

- **Primary / Action:** `#398263` (Teal/Green)
- **Primary Hover:** `#2C6B4F`
- **Warning / Alert:** `#D97706` (Amber 600 - untuk Low Stock, dll)
- **Danger / Destructive:** `#DC2626` (Red 600 - untuk Hapus, Void, Batal)
- **Background Aplikasi:** `#F3F4F6` (Gray 100)
- **Surface (Kartu/Modal):** `#FFFFFF` (White)
- **Border / Divider:** `#E5E7EB` (Gray 200)
- **Text Primary:** `#1A1A1A` (Hampir hitam untuk keterbacaan data utama)
- **Text Secondary:** `#6B7280` (Gray 500 - untuk label, deskripsi sekunder)
- **Text Inverse:** `#FFFFFF` (Putih - untuk teks di atas background Primary/Danger)

## 2. Tipografi

Sistem *Operate* menuntut kejelasan dan kecepatan membaca, terutama pada layar padat.

- **Keluarga Font:** `Inter` (sans-serif)
- **Ukuran Dasar:** `16px` (1rem)
- **Teks Data/Tabel:** `14px` (0.875rem) untuk memaksimalkan jumlah baris data yang terlihat.
- **Hirarki:** 
  - Label Data: Kecil (text-sm), warna abu-abu (text-secondary), Uppercase/Kapital.
  - Nilai Data: Hitam (text-primary), Medium atau Bold.
- **Angka/Mata Uang:** Harus selalu rata kanan dalam tabel.

## 3. Spacing & Tata Letak (Layout)

- **Grid/Jarak:** Menggunakan kelipatan 4px (4px, 8px, 16px, 24px).
- **Padding Card/Surface:** Standar `16px` (p-4) atau `24px` (p-6).
- **Jarak Antar Section:** `24px` (gap-6 atau mb-6).
- **Kepadatan Tabel:** 
  - Padding Vertikal: 8px (py-2)
  - Padding Horizontal: 12px (px-3)

## 4. Standar Halaman Form (Tambah & Edit)

Seluruh halaman tambah dan edit data (Bahan Baku, Produk, Kategori, User, Pergerakan Stok, Stock Opname, Shift) wajib mematuhi aturan layout berikut:

1. **Integrasi Sidebar & Topbar (`isAppLayout => true`)**:
   - Seluruh halaman form berada di dalam struktur layout utama aplikasi (`layout.php`) dengan **Sidebar** dan **Topbar** yang tetap terlihat.
   - **DILARANG** menyembunyikan sidebar/topbar (`isAppLayout => false`) pada halaman form internal.
2. **Proporsi Lebar & Tinggi (Full Width, Natural Height)**:
   - Kontainer form menggunakan `w-full space-y-6` agar mengisi area utama secara optimal.
   - Kartu form (`bg-surface rounded-lg border border-border shadow-sm p-6`) **DILARANG menggunakan `flex-1` atau `h-full`** yang memaksa kartu memanjang secara vertikal ke bawah layar.
   - Kartu harus memiliki **tinggi alami (*natural height*)** sesuai jumlah input.
3. **Kepadatan & Posisi Tombol Aksi**:
   - Bidang input dikelompokkan dengan rapi menggunakan `space-y-5` atau grid 2 kolom (`grid grid-cols-1 md:grid-cols-2 gap-5`).
   - Tombol aksi (**Batal** dan **Simpan**) diletakkan **langsung di bawah bidang input** dengan pembatas garis halus (`pt-4 border-t border-border flex justify-end gap-3`).
   - **DILARANG** menyisakan ruang kosong vertikal raksasa (*large empty void*) yang mendorong tombol ke batas bawah viewport.

## 5. Elevasi (Borders & Shadows)

- **Border Radius:** Kecil dan tegas (`rounded` atau `rounded-md`, maks 4px-6px).
- **Shadow:** Sangat minimal (`shadow-sm`). Hindari shadow berat (drop-shadow blur tinggi).
- **Borders:** Komponen form (input, select, textarea) **wajib** memiliki border 1px solid `#E5E7EB`.
- **Backgrounds:** Gunakan warna solid flat. **DILARANG** menggunakan gradient.

## 6. Komponen Form & Interaksi & Aturan UX (UI/UX Pro Max)

- **Tidak Ada Hover Layout-Shifting:** Dilarang menggeser layout saat hover, gunakan perubahan background/warna teks saja.
- **Transisi Durasi yang Tepat:** Gunakan durasi `150-300ms` untuk perubahan state (transition-colors).
- **Kursor yang Tepat:** Tambahkan `cursor-pointer` untuk button dan link.
- **Fokus Mudah Diakses:** Pastikan elemen form memiliki border aktif saat `focus`.
- **Input terlihat jelas:** Input wajib punya outline / 1px solid border.
- **Alert & Modal:** Konfirmasi penting menggunakan Modal Dialog, tidak memakai JS alert bawaan.
