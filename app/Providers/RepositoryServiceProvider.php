<?php

namespace App\Providers;

use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Repositories\Eloquent\TestimonialRepository;
use App\Services\Contracts\TestimonialServiceInterface;
use App\Services\Implementations\TestimonialService;
use App\Repositories\Contracts\AnalyticRepositoryInterface;
use App\Repositories\Eloquent\AnalyticRepository;
use App\Services\Contracts\AnalyticServiceInterface;
use App\Services\Implementations\AnalyticService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TestimonialRepositoryInterface::class, function ($app) {
            return new TestimonialRepository($app->make('App\Models\Testimonial'));
        });

        $this->app->singleton(TestimonialServiceInterface::class, function ($app) {
            return new TestimonialService($app->make(TestimonialRepositoryInterface::class));
        });

        $this->app->singleton(AnalyticRepositoryInterface::class, function ($app) {
            return new AnalyticRepository($app->make('App\Models\Analytic'));
        });

        $this->app->singleton(AnalyticServiceInterface::class, function ($app) {
            return new AnalyticService($app->make(AnalyticRepositoryInterface::class));
        });
    }

    public function boot()
    {
        //
    }
}