<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Exception\ClientNotSupportedException;
use App\ApiClient\Exception\QueryNotFoundException;
use App\ApiClient\Interfaces\ClientInterface;
use App\ApiClient\Interfaces\QueryInterface;
use App\ApiClient\Interfaces\ResponseInterface;
use ProxyManager\Proxy\GhostObjectInterface;

/**
 * Class AbstractClient
 */
abstract class AbstractClient implements ClientInterface
{
    protected const BASE_URI = '';

    /**
     * @var QueryInterface
     */
    public $query;

    /**
     * @var QueryContainer
     */
    private $queryContainer;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var LazyFactory
     */
    private $lazyFactory;

    /**
     * Client constructor.
     *
     * @param QueryContainer $queryContainer
     * @param Pool           $pool
     * @param LazyFactory    $lazyFactory
     */
    public function __construct(QueryContainer $queryContainer, Pool $pool, LazyFactory $lazyFactory)
    {
        $this->queryContainer = $queryContainer;
        $this->pool = $pool;
        $this->lazyFactory = $lazyFactory;
    }

    /**
     * @param string $queryClass
     *
     * @return QueryInterface
     *
     * @throws ClientNotSupportedException
     * @throws QueryNotFoundException
     */
    public function use(string $queryClass): QueryInterface
    {
        $this->query = $this->queryContainer->get($queryClass);
        if (!$this->query->isSupport($this)) {
            throw new ClientNotSupportedException($this, $this->query);
        }

        $this->query->setClient($this);

        return $this->query;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return static::BASE_URI;
    }

    /**
     * @return GhostObjectInterface|ResponseInterface
     */
    public function execute(): GhostObjectInterface
    {
        $this->pool->add($this->query);

        return $this->lazyFactory->create(clone $this);
    }

    /**
     * @return QueryInterface
     */
    public function getCurrentQuery(): QueryInterface
    {
        return $this->query;
    }
}
