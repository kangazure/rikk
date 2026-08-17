#!/bin/sh
set -e

cd /var/www/html

# Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] APP_KEY kosong, generate baru..."
    php artisan key:generate --force
fi

# Cache config/route/view untuk performa production.
echo "[entrypoint] Warming caches..."
php artisan package:discover --ansi 2>/dev/null || echo "[entrypoint] package:discover skipped"
php artisan config:cache 2>/dev/null || echo "[entrypoint] config:cache skipped (DB mungkin belum siap)"
php artisan route:cache 2>/dev/null || echo "[entrypoint] route:cache skipped"
php artisan view:cache 2>/dev/null || echo "[entrypoint] view:cache skipped"

# Start supervisord (nginx + php-fpm) DULU agar container langsung responsif
echo "[entrypoint] Starting supervisord (nginx + php-fpm)..."
supervisord -c /etc/supervisor/conf.d/supervisord.conf &

# Tunggu sebentar agar nginx & php-fpm siap
sleep 2

# Jalankan migration di background SETELAH services jalan
# Ini tidak blocking, jadi web tetap bisa diakses meskipun migrate gagal
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Menjalankan migration (background)..."
    php artisan migrate --force 2>&1 || echo "[entrypoint] Migration gagal/dilewati, cek koneksi database."
fi

# Keep container alive (supervisord sudah jalan di background)
wait
