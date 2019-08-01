<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Exception\ResponseNotFoundException;
use App\ApiClient\Interfaces\QueryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ApiPool
 */
class Pool
{
    /**
     * @var QueryInterface[]
     */
    private $pool = [];

    /**
     * @var Client[]
     */
    private $httpClients = [];

    /** @var array */
    private $responses = [];

    /**
     * @param QueryInterface $query
     */
    public function add(QueryInterface $query): void
    {
        $this->pool[] = $query;
    }

    public function execute(): void
    {
        $this->responses = [];

        /** @var PromiseInterface[] $promises */
        $promises = [];
        foreach ($this->pool as $query) {
            if (\array_key_exists($query->getHashKey(), $this->responses)) {
                continue;
            }

            $uri = $query->buildUri();
            if (!\array_key_exists($query->getHashKey(), $this->httpClients)) {
                $this->httpClients[$query->getHashKey()] = new Client([
                    'base_uri' => $query->getClient()->getBaseUri()
                ]);
            }

            $request = $query->getRequest();
            $httpRequest = new Request($query->getMethod(), $uri, $request->getHeaders()->all(), $request->getBody());

            $promise = $this->httpClients[$query->getHashKey()]->sendAsync($httpRequest);
            $promise->then(function (ResponseInterface $response) use ($query) {
                $this->responses[$query->getHashKey()] = $response->getBody()->getContents();
            });
            $promises[] = $promise;
        }

        foreach ($promises as $promise) {
            $promise->wait();
        }
    }

    /**
     * @param QueryInterface $query
     *
     * @return string
     *
     * @throws ResponseNotFoundException
     */
    public function getResponseForQuery(QueryInterface $query): string
    {
        if (!\array_key_exists($query->getHashKey(), $this->responses)) {
            throw new ResponseNotFoundException();
        }

        return $this->responses[$query->getHashKey()];
    }
}
