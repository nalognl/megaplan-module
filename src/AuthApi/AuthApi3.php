<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\AuthApi;

use Nalognl\MegaplanModule\Http\Requests\Request;
use Nalognl\MegaplanModule\Http\Requests\Request3;

class AuthApi3 extends AuthApi
{
    public function getRequest(): Request
    {
        $this->login(AuthApi::API3);
        return new Request3($this->auth_data->access_token);
    }
}
