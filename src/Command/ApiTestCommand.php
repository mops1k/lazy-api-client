<?php
declare(strict_types=1);

namespace App\Command;

use App\ApiClient\Manager;
use App\ReqresFakeApi\Client;
use App\ReqresFakeApi\Query\ListUsersQuery;
use App\ReqresFakeApi\Query\SingleUserQuery;
use App\ReqresFakeApi\StringResponse;
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
     * @throws \App\ApiClient\Exception\ClientNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $client  = $this->apiClientManager->get(Client::class);
        $listQuery = $client->use(ListUsersQuery::class);
        $request = $listQuery->getRequest();
        $request->getParameters()->set('page', 1);
        $request->getParameters()->set('per_page', 12);
        $listResult = $client->execute();

        $sigleUserQuery = $client->use(SingleUserQuery::class);
        $request = $sigleUserQuery->getRequest();
        $request->getParameters()->set('id', 11);
        $singleUserResult = $client->execute();

        var_dump($listResult->getContent());
        var_dump($singleUserResult->getContent());
    }
}
