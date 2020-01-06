<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Tests;

use Nalognl\MegaplanModule\Config;

class ConfigTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @test
     */
    public function getInstance_returns_instance_of_config_class(): void
    {
        $this->assertInstanceOf(Config::class, Config::new());
    }

    /**
     * @runInSeparateProcess
     * @test
     */
    public function load_method_loads_configurations(): void
    {
        Config::new()->load($conf = [
            'some_key' => 'Nice one',
            'another_key' => 'Cool one',
        ]);

        $this->assertSame($conf, Config::new()->config);
    }

    /**
     * @runInSeparateProcess
     * @test
     */
    public function get_method_returns_value_from_configurations_array(): void
    {
        Config::new()->load([
            'some_key' => 'Nice one',
            'another_key' => 'Cool one',
        ]);

        $this->assertSame('Nice one', Config::new()->get('some_key'));
        $this->assertSame('Cool one', Config::new()->get('another_key'));
    }

    /**
     * @runInSeparateProcess
     * @test
     */
    public function get_method_throws_an_exception_if_config_were_not_passed(): void
    {
        $this->expectExceptionMessage('Configurations were not load.');
        Config::new()->get('another_key');
    }

    /**
     * @runInSeparateProcess
     * @test
     */
    public function get_method_throws_an_exception_if_value_were_not_found(): void
    {
        $this->expectExceptionMessage('Configurations are loaded but key has not found');

        Config::new()->load([
            'some_key' => 'Nice one',
            'another_key' => 'Cool one',
        ]);

        Config::new()->get('not_existing_key');
    }
}
