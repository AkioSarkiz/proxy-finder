<?php

declare(strict_types=1);

namespace AkioSarkiz;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderInterface;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class ProxyFinder implements ProxyFinderInterface
{
    /**
     * ProxyFinder constructor.
     *
     * @param  ProxyAdaptersManager  $proxyAdaptersManager
     */
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

    /**
     * @inheritDoc
     */
    public function find(array $params = []): ProxyDataInterface
    {
        $proxyAdapter = $this->proxyAdaptersManager->getAdapter($params);

        try {
            return $proxyAdapter->find(
                $this->getOptions($params)
            );
        } catch (Throwable) {
            $this->proxyAdaptersManager->ignoreCurrent();

            return $this->find($params);
        }
    }

    #[ArrayShape([
        'verify' => "bool",
        'verify_max_attempt' => "int",
        'verify_timeout' => "int",
        'params' => "array",
    ])]
    private function getOptions(
        array $params
    ): array {
        return [
            'verify' => config('proxy_finder.verify', true),
            'verify_max_attempt' => 10,
            'verify_timeout' => 5,
            'params' => $params,
        ];
    }
}
