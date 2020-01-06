<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http\Requests;

use stdClass;

interface Request
{
    /**
     * @param string $uri
     * @param array|null $params
     * @return \stdClass|null
     */
    public function get(string $uri, ?array $params = null): ?stdClass;

    /**
     * @param string $uri
     * @param array|null $params
     * @return \stdClass|null
     */
    public function post(string $uri, ?array $params = null): ?stdClass;
}