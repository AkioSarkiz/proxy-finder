<?php

declare(strict_types=1);

namespace AkioSarkiz\Tests\Unit;

use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\Exceptions\ProxyAdapterNotFound;
use AkioSarkiz\ProxyAdaptersManager;
use AkioSarkiz\Tests\TestCase;

class ProxyAdaptersManagerTest extends TestCase
{
    /**
     * @test
     */
    public function not_found_adapter_exception_when_buffer_empty(): void
    {
        $manager = $this->app->make(ProxyAdaptersManager::class);

        $this->expectException(ProxyAdapterNotFound::class);
        $manager->get();
    }

    /**
     * @test
     */
    public function not_found_adapter_exception_when_buffer_in_blacklist()
    {
        $manager = $this->app->make(ProxyAdaptersManager::class);

        $manager->add(
            $this->mock(ProxyFinderAdapterInterface::class)
        );

        $this->assertInstanceOf(ProxyFinderAdapterInterface::class, $manager->get());
        $manager->ignoreCurrent();

        $this->expectException(ProxyAdapterNotFound::class);
        $manager->get();
    }
}
