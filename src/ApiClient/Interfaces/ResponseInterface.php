<?php
declare(strict_types=1);

namespace App\ApiClient\Interfaces;

/**
 * Class ResponseInterface
 */
interface ResponseInterface
{
    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return array|string[][]
     */
    public function getHeaders(): array;
}
