<?php

declare(strict_types=1);

namespace AkioSarkiz\Contracts;

use AkioSarkiz\Exceptions\ProxyAdapterNotFound;

interface ProxyFinderInterface
{
    /**
     * @param  array  $params  [
     *   // Country of the proxy
     *   'country' => array, (default: [])
     *
     *   // Avoid proxy countries
     *   'not_country' => array, (default: [])
     *
     *   // Anonymity Level
     *   'level' => array, (default: [], enum: 'elite', 'anonymous', 'transparent')
     *
     *   // Proxy Protocol
     *   'type' => array, (default: [], enum: 'socks5', 'socks4', 'https')
     *
     *   // How fast you get a response after you've sent out a request
     *   'ping' => int, (default: 0),
     * ]
     * @return ProxyDataInterface
     * @throws ProxyAdapterNotFound
     */
    public function find(array $params = []): ProxyDataInterface;
}
