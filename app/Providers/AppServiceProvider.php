<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Pluralizer;

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
        // Configurar locale de Carbon para fechas en español
        Carbon::setLocale(config("app.locale"));

        // Activar pluralizador en español (opcional)
        if (env("USE_SPANISH_PLURALIZER", false)) {
            Pluralizer::useLanguage("spanish");
        }
        //
    }
}
