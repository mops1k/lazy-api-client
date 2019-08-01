<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interfaces\ClientInterface;
use App\ApiClient\Interfaces\QueryInterface;
use App\ApiClient\Interfaces\ResponseInterface;
use ProxyManager\Factory\LazyLoadingGhostFactory;
use ProxyManager\Proxy\GhostObjectInterface;

/**
 * Class ApiLazyFactory
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
     * ApiLazyFactory constructor.
     *
     * @param Pool $apiPool
     */
    public function __construct(Pool $apiPool)
    {
        $this->lazyFactory  = new LazyLoadingGhostFactory();
        $this->initializer = function (
            GhostObjectInterface $ghostObject,
            string $method,
            array $parameters,
            &$initializer,
            array $properties
        ) use ($apiPool) {
            /** @var ResponseInterface $ghostObject */
            $initializer = null;
            $apiPool->execute();
            $properties["\0*\0content"] = $apiPool->getResponseForQuery($ghostObject::getQuery());

            return true;
        };
    }

    /**
     * @param QueryInterface $query
     *
     * @return GhostObjectInterface|ResponseInterface
     */
    public function create(ClientInterface $client): GhostObjectInterface
    {
        $responseClass = $client->getCurrentQuery()->getResponseClassName();
        $responseClass::setQuery($client->getCurrentQuery());

        return $this->lazyFactory->createProxy($client->getCurrentQuery()->getResponseClassName(), $this->initializer);
    }
}
