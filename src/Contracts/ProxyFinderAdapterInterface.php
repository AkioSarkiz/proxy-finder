<?php

declare(strict_types=1);

namespace AkioSarkiz\Contracts;

use AkioSarkiz\Exceptions\ProxyNotFound;

interface ProxyFinderAdapterInterface
{
    /**
     * Proxy adapter find proxy item.
     *
     * @param  array  $options  [
     *   'verify' => bool,
     *   'verify_timeout' => int,
     *   'verify_max_attempt' => int,
     *   'verify_host' => string,
     *   'params' => [
     *     // Country of the proxy
     *     'country' => array, (default: [])
     *
     *     // Avoid proxy countries
     *     'not_country' => array, (default: [])
     *
     *     // Anonymity Level
     *     'level' => array, (default: [], enum: 'elite', 'anonymous', 'transparent')
     *
     *     // Proxy Protocol
     *     'type' => array, (default: [], enum: 'socks5', 'socks4', 'https')
     *   ],
     * ]
     * @return ProxyDataInterface
     * @throws ProxyNotFound
     */
    public function find(array $options): ProxyDataInterface;

    /**
     * Get all supported params. It using when user set params.
     * If adapter not supported the param, then ProxyFinder will ignore him.
     *
     * @example [
     *  'country',
     *  'not_country',
     *  'level',
     *  'type',
     * ]
     * @return array
     */
    public function getSupportedParams(): array;
}
