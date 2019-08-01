<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interfaces\QueryInterface;
use App\ApiClient\Interfaces\ResponseInterface;

/**
 * Class AbstractResponse
 */
abstract class AbstractResponse implements ResponseInterface
{
    /** @var string */
    protected $content;

    /** @var QueryInterface */
    protected static $query;

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return QueryInterface
     */
    public static function getQuery(): QueryInterface
    {
        return static::$query;
    }

    /**
     * @param QueryInterface $query
     */
    public static function setQuery(QueryInterface $query)
    {
        static::$query = $query;
    }
}
