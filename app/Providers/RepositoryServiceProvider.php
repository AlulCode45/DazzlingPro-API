<?php

namespace App\Providers;

use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Repositories\Eloquent\TestimonialRepository;
use App\Services\Contracts\TestimonialServiceInterface;
use App\Services\Implementations\TestimonialService;
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
    }

    public function boot()
    {
        //
    }
}