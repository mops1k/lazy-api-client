<?php
declare(strict_types=1);

namespace App\ApiClient\Interfaces;

/**
 * Class ResponseInterface
 */
interface ResponseInterface
{
    /**
     * @return mixed
     */
    public function getContent(): string;
}
