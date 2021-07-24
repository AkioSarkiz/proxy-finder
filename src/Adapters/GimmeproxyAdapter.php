<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\Exceptions\ProxyNotFound;
use AkioSarkiz\ProxyData;
use Arr;
use GuzzleHttp\Exception\GuzzleException;

class GimmeproxyAdapter extends AbstractAdapter implements ProxyFinderAdapterInterface
{
    private const BASE_URL = 'https://gimmeproxy.com/api/getProxy';

    /**
     * @inheritDoc
     */
    public function find(array $options): ProxyDataInterface
    {
        $this->options = $options;

        try {
            $response = $this->client->get($this->getUrl());
            $data = json_decode($response->getBody()->getContents(), true);

            $proxyData = new ProxyData(
                (string) Arr::get($data, 'ip'),
                (int) Arr::get($data, 'port'),
                (string) Arr::get($data, 'protocol'),
            );

            if ($this->options['verify'] && !$this->checkProxy($proxyData, $this->options['verify_timeout'])) {
                if ($this->currentAttempt >= $this->options['verify_max_attempt']) {
                    throw new ProxyNotFound();
                }

                $this->currentAttempt++;

                return $this->find($this->options);
            }

            return $proxyData;
        } catch (GuzzleException) {
            throw new ProxyNotFound();
        }
    }

    private function getUrl(): string
    {
        return self::BASE_URL
            . '?key=' . config('proxy_finder.services.proxy11.key')
            . '&limit=1';
    }
}