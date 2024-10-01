<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AnalysisService;
use App\Services\AnalysisServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AnalysisServiceInterface::class, AnalysisService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
