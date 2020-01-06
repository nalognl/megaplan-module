<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule;

use Exception;

class Config
{
    /** @var array */
    public $config;

    /** @var self|null */
    private static $instance;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    /**
     * Get class instance
     *
     * @see https://en.wikipedia.org/wiki/Singleton_pattern
     * @return $this|\Nalognl\MegaplanModule\Config|null
     */
    public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static());
    }

    /**
     * @param string $config_key
     * @return mixed
     * @throws \Exception
     */
    public function get(string $config_key)
    {
        if (is_null($this->config)) {
            throw new Exception('Configurations were not load. See https://github.com/nalognl/megaplan-module/blob/master/README.md');
        }

        return $this->config[$config_key];
    }

    /**
     * Loads array of configurations
     *
     * @param array $config
     */
    public function load(array $config): void
    {
        $this->config = $config;
    }
}