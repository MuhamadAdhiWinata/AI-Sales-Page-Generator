---
title: Spesifikasi Teknis
description: Detail arsitektur, tumpukan teknologi, dan skema sistem AI Sales Gen.
---

**Nama Proyek:** AI-Sales-Gen Prototype  
**Pengembang:** Adhinath  
**Batas Waktu:** 30 April 2026  
**Tumpukan Teknologi:** Nuxt 3 (Frontend), Laravel 11 (Backend API), Gemini AI (Mesin AI)

---

## 1. Gambaran Proyek
Aplikasi ini adalah platform *SaaS-ready* yang memungkinkan pengguna mengubah data produk mentah menjadi halaman penjualan (*sales page*) yang persuasif dan siap pakai. Sistem menggunakan AI untuk menulis naskah iklan (*copywriting*) serta mendesain visual menggunakan kerangka kerja CSS modern.

---

## 2. Arsitektur Sistem
Sistem dibangun dengan arsitektur **Fullstack Terpisah**:
- **Backend (SSOT - Single Source of Truth):** Laravel 11 mengelola autentikasi, basis data, dan komunikasi aman dengan API AI.
- **Frontend (UI Reaktif):** Nuxt 3 menyediakan pengalaman pengguna yang cepat dengan *Client‑Side Rendering* (CSR) untuk dasbor interaktif.

---

## 3. Tumpukan Teknologi

| Lapisan | Teknologi | Peran |
| :--- | :--- | :--- |
| **Frontend** | **Nuxt 3** | Kerangka Vue.js untuk UI yang reaktif dan *auto‑routing*. |
| **Backend** | **Laravel 11** | API kuat untuk mengelola logika bisnis dan *Auth*. |
| **Autentikasi** | **Laravel Sanctum** | Token berbasis autentikasi untuk mengamankan API. |
| **Mesin AI** | **Gemini 1.5 Flash** | Menghasilkan konten pemasaran dan struktur HTML. |
| **Basis Data** | **MariaDB** | Menyimpan data pengguna dan riwayat generasi. |
| **Kerangka CSS** | **Tailwind CSS** | Styling responsif untuk UI aplikasi dan keluaran AI. |
| **Manajemen State** | **Pinia (Opsional)** | Mengelola state pengguna dan data hasil generate di Nuxt. |

---

## 4. Spesifikasi Fitur

### 4.1. Autentikasi Pengguna (Syarat: Laravel Auth)
- **Daftar/Masuk:** Menggunakan sistem *session/token* yang aman dari Laravel.
- **Kontrol Akses:** Pengguna hanya dapat melihat, mengedit, dan menghapus riwayat *sales page* miliknya sendiri.

### 4.2. Formulir Input Produk Terstruktur
Formulir di Nuxt 3 dirancang secara terstruktur untuk memberi konteks maksimal kepada AI:
- **Nama Produk/Layanan**
- **Deskripsi Produk**
- **Fitur Utama** (input dinamis multi‑input)
- **Target Audiens**
- **Harga**
- **Unique Selling Point (USP)**
- **Pilihan Nada** (Persuasif, Formal, Urgen)
- **Template Style** (Classic, Neon, Pastel)

### 4.3. Mesin AI & Pratinjau Langsung
- **Prompt Engineering:** Laravel menyusun data formulir menjadi instruksi teknis ke Gemini API.
- **Mode Pratinjau Live:** Hasil AI berupa potongan HTML + Tailwind CSS dirender dalam `<iframe>` untuk isolasi CSS.
- **Status Loading:** Tampilan visual (skeleton/spinner) saat AI memproses data.

### 4.4. Riwayat & Manajemen Generasi
- **Persistensi Basis Data:** Setiap hasil sukses disimpan ke tabel `sales_pages`.
- **Manajemen UI:** Pengguna dapat mencari, melihat ulang, menghapus, atau **menyempurnakan** (refine) halaman yang telah di‑generate.

