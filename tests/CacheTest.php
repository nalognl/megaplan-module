<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Tests;

use Nalognl\MegaplanModule\Cache;

class CacheTest extends TestCase
{
    /**
     * @var \Nalognl\MegaplanModule\Cache
     */
    private $cache;

    /**
     * @var string
     */
    private $file_path;

    public function setUp(): void
    {
        parent::setUp();
        $this->file_path = __DIR__ . '/storage/test-storage';
        $this->cache = new Cache($this->file_path);
    }

    public function tearDown(): void
    {
        @unlink($this->file_path);
        parent::tearDown();
    }

    /** @test */
    public function set_sets_given_value_to_cache(): void
    {
        $this->cache->set('my_key', 'my string to cache');
        $file_content = json_decode(file_get_contents($this->file_path, true))->my_key;
        $this->assertSame('my string to cache', $file_content);
    }

    /** @test */
    public function set_can_set_value_even_if_file_does_not_exist(): void
    {
        $this->cache->set('my_key', 'my string to cache');
        $file_content = json_decode(file_get_contents($this->file_path, true))->my_key;
        $this->assertSame('my string to cache', $file_content);
    }

    /** @test */
    public function set_can_be_type_array_or_object(): void
    {
        $this->cache->set('array-key', ['nice']);
        $this->assertSame(['nice'], $this->cache->get('array-key'));

        $object = (object) ['love' => 'anna'];
        $this->cache->set('obj-key', $object);
        $this->assertEquals($object, $this->cache->get('obj-key'));
    }

    /** @test */
    public function get_takes_given_value_from_cache(): void
    {
        file_put_contents($this->file_path, json_encode([
            'some_key' => $cached_value = 'my string to cache',
        ]));

        $this->assertSame($cached_value, $this->cache->get('some_key'));
    }

    /** @test */
    public function get_returns_null_if_file_does_not_exist(): void
    {
        $this->assertNull($this->cache->get('random-key'));
    }

    /** @test */
    public function get_can_return_int_and_float(): void
    {
        $this->cache->set('int-key', 10);
        $this->assertIsInt($this->cache->get('int-key'));

        $this->cache->set('float-key', 10.1);
        $this->assertIsFloat($this->cache->get('float-key'));
    }

    /** @test */
    public function delete_removes_data_from_cache(): void
    {
        $this->cache->set('my-key', 'some data');

        $this->assertTrue($this->cache->delete('my-key'));
        $this->assertSame('[]', file_get_contents($this->file_path));
    }

    /** @test */
    public function delete_returns_false_if_key_was_not_found_in_cache(): void
    {
        $this->cache->set('nice', 'nice');
        $this->assertFalse($this->cache->delete('random-key'));
    }

    /** @test */
    public function delete_returns_false_if_cache_file_does_not_exist(): void
    {
        $this->assertFalse($this->cache->delete('no-existing-key'));
    }
}