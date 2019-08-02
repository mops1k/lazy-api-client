<?php
declare(strict_types=1);

namespace App\Command;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use LazyHttpClientBundle\Client\Manager;
use App\ReqresFakeApi\Client;
use App\ReqresFakeApi\Query\ListUsersQuery;
use App\ReqresFakeApi\Query\SingleUserQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ApiTestCommand
 */
class ApiTestCommand extends Command
{
    /**
     * @var Manager
     */
    private $apiClientManager;

    /**
     * ApiTestCommand constructor.
     *
     * @param string|null $name
     * @param Manager     $apiClientManager
     */
    public function __construct(string $name = null, Manager $apiClientManager)
    {
        parent::__construct($name);
        $this->apiClientManager = $apiClientManager;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('test:api');
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws \LazyHttpClientBundle\Exception\ClientNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $client = $this->apiClientManager->get(Client::class);
        $client->use(ListUsersQuery::class);
        $listResult = $client->execute();

        $client->use(SingleUserQuery::class);
        $request = $client->getCurrentQuery()->getRequest();
        $request->getParameters()->set('id', 11);
        $singleUserResult = $client->execute();

        var_dump($listResult->getContent());
        var_dump($singleUserResult->getContent());
    }
}
