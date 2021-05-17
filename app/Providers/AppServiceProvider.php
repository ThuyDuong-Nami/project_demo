<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            AdminRepository::class,
        );
        $this->app->singleton(
            UserRepository::class,
        );
        $this->app->singleton(
            CategoryRepository::class,
        );
        $this->app->singleton(
            ProductRepository::class,
        );
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
