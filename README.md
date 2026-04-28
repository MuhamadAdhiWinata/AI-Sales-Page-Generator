# AI Sales Page Generator

A SaaS‑ready platform that converts raw product data into AI‑generated sales pages.

## 📦 Prasyarat
- Docker Compose (≥ 2.20)
- Node.js (≥ 20)
- PHP (≥ 8.2) & Composer

## 🚀 Memulai (lokal)
```bash
# Clone repository
git clone https://github.com/MuhamadAdhiWinata/AI-Sales-Page-Generator.git
cd AI-Sales-Page-Generator

# Copy environment file
cp .env.example .env   # edit .env (DB credentials, GEMINI_API_KEY, etc.)

# Build Docker services
docker compose up -d

# Install backend dependencies
docker compose exec backend composer install

# Run migrations
docker compose exec backend php artisan migrate --seed

# Install frontend dependencies
docker compose exec frontend npm ci

# Start Nuxt dev server (if not started by compose)
docker compose exec frontend npm run dev
```

Open the app:
- Frontend: <http://localhost:3000>
- API: <http://localhost:8000/api>

## ✨ Fitur Utama
- **Generate sales page** – AI generates HTML + Tailwind styling.
- **Edit with AI (Refine)** – Floating panel lets user send refinement instructions; backend runs `RefineSalesPageJob`.
- **Live preview** – Rendered inside an isolated `<iframe>` to keep CSS intact.
- **Secure routes** – Sanctum authentication, ownership checks.
- **Dockerised** – Backend, frontend, MariaDB, and queue worker run in containers.

## 📂 Struktur Proyek
```
backend/       # Laravel app
  app/        # Controllers, Jobs, Models
  routes/     # API routes (api.php)
frontend/      # Nuxt 3 app
  pages/      # generate.vue, history/[id].vue (refine UI)
  components/ # reusable UI components
docker-compose.yml  # services definition
```

## 🛠️ Pengembangan Selanjutnya
- Role‑based access control
- Multi‑language support
- Analytics dashboard
- CI/CD pipeline with GitHub Actions

---
*Dokumentasi ini dibuat pada 2026‑04‑29.*
