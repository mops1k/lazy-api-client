<?php
declare(strict_types=1);

namespace App\ApiClient;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class ApiParameter
 */
final class Request
{
    /**
     * @var ParameterBag
     */
    private $headers;

    /**
     * @var ParameterBag
     */
    private $parameters;

    /**
     * @var string
     */
    private $body;

    /**
     * ApiRequest constructor.
     */
    public function __construct()
    {
        $this->headers    = new HeaderBag();
        $this->parameters = new ParameterBag();
    }

    /**
     * @return ParameterBag
     */
    public function getHeaders(): HeaderBag
    {
        return $this->headers;
    }

    /**
     * @return ParameterBag
     */
    public function getParameters(): ParameterBag
    {
        return $this->parameters;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Request
     */
    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }
}
