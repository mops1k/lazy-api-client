<?php
declare(strict_types=1);

namespace App\ApiClient\Interfaces;

use ProxyManager\Proxy\GhostObjectInterface;

/**
 * Interface ClientInterface
 */
interface ClientInterface
{
    /**
     * @param string $queryClass
     *
     * @return QueryInterface
     */
    public function use(string $queryClass): QueryInterface;

    /**
     * @return string
     */
    public function getBaseUri(): string;

    /**
     * @return QueryInterface
     */
    public function getCurrentQuery(): QueryInterface;

    /**
     * @return GhostObjectInterface|ResponseInterface
     */
    public function execute(): GhostObjectInterface;
}
