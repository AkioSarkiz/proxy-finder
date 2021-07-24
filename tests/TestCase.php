<?php

namespace AkioSarkiz\Tests;

use AkioSarkiz\ProxyFinderServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app): array
    {
        return [
            ProxyFinderServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // perform environment setup
    }
}
