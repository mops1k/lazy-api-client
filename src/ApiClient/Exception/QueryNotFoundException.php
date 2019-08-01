<?php
declare(strict_types=1);

namespace App\ApiClient\Exception;

use App\ApiClient\Interfaces\ApiExceptionInterface;
use Throwable;

/**
 * Class QueryNotFoundException
 */
class QueryNotFoundException extends \Exception implements ApiExceptionInterface
{
    /**
     * ClientNotFoundException constructor.
     *
     * @param string         $queryClassName
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $queryClassName, $code = self::CODE, Throwable $previous = null)
    {
        parent::__construct(\sprintf(
            'No query FQCN "%s" are registered in api',
            $queryClassName
        ), $code, $previous);
    }
}
