<?php

declare(strict_types=1);

namespace AkioSarkiz\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Stringable;

interface ProxyDataInterface extends Stringable, Arrayable
{
    /**
     * Get ip proxy.
     *
     * @return string
     */
    public function getIp(): string;

    /**
     * Get proxy port.
     *
     * @return int
     */
    public function getPort(): int;

    /**
     * Get proxy type.
     *
     * @return string
     * @example http,http,socks4,socks5
     */
    public function getType(): string;
}
