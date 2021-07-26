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
use ipinfo\ipinfo\IPinfo;
use ipinfo\ipinfo\IPinfoException;

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
     * @throws ProxyNotFound|IPinfoException
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
     * @throws ProxyNotFound|IPinfoException
     */
    protected function afterMiddleware(ProxyDataInterface $proxyData): ProxyDataInterface
    {
        if ($this->options['verify'] && !$this->checkProxyLive($proxyData, $this->options['verify_timeout'])) {
            if ($this->currentAttempt >= $this->options['verify_max_attempt']) {
                throw new ProxyNotFound();
            }

            $this->currentAttempt++;

            return $this->baseFind($this->options);
        }

        if (array_key_exists('country', $this->options['params'])
            && !in_array($this->getProxyCountry($proxyData), $this->options['params']['country'])
        ) {
            return $this->baseFind($this->options);
        }

        return $proxyData;
    }

    /**
     * Generate url param.
     *
     * @param  string  $key
     * @param  string|null  $filter
     * @return string
     */
    public function addUrlParam(string $key, ?string $filter = null): string
    {
        $filter = $filter ?? $key;

        if (!Arr::exists($this->options['params'], $key)) {
            return '';
        }

        return '&' . $filter . '=' . implode(',', $this->options['params'][$key]);
    }

    /**
     * Get proxy country.
     *
     * @param  ProxyDataInterface  $proxyData
     * @return string
     * @throws IPinfoException
     */
    protected function getProxyCountry(ProxyDataInterface $proxyData): string
    {
        $proxyInfo = new IPinfo();

        return strtolower($proxyInfo->getDetails($proxyData->getIp())->all['country']);
    }
}
