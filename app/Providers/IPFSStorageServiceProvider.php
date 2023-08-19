<?php

namespace App\Providers;

use App\Services\IPFSStorageService;
use App\Services\NFTStorageService;
use Illuminate\Support\ServiceProvider;

class IPFSStorageServiceProvider extends ServiceProvider
{
    public $singletons = [
        IPFSStorageService::class => NFTStorageService::class
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
