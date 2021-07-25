<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\ProxyData;
use Arr;

class ProxyscanAdapter extends AbstractAdapter implements ProxyFinderAdapterInterface
{
    /**
     * @inheritDoc
     */
    public function find(array $options): ProxyDataInterface
    {
        return $this->baseFind($options);
    }

    /**
     * @inheritDoc
     */
    protected function getBaseUrl(): string
    {
        return 'https://www.proxyscan.io/api/proxy?'
            . "limit=1";
    }

    /**
     * @inheritDoc
     */
    protected function getProxyData(array $data): ProxyDataInterface
    {
        return new ProxyData(
            Arr::get($data[0], 'Ip'),
            Arr::get($data[0], 'Port'),
            strtolower(
                Arr::get($data[0], 'Type.0')
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getSupportedParams(): array
    {
        return [
            'country',
            'not_country',
            'level',
            'type',
            'ping',
        ];
    }
}
