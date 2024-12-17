<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Mengikat data settings ke semua tampilan
        View::composer('*', function ($view) {
            $settings = Setting::first(); // Ambil satu record pertama dari tabel settings
            $view->with('settings', $settings);
        });
    }
}
