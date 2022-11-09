<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\AuthApi;

use Nalognl\MegaplanModule\AuthCache;
use Nalognl\MegaplanModule\Config;
use Nalognl\MegaplanModule\Http\AuthRequest;
use stdClass;

class AuthApi
{
    public const API1 = 1;
    public const API3 = 3;

    /**
     * @var stdClass|null
     */
    protected $auth_data;

    /**
     * @var \Nalognl\MegaplanModule\Http\AuthRequest
     */
    protected $auth_request;

    public function __construct()
    {
        $this->auth_request = new AuthRequest;
    }

    /**
     * Set auth data from cache to property, if
     * there is no data in cache it will make a request to
     * megaplan in order to take them and save to cache.
     *
     * @param int $api
     * @throws \Exception
     */
    protected function login(int $api): void
    {
        [$cache1, $cache3] = $this->getPaths();

        $cache_path = $api === self::API1 ? $cache1 : $cache3;
        $cache = new AuthCache($cache_path);

        if ($cache->has()) {
            $this->auth_data = $cache->get();
        } else {
            $this->auth_data = $this->auth_request->getAuthDataForApi($api);
            $cache->put($this->auth_data);
        }
    }

    private function getPaths(): array
    {
        $config = Config::new();

        if ($config && $config->has('cache_dir_path')) {
            $cache_path = $config->get('cache_dir_path');

            return [
                "{$cache_path}/auth1",
                "{$cache_path}/auth3",
            ];
        }

        $path = $config->get('plugin_path');

        return [
            "{$path}/storage/cache/auth1",
            "{$path}/storage/cache/auth3",
        ];
    }
}