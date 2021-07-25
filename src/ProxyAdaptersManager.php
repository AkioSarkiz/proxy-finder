<?php

declare(strict_types=1);

namespace AkioSarkiz;

use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\Exceptions\ProxyAdapterNotFound;

class ProxyAdaptersManager
{
    /**
     * Current used adapter.
     *
     * @var ProxyFinderAdapterInterface|null
     */
    private ?ProxyFinderAdapterInterface $current = null;

    /**
     * @var array|ProxyFinderAdapterInterface[]
     */
    private array $blacklist = [];

    /**
     * @var array|ProxyFinderAdapterInterface[]
     */
    private array $adapters = [];

    /**
     * @param  ProxyFinderAdapterInterface  $adapter
     * @return $this
     */
    public function add(ProxyFinderAdapterInterface $adapter): self
    {
        $this->adapters[] = $adapter;

        return $this;
    }

    /**
     * @return $this
     */
    public function ignoreCurrent(): self
    {
        if ($this->current === null) {
            return $this;
        }

        $this->blacklist[] = $this->current::class;
        $this->current = null;

        return $this;
    }

    /**
     * @return ProxyFinderAdapterInterface
     * @throws ProxyAdapterNotFound
     */
    public function get(): ProxyFinderAdapterInterface
    {
        foreach ($this->adapters as $adapter) {
            if (!in_array($adapter::class, $this->blacklist)) {
                $this->current = $adapter;

                return $adapter;
            }
        }

        throw new ProxyAdapterNotFound();
    }
}
