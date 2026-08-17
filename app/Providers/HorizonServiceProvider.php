<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Horizon Service Provider — DISABLED on Windows.
 *
 * Horizon requires ext-pcntl and ext-posix which are Unix-only.
 * Queue processing uses QUEUE_CONNECTION=sync for local development.
 * Enable Horizon only on production Linux VPS.
 */
class HorizonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Horizon disabled for local Windows development
    }

    public function register(): void
    {
        // Horizon disabled for local Windows development
    }
}
