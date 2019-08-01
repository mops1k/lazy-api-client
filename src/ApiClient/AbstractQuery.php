<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interfaces\ClientInterface;
use App\ApiClient\Interfaces\QueryInterface;
use App\ApiClient\Interfaces\RequestMethodInterface;

/**
 * Class AbstractQuery
 */
abstract class AbstractQuery implements QueryInterface
{
    /**
     * @var string
     */
    private $buildedUri;

    /**
     * @var Request
     */
    protected $apiRequest;

    /**
     * @var ClientInterface
     */
    private   $client;

    public function supportedClients(): array
    {
        return [];
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        if (!$this->apiRequest) {
            $this->apiRequest = new Request();
            $this->apiRequest->getHeaders()->set('Content-Type', 'application/json');
        }

        return $this->apiRequest;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return RequestMethodInterface::GET;
    }

    /**
     * @return string
     */
    public function buildUri(): string
    {
        if (!$this->buildedUri) {
            $parameters = $this->getRequest()->getParameters()->all();
            $uri = $this->getUri();
            foreach ($parameters as $name => $value) {
                if (preg_match('#\{'.$name.'\}#i', $uri)) {
                    $uri = str_replace('{'.$name.'}', $value, $uri);
                    $this->getRequest()->getParameters()->remove($name);
                }
            }

            $this->body = $this->getRequest()->getParameters()->all();
            $uriParameters = null;
            if ($this->getMethod() === RequestMethodInterface::GET) {
                $uriParameters = '?'.\http_build_query($this->getRequest()->getParameters()->all());
            }

            $this->buildedUri = $uri.$uriParameters;
        }

        return $this->buildedUri;
    }

    /**
     * @return string
     */
    public function getHashKey(): string
    {
        return hash('sha512', \serialize(\array_merge(
            $this->getRequest()->getParameters()->all(),
            $this->getRequest()->getHeaders()->all()
        )));
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     *
     * @return static
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    public function isSupport(ClientInterface $client): bool
    {
        return !$this->supportedClients() || \in_array(\get_class($client), $this->supportedClients(), true);
    }
}
