<?php

declare(strict_types=1);

namespace AkioSarkiz\Adapters;

use AkioSarkiz\Contracts\ProxyDataInterface;
use AkioSarkiz\Contracts\ProxyFinderAdapterInterface;
use AkioSarkiz\Exceptions\ProxyNotFound;
use AkioSarkiz\ProxyData;
use AkioSarkiz\Traits\HasCheckerProxy;
use Arr;
use GuzzleHttp\Exception\GuzzleException;

class FetproxylistAdapter extends AbstractAdapter implements ProxyFinderAdapterInterface
{
    private const BASE_URL = 'https://api.getproxylist.com/proxy';

    /**
     * @inheritDoc
     */
    public function find(array $options): ProxyDataInterface
    {
        $this->options = $options;

        try {
            $response = $this->client->get(self::BASE_URL);
            $data = json_decode($response->getBody()->getContents(), true);

            $proxyData = new ProxyData(
                (string) Arr::get($data, 'Ip'),
                (int) Arr::get($data, 'Port'),
                (string) Arr::get($data, 'protocol'),
            );

            if ($this->options['verify'] && !$this->checkProxy($proxyData, $this->options['verify_timeout'])) {
                if ($this->currentAttempt >= $this->options['verify_max_attempt']) {
                    throw new ProxyNotFound();
                }

                $this->currentAttempt++;

                return $this->find($this->options);
            }

            return $proxyData;
        } catch (GuzzleException) {
            throw new ProxyNotFound();
        }
    }
}