<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AnalysisService;
use App\Services\AnalysisServiceInterface;
use App\Services\DecileService;
use App\Services\DecileServiceInterface;

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
        $this->app->bind(DecileServiceInterface::class, DecileService::class);
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
