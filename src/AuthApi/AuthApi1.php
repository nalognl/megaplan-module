<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\AuthApi;

use Exception;
use Nalognl\MegaplanModule\Http\Requests\Request;
use Nalognl\MegaplanModule\Http\Requests\Request1;

class AuthApi1 extends AuthApi
{
    public function getRequest(): Request
    {
        $this->login(AuthApi::API1);

        $resp = $this->auth_data;

        if (!$resp || $resp->status->code !== 'ok') {
            throw new Exception("Authentication failed. {$resp->status->message}");
        }

        return new Request1($resp->data->AccessId, $resp->data->SecretKey, getenv('NNND_HOST'));
    }
}
