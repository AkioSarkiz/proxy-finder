<?php

declare(strict_types=1);

namespace AkioSarkiz\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ProxyAdapterNotFound - when proxy finder not found adapter. This may be when all the adapters were not working.
 *
 * @package AkioSarkiz\Exceptions
 */
class ProxyAdapterNotFound extends ProxyFinderException
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Proxy adapter', 1);
    }
}
