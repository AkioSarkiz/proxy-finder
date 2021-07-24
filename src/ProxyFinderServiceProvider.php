<?php

declare(strict_types=1);

namespace AkioSarkiz;

use AkioSarkiz\Contracts\ProxyFinderInterface;
use Illuminate\Support\ServiceProvider;

class ProxyFinderServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/config.php' => config_path('proxy_finder.php')], 'config');
        }
    }

    /**
     * Register service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'proxy_finder');

        $this->app->bind(ProxyFinderInterface::class, ProxyFinder::class);
    }
}
