<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix MySQL key length issue with utf8mb4
        // Reduces default string length from 255 to 191 for indexes
        Schema::defaultStringLength(191);
    }
}



