<?php

declare(strict_types=1);

namespace AkioSarkiz\Traits;

use AkioSarkiz\Contracts\ProxyDataInterface;
use GuzzleHttp\Client;
use Throwable;

trait HasCheckerProxy
{
    /**
     * Check proxy is live.
     *
     * @param  ProxyDataInterface  $proxyData
     * @param  int  $timeout
     * @return bool
     */
    protected function checkProxyLive(ProxyDataInterface $proxyData, int $timeout): bool
    {
        try {
            $client = new Client(['timeout' => $timeout]);
            $response = $client->get(config('proxy_finder.verify_host'), ['proxy' => (string) $proxyData,]);

            return $response->getStatusCode() === 200;
        } catch (Throwable) {
            return false;
        }
    }
}
