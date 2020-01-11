<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\AuthApi;

use Exception;
use Nalognl\MegaplanModule\Http\Requests\Request;
use Nalognl\MegaplanModule\Http\Requests\Request3;

class AuthApi3 extends AuthApi
{
    /**
     * @return \Nalognl\MegaplanModule\Http\Requests\Request
     * @throws \Exception
     */
    public function getRequest(): Request
    {
        $this->login(AuthApi::API3);

        $token = $this->auth_data->access_token ?? null;
        
        if (is_null($token)) {
            throw new Exception('API3: Access token is not set. Check your .env file for correct credentials or delete megaplan authorization cache');
        }

        return new Request3($token);
    }
}
