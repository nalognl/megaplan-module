<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\AuthApi;

use Nalognl\MegaplanModule\AuthCache;
use Nalognl\MegaplanModule\Http\AuthRequest;
use stdClass;

class AuthApi
{
    const API1 = 1;
    const API3 = 3;

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
     */
    protected function login(int $api): void
    {
        $cache_path = $api === self::API1 ? NNND_AUTH1_CACHE : NNND_AUTH3_CACHE;
        $cache = new AuthCache($cache_path);

        if ($cache->has()) {
            $this->auth_data = $cache->get();
        } else {
            $this->auth_data = $this->auth_request->getAuthDataForApi($api);
            $cache->put($this->auth_data);
        }
    }
}