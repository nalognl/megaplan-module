<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http;

use Error;
use Exception;
use Nalognl\MegaplanModule\AuthApi\AuthApi;
use stdClass;

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
        $curl = new CurlRequest($this->url, $this->headers);

        try {
            return $curl->post($this->params);
        } catch (Exception | TypeError | Error $e) {
            tiny_log($e->getMessage());
            return new stdClass;
        }
    }

    public function getAuthDataForApi(int $api): stdClass
    {
        return $api === AuthApi::API1
            ? $this->getAuthDataForApi1()
            : $this->getAuthDataForApi3();
    }

    public function getAuthDataForApi1(): stdClass
    {
        $login = getenv('NNND_LOGIN');
        $pwd = getenv('NNND_MD5_HASH_PASSWORD');

        $this->headers = ['Content-Type: application/x-www-form-urlencoded'];
        $this->params = "Login=$login&Password=$pwd";
        $this->url = getenv('NNND_AUTH_URI');

        return $this->getAuthDataFromMegaplan();
    }

    public function getAuthDataForApi3(): stdClass
    {
        $this->headers = ['Content-Type: multipart/form-data'];
        $this->params = [
            'username' => getenv('NNND_LOGIN'),
            'password' => getenv('NNND_PASSWORD'),
            'grant_type' => 'password',
        ];
        $this->url = getenv('NNND_API3_AUTH_URI');

        return $this->getAuthDataFromMegaplan();
    }
}