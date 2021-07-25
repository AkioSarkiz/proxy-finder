<?php

declare(strict_types=1);

namespace AkioSarkiz\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ProxyNotFound.
 *
 * @package AkioSarkiz\Exceptions
 */
class ProxyNotFound extends ProxyFinderException
{
    #[Pure]
    public function __construct(
        string $message = 'Proxy not found'
    ) {
        parent::__construct($message, 2, null);
    }
}
