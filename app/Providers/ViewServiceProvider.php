<?php

namespace App\Providers;

use App\Models\Calendario;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Compartilha calendários com todas as views
        View::composer('*', function ($view) {
            $calendarios = Calendario::orderBy('ano', 'DESC')->get();
            $view->with('calendarios', $calendarios);
        });
    }
}