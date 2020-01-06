<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http\Requests;

use Error;
use Exception;
use Nalognl\MegaplanModule\Http\CurlRequest;
use ParseError;
use stdClass;
use TypeError;

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
     */
    public function get(string $uri, ?array $params = null): ?stdClass
    {
        return $this->send('GET', $uri);
    }

    /**
     * @param string $uri
     * @param array $params GET-параметры
     * @return \stdClass|null Response from megaplan
     */
    public function post(string $uri, array $params = null): ?stdClass
    {
        return $this->send('POST', $uri, $params);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array|null $params
     * @return \stdClass Response from megaplan
     */
    private function send(string $method, string $uri, array $params = null): stdClass
    {
        $headers = ["AUTHORIZATION: Bearer $this->access_token"];

        try {
            $curl = new CurlRequest($uri, $headers);
            return $method === 'POST' ? $curl->post($params) : $curl->get();
        } catch (Exception | TypeError | Error | ParseError $e) {
            tiny_log("api3 error while getting token. {$e->getMessage()}", 'error');
            return new stdClass;
        }
    }
}
