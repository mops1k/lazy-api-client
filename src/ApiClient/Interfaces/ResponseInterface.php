<?php
declare(strict_types=1);

namespace App\ApiClient\Interfaces;

/**
 * Class ResponseInterface
 */
interface ResponseInterface
{
    /**
     * Return query result content
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Return query status code
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Return query response headers
     *
     * @return array|string[][]
     */
    public function getHeaders(): array;
}
