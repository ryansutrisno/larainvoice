<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Invoice_detail;
use App\Observers\Invoice_detailObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Define observer yang telah dibuat
        // Invoice_detail adalah nama class dari model
        // Invoice_detailObserver adalah nama class dari observer itu sendiri
        Invoice_detail::observe(Invoice_detailObserver::class);
    }
}
