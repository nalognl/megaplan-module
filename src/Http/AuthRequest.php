<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http;

use Error;
use Exception;
use Nalognl\MegaplanModule\AuthApi\AuthApi;
use Nalognl\MegaplanModule\Config;
use stdClass;
use TypeError;

class AuthRequest
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array|string
     */
    private $params;

    private function getAuthDataFromMegaplan(): stdClass
    {
        return (new CurlRequest($this->url, $this->headers))->post($this->params);
    }

    public function getAuthDataForApi(int $api): stdClass
    {
        return $api === AuthApi::API1
            ? $this->getAuthDataForApi1()
            : $this->getAuthDataForApi3();
    }

    public function getAuthDataForApi1(): stdClass
    {
        $login = Config::new()->get('megaplan_login');
        $pwd = Config::new()->get('megaplan_hash_password');

        $this->headers = ['Content-Type: application/x-www-form-urlencoded'];
        $this->params = "Login=$login&Password=$pwd";
        $this->url = Config::new()->get('megaplan_api1_auth_uri');

        return $this->getAuthDataFromMegaplan();
    }

    public function getAuthDataForApi3(): stdClass
    {
        $this->headers = ['Content-Type: multipart/form-data'];
        $this->params = [
            'username' => Config::new()->get('megaplan_login'),
            'password' => Config::new()->get('megaplan_password'),
            'grant_type' => 'password',
        ];
        $this->url = Config::new()->get('megaplan_api3_auth_uri');

        return $this->getAuthDataFromMegaplan();
    }
}