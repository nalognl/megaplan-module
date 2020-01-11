<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\AuthApi;

use Exception;
use Nalognl\MegaplanModule\Config;
use Nalognl\MegaplanModule\Http\Requests\Request;
use Nalognl\MegaplanModule\Http\Requests\Request1;

class AuthApi1 extends AuthApi
{
    /**
     * @return \Nalognl\MegaplanModule\Http\Requests\Request
     * @throws \Exception
     */
    public function getRequest(): Request
    {
        $this->login(AuthApi::API1);

        $resp = $this->auth_data;

        if (!$resp || $resp->status->code !== 'ok') {
            throw new Exception("API1: Authentication failed. Check your .env file for correct credentials or delete megaplan authorization cache. Error: {$resp->status->message}");
        }

        $megaplan_host = Config::new()->get('megaplan_host');

        return new Request1($resp->data->AccessId, $resp->data->SecretKey, $megaplan_host);
    }
}
