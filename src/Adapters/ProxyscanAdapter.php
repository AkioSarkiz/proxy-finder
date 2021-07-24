<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\Exceptions\ProxyNotFound;
use AkioSarkiz\ProxyData;
use Arr;
use GuzzleHttp\Exception\GuzzleException;

class ProxyscanAdapter extends AbstractAdapter implements ProxyFinderAdapterInterface
{
    private const BASE_URL = 'https://www.proxyscan.io/api/proxy?';

    /**
     * @inheritDoc
     */
    public function find(array $options): ProxyDataInterface
    {
        $this->options = $options;

        try {
            $response = $this->client->get($this->getUrl());
            $data = json_decode($response->getBody()->getContents(), true);

            if (count($data) === 0) {
                if ($this->currentAttempt < $this->options['verify_max_attempt']) {
                    return $this->find($this->options);
                }

                throw new ProxyNotFound();
            }

            $proxyData = new ProxyData(
                Arr::get($data[0], 'Ip'),
                Arr::get($data[0], 'Port'),
                strtolower(
                    Arr::get($data[0], 'Type.0')
                )
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

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return self::BASE_URL . "limit=1";
    }
}
