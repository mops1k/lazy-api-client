<?php
declare(strict_types=1);

namespace App\ApiClient\Exception;

use App\ApiClient\Interfaces\ApiExceptionInterface;
use Throwable;

/**
 * Class ClientNotFoundException
 */
class ClientNotFoundException extends \Exception implements ApiExceptionInterface
{
    public const CODE = 500;

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
            'No client FQCN "%s" are registered in api',
            $queryClassName
        ), $code, $previous);
    }
}
