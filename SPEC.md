# Technical Specification: AI Sales Page Generator
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
| **Database** | **MySQL** | Menyimpan data user dan riwayat generasi. |
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
* `timestamps`

---

## 6. Development Milestone (48 Hours Sprint)

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
1.  **Live URL:** Aplikasi yang dapat diakses publik.
2.  **GitHub Repo:** Kode sumber yang bersih dan terdokumentasi.
3.  **YouTube Video:** Penjelasan teknis mengenai alur integrasi Laravel ke Nuxt dan cara kerja AI Prompting.

---