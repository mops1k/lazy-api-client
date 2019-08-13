<?php
declare(strict_types=1);

namespace App\Tests;

use LazyHttpClientBundle\Client\HttpQueue;
use LazyHttpClientBundle\Client\Manager;
use App\ReqresFakeApi\Client;
use App\ReqresFakeApi\Query\ListUsersQuery;
use App\ReqresFakeApi\Query\SingleUserQuery;
use LazyHttpClientBundle\Exception\ClientNotFoundException;
use LazyHttpClientBundle\Tests\MockedHttpQueue;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ResponseNotSameTest
 */
class ResponseNotSameTest extends KernelTestCase
{
    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::bootKernel(['environment' => 'test']);
        $httpProviderPoolMock = static::$container->get(MockedHttpQueue::class);
        static::$container->set(HttpQueue::class, $httpProviderPoolMock);
    }

    /**
     * @throws ClientNotFoundException
     */
    public function testResponse(): void
    {
        /** @var Manager $apiClientManager */
        $apiClientManager = self::$container->get(Manager::class);
        $client  = $apiClientManager->get(Client::class);
        $client->use(ListUsersQuery::class);
        $listResult = $client->execute();

        $client->use(ListUsersQuery::class);
        $request = $client->getCurrentQuery()->getRequest();
        $request->getParameters()->set('page', 2);
        $listResult2 = $client->execute();

        $client->use(ListUsersQuery::class);
        $request = $client->getCurrentQuery()->getRequest();
        $request->getParameters()->set('page', 2);
        $listResult3 = $client->execute();

        $client->use(SingleUserQuery::class);
        $request = $client->getCurrentQuery()->getRequest();
        $request->getParameters()->set('id', 11);
        $singleUserResult = $client->execute();

        $this->assertNotSame($listResult, $singleUserResult);
        $this->assertNotSame($listResult, $listResult2);
        $this->assertSame($listResult2, $listResult3);
        $this->assertNotEquals($listResult->getContent(), $singleUserResult->getContent());
    }
}
