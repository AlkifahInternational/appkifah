<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Direct Style Helpers ────────────────────────────────────
        \Illuminate\Support\Facades\Blade::directive('gradient', function ($expression) {
            return "<span class=\"bg-gradient-to-r from-orange-400 to-violet-400 bg-clip-text text-transparent drop-shadow-md\"><?php echo $expression; ?></span>";
        });

        \Illuminate\Support\Facades\Blade::directive('light', function ($expression) {
            return "<span class=\"text-white/90 font-light ml-1\"><?php echo $expression; ?></span>";
        });

        // ── Unified Brand Helper ────────────────────────────────────
        \Illuminate\Support\Facades\Blade::directive('brand', function () {
            return "<?php echo \App\Helpers\KifahHelper::brand(); ?>";
        });

        // ── Contextual Highlight Helper ──────────────────────────────
        \Illuminate\Support\Facades\Blade::directive('highlight', function ($expression) {
            // Expression format: ($text, $term)
            return "<?php echo \App\Helpers\KifahHelper::highlight($expression); ?>";
        });
    }
}
