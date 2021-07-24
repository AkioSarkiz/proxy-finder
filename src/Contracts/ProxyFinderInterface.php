<?php

declare(strict_types=1);

namespace AkioSarkiz\Contracts;

use AkioSarkiz\Exceptions\ProxyAdapterNotFound;

interface ProxyFinderInterface
{
    /**
     * @return ProxyDataInterface
     * @throws ProxyAdapterNotFound
     */
    public function find(): ProxyDataInterface;
}
