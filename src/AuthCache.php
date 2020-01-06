<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule;

use stdClass;

final class AuthCache
{
    /** @var string */
    private $file_path;

    /**
     * AuthCache constructor.
     *
     * @param string $file_path
     */
    public function __construct(string $file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * @return bool
     */
    public function has(): bool
    {
        $file_content = file_exists($this->file_path)
            ? file_get_contents($this->file_path)
            : null;

        return !empty($file_content);
    }

    /**
     * @return array|mixed|object
     */
    public function get()
    {
        return json_decode(file_get_contents($this->file_path));
    }

    /**
     * @param \stdClass $std_object
     */
    public function put(stdClass $std_object): void
    {
        file_put_contents($this->file_path, json_encode($std_object, JSON_PRETTY_PRINT));
    }
}