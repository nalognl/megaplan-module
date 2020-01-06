<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule;

class Cache
{
    /**
     * @var string
     */
    private $file_name;

    /**
     * Cache constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->file_name = $filename;
    }

    private function getContent(): array
    {
        if (!file_exists($this->file_name)) {
            return [];
        }

        $content = json_decode(file_get_contents($this->file_name), true);

        return is_array($content) ? $content : [];
    }

    private function writeToFile(array $data): void
    {
        file_put_contents($this->file_name, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Get data from a cache file
     *
     * @param string $key
     * @return mixed Can return any type
     */
    public function get(string $key)
    {
        $storage = $this->getContent();

        if (!is_null($storage) && array_key_exists($key, $storage)) {
            $value = $storage[$key];

            if (is_int($value) || is_float($value)) {
                return $value;
            }

            $unsterilized = @unserialize($value);
            return $unsterilized === false ? $value : $unsterilized;
        }

        return null;
    }

    /**
     * Saves data to cache file
     *
     * @param string $key
     * @param mixed $value Value can be array or object as well
     */
    public function set(string $key, $value): void
    {
        if (is_null($value) || empty($value)) {
            return;
        }

        if (is_array($value) || is_object($value)) {
            $value = serialize($value);
        }

        if (!file_exists($this->file_name)) {
            file_put_contents($this->file_name, json_encode([$key => $value], JSON_PRETTY_PRINT));
            return;
        }

        $storage = $this->getContent();
        $storage[$key] = $value;

        $this->writeToFile($storage);
    }

    /**
     * @param string $key
     * @return bool If value
     */
    public function delete(string $key): bool
    {
        $storage = $this->getContent();

        if (array_key_exists($key, $storage)) {
            unset($storage[$key]);
            $this->writeToFile($storage);
            return true;
        }

        return false;
    }
}
