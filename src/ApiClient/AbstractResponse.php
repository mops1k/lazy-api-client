<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interfaces\ResponseInterface;

/**
 * Class AbstractResponse
 */
abstract class AbstractResponse implements ResponseInterface
{
    /** @var string */
    protected $content;

    public function getContent(): string
    {
        return $this->content;
    }
}
