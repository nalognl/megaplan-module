<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http;

use Exception;
use Nalognl\MegaplanModule\Config;
use stdClass;

class CurlRequest
{
    const TIMEOUT = 800;
    const POST_METHOD = 'POST';
    const GET_METHOD = 'GET';

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array|null
     */
    private $headers;

    /**
     * CurlRequest constructor.
     *
     * @param string $uri
     * @param array|null $headers
     */
    public function __construct(string $uri, ?array $headers = null)
    {
        $this->uri = $uri;
        $this->headers = $headers;
    }

    /**
     * @param mixed $params
     * @return \stdClass
     * @throws \Exception
     */
    public function post($params = null): stdClass
    {
        return $this->send(self::POST_METHOD, $params);
    }

    /**
     * @return \stdClass
     * @throws \Exception
     */
    public function get(): stdClass
    {
        return $this->send(self::GET_METHOD);
    }

    /**
     * @param string $method
     * @param mixed $params
     * @return \stdClass
     * @throws \Exception
     */
    private function send(string $method, $params = ''): stdClass
    {
        $host = Config::new()->get('megaplan_host');
        $ch = curl_init("https://{$host}{$this->uri}");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method === self::POST_METHOD) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);

        $res = curl_exec($ch);

        if (isset($res->error)) {
            throw new Exception($res->error_description);
        }

        return json_decode($res);
    }
}