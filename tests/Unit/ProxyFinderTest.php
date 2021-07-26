<?php

declare(strict_types=1);

namespace AkioSarkiz\Tests\Unit;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderInterface;
use AkioSarkiz\Tests\TestCase;
use AkioSarkiz\Traits\HasCheckerProxy;
use Config;
use ipinfo\ipinfo\IPinfo;

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
        $this->assertTrue($this->checkProxyLive($proxyData, 30));
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

    /**
     * @test
     */
    public function find_proxy_with_param_country(): void
    {
        $countries = ['ua', 'br', 'ru', 'ar', 'us',];
        $finder = $this->app->make(ProxyFinderInterface::class);
        $proxyInfo = new IPinfo();

        foreach ($countries as $country) {
            $proxyData = $finder->find([
                'country' => [$country],
            ]);

            $this->assertInstanceOf(ProxyDataInterface::class, $proxyData);
            $this->assertSame(
                strtolower($proxyInfo->getDetails($proxyData->getIp())->all['country']),
                $country
            );
        }
    }
}