### 4.5. Fitur Penyempurnaan AI (Continue Edit)
- **Endpoint:** `POST /api/sales-pages/{salesPage}/refine`
- **Job:** `RefineSalesPageJob` – menerima `content_html` yang ada, instruksi pengguna, memanggil Gemini, dan menimpa (`overwrite`) konten.
- **Frontend:** Panel mengambang “Edit dengan AI” pada halaman Detail Riwayat & Generate, memungkinkan pengguna menulis instruksi (contoh: “ganti warna tombol ke emas”) dan memperbarui halaman secara real‑time.
- **Polling:** UI memantau status `processing` → `completed` → memperbarui iframe.

---

## 5. Skema Basis Data (Sorotan)

### Tabel: `users`
- `id`, `name`, `email`, `password`, `timestamps`

### Tabel: `sales_pages`
- `id`
- `user_id` (FK ke `users.id`)
- `product_name` (String)
- `product_description` (Text)
- `features` (JSON) – *Menyimpan array fitur*
- `target_audience` (String)
- `price` (String)
- `usp` (Text)
- `content_html` (LongText) – *Menyimpan hasil render AI*
- `status` (enum: `processing`, `completed`, `failed`)
- `template` (String) - *Menyimpan pilihan template/style*
- `error_message` (nullable Text)
- `timestamps`

---

## 6. Tonggak Pengembangan
1. **Fase 1 (Backend – Hari 1):**
   - Menyiapkan Laravel & Sanctum.
   - Integrasi Gemini API Client.
   - Implementasi endpoint Auth, Generate, Status, Refine.
2. **Fase 2 (Frontend – Hari 2):**
   - Menyiapkan Nuxt 3 & Tailwind.
   - Implementasi formulir terstruktur, pratinjau live (`<iframe>`), dan panel Refine.
   - Pengelolaan state & polling UI.
3. **Fase 3 (Final & Pengiriman – Hari 3):**
   - Dockerisasi backend & frontend.
   - Deploy ke server.
   - Dokumentasi README & Development‑Process.

---

## 7. Deliverables Pengiriman
- **URL Live** – Aplikasi dapat diakses publik.
- **Repositori GitHub** – Kode sumber bersih & terdokumentasi.
- **README.md** – Panduan instalasi, menjalankan, dan penggunaan.
- **Development‑Process.md** – Langkah‑langkah detail dari nol hingga fitur lengkap.

---

## 8. Catatan
1. **Database:** MariaDB disediakan di container terpisah.
2. **Lingkungan:** `.env.example` disertakan, gunakan Docker Compose untuk layanan.
3. **Docker:** Laravel & Nuxt dijalankan dalam container masing‑masing, dengan `queue-worker` untuk job background.

**Project Name:** AI-Sales-Gen Prototype  
**Developer:** Adhinath  
**Deadline:** 30 April 2026  
**Stack:** Nuxt 3 (Frontend), Laravel 11 (Backend API), Gemini AI (AI Engine)

---

## 1. Project Overview
Aplikasi ini adalah platform *SaaS-ready* yang memungkinkan pengguna untuk mentransformasi data produk mentah menjadi halaman penjualan (*sales page*) yang persuasif dan siap pakai. Sistem menggunakan AI untuk menangani penulisan naskah iklan (*copywriting*) sekaligus perancangan visual menggunakan framework CSS modern.

---

## 2. System Architecture
Sistem dibangun dengan arsitektur **Decoupled Fullstack**:
* **Backend (SSOT - Single Source of Truth):** Laravel 11 mengelola autentikasi, database, dan komunikasi aman dengan API AI.
* **Frontend (Reactive UI):** Nuxt 3 menyediakan pengalaman pengguna yang cepat dengan *Client-Side Rendering* (CSR) untuk dashboard interaktif.

---

## 3. Technology Stack

