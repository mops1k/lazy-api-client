<?php
declare(strict_types=1);

namespace App\ReqresFakeApi;

use App\ApiClient\AbstractClient;

/**
 * Class Client
 */
class Client extends AbstractClient
{
    protected const BASE_URI = 'https://reqres.in';
}
