<?php

declare(strict_types=1);

namespace AkioSarkiz\Tests\Unit;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderInterface;
use AkioSarkiz\Tests\TestCase;
use AkioSarkiz\Traits\HasCheckerProxy;
use Config;

class ProxyFinderTest extends TestCase
{
    use HasCheckerProxy;

    /**
     * @test
     */
    public function find_proxy_verify(): void
    {
        Config::set('proxy_finder.verify', true);

        $finder = $this->app->make(ProxyFinderInterface::class);
        $proxyData = $finder->find();

        $this->assertInstanceOf(ProxyDataInterface::class, $proxyData);
        $this->assertTrue($this->checkProxy($proxyData, 30));
    }

    /**
     * @test
     */
    public function find_proxy_no_verify(): void
    {
        Config::set('proxy_finder.verify', false);

        $finder = $this->app->make(ProxyFinderInterface::class);
        $proxyData = $finder->find();

        $this->assertInstanceOf(ProxyDataInterface::class, $proxyData);
    }
}
