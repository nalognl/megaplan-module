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
    public function __wakeup() {}

    /**
     * Get class instance
     *
     * @see https://en.wikipedia.org/wiki/Singleton_pattern
     * @return $this|\Nalognl\MegaplanModule\Config|null
     */
    public static function new()
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
            throw new Exception('Configurations were not load.');
        }

        $value = $this->config[$config_key] ?? null;

        if (is_null($value)) {
            throw new Exception('Configurations are loaded but key has not found');
        }

        return $value;
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