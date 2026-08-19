<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Hotel;
use App\Models\HotelImages;
use App\Policies\HotelPolicy;
use App\Policies\HotelImagePolicy;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Hotel::class, HotelPolicy::class);

        Gate::policy(HotelImages::class, HotelImagePolicy::class);
    }
}