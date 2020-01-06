<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http;

use Exception;

class RequestInfo
{

    /** @var array Список параметров */
    protected $params;

    /** @var array Список поддерживаемых HTTP-методов */
    protected static $supporting_methods = [
        'GET', 'POST', 'PUT', 'DELETE'
    ];

    /** @var array Список принимаемых HTTP-заголовков */
    protected static $accepted_headers = [
        'Date', 'Content-Type', 'Content-MD5', 'Post-Fields'
    ];

    /**
     * RequestInfo constructor.
     *
     * @param array $params
     */
    protected function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Creates and returns this object
     *
     * @param string $method Метод запроса
     * @param string $host Хост мегаплана
     * @param string $uri URI запроса
     * @param array $headers Заголовки запроса
     * @return self
     * @throws \Exception
     */
    public static function create(string $method, string $host, string $uri, array $headers)
    {
        $method = mb_strtoupper($method);

        if (!in_array($method, self::$supporting_methods)) {
            throw new Exception("Unsupported HTTP-Method '$method'");
        }

        $params = [
            'Method' => $method,
            'Host' => $host,
            'Uri' => $uri
        ];

        // фильтруем заголовки
        $valid_headers = array_intersect_key($headers, array_flip(self::$accepted_headers));
        $params = array_merge($params, $valid_headers);

        return new self($params);
    }

    /**
     * Возвращает параметры запроса
     *
     * @param string $name
     * @return string
     */
    public function __get(string $name)
    {
        $name = preg_replace("/([a-z])([A-Z])/u", '$1-$2', $name);
        return !empty($this->params[$name]) ? $this->params[$name] : '';
    }
}