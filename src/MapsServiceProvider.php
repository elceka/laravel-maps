<?php

namespace Elceka\Maps;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * MapsServiceProvider
 *
 * @package Elceka\Maps
 */
class MapsServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishFiles();

        Blade::include('maps::styles', 'mapstyles');
        Blade::include('maps::scripts', 'mapscripts');
        Blade::include('maps::index', 'map');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'maps');

        View::composer('maps::*', function ($view) {
            if (!isset($view->service)) {
                $view->with('service', Config::get('vendor.maps.default'));
            }
            if (!isset($view->enabled)) {
                $view->with('enabled', Config::get('vendor.maps.enabled'));
            }

            return $view;
        });
    }

    /**
     * Publish files.
     */
    private function publishFiles(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/maps.php' => config_path('vendor/maps.php'),
            ], 'config');
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/maps'),
            ], 'public');
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/maps'),
            ], 'maps');
        }
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/maps.php', 'vendor.maps');
    }
}
