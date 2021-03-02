<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http\Requests;

use Nalognl\MegaplanModule\Http\CurlRequest;
use stdClass;

class Request3 implements Request
{
    /**
     * @var string
     */
    private $access_token;

    public function __construct(string $access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @param string $uri
     * @param array|null $params
     * @return \stdClass|null Response from megaplan
     * @throws \Exception
     */
    public function get(string $uri, ?array $params = null): ?stdClass
    {
        return $this->send('GET', $uri);
    }

    /**
     * @param string $uri
     * @param array|null $params GET-параметры
     * @return \stdClass|null Response from megaplan
     * @throws \Exception
     */
    public function post(string $uri, ?array $params = null): ?stdClass
    {
        return $this->send('POST', $uri, $params);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array|null $params
     * @return \stdClass Response from megaplan
     * @throws \Exception
     */
    private function send(string $method, string $uri, array $params = null): stdClass
    {
        $headers = ["AUTHORIZATION: Bearer $this->access_token"];
        $curl = new CurlRequest($uri, $headers);
        return $method === 'POST' ? $curl->post($params) : $curl->get();
    }
}
