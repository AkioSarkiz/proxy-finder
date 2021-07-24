<?php

declare(strict_types=1);

namespace AkioSarkiz\Contracts;

use AkioSarkiz\Exceptions\ProxyNotFound;

interface ProxyFinderAdapterInterface
{
    /**
     * @param  array  $options [
     *   'verify' => bool,
     *   'verify_timeout' => int,
     *   'verify_max_attempt' => int,
     * ]
     * @return mixed
     * @throws ProxyNotFound
     */
    public function find(array $options): ProxyDataInterface;
}
