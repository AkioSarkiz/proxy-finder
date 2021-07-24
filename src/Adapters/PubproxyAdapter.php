<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\Exceptions\ProxyNotFound;
use AkioSarkiz\ProxyData;
use AkioSarkiz\Traits\HasCheckerProxy;
use Arr;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class PubproxyAdapter implements ProxyFinderAdapterInterface
{
    use HasCheckerProxy;

    private const BASE_URL = 'http://pubproxy.com/api/proxy?format=json&';

    /**
     * Current attempt get proxy.
     *
     * @var int
     */
    private int $currentAttempt = 1;

    /**
     * Count errors.
     *
     * @var int
     */
    private int $httpErrors = 0;

    /**
     * @var array|null
     */
    private ?array $options;

    /**
     * @inheritDoc
     */
    public function find(array $options): ProxyDataInterface
    {
        $this->options = $options;
        $client = new Client();

        try {
            $response = $client->get($this->getUrl());
            $data = json_decode($response->getBody()->getContents(), true)['data'];

            if (count($data) === 0) {
                if ($this->currentAttempt < $this->options['verify_max_attempt']) {
                    return $this->find($this->options);
                }

                throw new ProxyNotFound();
            }

            $proxyData = new ProxyData(
                Arr::get($data[0], 'ip'),
                (int) Arr::get($data[0], 'port'),
                Arr::get($data[0], 'type'),
            );

            if ($this->options['verify'] && !$this->checkProxy($proxyData, $this->options['verify_timeout'])) {
                if ($this->currentAttempt >= $this->options['verify_max_attempt']) {
                    throw new ProxyNotFound();
                }

                $this->currentAttempt++;

                return $this->find($this->options);
            }

            return $proxyData;
        } catch (GuzzleException $e) {
            if ($e->getCode() === 503 && $this->httpErrors < 5) {
                $this->httpErrors++;
                sleep(1);

                return $this->find($this->options);
            }

            throw new ProxyNotFound($e->getMessage());
        }
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return self::BASE_URL
            . "&key=" . config_path('proxy_finder.services.pubproxy.key');
    }
}