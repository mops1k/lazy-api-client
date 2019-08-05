<?php
declare(strict_types=1);

namespace App\Controller;

use App\ReqresFakeApi\Client;
use App\ReqresFakeApi\Query\ListUsersQuery;
use App\ReqresFakeApi\Query\SingleUserQuery;
use LazyHttpClientBundle\Client\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 */
class IndexController extends Controller
{
    use ControllerTrait;
    /**
     * @Route("/")
     *
     * @throws \LazyHttpClientBundle\Exception\ClientNotFoundException
     */
    public function index(Manager $apiClientManager)
    {
        $client = $apiClientManager->get(Client::class);
        $client->use(ListUsersQuery::class);
        $listResult = $client->execute();

        $client->use(ListUsersQuery::class);
        $request = $client->getCurrentQuery()->getRequest();
        $request->getParameters()->set('page', 2);
        $listResult2 = $client->execute();

        $client->use(SingleUserQuery::class);
        $request = $client->getCurrentQuery()->getRequest();
        $request->getParameters()->set('id', 11);
        $singleUserResult = $client->execute();

        dump($listResult->getContent());
        dump($listResult2->getContent());
        dump($singleUserResult->getContent());

        return $this->render('base.html.twig');
    }
}
