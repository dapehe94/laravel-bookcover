<?php
namespace Dapehe94\LaravelBookcover\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelBookcoverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            dirname(__DIR__) . '/../public' => public_path('vendor/laravel-bookcover'),
        ], 'laravel-assets');        
    }
}