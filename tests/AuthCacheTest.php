<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Tests;

use Nalognl\MegaplanModule\AuthCache;

final class AuthCacheTest extends TestCase
{
    public $file_name = NNND_PATH . 'storage/cache/test-file';

    public function tearDown(): void
    {
        if (file_exists($this->file_name)) {
            unlink($this->file_name);
        }
        parent::tearDown();
    }

    /**
     * Method helper
     *
     * @param string|null $file_name
     * @return string
     */
    private function createCacheFile(?string $file_name = ''): string
    {
        file_put_contents($this->file_name, $file_name);
        return $this->file_name;
    }

    /** @test */
    public function has_returns_false_if_there_are_no_file_that_you_ask(): void
    {
        $this->assertFalse((new AuthCache('some-random-file'))->has());
    }
    
    /** @test */
    public function has_returns_true_if_there_is_a_file_that_you_ask(): void
    {
        $cache_file = $this->createCacheFile();
        $this->assertFalse((new AuthCache($cache_file))->has());
    }

    /** @test */
    public function get_returns_object_cached_in_a_file(): void
    {
        $cached = '{"first": "nice","second": "nice2"}';
        $expected = json_decode($cached);

        $cache_file = $this->createCacheFile($cached);

        $this->assertEquals($expected, (new AuthCache($cache_file))->get());
    }

    /** @test */
    public function saveData_creates_cache_file_with_given_std_object(): void
    {
        $obj_to_cache = (object) ['nice' => 'hello'];
        $expected = json_encode($obj_to_cache, JSON_PRETTY_PRINT);

        (new AuthCache($this->file_name))->put($obj_to_cache);

        $this->assertEquals($expected, file_get_contents($this->file_name));
    }
}