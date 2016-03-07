<?php

namespace Edcs\Mondo\Test\Resources;

use Edcs\Mondo\Entitites\Accounts\Account;
use Edcs\Mondo\Test\TestCase;
use Exception;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client;
use Mockery as m;

class AccountTest extends TestCase
{
    /**
     * Ensures that a collection of account entities are returned by the get accounts resource.
     */
    public function testWhoAmIEndpoint()
    {
        $json = [
            'accounts' => [
                [
                    'id'             => uniqid(),
                    'description'    => uniqid(),
                    'account_number' => uniqid(),
                    'sort_code'      => uniqid(),
                    'created'        => uniqid(),
                ]
            ]
        ];

        $client = m::mock(Client::class);

        $client->shouldReceive('sendRequest')
               ->once()
               ->andReturn(new Response(200, [], json_encode($json)));

        $accounts = new \Edcs\Mondo\Resources\Accounts($client);

        $collection = $accounts->get();

        /** @var Account $account */
        foreach ($collection as $key => $account) {
            $this->assertEquals($json['accounts'][$key]['id'], $account->getId());
            $this->assertEquals($json['accounts'][$key]['description'], $account->getDescription());
            $this->assertEquals($json['accounts'][$key]['account_number'], $account->getAccountNumber());
            $this->assertEquals($json['accounts'][$key]['sort_code'], $account->getSortCode());
            $this->assertEquals($json['accounts'][$key]['created'], $account->getCreated());
        }
    }

    /**
     * Ensures that a 401 response from the api can be handled.
     *
     * @expectedException \Edcs\Mondo\Exceptions\HttpException
     */
    public function testWhoAmIEndpointNonAuthenticated()
    {
        $client = m::mock(Client::class);

        $client->shouldReceive('sendRequest')
               ->once()
               ->andThrow(
                   Exception::class,
                   'Client error: `GET https://api.getmondo.co.uk/accounts` '.
                   'resulted in a `401 UNAUTHORIZED` response: {}',
                   401
               );

        $accounts = new \Edcs\Mondo\Resources\Accounts($client);

        $accounts->get();
    }
}
