<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
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
        $this->migrateAndSeedOnFirstBoot();
    }

    /**
     * Stateless hosts (Wasmer Edge, etc.) have no entrypoint script, so the
     * database is migrated and seeded on the very first request. A marker
     * file keeps this from running on every request of the same instance.
     */
    private function migrateAndSeedOnFirstBoot(): void
    {
        if (! config('app.migrate_on_start') || $this->app->runningInConsole()) {
            return;
        }

        $marker = storage_path('app/.deployed');

        if (is_file($marker)) {
            return;
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);

            @file_put_contents($marker, now()->toDateTimeString());
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
