<?php

namespace Edcs\Mondo\Resources;

use Edcs\Mondo\Entitites\Account;
use Edcs\Mondo\Entitites\Collection;
use GuzzleHttp\Psr7\Request;

class Accounts extends ResourceAbstract
{
    /**
     * Returns a list of accounts owned by the currently authorised user.
     *
     * @link https://getmondo.co.uk/docs/#list-accounts
     *
     * @return Collection
     */
    public function get()
    {
        $request = new Request('get', '/accounts');
        $response = $this->sendRequest($request);

        return new Collection($response, 'accounts', Account::class);
    }
}
