# PT Jaringan Teknologi Sejahtera (JTS) — Website & Admin Platform

Website resmi dan sistem manajemen konten untuk **PT Jaringan Teknologi Sejahtera**, penyedia layanan internet fiber optik (ISP) yang melayani wilayah Lampung Timur dan Lampung Tengah.

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12 (PHP 8.3) |
| Database | PostgreSQL via Supabase |
| Frontend | Blade + Tailwind CSS + Alpine.js |
| Animasi | GSAP, AOS, Lenis (smooth scroll), Three.js (hero background) |
| Build Tool | Vite |
| Queue/Cache | Redis + Laravel Horizon |
| Auth | Laravel Sanctum (admin session) + JWT (REST API) |

## Fitur Utama

**Publik:**
- Landing page dengan hero animasi, layanan, paket internet, status jaringan real-time
- Blog dengan sistem like/bookmark/komentar
- Cek jangkauan area berbasis GPS
- Portfolio, galeri, testimoni pelanggan
- Halaman karir dengan form lamaran kerja (upload CV)
- Laporan gangguan jaringan dari pelanggan

**Admin Dashboard:**
- Manajemen konten lengkap (artikel, kategori, tag, komentar)
- Manajemen layanan & paket internet
- Manajemen portfolio, galeri, tim, testimoni, FAQ
- Manajemen banner/slider promosi
- Monitoring jaringan (Network Monitor, Coverage Area, Maintenance, Trouble Report)
- Manajemen pengguna & role (Super Admin, Admin, Editor, Marketing, Operator)
- Analytics pengunjung, log aktivitas, backup database

**REST API** (`/api/v1/*`) — untuk integrasi mobile app atau pihak ketiga, dengan autentikasi JWT.

## Struktur Project

```
app/
├── Console/Commands/     # Scheduled jobs (poll network, sitemap, dll)
├── Http/
│   ├── Controllers/
│   │   ├── Web/          # Controller halaman publik
│   │   ├── Admin/        # Controller dashboard admin
│   │   └── Api/V1/       # Controller REST API
│   ├── Requests/         # Form validation
│   └── Resources/        # API response formatting
├── Models/                # Eloquent models
├── Repositories/          # Repository pattern (data access layer)
├── Services/              # Business logic layer
└── Events/, Listeners/    # Event-driven notifications

resources/
├── views/
│   ├── layouts/           # Master layout (publik & admin)
│   ├── pages/             # Halaman publik
│   ├── admin/             # Halaman dashboard admin
│   └── partials/          # Navigation, footer, popup
├── js/                    # app.ts (publik), admin.ts (dashboard)
└── scss/                  # app.scss (design system)

routes/
├── web.php                # Route halaman publik
├── blog.php               # Route blog
├── admin.php              # Route dashboard admin (role-based)
├── api.php                # Route REST API v1
├── webhooks.php           # Webhook WhatsApp/Telegram
└── console.php             # Jadwal scheduler

supabase/migrations/        # SQL migration (schema, RLS, functions, seed)
database/
├── migrations/             # Mirror schema Laravel (untuk tooling Eloquent)
└── seeders/                 # Data awal (roles, users, konten placeholder)

deployment/
├── nginx/, supervisor/     # Config VPS manual
├── docker/                  # Config untuk Dockerfile
├── README.md               # Panduan deploy VPS manual
└── DOKPLOY.md              # Panduan deploy via Dokploy
```

## Instalasi Cepat (Local Development)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Isi `.env` dengan kredensial Supabase Anda, lalu:

```bash
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`. Login admin: `superadmin@ptjts.id` / `SuperAdmin@JTS2024!` — **wajib ganti password default sebelum production.**

## Deploy ke Production

Lihat panduan lengkap di:
- `deployment/README.md` — deploy manual ke VPS Ubuntu (Nginx + PHP-FPM + Supervisor)
- `deployment/DOKPLOY.md` — deploy otomatis via Dokploy (Docker-based)

## Catatan Penting

- **Data placeholder**: harga paket, nama tim, testimoni, dan beberapa konten lain masih berupa data contoh (ditandai `[PLACEHOLDER]`). Ganti dengan data asli sebelum go-live.
- **Password default**: seluruh akun di `UserSeeder` memakai password default — **wajib diganti**.
- **Koneksi database**: gunakan port `6543` (connection pooler Supabase) untuk runtime aplikasi, dan port `5432` (direct connection) khusus saat menjalankan migration.

## Lisensi

Proprietary — Hak cipta PT Jaringan Teknologi Sejahtera.
"# rikk"  
