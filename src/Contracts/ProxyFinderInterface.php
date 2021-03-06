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
     * ]
     * @return ProxyDataInterface
     * @throws ProxyAdapterNotFound
     */
    public function find(array $params = []): ProxyDataInterface;
}