| Layer | Technology | Role |
| :--- | :--- | :--- |
| **Frontend** | **Nuxt 3** | Framework Vue.js untuk UI yang reaktif dan *auto-routing*. |
| **Backend** | **Laravel 11** | API Robust untuk mengelola logika bisnis dan *Auth*. |
| **Authentication** | **Laravel Sanctum** | Autentikasi berbasis token untuk mengamankan API. |
| **AI Engine** | **Gemini 1.5 Flash** | Menghasilkan konten marketing dan struktur HTML. |
| **Database** | **MariaDB** | Menyimpan data user dan riwayat generasi. |
| **CSS Framework** | **Tailwind CSS** | Styling responsif untuk UI aplikasi dan *output* AI. |
| **State Management** | **Pinia (Optional)** | Mengelola state user dan data hasil generate di Nuxt. |

---

## 4. Feature Specifications

### 4.1. User Authentication (Requirement: Laravel Auth)
* **Register/Login:** Menggunakan sistem *secure session/token* dari Laravel.
* **Access Control:** User hanya dapat melihat, mengedit, dan menghapus riwayat *sales page* miliknya sendiri.

### 4.2. Structured Product Input Form
Formulir input di Nuxt 3 dirancang secara terstruktur untuk memberikan konteks maksimal kepada AI:
* **Product/Service Name:** Nama merek.
* **Product Description:** Penjelasan umum produk.
* **Key Features:** Input dinamis (multi-input) untuk poin-poin keunggulan.
* **Target Audience:** Demografi spesifik yang dituju.
* **Price:** Informasi harga dan penawaran khusus.
* **Unique Selling Point (USP):** Nilai jual unik yang membedakan dari kompetitor.
* **Tone Selection:** Pilihan gaya bahasa (Persuasif, Formal, Urgen/Bergegas).
* **Template Style:** Pilihan gaya layout (Classic, Neon, Pastel).

### 4.3. AI Engine & Live Preview
* **Prompt Engineering:** Laravel menyusun data form menjadi instruksi teknis kepada Gemini API.
* **Live Preview Mode:** Hasil dari AI berupa potongan kode HTML + Tailwind CSS yang langsung dirender dalam kontainer khusus di Nuxt menggunakan `v-html`.
* **Loading State:** Feedback visual (skeleton/spinner) saat AI memproses data.

### 4.4. Generation History & Management
* **Database Persistence:** Setiap hasil sukses disimpan ke tabel `sales_pages`.
* **Management:** Pengguna dapat mencari, melihat ulang (*view*), memperbarui deskripsi (*re-generate*), atau menghapus riwayat.

---

## 5. Database Schema (Highlights)

### Table: `users`
* `id`, `name`, `email`, `password`, `timestamps`

### Table: `sales_pages`
* `id`
* `user_id` (FK: `users.id`)
* `product_name` (String)
* `product_description` (Text)
* `features` (JSON) - *Menyimpan array fitur*
* `target_audience` (String)
* `price` (String)
* `usp` (Text)
* `content_html` (LongText) - *Menyimpan hasil render AI*
* `template` (String) - *Menyimpan pilihan template/style*
* `timestamps`

---

## 6. Development Milestone

1.  **Phase 1 (Backend - Day 1):**
    * Setup Laravel & Sanctum.
    * Integrasi Gemini API Client.
    * Pembuatan API Endpoint (Auth & Generation).
2.  **Phase 2 (Frontend - Day 2):**
    * Setup Nuxt 3 & Tailwind.
    * Implementasi Form terstruktur & Validasi.
    * Integrasi Live Preview & Rendering.
3.  **Phase 3 (Final & Submission - Day 3):**
    * Deployment ke server.
    * Pembuatan dokumentasi README (Technical Writing).
    * Recording walkthrough video dalam Bahasa Inggris.

---

## 7. Submission Deliverables
<!-- 1.  **Live URL:** Aplikasi yang dapat diakses publik. -->
2.  **GitHub Repo:** Kode sumber yang bersih dan terdokumentasi.
<!-- 3.  **YouTube Video:** Penjelasan teknis mengenai alur integrasi Laravel ke Nuxt dan cara kerja AI Prompting. -->

---

## 8. Notes
1.  **Saya sudah menyiapkan database MariaDB di container lain**
2.  **pastinya kita menggunakan .env dan menyediakan .env.example**
3.  **laravel,nuxt akan didockerisasi**

