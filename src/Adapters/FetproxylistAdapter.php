<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\ProxyData;
use Arr;

class FetproxylistAdapter extends AbstractAdapter implements ProxyFinderAdapterInterface
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
        return 'https://api.getproxylist.com/proxy';
    }

    /**
     * @inheritDoc
     */
    protected function getProxyData(array $data): ProxyDataInterface
    {
        return new ProxyData(
            (string) Arr::get($data, 'ip'),
            (int) Arr::get($data, 'port'),
            (string) Arr::get($data, 'protocol'),
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
        ];
    }
}
