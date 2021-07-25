<?php

declare(strict_types=1);

namespace AkioSarkiz\Facades;

use Illuminate\Support\Facades\Facade;

class ProxyFinder extends Facade
{
    /**
     * Get facade accessor from container.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'proxy_finder';
    }
}