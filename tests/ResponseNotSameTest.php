<?php
declare(strict_types=1);

namespace App\Tests;

use LazyHttpClientBundle\Client\Manager;
use App\ReqresFakeApi\Client;
use App\ReqresFakeApi\Query\ListUsersQuery;
use App\ReqresFakeApi\Query\SingleUserQuery;
use LazyHttpClientBundle\Exception\ClientNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ResponseNotSameTest
 */
class ResponseNotSameTest extends KernelTestCase
{
    /**
     * @throws ClientNotFoundException
     */
    public function testResponse()
    {
        self::bootKernel();
        /** @var Manager $apiClientManager */
        $apiClientManager = self::$container->get(Manager::class);
        $client  = $apiClientManager->get(Client::class);
        $client->use(ListUsersQuery::class);
        $listResult = $client->execute();

        $client->use(SingleUserQuery::class);
        $request = $client->getCurrentQuery()->getRequest();
        $request->getParameters()->set('id', 11);
        $singleUserResult = $client->execute();

        $this->assertNotSame($listResult, $singleUserResult);
        $this->assertNotEquals($listResult->getContent(), $singleUserResult->getContent());
    }
}
