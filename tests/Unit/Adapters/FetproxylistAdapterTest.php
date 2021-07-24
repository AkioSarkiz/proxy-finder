<?php

declare(strict_types=1);

namespace AkioSarkiz\Tests\Unit\Adapters;

use AkioSarkiz\Adapters\FetproxylistAdapter;
use AkioSarkiz\Tests\Unit\ProxyFinderTest;
use Config;

class FetproxylistAdapterTest extends ProxyFinderTest
{
    /**
     * @test
     */
    public function find_proxy_verify(): void
    {
        Config::set('proxy_finder.adapters', [FetproxylistAdapter::class,]);
        parent::find_proxy_verify();
    }

    /**
     * @test
     */
    public function find_proxy_no_verify(): void
    {
        Config::set('proxy_finder.adapters', [FetproxylistAdapter::class,]);
        parent::find_proxy_no_verify();
    }
}