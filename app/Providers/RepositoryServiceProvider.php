<?php

namespace App\Providers;

use App\Repositories\ActivityLogRepository;
use App\Repositories\BranchRepository;
use App\Repositories\ItemRepository;
use App\Repositories\UserRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ItemRepository::class,
            ItemRepository::class
        );
        $this->app->bind(
            WarehouseRepository::class,
            WarehouseRepository::class
        );
        $this->app->bind(
            BranchRepository::class,
            BranchRepository::class
        );
        $this->app->bind(
            UserRepository::class,
            UserRepository::class
        );
        $this->app->bind(
            ActivityLogRepository::class,
            ActivityLogRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
