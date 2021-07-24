<?php

declare(strict_types=1);

namespace AkioSarkiz;

use AkioSarkiz\Contracts\ProxyDataInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Class ProxyData
 *
 * @package AkioSarkiz
 */
class ProxyData implements ProxyDataInterface
{
    /**
     * ProxyData constructor.
     */
    public function __construct(
        private string $ip,
        private int $port,
        private string $type,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    #[ArrayShape(['host' => "string", 'port' => "int", 'type' => "string"])]
    public function toArray(): array
    {
        return [
            'host' => $this->getIp(),
            'port' => $this->getPort(),
            'type' => $this->getType(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */

    #[Pure]
    public function __toString(): string
    {
        return strtolower($this->type) . "://{$this->ip}:{$this->port}";
    }
}
