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
     * Get adapter from collection.
     *
     * @param  array  $params
     * @return ProxyFinderAdapterInterface
     * @throws ProxyAdapterNotFound
     */
    public function getAdapter(array $params): ProxyFinderAdapterInterface
    {
        $adapters = $this->validateAdapters($params);

        if (!count($adapters)) {
            throw new ProxyAdapterNotFound();
        }

        $this->current = $adapters[0];

        return $this->current;
    }

    /**
     * Get validated adapters.
     *
     * @param  array  $params
     * @return array
     */
    private function validateAdapters(array $params): array
    {
        $valid = [];

        foreach ($this->adapters as $adapter) {
            if (in_array($adapter::class, $this->blacklist)) {
                continue;
            }

            $supportedParams = $adapter->getSupportedParams();

            foreach ($params as $param => $value) {
                if (!in_array($param, $supportedParams)) {
                    continue 2;
                }
            }

            $valid[] = $adapter;
        }

        return $valid;
    }
}
