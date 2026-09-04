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

## 4. Elevasi (Borders & Shadows)

- **Border Radius:** Kecil dan tegas (`rounded` atau `rounded-md`, maks 4px-6px).
- **Shadow:** Sangat minimal (`shadow-sm`). Hindari shadow berat (drop-shadow blur tinggi).
- **Borders:** Komponen form (input, select, textarea) **wajib** memiliki border 1px solid `#E5E7EB`.
- **Backgrounds:** Gunakan warna solid flat. **DILARANG** menggunakan gradient.

## 5. Komponen Form & Interaksi & Aturan UX (UI/UX Pro Max)

- **Tidak Ada Hover Layout-Shifting:** Dilarang menggeser layout saat hover, gunakan perubahan background/warna teks saja.
- **Transisi Durasi yang Tepat:** Gunakan durasi `150-300ms` untuk perubahan state (transition-colors).
- **Kursor yang Tepat:** Tambahkan `cursor-pointer` untuk button dan link.
- **Fokus Mudah Diakses:** Pastikan elemen form memiliki border aktif saat `focus`.
- **Input terlihat jelas:** Input wajib punya outline / 1px solid border.
- **Alert & Modal:** Konfirmasi penting menggunakan Modal Dialog, tidak memakai JS alert bawaan.
