<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Share data global ke seluruh view publik (header/footer perlu info
     * perusahaan, social link, dan pengumuman aktif di setiap halaman).
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'partials.header', 'partials.footer'], function ($view) {
            try {
                $view->with([
                    'globalSettings' => app(SettingService::class)->getPublicSettings(),
                    'activeAnnouncement' => Announcement::query()
                        ->where('is_active', true)
                        ->where('starts_at', '<=', now())
                        ->where(function ($q) {
                            $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                        })
                        ->orderByDesc('severity')
                        ->first(),
                ]);
            } catch (\Throwable $e) {
                // Graceful degradation: jika DB/cache belum siap, view tetap
                // render tanpa data global (container baru deploy, dsb).
                $view->with([
                    'globalSettings' => [],
                    'activeAnnouncement' => null,
                ]);
            }
        });
    }
}
