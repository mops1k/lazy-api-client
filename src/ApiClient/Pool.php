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

    /**
     * @var ResponseInterface[]
     */
    private $responses = [];

    /**
     * Add query to pool
     *
     * @param QueryInterface $query
     */
    public function add(QueryInterface $query): void
    {
        $this->pool[] = $query;
    }

    /**
     * Execute queue requests pool
     */
    public function execute(): void
    {
        /** @var PromiseInterface[] $promises */
        $promises = [];
        foreach ($this->pool as $query) {
            if (\array_key_exists($query->getHashKey(), $this->responses)) {
                continue;
            }

            $uri = $query->buildUri();
            if (!\array_key_exists($query->getHashKey(), $this->httpClients)) {
                $this->httpClients[\get_class($query->getClient())] = new Client(\array_merge([
                    'base_uri' => $query->getClient()->getBaseUri()
                ], $query->getClient()->getOptions()));
            }

            $request = $query->getRequest();
            $httpRequest = new Request($query->getMethod(), $uri, $request->getHeaders()->all(), $request->getBody());

            $promise = $this->httpClients[\get_class($query->getClient())]->sendAsync($httpRequest, $query->getRequest()->getOptions());
            $promise->then(function (ResponseInterface $response) use ($query) {
                $this->responses[$query->getHashKey()] = [
                    'headers' => $response->getHeaders(),
                    'statusCode' => $response->getStatusCode(),
                    'content' => $response->getBody()->getContents(),
                    $response
                ];
            });
            $promises[] = $promise;
        }

        foreach ($promises as $promise) {
            $promise->wait();
        }
    }

    /**
     * Returns response for executed query
     *
     * @param QueryInterface $query
     *
     * @return array
     *
     * @throws ResponseNotFoundException
     */
    public function getResponseForQuery(QueryInterface $query): array
    {
        if (!\array_key_exists($query->getHashKey(), $this->responses)) {
            throw new ResponseNotFoundException();
        }

        return $this->responses[$query->getHashKey()];
    }
}
