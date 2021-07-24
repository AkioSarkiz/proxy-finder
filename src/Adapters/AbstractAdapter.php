<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Traits\HasCheckerProxy;
use GuzzleHttp\Client;

class AbstractAdapter
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
}