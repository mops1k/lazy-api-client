<?php
declare(strict_types=1);

namespace App\ApiClient\Exception;

use App\ApiClient\Interfaces\ApiExceptionInterface;
use Throwable;

/**
 * Class ResponseNotFoundException
 */
class ResponseNotFoundException extends \Exception implements ApiExceptionInterface
{
    /**
     * ClientNotFoundException constructor.
     *
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($code = self::CODE, Throwable $previous = null)
    {
        parent::__construct('Response for query are not found', $code, $previous);
    }
}
