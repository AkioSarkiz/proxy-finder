<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Exceptions\ProxyNotFound;
use AkioSarkiz\ProxyData;
use AkioSarkiz\Traits\HasCheckerProxy;
use Arr;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractAdapter
{
    use HasCheckerProxy;

    /**
     * Current attempt get proxy.
     *
     * @var int
     */
    protected int $currentAttempt = 1;

    /**
     * Current options.
     *
     * @var array|null
     */
    protected ?array $options;

    /**
     * AbstractAdapter constructor.
     *
     * @param  Client  $client
     */
    public function __construct(
        protected Client $client,
    ) {
    }

    /**
     * Base algorithm for find proxy. It solution for many api. But you can realization your custom algorithm.
     *
     * @param  array  $options
     * @return ProxyDataInterface
     * @throws ProxyNotFound
     */
    protected function baseFind(array $options): ProxyDataInterface
    {
        $this->options = $options;

        try {
            $response = $this->client->get($this->getBaseUrl());
        } catch (GuzzleException) {
            throw new ProxyNotFound();
        }

        $proxyData = $this->getProxyData(
            $this->decodeBody($response->getBody()->getContents())
        );

        return $this->afterMiddleware($proxyData);
    }

    /**
     * Base url for get proxy list.
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return "";
    }

    /**
     * Decode body request.
     *
     * @param  string  $body
     * @return array
     */
    protected function decodeBody(string $body): array
    {
        return json_decode($body, true);
    }

    /**
     * Generate proxy data.
     *
     * @param  array  $data
     * @return ProxyDataInterface
     */
    protected function getProxyData(array $data): ProxyDataInterface
    {
        return new ProxyData(
            (string) Arr::get($data, 'Ip'),
            (int) Arr::get($data, 'Port'),
            (string) Arr::get($data, 'protocol'),
        );
    }

    /**
     * For checking proxy data before return.
     *
     * @param  ProxyDataInterface  $proxyData
     * @return ProxyDataInterface
     * @throws ProxyNotFound
     */
    protected function afterMiddleware(ProxyDataInterface $proxyData): ProxyDataInterface
    {
        if ($this->options['verify'] && !$this->checkProxy($proxyData, $this->options['verify_timeout'])) {
            if ($this->currentAttempt >= $this->options['verify_max_attempt']) {
                throw new ProxyNotFound();
            }

            $this->currentAttempt++;

            return $this->baseFind($this->options);
        }

        return $proxyData;
    }
}
