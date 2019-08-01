<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interfaces\ClientInterface;
use App\ApiClient\Interfaces\QueryInterface;
use App\ApiClient\Interfaces\ResponseInterface;
use ProxyManager\Factory\LazyLoadingGhostFactory;
use ProxyManager\Proxy\GhostObjectInterface;

/**
 * Class LazyFactory
 */
class LazyFactory
{
    /**
     * @var \Closure
     */
    private $initializer;

    /**
     * @var LazyLoadingGhostFactory
     */
    private $lazyFactory;

    /**
     * @var Pool
     */
    private $apiPool;

    /**
     * LazyFactory constructor.
     *
     * @param Pool $apiPool
     */
    public function __construct(Pool $apiPool)
    {
        $this->lazyFactory  = new LazyLoadingGhostFactory();
        $this->apiPool      = $apiPool;
    }

    /**
     * Return not initialized proxy response
     *
     * @param QueryInterface $query
     *
     * @return GhostObjectInterface|ResponseInterface
     */
    public function create(ClientInterface $client): GhostObjectInterface
    {
        $initializer = function (
            GhostObjectInterface $ghostObject,
            string $method,
            array $parameters,
            &$initializer,
            array $properties
        ) use ($client) {
            /** @var ResponseInterface $ghostObject */
            $initializer = null;

            $this->apiPool->execute();
            $response = $this->apiPool->getResponseForQuery($client->getCurrentQuery());

            $properties["\0*\0content"]    = $response['content'];
            $properties["\0*\0statusCode"] = $response['statusCode'];
            $properties["\0*\0headers"]    = $response['headers'];

            return true;
        };

        return $this->lazyFactory->createProxy($client->getCurrentQuery()->getResponseClassName(), $initializer);
    }
}
