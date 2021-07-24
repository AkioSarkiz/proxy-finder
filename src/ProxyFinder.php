<?php

declare(strict_types=1);

namespace AkioSarkiz;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderInterface;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class ProxyFinder implements ProxyFinderInterface
{
    public function __construct(
        private ProxyAdaptersManager $proxyAdaptersManager
    ) {
        $adapters = config('proxy_finder.adapters');

        foreach ($adapters as $adapter) {
            $this->proxyAdaptersManager->add(
                app($adapter)
            );
        }
    }

    public function find(): ProxyDataInterface
    {
        $proxyAdapter = $this->proxyAdaptersManager->get();

        try {
            return $proxyAdapter->find($this->getOptions());
        } catch (Throwable) {
            $this->proxyAdaptersManager->ignoreCurrent();

            return $this->find();
        }
    }

    #[ArrayShape([
        'verify' => "bool",
        'verify_max_attempt' => "int",
        'verify_timeout' => "int",
    ])]
    private function getOptions(): array
    {
        return [
            'verify' => config('proxy_finder.verify', true),
            'verify_max_attempt' => 10,
            'verify_timeout' => 5,
        ];
    }
}
