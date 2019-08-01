<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Exception\ClientNotFoundException;
use App\ApiClient\Interfaces\ClientInterface;

/**
 * Class ApiClientManager
 */
class Manager
{
    /**
     * @var ClientInterface[]
     */
    private $clients = [];

    /**
     * ApiClientManager constructor.
     *
     * @param iterable|null $clients
     */
    public function __construct(?iterable $clients)
    {
        if (!$clients) {
            return;
        }

        foreach ($clients as $client) {
            $this->clients[\get_class($client)] = $client;
        }
    }

    /**
     * @param string $name
     *
     * @return ClientInterface
     *
     * @throws ClientNotFoundException
     */
    public function get(string $name): ClientInterface
    {
        if (!isset($this->clients[$name])) {
            throw new ClientNotFoundException($name);
        }
        return $this->clients[$name];
    }
}
