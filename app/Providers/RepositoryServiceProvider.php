<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\ItemRepository::class,
            \App\Repositories\ItemRepository::class
        );
        $this->app->bind(
            \App\Repositories\WarehouseRepository::class,
            \App\Repositories\WarehouseRepository::class
        );
        $this->app->bind(
            \App\Repositories\UserRepository::class,
            \App\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\ActivityLogRepository::class,
            \App\Repositories\ActivityLogRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
