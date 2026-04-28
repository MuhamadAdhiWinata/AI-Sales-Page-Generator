# Development Process (Story)

## 1️⃣ Ideation & Problem Definition
Perjalanan dimulai dengan pertanyaan sederhana: *Bagaimana kita dapat memungkinkan marketer non‑teknis membuat halaman penjualan yang konversi tinggi tanpa harus menyewa desainer?* Jawabannya: sebuah **generator halaman penjualan berbasis AI** yang mengambil deskripsi produk terstruktur dan menghasilkan HTML siap terbit dengan estetika “Quiet Luxury”.

## 2️⃣ Choosing the Stack
| Concern | Decision | Reason |
|---|---|---|
| **Frontend** | **Nuxt 3 (Vue 3)** | Server‑side rendering untuk SEO, auto‑routing, composable API yang kuat. |
| **Styling** | **Tailwind CSS** | Utility‑first, mudah meng‑implementasikan dark‑mode, micro‑animations, dan desain premium. |
| **Backend** | **Laravel 11** | Ekosistem matang, Sanctum untuk otentikasi token, queue workers untuk pekerjaan latar belakang AI. |
| **AI Engine** | **Gemini 1.5 Flash** | Output teks‑ke‑HTML berkualitas tinggi, latency rendah, integrasi HTTP sederhana. |
| **Database** | **MariaDB (Docker)** | Relasional, migrasi mudah, cocok untuk data user + halaman yang di‑generate. |
| **DevOps** | **Docker‑Compose** | Menjamin lingkungan reproducible untuk backend, frontend, database, dan queue worker. |

## 3️⃣ Project Scaffolding
1. **Repository layout** dibuat dengan dua folder utama: `backend/` (Laravel) dan `frontend/` (Nuxt). 
2. **docker‑compose.yml** mendefinisikan empat service: `backend`, `frontend`, `mariadb`, dan `queue`. 
3. **Version control** dimulai dengan commit bersih, kemudian dibuat *feature branch* `feature/core-generator`. 

### Backend scaffolding
- `composer create-project laravel/laravel backend` 
- Tambahkan Sanctum (`composer require laravel/sanctum`). 
- Konfigurasi `.env` untuk DB dan `GEMINI_API_KEY`. 

### Frontend scaffolding
- `npx nuxi init frontend` 
- Instal Tailwind (`npm i -D tailwindcss postcss autoprefixer`). 
- Tambahkan Pinia (opsional) untuk state management. 

## 4️⃣ Building the Backend API
- **Authentication** – Sanctum token routes (`register`, `login`, `me`, `logout`).
- **Data model** – Migrasi `users` dan `sales_pages`. `sales_pages` menyimpan field produk serta `content_html` yang di‑generate AI.
- **Job system** – Dua job yang dijalankan di queue:
  1. `GenerateSalesPageJob` – menerima data produk, membangun prompt, memanggil Gemini, menyimpan HTML.
  2. `RefineSalesPageJob` – menerima halaman yang sudah ada, instruksi pengguna, memanggil Gemini lagi, menimpa `content_html`.
- **Controllers** – `SalesPageController` mengekspose CRUD serta endpoint `/refine`. Semua route berada dalam middleware `auth:sanctum` dan memeriksa `user_id` untuk otorisasi.
- **Error handling & status** – Setiap job meng‑update kolom `status` (`processing`, `completed`, `failed`) dan `error_message`. Frontend melakukan polling ke `/status`.

## 5️⃣ Crafting the Frontend Experience
- **Product input form** – Komponen Vue terstruktur untuk nama, deskripsi, fitur (array dinamis), audiens, harga, USP, dan pilihan tone. Validasi dilakukan dengan utilitas Nuxt.
- **Live preview** – Hasil AI ditampilkan dalam `<iframe>` via `srcdoc`, sehingga CSS Tailwind yang di‑generate tidak bentrok dengan styling aplikasi.
- **Floating “Edit with AI” panel** – Vue component yang muncul dari kanan, berisi textarea untuk instruksi refinemen dan tombol (atau `Ctrl+Enter`) yang meng‑POST ke `/sales-pages/{id}/refine`.
- **Polling & refresh** – Composable kecil yang terus memanggil endpoint `/status` sampai job melaporkan `completed`, kemudian mengganti `srcdoc` iframe dengan HTML yang diperbarui.
- **Smooth‑scroll handler** – Potongan JavaScript vanilla dimasukkan ke dalam `refrensi.html` (template AI) agar anchor link berfungsi di dalam iframe.

## 6️⃣ Orchestrating Docker & Queues
- **Dockerfile (backend)**: membangun image Laravel, meng‑install Composer dependencies, menyiapkan PHP‑FPM + Nginx.
- **Dockerfile (frontend)**: membangun Nuxt (`npm run build`) dan melayani dengan `node`.
- **Queue worker**: container terpisah menjalankan `php artisan queue:work --sleep=3 --tries=3`, mendengarkan job yang di‑dispatch oleh Laravel.
- **docker‑compose.yml** meng‑link service, mount kode, dan mendefinisikan variabel environment untuk PHP & Node.

## 7️⃣ Testing, Linting & CI
- **Backend tests** – PHPUnit menguji autentikasi, cek izin, serta kedua job (dengan mock response Gemini).
- **Frontend tests** – Vitest + Vue Test Utils untuk rendering komponen dan alur refinemen.
- **Linting** – `phpcs` untuk PHP, `eslint` untuk Vue/JS.
- **GitHub Actions** – Workflow yang menjalankan lint, test, dan build Docker images pada setiap push ke `main`.

## 8️⃣ Deployment Narrative
1. **Prepare production .env** – Kunci DB aman, `APP_ENV=production`, non‑aktifkan debug.
2. **Build images** – `docker compose -f docker-compose.prod.yml build`.
3. **Push ke registry** – Upload ke Docker Hub atau registry privat.
4. **Run on server** – `docker compose -f docker-compose.prod.yml up -d`; queue worker otomatis mulai memproses job.
5. **Post‑deployment checks** – Verifikasi token Sanctum, pastikan endpoint `/refine` men‑queue job, dan iframe memperbarui konten secara tepat.

## 9️⃣ Reflections & Future Roadmap
- **What worked** – Queue‑based AI jobs menjaga respons API tetap cepat; penggunaan `<iframe>` memberi isolasi styling yang stabil.
- **Challenges** – Menyelaraskan instruksi pengguna menjadi prompt Gemini yang konsisten membutuhkan beberapa iterasi; meng‑handle payload HTML besar di API memerlukan serialisasi hati‑hati.
- **Next steps** –
  * Tambahkan peran/role‑based permissions.
  * Dashboard analytics untuk melacak performa halaman yang di‑generate.
  * Dukungan multi‑bahasa pada AI generation.
  * Penyempurnaan UI/UX untuk editor teks yang lebih kaya (markdown preview, spell‑check).

---

*Dokumen ini menceritakan bagaimana AI Sales Page Generator dirancang, di‑arsitekturkan, dibangun, diuji, dan dideploy – sebuah perjalanan pengembangan end‑to‑end yang lengkap.*
