<?php
namespace Dapehe94\LaravelBookcover\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class LaravelBookcoverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Storage::deleteDirectory(public_path('vendor/laravel-bookcover'));

        $this->publishes([
            dirname(__DIR__) . '/../public' => public_path('vendor/laravel-bookcover'),
        ], 'laravel-assets');        
    }
}